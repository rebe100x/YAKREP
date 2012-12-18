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

class PushAPISerializer {
  function __construct($doc, $chunked=false) {
    $this->doc = $doc;
    $this->chunked = $chunked;
    $this->buff = '';
    $this->b = PushAPISerializer::generateBoundary60();

    $this->nextVar1Len = strlen(PushAPISerializer::nextVar1);
    $this->nextVar2Len = strlen(PushAPISerializer::nextVar2);
    $this->nextVar3Len = strlen(PushAPISerializer::nextVar3);
    $this->nextVar4Len = strlen(PushAPISerializer::nextVar4);
  }
  
  public function boundary() {
    return $this->b;
  }
  
  public function length() {
    $this->id = 0;
    $this->ret = 0;
    $this->ser = false;
    if (!$this->process($this->doc)) {
      return false;
    }
    return $this->ret;
  }
  
  public function serialize($ch) {
    if (!is_resource($ch)) {
      throw new Exception("ch is not a stream resource");
    }
    $this->ch = $ch;
    $this->id = 0;
    $this->ret = false;
    $this->ser = true;
    if (!$this->process($this->doc)) {
      return false;
    }
    if ($this->chunked) {
      if (!$this->write(false)) {
        return false;
      }
    }
    return true;
  }

  public function getLastError() {
    return $this->lasterror;
  }

  protected function setLastError() {
    $err = error_get_last();
    if ($err === null) {
      throw new Exception('internal error');
    }
    $this->lasterror = $err;
  }

  public static $rbnd
    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  /* 60 bytes */
  protected static function generateBoundary60() {
    $len = strlen(PushAPISerializer::$rbnd);
    $r = '--MultiPart_';                   /* 12 bytes */
    for($i = 0 ; $i < 40 ; $i++) {         /* 40 bytes */
      $r .= PushAPISerializer::$rbnd[mt_rand() % $len];
    }
    $r .= substr(md5(microtime()), 0, 8);  /* 8 bytes */
    return $r; 
  }

  const nextVar1 = "\r\nContent-Disposition: form-data; name=\"";
  const nextVar2 = "\"\r\n";
  const nextVar3 = 'Content-Type: ';
  const nextVar4 = 'Content-Length: ';
  
  protected function write($data) {
    //if (strlen($data) < 512) {
    //  PushAPIv4::debug("<<< ".$data);
    //}

    if ($this->chunked) {
      if ($data !== false) {
        $this->buff .= $data;
      }
      $len  = strlen($this->buff);
      if ($len > 8192 || ( $data === false && $len > 0 ) ) {
        $ret = @fwrite($this->ch, dechex($len) . "\r\n")
          && ( $len == 0 || @fwrite($this->ch, $this->buff) )
          && @fwrite($this->ch, "\r\n");
        $this->buff = '';
        return $ret;
      } else {
        return true;
      }
    } else {
      return @fwrite($this->ch, $data);
    }
  }
  
  protected function nextVar($name, $len, $type=false) {
    if ($this->ser) {
      if ($this->write
          ('--'
           . $this->b
           . PushAPISerializer::nextVar1
           . $name.PushAPISerializer::nextVar2
           . ($type !== false ? PushAPISerializer::nextVar3
              . $type
              . "\r\n" : '')
           . ( $this->chunked ? ''
               : ( PushAPISerializer::nextVar4 . strval($len) . "\r\n" )
               )
           . "\r\n"
           ) === false) {
        $this->setLastError();
        return false;
      }
      return true;
    } else {
      $this->ret += 62 /* boundary */
        + $this->nextVar1Len
        + strlen($name)
        + $this->nextVar2Len
        + ($type !== false
           ? $this->nextVar3Len + strlen($type) + 2
           : 0)
        + $this->nextVar4Len + strlen(strval($len)) + 2
        + 2
        ;
      return true;
    }
  }

  protected function nextSubPart($name, &$v, $type=false) {
    return $this->nextVar('PAPI_'.$this->id.':'.$name, $this->dataSize($v),
                          $type)
      && $this->nextData($v);
  }
  
