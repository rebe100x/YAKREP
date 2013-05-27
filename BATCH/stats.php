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
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;



$tsNow = gmmktime();
$tsLastWeek = $tsNow - 7*24*60*60; 	
$tsThisEvening = strtotime('tomorrow midnight');
$tsThisMorning = strtotime('today midnight');
var_dump(date('d M Y H:i:s',$tsThisMorning));	
var_dump(date('d M Y H:i:s',$tsThisEvening));	
$cond['creationDate'] = array('$gte'=>new MongoDate($tsThisMorning),'$lte'=>new MongoDate($tsThisEvening));

$stats['creationDate'] = new MongoDate($tsThisEvening);

$stats['infoTotal']  = $infoColl->count($cond);

$cond['yakType'] = 4;
$stats['infoYassala']  = $infoColl->count($cond);


$statColl->update(array('creationDate'=>$stats['creationDate']),$stats,array('upsert'=>true));


	

	
	
	

	
	
?>


