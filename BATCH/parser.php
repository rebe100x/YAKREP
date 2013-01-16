<?php

/* 
	read feeds and generate a formated xml for exalead
*/
require_once("../LIB/conf.php");
$conf = new conf();

/*
include_once("../LIB/arc/ARC2.php");
include_once("../LIB/Graphite.php");
 
$graph = new Graphite();
$uri = "http://api.mp2013.fr/events?from=2013-01-01&to=2013-02-15&lang=fr&format=rdf&offset=0&limit=100";
$uri = "http://dev.batch.yakwala.fr/BATCH/input/mp2013_small.rdf";
$uri = "http://data.ordnancesurvey.co.uk/id/postcodeunit/SO171BJ";
$graph->load( $uri );
$var = $graph->resource( $uri )->dump();
var_dump($var);
*/
//$uri = "http://api.mp2013.fr/events?from=2013-01-01&to=2013-02-15&lang=fr&format=rdf&offset=0&limit=100";
$uri = "./input/test.xml";
$xml = file_get_contents($uri);
$events = simplexml_load_string($xml);
var_dump($events);exit;
$currentPlace;
$callCibull = 0;
foreach ($urlset->description as $desc) {
	echo $desc;
}
exit;
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$infoColl = $db->info;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;
$feedColl = $db->feed;

$feeds = $feedColl->find(array('status'=>1));
		
foreach ($feeds as $feed) {
	if(!empty($feed['feedType']) && $feed['feedType'] == "RDS"){
		echo $feed['name'];
	}
	
}
	
	$file = "testFeed.xml";

	header("Content-Type: application/rss+xml; charset=utf-8");
	$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><items>";
	
	$xml = "";
	
	$title1 = "title1 rue des Martyrs";
	$title2 = "title2";
	$content = "content1";
	$outGoingLink = "http://link1.com";
	$thumb1 = "http://ta.kewego.com/t/0/0288/154x114_8173d12acb2s_2.jpg";
	$thumb2 = "http://www.laprovence.com/media/imagecache/home_image_article1/aixrotondephotoneige.jpg";
	$yakCatId1 = "504d89cffa9a957004000001";	
	$yakCatId2 = "50923b9afa9a95d409000000";	
	$yakType1 = 2;
	$yakType2 = 3;
	$freeTag = "Ligue1";
	$pubDate = "2013-01-16T09:30:00.0Z";
	$address1 = "rue des Martyrs, Paris, France";
	$address2 = "";
	$lat1 = "48.878095";
	$lng1 = "2.339474";
	$lat2 = "";
	$lng2 = "";
	$dateTimeFrom1 = "2013-03-19T09:30:00.0Z";
	$dateTimeEnd1 = "2013-03-19T17:00:00.0Z";
	$dateTimeFrom2 = "2013-03-20T09:30:00.0Z";
	$dateTimeEnd2 = "2013-03-20T17:00:00.0Z";
	$place1 = "Hôpital Necker";
	$place2 = "Hotel de Crillon";
		
	$xml .= "
		<item>
			<title><![CDATA[".$title1."]]></title>
			<description><![CDATA[".$content."]]></description>
			<outGoingLink><![CDATA[".$outGoingLink."]]></outGoingLink>
			<thumb><![CDATA[".$thumb1."]]></thumb>
			<yakCats><![CDATA[".$yakCatId1."#".$yakCatId2."]]></yakCats>
			<yakType><![CDATA[".$yakType1."]]></yakType>
			<freeTag><![CDATA[".$freeTag."]]></freeTag>
			<pubDate><![CDATA[".$pubDate."]]></pubDate>
			<address><![CDATA[".$address1."]]></address>
			<place><![CDATA[".$place1."]]></place>
			<geolocation><![CDATA[".$lat1."#".$lng1."]]></geolocation> 
			<eventDate><![CDATA[".$dateTimeFrom1."#".$dateTimeEnd1."|".$dateTimeFrom2."#".$dateTimeEnd2."]]></eventDate>
		</item>
		";
	
	$xml .= "
		<item>
			<title><![CDATA[".$title2."]]></title>
			<description><![CDATA[".$content."]]></description>
			<outGoingLink><![CDATA[".$outGoingLink."]]></outGoingLink>
			<thumb><![CDATA[".$thumb2."]]></thumb>
			<yakCats><![CDATA[".$yakCatId1."#".$yakCatId2."]]></yakCats>
			<yakType><![CDATA[".$yakType2."]]></yakType>
			<freeTag><![CDATA[".$freeTag."]]></freeTag>
			<pubDate><![CDATA[".$pubDate."]]></pubDate>
			<address><![CDATA[".$address2."]]></address>
			<place><![CDATA[".$place2."]]></place>
			<geolocation><![CDATA[".$lat2."#".$lng2."]]></geolocation> 
			<eventDate><![CDATA[".$dateTimeFrom1."#".$dateTimeEnd1."|".$dateTimeFrom2."#".$dateTimeEnd2."]]></eventDate>
		</item>
		";


$footer ="</items>";

echo  $header.$xml.$footer;
/*
$fh = fopen('/usr/share/nginx/html/DATA/'.$file, 'w') or die("error");
fwrite($fh, $header.$xml.$footer);
fclose($fh);*/
   
?>


