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

class HttpClient {
  public function __construct($host, $port, $login=false, $password=false) {
    if (!is_int($port)) {
      throw new PushAPIException('Bad port (' . $port . ')');
    }
    $this->host = $host;
    $this->port = $port;
    $this->login = $login;
    $this->password = $password;
    $this->ch = false;
    $this->customRequestHeaders = false;
    $this->bodySize = false;
    $this->chunked = false;
    $this->retryCount = 0;
    $this->retryDelay = 30;
  }
  
  protected function setChunked($enabled=true) {
    $this->chunked = $enabled;
    if ($enabled) {
      $this->bodySize = false;
    }
  }

  protected function setBodySize($size) {
    $this->bodySize = $size;
  }

  protected function serverIsHTTP11() {
    return $this->isHTTP11;
  }

  protected function setRetry($retryCount, $retryDelay) {
    $this->retryCount = $retryCount;
    $this->retryDelay = $retryDelay;
  }
  
  protected function query($args) {
    $q = false;
    foreach($args as $k => $v) {
      if (is_array($v)) {
        foreach($v as $vv) {
          if ($q === false) {
            $q = '?';
          } else {
            $q .= '&';
          }
          $q .= urlencode($k) . '=' . urlencode($vv);
        }
      } else {
        if ($q === false) {
          $q = '?';
        } else {
          $q .= '&';
        }
        $q .= urlencode($k) . '=' . urlencode($v);
      }
    }
    return $q;
  }

  protected function addCustomRequestHeader($h) {
    if ($this->customRequestHeaders === false) {
      $this->customRequestHeaders = array();
    }
    $this->customRequestHeaders[] = $h;
  }
  
  protected function ensureConnected() {
    for($i = 0 ; !is_resource($this->ch) && $i <= $this->retryCount ; $i++) {
      if ($i != 0) {
        sleep($this->retryDelay);
      }
      $this->ch = @fsockopen($this->host, $this->port, $errno, $errstr,
                             $this->connectTimeout);
      if (is_resource($this->ch) && $this->readTimeout > 0) {
        stream_set_timeout($this->ch, $this->readTimeout);
      }
    }
    if (!is_resource($this->ch)) {
      $this->socketPapiThrow();
    }
  }

  protected function buildRequest($cmd, $uri, $headers=false)  {
    $hdrs = array($cmd . ' ' . $uri . ' HTTP/1.0',
                  'Host: ' . $this->host . ':' . $this->port,
                  'User-Agent: Mercury Push API Php Client/PHP ' . phpversion(),
                  'Date: ' . @date('r'),
                  'Connection: keep-alive'
                  );
    // add credentials
    if ($this->login !== false) {
      $hdrs[] = 'Authorization: Basic '
        . base64_encode($this->login . ':' . $this->password);
    }
    if (count($this->customRequestHeaders) > 0) {
      foreach($this->customRequestHeaders as $h) {
        $hdrs[] = $h;
      }
    }
    if ($headers !== false) {
      foreach($headers as $h) {
        $hdrs[] = $h;
      }
    }
    return $hdrs;
  }

  protected function get($uri)  {
    return $this->request($this->buildRequest('GET', $uri));
  }
  
  protected function options($uri)  {
    return $this->request($this->buildRequest('OPTIONS', $uri));
  }

  public function httpPing() {
    $r = $this->options('*');
    return $r !== false && is_array($r) && intval($r['status'] / 100) == 2;
  }

  protected function requestSend($hdrs, $data=false) {
    $this->ensureConnected();
    $req = implode("\r\n", $hdrs) . "\r\n"
      . ( $this->chunked ? "Transfer-Encoding: chunked\r\n" : '' )
      . ( $this->bodySize !== false ? ( 'Content-Length: ' . $this->bodySize
                                        . "\r\n")
          : '' )
      . "\r\n";
    $this->bodySize = false;
    //PushAPIv4::debug("<<< ".$req);
    return $this->write($req) !== false;
  }

