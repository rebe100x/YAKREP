<?php
/******************************************************************************
 *
 *                                 Cloudview
 *
 *                            PHP Push API Client Library.
 *
 *                       Copyright (c) 2009 Exalead S.A.
 *
 *****************************************************************************/

/**
 * Push client interface. (Version 4)
 * 
 * Main entry point for every push API calls.
 */
interface PushAPI {
  /**
   * Return the source name (the connector name).
   **/
  public function getSourceName();
  
  /**
   * Tests the connection with the server.
   * 
   * This test should be called after the construction of the Push API. The
   * purpose of this method is to:
   * <ul>
   * <li>test the server availability</li>
   * <li>checks for the existence of the connectorName and its security</li>
   * <li>compare the PAPI protocol versions</li>
   * </ul>
   * 
   * @throws PushAPIException
   *           upon error (invalid connector name or IO error)
   **/
  public function ping();
  
  /**
   * Adds a document. If a document with the same URI has already been added,
   * the document will be updated.
   * 
   * @param document
   *          Document to be added.
   * @throws PushAPIException
   *           upon error
   */
  public function addDocument($doc);

  /**
   * Adds a set of documents. If a document with the same URI has already been
   * added, the document will be updated.
   * 
   * @param documents
   *          Documents to be added, as as array() of Document or
   *          a DocumentEnumeration-derivated object.
   * @throws PushAPIException
   *           upon error.
   */
  public function addDocumentList($doc);

  /**
   * Updates a document.
   * 
   * Request to update a document. This replaces its stamp with the new one. It
   * also updates the index for the fields indicated in the list of fields
   * according to the document description. <br />
   * For instance, let's imagine you are pushing documents which contains a meta
   * named "ORG" which is mapped (indexing chain) on the index field
   * "organization".<br />
   * <code>
   * Document doc = new Document("mydocument", null);<br />
   * doc.addMeta(new Meta("ORG", "myorg"));<br />
   * papi.addDocument(doc);<br />
   * </code> <br />
   * If you want to update the value of the index field "organization", you will
   * need to write the following:<br />
   * <code>
   * Document doc = new Document("mydocument", null);<br />
   * doc.addMeta(new Meta("ORG", "myneworg");<br />
   * papi.updateDocument(doc, new String[] {"organization"});<br />
   * </code>
   * <p />
   * If the document does not exist yet, an error is returned by the push
   * server.
   * 
   * @param doc
   *          the document to update
   * @param fields
   *          the list of index fields to update. (array())
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function updateDocument($doc, $fields);

  /**
   * Updates a set of documents.
   * 
   * If one of the documents does not exist, an error is returned by the push
   * server. As the update document commands are processed sequentially, all the
   * updates priori to the one which failed are taken into account. However,
   * consider it is require to send again the whole set of documents once the
   * document in error has been removed from the list.
   * 
   * @param docList
   *          the list of documents to update, as as array() of Document or
   *          a DocumentEnumeration-derivated object.
   * @param fieldsList
   *          for each document, the fields to be updated (array(array())
   * 
   * @see #updateDocument(Document, String[])
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function updateDocumentList($doc, $fields);
  
  /**
   * Deletes a document specified by its URI.
   * 
   * If the document does not exist yet, the instruction is silently
   * ignored.
   * 
   * @param uri
   *          the URI or the array of URIs of the documents to delete
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function deleteDocument($uri);

  /**
   * Deletes a list of documents specified by their URIs.
   * 
   * For each document which do not exist yet, the instruction is silently
   * ignored.
   * 
   * @param uri
   *          the URI or the array of URIs of the documents to delete
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function deleteDocumentList($uri);
  
  /**
   * Deletes a set of documents (collection) as specified by a rootPath and
   * optionally recursively.
   * 
   * It is possible to only delete documents at the first level of the rootPath
   * (not recursively) by using the recursive flag.
   * 
   * @param rootPath
   *          URI prefix used to select a subset of the corpus. Empty string can
   *          be used to delete the whole collection. The character '/' is used
   *          to build a hierarchical view of your corpus. If your index
   *          contains a document with the URI <code>A/B</code>, you can use the
   *          call <code>papi.deleteDocumentrootPath("A/")</code> to delete it.
   * @param recursive
   *          indicates if the deletion should be recursive (follow sub folders)
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function deleteDocumentRootPath($rootPath, $recursive=false);
  
  /**
   * Sets a checkpoint in the indexing system.
   * 
   * Checkpoints are used when:
   * <ul>
   * <li>The connector must process a journalized or logged data source, which
   * can be abstractly represented as a flow of "add" and "delete" events in the
   * corpus and where an id can be used to refer to events on a timeline. The
   * Connector will then call from time to time the setCheckpoint command with
   * the id referring to the last add or delete events which have been sent to
   * the Indexing System.</li>
   * <li>Crash-proof synchronization is required. Upon crash, or system restart,
   * the Connector will call the getCheckpoint method to retrieve the last
   * checkpoint saved by the Indexing System. The Indexing System guarantees
   * that any add or delete commands called before that checkpoint were saved
   * and will never be lost.</li>
   * <li>In order to keep track of the synchronization.</li>
   * </ul>
   * <p />
   * If the checkpoint already exists, it's value is replaced with the new
   * value.
   * <p />
   * Note that on the server side, checkpoints are not automatically
   * synchronized on disk, unless you set the value of the <b>sync</b> attribute
   * to true. This means that a <b>getCheckpoint</b> operation called
   * immediately after a <b>setCheckpoint</b> operation might not return the
   * last value set. Refer to the Exalead CloudView documentation for more
   * information on the handling of indexing tasks by the push server.
   * <p />
   * The setCheckpoint operation returns the serial of the last pending
   * operation before the checkpoint which can be used to check when the
   * documents sent prior this checkpoint can be searched. See
   * {@link #areDocumentsSearchable(BigInteger)} for more information.
   * 
   * @param value
   *          the value of the checkpoint
   * @param name
   *          the name of the checkpoint
   * @param sync
   *          if all the pending operations should be synchronized to disk
   *          before returning
   * 
   * @return the serial of the last pending operation before the checkpoint.
   * 
   * @throws PushAPIException
   *           upon error
   * 
   * @see #sync()
   * @see #areDocumentsSearchable(BigInteger)
   */
  public function setCheckpoint($value, $sync=false, $name=false);

