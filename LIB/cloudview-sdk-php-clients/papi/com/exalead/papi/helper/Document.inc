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
 * Class representing a document to be indexed.
 * 
 * A document contains the following information
 * <ul>
 * <li>uri: the document unique identifier</li>
 * <li>stamp: the document stamp</li>
 * <li>metas: a set of meta data associated with the document</li>
 * <li>parts: a set of parts (raw binary content) associated with the document</li>
 * <li>directives: a set of meta information used optionally as hints by the
 * indexing chain to control the indexing of the document (for instance, force
 * an internal identifier, force the slice on which the document should be
 * pushed ...)</li>
 * </ul>
 * <p />
 * A document can be pushed using a push client ({@link PushAPI}).
 **/
class Document {
  /**
   * Document constructor specifying the whole document content.
   * Arguments are given in a key => value array.
   * 
   * @param 'uri' (mandatory)
   *          the unique identifier of the document.
   *          must be a string.
   * @param 'stamp'
   *          the stamp of the document (string).
   * @param 'parts'
   *          the part or the array of name => parts of documents.
   *          must be a Part or array(Part). if a single part is used, the
   *          implicit part name is "master"
   * @param 'metas'
   *          the array of meta data of the document.
   *          must be an array() of key => value pairs or an array() of
   *          key => stream pairs.
   * @param 'directives'
   *          the array of directives of the document.
   *          must be an array() of key => value pairs or an array() of
   *          key => stream pairs.
   * @param 'fields'
   *          array of fields to update
   *
   * Example:
   *   $doc = new Document(array('uri' => 'http://example.com/doc.html',
   *                             'parts' => $myParts));
   *
   */
  public function __construct($args) {
    if (!is_array($args)) {
      $this->badTypePapiThrow($args, 'array');
    }
    // "this->metas" are internal raw metas (after PAPI_id:)
    $this->metas = array();
    if (isset($args['raw_metas'])) {
      if (is_array($args['raw_metas'])) {
        $this->metas = $args['raw_metas'];
      } else if ($args['raw_metas'] !== false) {
        $this->badTypePapiThrow($args['raw_metas'], 'array');
      }
    }
    if (isset($args['metas'])) {
      if (is_array($args['metas'])) {
        foreach($args['metas'] as $k => &$v) {
          $this->metas['meta:' . $k] = $v;
        }
      } else if ($args['metas'] !== false) {
        $this->badTypePapiThrow($args['metas'], 'array');
      }
    }
    if (isset($args['directives'])) {
      if (is_array($args['directives'])) {
        foreach($args['directives'] as $k => &$v) {
          $this->metas['directive:' . $k] = $v;
        }
      } else if ($args['directives'] !== false) {
        $this->badTypePapiThrow($args['directives'], 'array');
      }
    }
    if (isset($args['uri']) && is_string($args['uri'])) {
      $this->metas['uri'] = $args['uri'];
    } else {
      $this->badTypePapiThrow($args['uri'], 'string');
    }
    /* can be undefined for updates */
    if (isset($args['stamp'])) {
      $this->metas['stamp'] = $args['stamp'];
    }
    $this->parts = array();
    if (isset($args['parts'])) {
      $this->addPart($args['parts']);
    }
  }

  protected function addInternalMeta($key, $value) {
    if (!isset($this->metas[$key])) {
      $this->metas[$key] = $value;
    } else {
      if (is_string($this->metas[$key])) {
        $this->metas[$key] = array($this->metas[$key]);
      }
      $this->metas[$key][] = $value;
    }
  }

  public function addCustomDirective($key, $value) {
    addInternalMeta('directive:' . $key, $value);
  }

  public function addMeta() {
    addInternalMeta('meta:' . $key, $value);
  }

  public function setForcedDid($value) {
    $this->metas['forcedDid'] = $value;
  }
  
  public function setForcedSlice($value) {
    $this->metas['forcedSlice'] = $value;
  }

  public function setStamp($value) {
    $this->metas['stamp'] = $value;
  }

  public function setUri($value) {
    $this->metas['uri'] = $value;
  }
  
  public function addPart($part) {
    if ($part instanceof Part) {
      $this->parts = array('master' => $part);
    } else if (is_array($part)) {
      $this->parts = array_merge($this->parts, $part);
    } else if ($part !== false) {
      $this->badTypePapiThrow($part, 'Part');
    }
  }
  
  /** Document meta data. Must be an array() of key => value pairs or an
      array() of key => stream pairs.. */
  public $metas;

  /** Document parts. Must be a Part or array(Part) */
  public $parts;

  protected function badTypePapiThrow($obj, $expected) {
    throw new PushAPIException('Bad argument type: '
                               . gettype($obj) . ' instead of ' . $expected
                               );
  }
}

?>