  protected function fread($ch, $size) {
    for($result = '', $data = '' ; $size > 0 ; $size -= strlen($data)) {
      $data = @fread($ch, $size);
      if ($data === false || strlen($data) == 0) {
        return false;
      }
      $result .= $data;
    }
    return $result;
  }

  protected function responseReceive() {
    $hdr = $this->readHdrs();
    if (!$this->isEmptyHttpCode($hdr['status'])) {
      /* known size */
      if (isset($hdr['content-length'])) {
        $len = intval($hdr['content-length']);
        $hdr['body'] = $len != 0 ? $this->fread($this->ch, $len) : '';
        if ($hdr['body'] === false) {
          $this->papiThrow();
        }
      }
      /* too much data for remote buffering (unknown size) ; eat everything */
      else {
        $hdr['body'] = '';
        while(!feof($this->ch)) {
          $data = @fread($this->ch, 8192);
          if ($data === false || strlen($data) == 0) {
            if (!feof($this->ch)) {
              $this->papiThrow();
            } else {
              break;
            }
          }
          $hdr['body'] .= $data;
        }
      }
    }
    /* empty */
    else {
      $hdr['body'] = '';
    }
    /* connection: close */
    if (!isset($hdr['connection'])
        || strtolower($hdr['connection']) != 'keep-alive') {
      @fclose($this->ch);
      $this->ch = false;
    }
    return $hdr;
  }
  
  protected function request($hdrs, $data=false) {
    if (!$this->requestSend($hdrs)
        || ($data !== false && $this->writeData($data) == false)
        || !@fflush($this->ch)) {
      $this->papiThrow();
    }
    return $this->responseReceive();
  }

  protected function readHdrs() {
    $hdr = array();
    $line = $this->readLine();
    $arr = explode(' ', $line);
    if (count($arr) < 3
        || ( strcasecmp($arr[0], 'HTTP/1.0') != 0
             && strcasecmp($arr[0], 'HTTP/1.1') != 0 )
        || !is_numeric($arr[1])) {
      $this->close();
      throw new PushAPIException('Bad HTTP response: ' . substr($line, 0, 16));
    }
    $this->isHTTP11 = strcasecmp($arr[0], 'HTTP/1.1');
    $hdr['status'] = intval($arr[1]);
    $hdr['message'] = $arr[2];
    for($line = $this->readLine()
          ; $line !== false && strlen($line) != 0
          ; $line = $this->readLine()) {
      $arr = explode(':', $line);
      if (count($arr) == 2) {
        $field = strtolower($arr[0]);
        $value = ltrim($arr[1]);
        $hdr[$field] = $value;
      }
    }
    return $hdr;
  }
  
  protected function readLine() {
    $line = @fgets($this->ch, 32768);
    if ($line !== false) {
      if (substr($line, -2) == "\r\n") {
        $line = substr($line, 0, strlen($line) - 2);
      }
      else if (substr($line, -1) == "\n" || substr($line, -1) == "\r") {
        $line = substr($line, 0, strlen($line) - 1);
      }
      //PushAPIv4::debug('>>> '.$line);
      return $line;
    }
    $this->papiThrow();
  }

  protected static function isEmptyHttpCode($status) {
    return ( $status >= 100 && $status <= 199 )
      || $status == 204  /* no content */
      || $status == 304  /* not modified */
      ;
  }
 
  public function close() {
    if ($this->ch !== false) {
      if (is_resource($this->ch)) {
        fclose($this->ch);
      }
      $this->ch = false;
    }
  }
  
  protected function write($data) {
    //if (strlen($data) < 512) {
    //  PushAPIv4::debug("<<< ".$data);
    //}
    return @fwrite($this->ch, $data);
  }

  protected function writeData($data) {
    $this->write($data);
  }

  protected $ch;
  private $host;
  private $port;

  private $login;
  private $password;

  private $connectTimeout = 3;
  private $readTimeout = 30;

  private $customRequestHeaders;

  private $chunked = false;
  private $bodySize = false;
  private $isHTTP11 = false;
}

?>
