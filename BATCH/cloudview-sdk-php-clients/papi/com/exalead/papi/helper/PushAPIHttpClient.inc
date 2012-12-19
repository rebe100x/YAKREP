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

require('HttpClient.inc');

class PushAPIHttpClient extends HttpClient {

  public function __construct($host, $port,
                              $connectorName, $connectorType=false,
                              $login=false, $password=false) {
    $this->connectorName = $connectorName;
    $this->connectorType = $connectorType;
    parent::__construct($host, $port, $login, $password);
    if ($this->connectorType !== false) {
      $this->addCustomRequestHeader('X-Connector-Type: '
                                    . $this->connectorType);
    }

    /* Ping the server to ensure that it is available AND to get the remote
       server's HTTP version */
    if (!$this->httpPing()) {
      throw new PushAPIException('Unable to ping host');
    }
    
    /* We can't assume the server is chunked-ready if not in 1.1 */
    $this->fixedLength = !$this->serverIsHTTP11();
    
    /* Chunked ? (streamed) */
    $this->setChunked(!$this->fixedLength);
  }

  public function setStaticArgs($staticArgs) {
    $this->staticArgs = $staticArgs;
  }
  
  static function check2xx($r) {
    if ($r === false || !is_array($r)) {
      throw new PushAPIException('bad response');
    } else if (intval($r['status'] / 100) != 2) {
      if (isset($r['body']) && strlen($r['body']) > 0) {
        $ser = new XMLPapiSerializer($r['body']);
        $err = $ser->getObject();
        if (isset($err['message'])) {
          throw new PushAPIException($err['message']);
        }
      }
      /* Else, display regular message */
      switch($r['status']) {
      case 400:
        throw new PushAPIException("An error occurred while parameters parsing or during command processing. The body of the result contains the error description (see below for the xml format of the error description)" . ':' . $r['message']);
        break;
      case 405:
        throw new PushAPIException("Usage of POST or GET method is strict with the HTTP Push API, only specified methods are authorized for each Command" . ':' . $r['message']);
        break;
      case 401:
        throw new PushAPIException("The access to connector's operations through HTTP is protected using basic authentication, and has been forbidden" . ':' . $r['message']);
        break;
      case 500:
        throw new PushAPIException("An unexpected error on the server side has occurred" . ':' . $r['message']);
        break;
      default:      
        throw new PushAPIException("HTTP error " . $r['status']
                                   . ':' . $r['message']);
        break;
      }
    }
  }
  
  protected function papiUrl($command, $args=false) {
    return '/papi/4/connectors/' . urlencode($this->connectorName)
      . '/' . $command
      . ( $args !== false ? $args : '' )
      . ( $this->staticArgs !== false
          ? ( ( $args !== false ? '&' : '?' ) . $this->staticArgs )
          : ''
          )
      ;
  }

  protected function papiExecuteDocumentListCommand($cmd, $doc) {
    $ser = new PushAPISerializer($doc, !$this->fixedLength);
    $boundary = $ser->boundary();

    /* do we have to compute the length ? */
    if ($this->fixedLength) {
      $bodySize = $ser->length();
      if ($bodySize === false) {
        $this->papiThrow($ser->getLastError());
      }
      $this->setChunked(false);
      $this->setBodySize($bodySize);
    } else {
      $this->setChunked(true);
    }     
    
    $request = $this->buildRequest('POST',
                                   $this->papiUrl($cmd),
                                   array('Content-Type: '
                                         . 'multipart/form-data; boundary='
                                         . $boundary)
                                   );

    if (!$this->requestSend($request)) {
      $this->papiThrow();
    }
    
    if (!$ser->serialize($this->ch)) {
      $this->papiThrow($ser->getLastError());
    }

    if (!$this->fixedLength) {
      if (!@fwrite($this->ch, "0\r\n\r\n")) {
        $this->papiThrow();
      }
    }
    
    if (!@fflush($this->ch)) {
      $this->papiThrow();
    }

    return $this->responseReceive();
  }
  
  protected function papiExecuteUrl($command, $args=false) {
    $this->setChunked(false);
    $this->setBodySize(0);
    return $this->request($this->buildRequest('POST',
                                              $this->papiUrl($command, $args)));
  }
  
  protected function papiParseVoid($resp) {
    $this->check2xx($resp);
    return false;
  }

  protected function papiParseString($resp) {
    $this->check2xx($resp);
    if (isset($resp['body'])) {
      return $resp['body'];
    } else {
      throw new PushAPIException('bad server response (no data)');
    }
  }

  protected function papiParseNumerical($resp) {
    $this->check2xx($resp);
    if (isset($resp['body']) && is_numeric($resp['body'])) {
      return intval($resp['body']);
    } else {
      throw new PushAPIException('bad server response (non-numerical)');
    }
  }

  protected function papiParseBoolean($resp) {
    $this->check2xx($resp);
    if (isset($resp['body'])) {
      if ($resp['body'] == 'true' || $resp['body'] == 'false') {
        return $resp['body'] == 'true';
      } else if ($resp['body'] == 'unknown') {
        throw new PushAPIException('unknown');
      } else {
        throw new PushAPIException('bad server response (non-boolean, response='
                                   . substr($resp['body'], 0, 10)
                                   . ')');
      }
    } else {
      throw new PushAPIException('bad server response (empty body, status='
                                 . $resp['status'] . ' ' . $resp['message']
                                 . ')');
    }
  }
  
  protected function papiParseObject($resp) {
    $this->check2xx($resp);
    $ser = new XMLPapiSerializer($resp['body']);
    return $ser->getObject();
  }

  protected function papiParseDataLines($resp) {
    $this->check2xx($resp);
    $ret = array();
    $data = str_replace("\r", '', $resp['body']);
    $arr = explode("\n", $data);
    $ret = array();
    foreach($arr as $line) {
      if (strlen($line) == 0) {
        continue;
      }
      $ret[] = explode(' ', $line);
    }
    return $ret;
  }

  protected function papiParseStampList($resp) {
    $data = $this->papiParseDataLines($resp);
    $ret = array();
    foreach($data as $st) {
      if (count($st) >= 2) {
        if ($st[0] == 'D') {
          $ret[urldecode($st[1])] = implode(' ', array_slice($st, 2));
        }
      } else {
        throw new PushAPIException('invalid reponse');
      }
    }
    return $ret;
  }

  public static function debug($msg) {
    echo $msg . "\n";
  }
  
  protected function papiThrow($msg = false) {
    if ($msg === false) {
      $msg = error_get_last();
    }
    if ($msg !== null) {
      throw new PushAPIException($msg['message'], $msg['file'], $msg['line']);
    } else {
      throw new PushAPIException('unknown error');
    }
  }
  
  protected function socketPapiThrow() {
    $msg = error_get_last();
    $this->close();
    throw new PushAPIException($msg['message'], $msg['file'], $msg['line']);
  }

  protected function badTypePapiThrow($obj, $expected) {
    throw new PushAPIException('Bad argument type: '
                               . gettype($obj) . ' instead of ' . $expected
                               );
  }
  
  private $connectorName;
  private $connectorType;

  protected $fixedLength = false;
  protected $staticArgs = false;
}


?>
