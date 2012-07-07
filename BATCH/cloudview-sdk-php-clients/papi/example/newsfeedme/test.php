<?php
require('../../PushAPI.inc');
require('bleachlib.inc');

/* Process the article */

function safeGet(&$arr, $index) {
  if (isset($arr[$index])) {
    return $arr[$index];
  }
  return false;
}

function getUri($mid) {
  return 'news:' . substr($mid, 1, strlen($mid) - 2);
}

function processArticle($papi, &$hdrs) {
  /* Sanity check */
  if (isset($hdrs['control'])) {
    return false;
  }
  if (!isset($hdrs['body'])) {
    return true;
  }
  
  /* Define missing headers if necessary */
  $moreHeaders = '';
  if (!isset($hdrs['content-type'])) {
    $moreHeaders .= "Content-Type: text/plain\r\n";
  }
  
  /* Part is a regular RFC822 message */
  $part = new Part(array('content' => $hdrs['raw_hdrs'] . $moreHeaders
                         . "\r\n" . $hdrs['body'],
                         'directives' => array('mimeHint' => 'message/rfc822')
                         )
                   );
  
  /* Build category paths */
  $paths = array();
  foreach(explode(',', $hdrs['newsgroups']) as $group) {
    $paths[] = str_replace('.', '/', $group);
    break; /* FIXME ONLY ONE PATH BY DEFAULT */
  }

  /* "The" URI */
  $uri = getUri($hdrs['message-id']);
  
  /* Meta-datas */
  $metas = array('publicurl' => $uri,
                 'file_name' => substr($uri, 5) . '.eml',
                 'path' => $paths,
                 'file_size' => strlen($hdrs['body']),
                 'std:numlines' => safeGet($hdrs, 'lines')
                 );
  
  /* Merge references to parent(s) */
  $threadUri = false;
  if (isset($hdrs['references'])) {
    $refUri = array();
    foreach(explode(' ', str_replace('><', '> <', $hdrs['references']))
            as $ref) {
      $ref = trim($ref);
      if (strlen($ref) != 0) {
        $refUri[] = getUri($ref);
      }
    }
    $metas['parent_count'] = count($refUri);
    for($i = 0 ; $i < count($refUri) ; $i++) {
      $metas['parent'.($i + 1).'_uri'] = $refUri[$i];
    }
    /* First element is the top thread ID (the one which started the thread)*/
    if (count($refUri) != 0) {
      $threadUri = $refUri[0];
    }
  }
  if ($threadUri == false) {
    $threadUri = $uri;
  }
  $metas['thread_uri'] = $threadUri;

  /* FIXME TEST - pseudo-thread identifier in path */
  $metas['path'][0] .= '/' . substr(md5($threadUri), 0, 8);

  /* Create document */
  $doc = new Document(array('uri' => $uri,
                            'parts' => $part,
                            'metas' => $metas
                            )
                      );

  /* Sent the document to the Push API */
  $resp = $papi->addDocument(array($doc));

/*   if (isset($hdrs['content-type']) */
/*       && strpos($hdrs['content-type'], 'iso-8859-15') !== false) { */
/*     //$stdout = fopen('php://stdout', 'w'); */
/*     $stdout = fopen('/tmp/out', 'w'); */
/*     $ser = new PushAPISerializer($doc); */
/*     $ser->serialize($stdout); */
/*     echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>\n"; */
/*     fclose($stdout); */
/*   } */
  
  return true;
}

/* Delete an article */
function deleteArticle($papi, $mid) {
  $papi->deleteDocument(getUri($mid));
}

/* The Exalead "newsburst" server address and port */
$newsburst = 'reddmz005.prod.exalead.com';
$newsburstport = 42119;
$flushTime = 60;

