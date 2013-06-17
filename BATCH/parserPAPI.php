<?php

/* 
	read feeds and push to XL
	runs in cron every 30 minutes
*/
require_once("../LIB/conf.php");
$conf = new conf();


$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$infoColl = $db->info;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;
$feedColl = $db->feed;

$docsPAPI = array();

$q = (empty($_GET['q']))?"all":$_GET['q']; 
$forceUpdate = (empty($_GET['forceUpdate']))?0:$_GET['forceUpdate']; 

if($q != ''){
	if($q == 'all') // we parse all valid feeds
		$query = array('status'=>1);
	else
		$query = array('name'=>$q,'status'=>1);

	$res = $feedColl->find($query);

	$feeds = iterator_to_array($res);
	//var_dump($feeds);

	try {
		$papi = PushAPIFactory::createHttp("ec2-54-246-84-102.eu-west-1.compute.amazonaws.com", 62002, "parserPAPI");
		//echo "Ping\n";
		//$papi->ping();
	} catch(PushAPIFactory $e)  {
		echo "Error: " . $e->getMessage() . "\n";
	}

	function mapIt($i){
		$i = convert_smart_quotes($i); // clean rounded quotes from MSWord
		return '/'.$i.'/';
	}
	
	
	foreach ($feeds as $feed) {
		//var_dump($feed);
		$file = $feed['name'].".xml";
		echo 'Parsing feed : '.$feed['name'].'<br>';
		echo 'Data : '.empty($feed['linkSource'])?implode(' , ',$feed['fileSource']):implode(' , ',$feed['linkSource']).'<br>';
		echo 'Last Execution (GMT):'.gmdate('Y/m/d H:i:s',$feed['lastExecDate']->sec) .'<br>';
		echo 'Next Execution (GMT):'.gmdate('Y/m/d H:i:s',$feed['lastExecDate']->sec + ($feed['parsingFreq']*60)) .'<br>';
		echo 'Time GMT now :'.date('Y/m/d H:i:s') .'<br>';
		
		if( ( $feed['parsingFreq'] > 0 && gmmktime() >= ($feed['lastExecDate']->sec + ($feed['parsingFreq']*60)) )  || $forceUpdate == 1){
			// echo 'Set Execution Status to 2 for the time of the parsing execution';
			$feedColl->update(
							array('_id'=>$feed['_id']),
							array('$set'=>array('lastExecStatus'=>2),'lastExecDate'=>new MongoDate())
						);
			
			
			if(!empty($feed['feedType'])){
				$canvas = $feed['parsingTemplate'];
				//var_dump($feed);
				$data = getFeedData($feed);
				//var_dump($data);
				if(!empty($data)){
					$line = 0;
					foreach($data as $item){
						
						
						if(!empty($canvas['title']) ){ // we don't take empty lines and header
							//var_dump($item);
							$itemArray = array();
						
							if($feed['feedType'] == 'CSV'){
								if($line >= $feed['lineToBegin']){
									foreach($canvas as $key=>$val){
										$thevalue = '';
										if(!empty($val)){
											
											preg_match_all('/(#YKL)(\d+)/', $val, $out);
											$tmp = array();
											foreach($out[2] as $o)
												$tmp[] = $item[$o];
											$thevalue = preg_replace(array_map('mapIt',$out[0]), $tmp, $val);
											
										}else
											$thevalue = '';
											
										$thevalueClean = $thevalue;
										
										if($key == 'freeTag' || $key == 'yakCats'){
											$tmp = explode(',',$thevalueClean);
											$tmp = array_map('trimArray',$tmp);  
											$thevalueClean = implode('#',$tmp);
										}
										if($key == 'longitude' || $key == 'latitude'){
											$thevalueClean = str_replace(',','.',$thevalueClean);
											$thevalueClean = (float)$thevalueClean;
										}
										
										$itemArray[$key] = $thevalueClean;
										
									}
								}
							}
							
							if($feed['feedType'] == 'XML'){
								//echo '<br>ITEM=';
								//var_dump($item);
								foreach($canvas as $key=>$val){
									$thevalue = '';
									if(!empty($val)){
										$val = str_replace('#YKLcurrent_french_date',date('d m Y'),$val);
										//echo '<br>'.$val;
										if(strpos($val,'->')){
											preg_match_all('/(#YKL)(\w+->\w+)/', $val, $out);
										}else
											if(strpos($val,':'))
												preg_match_all('/(#YKL)(\w+:\w+)/', $val, $out);
											else
												preg_match_all('/(#YKL)(\w+)/', $val, $out);
										//echo '<br>OUTPUT=';
										//var_dump($out);
										$tmp = array();
										$o1 = array();
										foreach($out[2] as $o){
											if(strpos($o,'->')){
												$o1 = explode('->',$o);
												if(!empty($o1[1])){
													if( !empty($item[$o1[0]]) && sizeof($item[$o1[0]]) > 1)
														$tmp[] = ( !empty($item[$o1[0]]) && !empty($item[$o1[0]][0]['@attributes'][$o1[1]]) )? $item[$o1[0]][0]['@attributes'][$o1[1]] : '';
													else
														$tmp[] = ( !empty($item[$o1[0]]) && !empty($item[$o1[0]]['@attributes'][$o1[1]]) )? $item[$o1[0]]['@attributes'][$o1[1]] : '';
												}else{
													$tmp[] = (empty($item[$o]))?'':$item[$o];
												}
											}else{
												if(strpos($o,':'))
													$o = str_replace(':','',$o);
												
												$tmp[] = (empty($item[$o]))?'':$item[$o];
											}
										}
										//var_dump( $o);
										//echo '<br>TMP=';
										//var_dump( $tmp);
										if(is_array($tmp[0]))
											$t = implode(',',$tmp[0]);
										else
											$t = $tmp;
										//echo '<br>T=';	
										//var_dump($t);
										//echo '<br>VAL=';	
										//var_dump($val);
										$thevalue = @preg_replace(array_map('mapIt',$out[0]), $t, $val);
									}else
										$thevalue = '';
										
									$thevalueClean = $thevalue;
									
									if($key == 'freeTag' || $key == 'yakCats'){
										$tmp = explode(',',$thevalue);
										$tmp = array_map('trimArray',$tmp);  
										$thevalueClean = implode('#',$tmp);
									}
									//echo '<br>key:'.$key.' - '.$thevalueClean;
									if($key == 'longitude' || $key == 'latitude'){
										$thevalueClean = str_replace(',','.',$thevalueClean);
										$thevalueClean = (float)$thevalueClean;
									}
									
									$itemArray[$key] = $thevalueClean;
								}
								
							}
							
							if($feed['feedType'] == 'JSON'){ 
								foreach($canvas as $key=>$val){
									$thevalue = '';
									if(!empty($val)){
										preg_match_all('/(#YKL)(\w+)/', $val, $out);
										//var_dump($out);
										$tmp = array();
										$o1 = array();
										foreach($out[2] as $o){
											echo "<br>$o:".$o;
											if(is_array($item[$o])){
												$thevalue = implode('#',$item[$o]);
											}else
												$thevalue =  trim($item[$o]);
												
											
											if($key == 'pubDate'){
												//echo '<br>'.$thevalue.' '.date('r',$thevalue).'  '.(mktime()-10*24*60*60);
												if((int)$thevalue > mktime()-10*24*60*60 && (int)$thevalue <= mktime() ){
													$thevalue = date('r',(int)$thevalue);
												}
											}
											
											//if($key == 'content')
											//	echo "CONTENT".$thevalue;
											if($key == 'freeTag' || $key == 'yakCats'){
												$tmp = explode(',',$thevalue);
												$tmp = array_map('trimArray',$tmp);  
												$thevalue = implode('#',$tmp);
												
											}
											
											if($key == 'longitude' || $key == 'latitude'){
												$thevalue = str_replace(',','.',$thevalue);
												$thevalue = (float)$thevalue;
											}	
											
											if(!array_key_exists($key,$itemArray))
												$itemArray[$key] = $thevalue;
											else
												$itemArray[$key] .= $thevalue;
												
										}
									}
								}
							}
							
							//var_dump($itemArray);
							
							try {

								//echo "Checking if document exists\n";
								$stamp = $papi->getDocumentStatus($itemArray['outGoingLink']);
								if ($stamp == false || $forceUpdate ==  1) {
									echo "Doc is new, we push to XL<br>";
									$docsPAPI[] = buildPAPIItem($itemArray,$file);
									//var_dump($docsPAPI);
								} else {
									echo "Doc is already in XL<br>";
								}
							} catch(PushAPIFactory $e)  {
								echo "Error: " . $e->getMessage() . "\n";
							}
							
						}			
						$line++;
					}
					
					// PUSH DOCS TO XL
					if(sizeof($docsPAPI) > 0){
					
						$resp = $papi->addDocument($docsPAPI);

						//echo "Set checkpoint\n";
						$serial = $papi->setCheckpoint(0, true);

						$cp = $papi->enumerateCheckpointInfo();
						/*foreach($cp as $k => $v) {
							echo "Checkpoint: name='" . $k . "' value='" . $v . "'\n";
						}*/

						$rootUrlTmp = parse_url($itemArray['outGoingLink']);	
						$rootUrl = $rootUrlTmp['scheme'].'://'.$rootUrlTmp['host'];
						$se = $papi->enumerateSyncedEntries($rootUrl,PushAPI::RECURSIVE_DOCUMENTS);
						/*foreach($se as $url => $stamp) {
							echo "Entry: url='" . $url . "' stamp='" . $stamp . "'\n";
						}*/

						//echo "Waiting for checkpoint serial " . $serial . "\n";

						//echo "Triggering indexing job\n";
						$papi->triggerIndexingJob();

						//echo "Waiting for documents to be searchable ..\n";
						while(!$papi->areDocumentsSearchable($serial)) {
							//echo "<br>Waiting .. current  checkpoint: " . $papi->getCheckpoint(). "        " . "\r";
							sleep(3);
						}
					  
					  
					  
						echo "XL Build OK<br>";
					
					
						echo "Begin Fetching<br>";	
						include('./fetchPAPI.php');
					}else{
						echo 'No docs to push!<br>';
						$feedColl->update(
							array('_id'=>$feed['_id']),
							array('$set'=>array('lastExecStatus'=>4,'lastExecDate'=>new MongoDate(),'lastExecErr'=>'No doc to parse !'))
						);
					}
				}else
					echo 'No DATA';
				
				
			}
		}else{
			echo '=> No need to run '.$feed['humanName'].'<br>';
			echo "To force update, call <a href=\"".$_SERVER["REQUEST_URI"]."&forceUpdate=1\">&forceUpdate=1</a>";    
		}
	}

	//$papi->ping();	
	$papi->close();
	
	// echo 'Set Execution Status to 1 for the time of the parsing execution';
	$feedColl->update(
				array('_id'=>$feed['_id']),
				array('$set'=>array('lastExecStatus'=>1,'lastExecDate'=>new MongoDate()))
			);	
			
			
	
}else{
	echo "<!doctype html><html><head><meta charset='utf-8' /><title>YAKWALA BATCH</title></head><body>";
	echo "<br><br><hr><b>FEEDS:</b><br>";
	echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=all\">ALL FEEDS</a>";
	$feeds = $feedColl->find()->sort(array('humanName'=>'desc'));
	
	foreach ($feeds as $feed) {
		echo "<br>".(($feed['status']==1)?"ACTIVE":"DISABLED")."--- <a href=\"".$_SERVER['PHP_SELF']."?q=".$feed['name']."\"/>".$feed['name']."</a> " ;
	
	}

}	
?>


