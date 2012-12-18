<?php
require('../../PushAPI.inc');
require('tick.inc');

try {
  echo "Create\n";
  $papi = PushAPIFactory::createHttp("localhost", 42002, "default");

  echo "Ping\n";
  $papi->ping();

  $myPart1 = new Part(array('content' =>
                            '<html><body>Hello, world</body></html>',
                            'directives' =>
                            array('mimeHint' => 'text/html',
                                  'encoding' => 'utf-8')
                            )
                      );
  $myDoc1 = new Document(array('uri' => 'http://example.com/doc.html',
                               'parts' => $myPart1,
                               'metas' => array('path' => 'Path/To/Example/1')
                               )
                         );

  $tsukemono = "\xe6\xbc\xac\xe7\x89\xa9";
  $tsukemonoh = "\xe3\x81\xa4\xe3\x81\x91\xe3\x82\x82\xe3\x81\xae";
  $myPart2 = new Part(array('content' =>
                            '<html>'
                            . '<head><title>Hello World</title></head>'
                            . '<body>Hello, world'
                            . '<br />I like eating ' . $tsukemono
                            . ' (' . $tsukemonoh . ')!'
                            . '</body></html>',
                            'directives' =>
                            array('mimeHint' => 'text/html',
                                  'encoding' => 'utf-8')
                            )
                      );
  $myDoc2 = new Document(array('uri' => 'http://example.com/doc2.html',
                               'parts' => $myPart2,
                               'metas' => array('path' => 'Path/To/Example/2')
                               )
                         );
  tickSet('serialization');

  echo "Checking if document exists\n";
  $stamp = $papi->getDocumentStatus('http://example.com/doc.html');
  if ($stamp !== false) {
    echo "Yes, its stamp is " . $stamp . "\n";
  } else {
    echo "No, it does not yet exist\n";
  }
  echo "Pushing documents\n";
  $resp = $papi->addDocument(array($myDoc1, $myDoc2));
  tickSet('pushing');

  echo "Set checkpoint\n";
  $serial = $papi->setCheckpoint(0, true);

  $cp = $papi->enumerateCheckpointInfo();
  foreach($cp as $k => $v) {
    echo "Checkpoint: name='" . $k . "' value='" . $v . "'\n";
  }

  $se = $papi->enumerateSyncedEntries('http://example.com/',
                                      PushAPI::RECURSIVE_DOCUMENTS);
  foreach($se as $url => $stamp) {
    echo "Entry: url='" . $url . "' stamp='" . $stamp . "'\n";
  }
  
  echo "Waiting for checkpoint serial " . $serial . "\n";
  
  echo "Triggering indexing job\n";
  $papi->triggerIndexingJob();
  tickSet('checkpoint+trigger');

  echo "Waiting for documents to be searchable ..\n";
  while(!$papi->areDocumentsSearchable($serial)) {
    echo "Waiting .. current  checkpoint: " . $papi->getCheckpoint()
      . "        " . "\r";
    sleep(3);
  }
  tickSet('indexing');

  /* Update a document */
  $docUpdate = new Document(array('uri' => 'http://example.com/doc2.html',
                                  'metas' => array('title' => 'Bye bye!')
                                  )
                            );
  $resp = $papi->updateDocument($docUpdate, array('title'));
  $serial = $papi->setCheckpoint(0, true);
  $papi->triggerIndexingJob();
  while(!$papi->areDocumentsSearchable($serial)) {
    echo "Waiting .. current  checkpoint: " . $papi->getCheckpoint()
      . "        " . "\r";
    sleep(3);
  }
  tickSet('updating');
  
  $papi->ping();
  $papi->close();
  echo "All done!\n";
  
  tickShow();
} catch(PushAPIFactory $e)  {
  echo "Error: " . $e->getMessage() . "\n";
}

?>