/* Connect to PAPI */
echo "Create\n";
if (count($argv) != 4) {
  $stderr = fopen('php://stderr', 'w');
  fwrite($stderr, "Usage: " . $argv[0]
         . " papi_hostname papi_port papi_source\n");
  fwrite($stderr, "Example: " . $argv[0] . " localhost 10002 default\n");
  exit;
}
$papi = PushAPIFactory::createHttp($argv[1], intval($argv[2]), $argv[3]);

echo "Ping\n";
$papi->ping();

/* Let's have some burst */
$last = time();
$serial = false;
$flushRetry = 0;
$seen = 0;
$checkpoint = intval($papi->getCheckpoint());
for($fp = false ; ;) {
  /* (Re)Open a connection to newsburst */
  if ($fp === false) {
    echo "Connecting to newsburst: ";
    for($i = 0 ; $i < 60 ; $i++) {
      $fp = @fsockopen($newsburst, $newsburstport, &$errno, &$errstr, 120);
      if ($fp !== false && is_resource($fp)) {
        break;
      } else {
        echo '. ';
        sleep(1);
      }
    }
    if (!is_resource($fp)) {
      throw new Exception("Unable to open socket to newsburst: "
                          . socket_strerror(socket_last_error()));
    }
    echo "DONE\n";
  }
  
  /* Wait for data */
  $r = $e = array($fp);
  $p = stream_select($r, $w = null, $e, 1);
  if ($p === false) {
    $e = array($fp);
    echo "Error: " . socket_strerror(socket_last_error()) . "\n";
  }
  
  /* Error ? */
  if (count($e) != 0) {
    echo "Closing connection to newsburst\n";
    fclose($fp);
    $fp = false;
    sleep(3);
    continue;
  }
  
  /* Read available */
  if (count($r) > 0) {
    $hdrs = false;
    if (takethisclean($hdrs, $fp) && $hdrs !== false && is_array($hdrs)
        && isset($hdrs['message-id'])) {
      /* Cancel */
      if (isset($hdrs['control'])) {
        /* Example:
           Control: cancel <49b45d0a-8c8d-4d6a-876c-781f659f6319@s21g2000prm.googlegroups.com>
        */  
        if (substr($hdrs['control'], 0, 8) == 'cancel <') {
          $mid = substr($hdrs['control'], 7, strlen($hdrs['control']) - 7);
          echo "Deleting " . $mid . ": ";
          if (!deleteArticle($papi, $mid)) {
            /* Ignored */
          }
          echo "DONE\n";
        }
      }
      /* Regular message */
      else {
        echo "Adding " . $hdrs['message-id'] . ": ";
        if (!processArticle($papi, $hdrs)) {
          throw new Exception("Unable to push article to PAPI");
        }
        echo "DONE\n";
      }
      $seen++;
    } else {
      echo "Error: closing socket\n";
      fclose($fp);
      $fp = false;
      sleep(3);
    }
  } else {
    echo "Sleeping: ";
    sleep(1);
    /* Ping at least the PAPI to keep the connection alive */
    $papi->ping();
    echo "DONE\n";
  }

  /* Time to trigger indexing job ? */
  if (time() - $last > $flushTime) {
    /* Flush if pending documents */
    if ($seen > 0) {
      try {
        if ($serial === false || $papi->areDocumentsSearchable($serial)) {
          $serial = $papi->setCheckpoint(0, true);
          echo "Triggering indexing job, serial=" . $serial . ": ";
          $papi->triggerIndexingJob();
          $seen = 0;
          $flushRetry = 0;
          echo "DONE\n";
        } else {
          echo "Can not yet trigger indexing job (documents not searchable)\n";
          $flushRetry++;
          if ($flushRetry > 10) {
            echo "Resetting serial (did the PAPI died ?)\n";
            $serial = false;
          }
        }
      } catch(PushAPIException $e) {
        echo "Can not yet trigger indexing job (index not ready ?)\n";
      }
    }
    /* Update last timestamp */
    $last = time();
  }
}

/* End */
$papi->close();
fclose($fp);
?>
