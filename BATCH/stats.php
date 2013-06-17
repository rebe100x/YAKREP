<?php

/* 
	Get stats everyday from db
	
*/

echo "<!doctype html><html><head><meta charset='utf-8' /><title>YAKWALA STATS</title></head><body>";
	
	
require_once("../LIB/conf.php");
$conf = new conf();

$db =$conf->mdb();

$infoColl = $db->info;
$userColl = $db->user;
$zoneColl = $db->zone;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;



$tsNow = gmmktime();
$tsLastWeek = $tsNow - 7*24*60*60; 	
$tsLast10days = $tsNow - 10*24*60*60; 	
$tsYesterdayMorning = strtotime('yesterday midnight');
$tsThisMorning = strtotime('today midnight');
var_dump(date('d M Y H:i:s',$tsYesterdayMorning));	
var_dump(date('d M Y H:i:s',$tsThisMorning));	

$stats = array();

$stats['creationDate'] = new MongoDate($tsThisMorning);

/*INFO*/
$cond = array();
$cond['creationDate'] = array('$gte'=>new MongoDate($tsYesterdayMorning),'$lte'=>new MongoDate($tsThisMorning));
$stats['info']['totalToday'] = $infoColl->count($cond);

$cond = array();
$cond['yakType'] = 4;
$cond['creationDate'] = array('$gte'=>new MongoDate($tsYesterdayMorning),'$lte'=>new MongoDate($tsThisMorning));
$stats['info']['yassala']  = $infoColl->count($cond);

$cond = array();
$cond['status'] = array('$in'=>array(2,10));
$cond['creationDate'] = array('$gte'=>new MongoDate($tsYesterdayMorning),'$lte'=>new MongoDate($tsThisMorning));
$stats['info']['tovalidate']  = $infoColl->count($cond);

$cond = array();
$cond['creationDate'] = array('$lte'=>new MongoDate($tsYesterdayMorning));
$stats['info']['total'] = $infoColl->count($cond);

/*USER*/
$cond = array();
$cond['creationDate'] = array('$gte'=>new MongoDate($tsYesterdayMorning),'$lte'=>new MongoDate($tsThisMorning));
$stats['user']['totalToday']  = $userColl->count($cond);

$cond = array();
$cond['status'] = array('$in'=>array(2));
$cond['creationDate'] = array('$gte'=>new MongoDate($tsYesterdayMorning),'$lte'=>new MongoDate($tsThisMorning));
$stats['user']['tovalidate']  = $userColl->count($cond);

$cond = array();
$cond['creationDate'] = array('$lte'=>new MongoDate($tsYesterdayMorning));
$stats['user']['total'] = $userColl->count($cond);

/*PLACE*/
$cond = array();
$cond['creationDate'] = array('$gte'=>new MongoDate($tsYesterdayMorning),'$lte'=>new MongoDate($tsThisMorning));
$stats['place']['totalToday']  = $placeColl->count($cond);

$cond = array();
$cond['status'] = array('$in'=>array(2,10));
$cond['creationDate'] = array('$gte'=>new MongoDate($tsYesterdayMorning),'$lte'=>new MongoDate($tsThisMorning));
$stats['place']['tovalidate']  = $placeColl->count($cond);

$cond = array();
$cond['creationDate'] = array('$lte'=>new MongoDate($tsYesterdayMorning));
$stats['place']['total'] = $placeColl->count($cond);

/*ZONE*/
$zones = $zoneColl->find()->sort(array('name'=>1));
foreach($zones as $zone){
	
	$cond = array();
	$cond['creationDate'] = array('$gte'=>new MongoDate($tsLast10days));
	$TR = $zone['box']['tr'];
	$BL = $zone['box']['bl'];
	$TL = array("lat"=>$TR['lat'],"lng"=>$BL['lng']);
	$BR = array("lat"=>$BL['lat'],"lng"=>$TR['lng']);
	$box = array(
		"tl"=>$TL,
		"br"=>$BR
	);
	$cond['location'] = array('$within'=>array('$box'=>$box));
	//$cond['location'] = array('$within'=>array('$box'=>array(0,0),array(100,100)));
	$stats['zone'][] = array('name' => $zone['name'], 'total' => $infoColl->count($cond));
	echo "<br> COUNT=".$infoColl->count($cond);
}



$statColl->update(array('creationDate'=>$stats['creationDate']),$stats,array('upsert'=>true));


	

	
	
	

	
	
?>