  protected function endVar() {
    if ($this->ser) {
      if ($this->write('--' . $this->b . "--\r\n") === false) {
        $this->setLastError();
        return false;
      }
      return true;
    } else {
      $this->ret += 66;
      return true;
    }
  }

  // Data size (string or stream)
  protected static function dataSize(&$v) {
    if (is_resource($v)) {  // file or stream
      $a = fstat($v);
      if (!is_array($a) || !isset($a['size'])) {
        throw new Exception('stat failed');
      }
      return $a['size'];
    } else if ($v === false) {  /* no data */
      return 0;
    } else if ($v instanceof ContentStream) {
      return $v->size();
    } else {
      return strlen(strval($v));
    }
  }
  
  // Put raw data
  protected function nextData(&$v) {
    if ($this->ser) {
      if (is_resource($v)) {  // file or stream
        $data = false;
        for($size = $this->dataSize($v), $data = '' ; $size > 0
              ; $size -= strlen($data)) {
          $data = @fread($v, 8192);
          if ($data === false || strlen($data) == 0) {
            $this->setLastError();
            return false;
          }
          if ($this->write($data) === false) {
            $this->setLastError();
            return false;
          }
        }
        unset($data);
      } else if ($v instanceof ContentStream) {
        if ($this->write($v->data()) == false) {
          $this->setLastError();
          return false;
        }
      } else {
        if ($this->write($v) === false) {
          $this->setLastError();
          return false;
        }
      }
      if ($this->write("\r\n") === false) {
        $this->setLastError();
        return false;
      }
      return true;
    } else {
      if (is_resource($v)) {  // file or stream
        $this->ret += $size;
      } else if ($v instanceof ContentStream) {
        $this->ret += $v->size();
      } else {
        $this->ret += strlen(strval($v));
      }
      $this->ret += 2;
      return true;
    }
  }
  
  protected function nextPart($name, &$part) {
    if ($part instanceof Part) {
      // Push all meta-data for the document #id
      foreach($part->metas as $k => &$v) {
        if (is_array($v)) {
          foreach($v as $vv) {
            if (!$this->nextSubPart('part_directive:' . $name . ':' . $k,
                                    $vv)) {
              return false;
            }
          }
        } else {
          if (!$this->nextSubPart('part_directive:' . $name . ':' . $k, $v)) {
            return false;
          }
        }
      }
      // Put data at last
      if (!$this->nextSubPart('part_bytes:'.$name, $part->content,
                              'application/octet-stream')) {
        return false;
      }
      return true;
    } else {
      $this->badTypePapiThrow($doc, 'Part');
    }
  }

  protected function process(&$doc, $sub=false) {
    if (is_array($doc)) {
      foreach($doc as &$d) {
        if (!$this->process($d, true)) {
          return false;
        }
      }
    } else if ($doc instanceof Document) {
      // Push all meta-data for the document #id
      foreach($doc->metas as $k => &$v) {
        if (is_array($v)) {
          foreach($v as $vv) {
            if (!$this->nextSubPart($k, $vv)) {
              return false;
            }
          }
        } else {
          if (!$this->nextSubPart($k, $v)) {
            return false;
          }
        }
      }
      // Scan parts
      foreach($doc->parts as $partName => &$part) {
        if (!$this->nextPart($partName, $part)) {
          return false;
        }
      }
      // One more id
      $this->id++;
    } else if ($doc instanceof DocumentEnumeration) {
      if (!$this->ser) {
        throw new Exception('DocumentEnumeration objects can not be used on a non-streamed channel (HTTP/1.0 ?)');
      }
      for($doc->start() ; ( $d = $doc->next() ) !== false ; ) {
        if (!$this->process($d, true)) {
          return false;
        }
      }
      $doc->end();
    } else {
      @trigger_error('not a Document object');
      $this->setLastError();
      return false;
    }
    // End
    if (!$sub) {
      return $this->endVar();
    } else {
      return true;
    }
  }

  protected $ch;
  protected $b;
  protected $id;
  protected $doc;
  protected $ser;
  protected $ret;
  protected $lasterror;
  protected $chunked;
  protected $buff;
}

?>
