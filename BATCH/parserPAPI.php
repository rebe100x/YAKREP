<?php
/*
	styles
*/
echo "<!DOCTYPE html><html>
<head><meta charset='utf8'/>
<style>
		.warning{background-color:#00FF00;font-weight:bold;}
		.error{background-color:#FF0000;font-weight:bold;color:#FFFFFF;}
		.message{background-color:#0000;font-weight:bold;}
</style>
</head>
<body>
";
/* 
	This script can be run as php script from the backend ( vwhen you click on the RUN command in the feed page ) or as a CLI script. So becarefull with your path and $_SERVER variables.
	url to be run :
	http://batch.yakwala.fr/PROD/YAKREP/BATCH/parserPAPI.php?q=FEED['HUMANNAME']&forceUpdate=1
	
	
	DESCRIPTION : 
	
	1) Read feeds with getFeedData()
	2) Parse the feed according to the parsing template with parseFeedData() ( in db : $canvas = $feed['parsingTemplate'] )
	3) Push data to XL with the sdk Push API ( PAPI )
	4) Trigger data analysis by XL and wait until done.
	5) Trigger fetching batch : fetchPAPI.php which will get the data analysed by XL and enter it in db.
	
	Runs in cron every 30 minutes ( check crontab for frequency )
	

	
*/

if(sizeof($_GET) == 0){
	require_once("/home/bitnami/stack/nginx/html/PROD/YAKREP/LIB/conf.php");
	include_once "/home/bitnami/stack/nginx/html/PROD/YAKREP/LIB/cloudview-sdk-php-clients/papi/PushAPI.inc";
}else{
	require_once("../LIB/conf.php");
	include_once "../LIB/cloudview-sdk-php-clients/papi/PushAPI.inc";
}

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
		echo 'Time GMT now :'.gmdate('Y/m/d H:i:s') .'<br>';
		
		if( ( $feed['parsingFreq'] > 0 && gmmktime() >= ($feed['lastExecDate']->sec + ($feed['parsingFreq']*60)) )  || $forceUpdate == 1){
			// echo 'Set Execution Status to 2 for the time of the parsing execution';
			$feedColl->update(
							array('_id'=>$feed['_id']),
							array('$set'=>array('lastExecStatus'=>2),'lastExecDate'=>new MongoDate(),'lastExecErr'=>'')
						);
			
			if(!empty($feed['feedType'])){
				$canvas = $feed['parsingTemplate'];
				//var_dump($feed);
				$data = getFeedData($feed,$conf);
				//var_dump($data);
				if(!empty($data)){
					$line = 0;
					foreach($data as $item){
						if(!empty($canvas['title']) ){ // we don't take empty lines and header
							$itemArray = array();
							if($feed['feedType'] == 'CSV'){
								if($line >= $feed['lineToBegin']){
									$itemArray = parseFeedData($feed,$item);
									$line++;
								}else{
									$line++;
									continue;
								}
							}else
								$itemArray = parseFeedData($feed,$item);
							
							if(empty($itemArray['title']) || empty($itemArray['outGoingLink'])){
								echo "<br><b>Erreur : </b> le titre et l'identifiant externe ne sont par remplis.";
								echo '<br>Titre : '.$itemArray['title'];
								echo '<br>Identifiant externe ( outGoingLink ) : '.$itemArray['outGoingLink'];
								continue;
							}
							try {

								//echo "Checking if document exists\n";
								echo "<br>-----<b>".$itemArray['title']."</b>---------";
								$stamp = $papi->getDocumentStatus($itemArray['outGoingLink']);
								if ($stamp === false || $forceUpdate ==  1) {
									echo "Doc is new, we push to XL";
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
						if(!empty($rootUrlTmp['scheme']) && !empty($rootUrlTmp['host']) )
							$rootUrl = $rootUrlTmp['scheme'].'://'.$rootUrlTmp['host'];
						elseif(!empty($rootUrlTmp['path']))
							$rootUrl = $rootUrlTmp['path'];
						else
							$rootUrl = mktime();
							
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
						if(sizeof($_GET) == 0){
							echo "FETCHING";
							include("/home/bitnami/stack/nginx/html/PROD/YAKREP/BATCH/fetchPAPI.php");
						}else{
							include('./fetchPAPI.php');
						}
					}else{
						echo 'No docs to push!<br>';
						$feedColl->update(
							array('_id'=>$feed['_id']),
							array('$set'=>array('lastExecStatus'=>4,'lastExecDate'=>new MongoDate(),'lastExecErr'=>'No doc to parse !'))
						);
					}
				}else
					echo '<br><b>Error: </b>We could not retrieve data from this feed !';
			}
			
			// echo 'Set back Execution Status to 1';
			$feedColl->update(
				array('_id'=>$feed['_id']),
				array('$set'=>array('lastExecStatus'=>1,'lastExecDate'=>new MongoDate(),'lastExecErr'=>''))
			);	
			
		}else{
			echo '=> No need to run '.$feed['humanName'].'<br>';
			//echo "To force update, call <a href=\"".$_SERVER["REQUEST_URI"]."&forceUpdate=1\">&forceUpdate=1</a>";    
		}
		
		
			
			
			
	}

	//$papi->ping();	
	$papi->close();
	
	
	
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


