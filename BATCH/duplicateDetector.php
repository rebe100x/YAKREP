<?php

/* 
	detect duplicated infos
*/

echo "<!doctype html><html><head><meta charset='utf-8' /><title>YAKWALA BATCH DUPLICATE DETECTOR</title></head><body>";
	
	
require_once("../LIB/conf.php");
$conf = new conf();

$db =$conf->mdb();

$infoColl = $db->info;

echo '<hr>***** DUPLICATED ******<br>';

$tsNow = gmmktime();
$tsLastWeek = $tsNow - 7*24*60*60; 	


// format : YYYY-MM-DD
$from = (empty($_GET['from']))?"":$_GET['from']; 
$to = (empty($_GET['to']))?"":$_GET['to']; 
$del = (empty($_GET['del']))?"0":$_GET['del']; 

$tsNow = gmmktime();
$cond = array('status'=>1);
$supprInfos = array();
if(!empty($from) && !empty($to) ){
	$DTFrom = DateTime::createFromFormat('Y-m-d', $from);
	$DTTo = DateTime::createFromFormat('Y-m-d', $to);
	$TSFrom = $DTFrom->getTimestamp();
	$TSTo = $DTTo->getTimestamp();
}else{
	echo '<br>Usage : <a href="duplicateDetector.php?from='.date('Y-m-d',$tsLastWeek).'&to='.date('Y-m-d',mktime()).'">duplicateDetector.php?from='.date('Y-m-d',$tsLastWeek).'&to='.date('Y-m-d',mktime()).'</a>';
	$TSFrom =$tsLastWeek;
	$TSTo = mktime();
	
}	
$cond['creationDate'] = array('$gte'=>new MongoDate($TSFrom),'$lte'=>new MongoDate($TSTo));

echo '<br>**********************<br><b>FROM:</b> '.date('Y-m-d',(int)$TSFrom);
echo '<br> <b>TO:</b> '.date('Y-m-d',(int)$TSTo).'<br>*********************';
if($del)
	echo '<br><span style="background-color:#2F2;">!!!!MODE DELETE <a href="duplicateDetector.php?from='.date('Y-m-d',$tsLastWeek).'&to='.date('Y-m-d',mktime()).'&del=0">ON</a>!!!</span><br>';
else
	echo '<br><span> MODE DELETE <a href="duplicateDetector.php?from='.date('Y-m-d',$tsLastWeek).'&to='.date('Y-m-d',mktime()).'&del=1">OFF</a></span><br>';
$infos = $infoColl->find($cond)->sort(array('title'=>1,'creationDate'=>1));
foreach($infos as $info){
	$dataExists = $infoColl->find(array("title"=>$info['title'],"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000055),"status"=>1,"_id"=>array('$nin'=>$supprInfos)));
	$numInfo = $dataExists->count();
	//echo '<br>'.$numInfo.' - INFO: '.$info['title'].' -'.$info['_id'].'- '.$info['origin'].' - '.date('Y-m-d',$info['dateEndPrint']->sec).' - <i>'.$info['address'].'</i>';
	if($numInfo > 1){
		echo '<br>'.$numInfo.' - INFO: '.$info['title'].' -'.$info['_id'].'- '.$info['origin'].' - '.date('Y-m-d',$info['dateEndPrint']->sec).' - <i>'.$info['address'].'</i>';
		 
		echo '<br>Keep this info : '.$info['_id'];
		foreach($dataExists as $suppr){
			$supprInfos[] = $suppr["_id"];
			if($suppr["_id"] != $info['_id']){
				echo '<br> Delete : '.$suppr["_id"];
				if($del)
					$infoColl->remove(array('_id'=>$suppr["_id"]),array("justOne" => true));
			}
		}
	}
}


	
echo "<br>***** END DUPLICATED *****";
	
	

	
	
?>



