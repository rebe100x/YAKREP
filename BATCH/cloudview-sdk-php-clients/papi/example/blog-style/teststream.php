<?php
require('../../PushAPI.inc');
require('tick.inc');

class MyDocProducer implements DocumentEnumeration {
  public function __construct($count) {
    $this->n = 0;
    $this->count = $count;
    require('prenoms.inc');
    require('noms.inc');
    $this->noms = $noms;
    $this->prenoms = $prenoms;
  }

  public function nextBatch() {
    $this->n = 0;
  }

  public function startBatch() {
  }
  
  public function endBatch() {
  }
  
  public function next() {
    if ($this->n >= $this->count) {
      return false;
    }
    $k = $this->n / 1000;
    $j = ( $this->n / 10 ) % 100;
    $i = ( ( $this->n ) % 10 ) + 1;
    $this->n++;
    
    $author = ucwords($this->prenoms[mt_rand() % count($this->prenoms)]
                      .' ' . $this->noms[mt_rand() % count($this->noms)]);
        
    $myPart = new Part(array('content' =>
                             file_get_contents($i),
                             'directives' =>
                             array('mimeHint' => 'text/plain',
                                   'encoding' => 'utf-8')
                             )
                       );
    
    return new Document(array('uri' =>
                              'http://' . 'skyblog.com/doc'
                              . ($i + $j*10 + $k*1000) . '.html',
                              'parts' => $myPart,
                              'metas' => array('path' =>
                                               'Skyrock'
                                               . '/Utilisateur ' . $k
                                               . '/Blog ' . $j
                                               . '/Page ' . $i,
                                               'author' => $author,
                                               'filename' =>
                                               $i . '.txt',
                                               'title' =>
                                               'Le blog de '
                                               . $author,
                                               'size' =>
                                               mt_rand() % 32768
                                               )
                              )
                        );
  }
  
  public function start() {
  }

  public function end() {
  }

  protected $noms, $prenoms;
  private $n;
  private $count;
}


try {
  echo "Create\n";
  $papi = PushAPIFactory::createHttp("localhost", 42002, "default");
  tickSet('initialization');

  /* wipe previous documents */
  $papi->deleteDocumentRootPath('Skyrock', true);
  tickSet('cleanup');

  /* 1,000,000 docs */
  $prod = new MyDocProducer(1000);
  $prod->startBatch();
  for($k = 0 ; $k < 10/*00*/ ; $k++) {
    echo '[' . $k . "] Producing 1,000 docs\n";
    $papi->addDocumentList($prod);
    $prod->nextBatch();
  }
  $prod->endBatch();
  
  tickSet('processing of 1,000,000 docs');
  tickShow();

  echo "Set checkpoint\n";
  $serial = $papi->setCheckpoint(0, true);

  echo "Waiting for checkpoint serial " . $serial . "\n";
  
  echo "Triggering indexing job\n";
  $papi->triggerIndexingJob();
  tickSet('checkpoint+trigger of 1000 docs');

  echo "Waiting for documents to be searchable ..\n";
  while(!$papi->areDocumentsSearchable($serial)) {
    echo "Waiting .. current  checkpoint: " . $papi->getCheckpoint()
      . "        " . "\r";
    sleep(3);
  }
  tickSet('indexing');

  $papi->close();
  echo "All done!\n";
  
  tickShow();
} catch(PushAPIFactory $e)  {
  echo "Error: " . $e->getMessage() . "\n";
}

?>
