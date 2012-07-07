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

require('PushAPIHttpClient.inc');

/**
 * Push API Version 4 implementation.
 * 
 * Main entry point for every push API calls.
 */
class PushAPIv4 extends PushAPIHttpClient /* implements PushAPI */ {
  /**
   * Create a new push client instance.
   * @param host the host name of the push server
   * @param port the port number of the push server 
   * @param connectorName the name of the connector to be used. For more information on connectors, refer to the Exalead CloudView documentation set.
   * @param connectorType the type of the connector to be used (mainly used for license checking purpose;  the available connector types are described in your product's license)
   * @param login identification login to be used if the target connector is secured
   * @param password password associated with the identification login
   * @return an new push client instance.
   * @throws PushAPIException upon error
   **/
  function __construct($host, $port, $connectorName, $connectorType=false,
                       $login=false, $password=false) {
    parent::__construct($host, $port, $connectorName, $connectorType,
                       $login, $password);
  }

  function __destruct() {
    $this->close();
  }

  public function getSourceName() {
    return $this->connectorName;
  }
  
  public function ping() {
    $this->check2xx($this->get($this->papiUrl('ping')));
  }

  public function addDocument($doc) {
    return $this->papiExecuteDocumentListCommand('add_documents', $doc);
  }

  public function addDocumentList($doc) {
    return $this->addDocument($doc);
  }
  
  public function updateDocument($doc, $fields) {
    $doc->metas['directive:field'] = $fields;
    $ret = $this->papiExecuteDocumentListCommand('update_documents', $doc);
    unset($doc->metas['directive:field']);
    return $ret;
  }

  public function updateDocumentList($doc, $fields) {
    if (!is_array($doc)) {
      $this->badTypePapiThrow($doc, 'array');
    }
    if (count($doc) == 0) {
      throw new PushAPIException('fields is empty: no field(s) to update');
    }
    if (!is_array($fields)) {
       $this->badTypePapiThrow($fields, 'array');
    }
    if (count($doc) != count($fields)) {
      throw new PushAPIException('doc and fields do not have the same number of elements');
    }
    for($i = 0 ; $i < count($doc) ; $i++) {
      if (!isset($doc[$i])) {
        throw new PushAPIException('doc is not an integer-based index array');
      }
      if (!isset($fields[$i])) {
        throw new PushAPIException('fields is not an integer-based index array');
      }
      $this->updateDocument($doc[$i], $fields[$i]);
    }
  }
  
  public function deleteDocumentList($uri) {
    return $this->deleteDocument($uri);
  }
  
  public function deleteDocument($uri) {
    return $this->papiExecuteUrl('delete_documents',
                                 $this->query(array('PAPI_uri' => $uri)));
  }

  public function deleteDocumentRootPath($rootPath, $recursive=false) {
    return $this->papiExecuteUrl('delete_documents_root_path',
                                 $this->query(array('PAPI_rootPath' =>
                                                    $rootPath,
                                                    'PAPI_recursive' =>
                                                    $recursive
                                                    ? 'true' : 'false')
                                              )
                                 );
  }

  // Note: BigInteger overflows php's int
  public function setCheckpoint($value, $sync=false, $name=false) {
    return $this->papiParseString($this->papiExecuteUrl
                                  ('set_checkpoint',
                                   $this->query(array('PAPI_checkpoint' =>
                                                      $value,
                                                      'PAPI_sync' =>
                                                      $sync ? 'true'
                                                      : 'false',
                                                      'PAPI_name' => $name
                                                      )
                                                )
                                   )
                                  );
  }

  public function getCheckpoint($name=false) {
    $resp = $this->papiExecuteUrl('get_checkpoint',
                                  $this->query(array('PAPI_name' => $name)));
    $this->check2xx($resp);
    return isset($resp['body']) ? $resp['body'] : false;
  }
  
  public function areDocumentsSearchable($serial) {
    return $this->papiParseBoolean($this->papiExecuteUrl
                                   ('are_documents_searchable',
                                    $this->query(array('PAPI_serial' => $serial
                                                       )
                                                 )
                                    )
                                   );
  }

  public function triggerIndexingJob() {
    return $this->papiExecuteUrl('trigger_indexing_job');
  }
  
  public function getDocumentStatusList($uri) {
    return $this->papiParseStampList($this->papiExecuteUrl
                                     ('get_documents_status',
                                      $this->query(array('PAPI_uri' => $uri
                                                         )
                                                   )
                                      ));
  }
  
  public function enumerateSyncedEntries($rootPath, $mode) {
    return $this->papiParseStampList($this->papiExecuteUrl
                                     ('enumerate_synced_entries',
                                      $this->query(array
                                                   ('PAPI_rootPath' =>
                                                    $rootPath,
                                                    'PAPI_enumerationMode' =>
                                                    $mode
                                                    )
                                                   )
                                      ));
  }

  public function getDocumentStatus($uri) {
    if (is_array($uri)) {
      throw new PushAPIException('please use getDocumentStatusList() when using an array of URIs');
    }
    $st = $this->getDocumentStatusList($uri);
    if (isset($st[$uri])) {
      return $st[$uri];
    }
    return false;
  }

  public function clearAllCheckpoints() {
    return $this->papiParseVoid($this->papiExecuteUrl('clear_all_checkpoints'));
  }

  public function enumerateCheckpointInfo() {
    $data = $this->papiParseDataLines
      ($this->papiExecuteUrl('enumerate_checkpoints_info'));
    $ret = array();
    foreach($data as $st) {
      if (count($st) == 2) {
        $ret[$st[0]] = $st[1];
      } else {
        throw new PushAPIException('invalid reponse');
      }
    }
    return $ret;
  }

  public function countSyncedEntries($rootPath, $mode) {
    return $this->papiParseNumerical($this->papiExecuteUrl
                                     ('counted_synced_entries',
                                      $this->query(array
                                                   ('PAPI_rootPath' =>
                                                    $rootPath,
                                                    'PAPI_enumerationMode' =>
                                                    $mode
                                                    )
                                                   )
                                      ));
  }

  public function sync() {
    return $this->papiParseVoid($this->papiExecuteUrl('sync'));
  }

  public function startPushSession() {
    if ($this->sessionId === false) {
      $this->sessionId = $this->papiParseString($this->papiExecuteUrl
                                                ('get_current_session_id'));
      $this->setStaticArgs('PAPI_session=' . $this->sessionId);
    }
  }

  protected $sessionId = false;
}

?>