  /**
   * Get the value of a named checkpoint in the indexing process.
   * 
   * @param name
   *          the name of the checkpoint
   * 
   * @return the last synchronized value set for this checkpoint.
   * 
   * @throws PushAPIException
   *           upon error
   * 
   * @see #setCheckpoint(String, String, boolean)
   */
  public function getCheckpoint($name=false);

  /**
   * Check whether documents sent prior a given <b>setCheckpoint</b> operation
   * are searchable.
   * 
   * @param serial
   *          the serial returned by a <b>setChecpoint</b> operation
   * 
   * @return true if the documents sent prior a given <b>setCheckpoint</b> are
   *         searchable
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function areDocumentsSearchable($serial);

  /**
   * Gets the status of a given document in the indexing chain.
   * 
   * Retrieves the status of a document within the Indexed Corpus specified by
   * the URI parameters. This status can be used by the connector to determine
   * whether the document needs to be updated, or deleted.
   * <p />
   * When the document exists, it does not necessarily means that it has been
   * indexed but at least that it is known by the indexing chain. See
   * {@link #areDocumentsSearchable} for more information on how to know whether
   * a document is available to search.
   * 
   * @param uri
   *          URI of the document.
   * @return the status of the document as string stamp, or false if the
   *         document does not exist. (use '=== false' or '!== false')
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function getDocumentStatus($uri);

  /**
   * Gets the status of a given list of documents in the indexing chain.
   * 
   * @param uri
   *          the URIs of the documents for which the status should be retrieved
   * @return for each existing document the corresponding stamp, as
   *         an uri => stamp array. (use isset($ret[$url]) to check if the
   *         document was found or not)
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function getDocumentStatusList($uri);

  /**
   * Reset all the checkpoints values.
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function clearAllCheckpoints();

  /**
   * Enumerate all the checkpoints known by the system (synchronized).
   * 
   * @return an array of name => value checkpoint elements
   * 
   * @throws PushAPIException
   *           upon error
   * 
   * @see #setCheckpoint(String, String, boolean)
   * @see #sync()
   *
   * Example:
   * $cp = $papi->enumerateCheckpointInfo();
   * foreach($cp as $name => $value) {
   *   echo "Checkpoint: name='" . $name . "' value='" . $value . "'\n";
   * }
   */
  public function enumerateCheckpointInfo();

  /** Enumerate non recursively the folders contained in a given folder. */
  const NOT_RECURSIVE_FOLDERS = 'NOT_RECURSIVE_FOLDERS';

  /** Enumerate non recursively the documents contained in a given folder. */
  const NOT_RECURSIVE_DOCUMENTS = 'NOT_RECURSIVE_DOCUMENTS';

  /**
   * Enumerate non recursively the documents and folders contained in a given
   * folder.
   */
  const NOT_RECURSIVE_ALL = 'NOT_RECURSIVE_ALL';

  /** Recursively enumerate all the documents contained in a given folder. */
  const RECURSIVE_DOCUMENTS = 'RECURSIVE_DOCUMENTS';
  
  /**
   * Enumerate synchronized entries.
   * 
   * Synchronized entries are entries that have been effectively synchronized to
   * disk. This method allows you to enumerate both documents but also folders.
   * Folders are virtual objects built from the document hierarchical view.
   * 
   * @param rootPath
   *          URI prefix used to select a subset of the corpus. Empty string can
   *          be used to delete the whole collection. The character '/' is used
   *          to build a hierarchical view of your corpus
   * @param mode
   *          the enumeration mode
   * 
   * @return an array of uri => stamp synchronized entries
   * 
   * @throws PushAPIException
   *           upon error
   * 
   * @see #sync()
   * 
   * Example:
   * $se = $papi->enumerateSyncedEntries('http://example.com/',
   *                                     PushAPI::RECURSIVE_DOCUMENTS);
   * foreach($se as $url => $stamp) {
   *   echo "Entry: url='" . $url . "' stamp='" . $stamp . "'\n";
   * }
   */
  public function enumerateSyncedEntries($rootPath, $mode);

  /**
   * Count the number of synchronized entries.
   * 
   * @param rootPath
   *          URI prefix used to select a subset of the corpus. Empty string can
   *          be used to delete the whole collection. The character '/' is used
   *          to build a hierarchical view of your corpus
   * 
   * @param mode
   *          the enumeration mode
   * @return the number of synchronized entries found
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function countSyncedEntries($rootPath, $mode);

  /**
   * Force the synchronization of all pending operations before returning.
   * <p />
   * Every operation sent to the push server are kept in a tasks log. These
   * tasks are not automatically synchronized to disk as the synchronization
   * operation requires the use of IO operations which can consume some time.
   * Thus, the synchronization is done asynchronously. This method can be used
   * to force the tasks log synchronization. Refer to the Exalead CloudView
   * documentation for more information on the tasks log and related components.
   * 
   * @throws PushAPIException
   *           upon error
   */
  public function sync();
}

?>
