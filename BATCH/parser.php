<?php

/* 
	read feeds and generate a formated xml for exalead
*/

include_once "../LIB/conf.php";
ini_set('display_errors',1);

	$file = "testFeed.xml";

	//header("Content-Type: application/rss+xml; charset=utf-8");
	$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><items>";
	
	$xml = "";
	
	$title = "title1 rue des Martyrs";
	$title2 = "title2";
	$content = "content1";
	$outGoingLink = "http://link1.com";
	$thumb1 = "http://ta.kewego.com/t/0/0288/154x114_8173d12acb2s_2.jpg";
	$thumb2 = "http://www.laprovence.com/media/imagecache/home_image_article1/aixrotondephotoneige.jpg";
	$yakCatId1 = "504d89cffa9a957004000001";	
	$yakCatId2 = "50923b9afa9a95d409000000";	
	$freeTag = "Ligue1";
	$pubDate = "2013-03-19T09:30:00.0Z";
	$address = "rue des Martyrs, Paris, France";
	$lat = "48.878095";
	$lng = "2.339474";
	$dateTimeFrom1 = "2013-03-19T09:30:00.0Z";
	$dateTimeEnd1 = "2013-03-19T17:00:00.0Z";
	$dateTimeFrom2 = "2013-03-20T09:30:00.0Z";
	$dateTimeEnd2 = "2013-03-20T17:00:00.0Z";
		
	$xml .= "
		<item>
			<title><![CDATA[".$title."]]></title>
			<description><![CDATA[".$content."]]></description>
			<outGoingLink><![CDATA[".$outGoingLink."]]></outGoingLink>
			<thumb><![CDATA[".$thumb1."]]></thumb>
			<yakCats><![CDATA[".$yakCatId1."#".$yakCatId2."]]></yakCats>
			<yakType><![CDATA[".$yakType."]]></yakType>
			<freeTag><![CDATA[".$freeTag."]]></freeTag>
			<pubDate><![CDATA[".$pubDate."]]></pubDate>
			<address><![CDATA[".$address."]]></address>
			<geolocation><![CDATA[".$lat."#".$lng."]]></geolocation> 
			<eventDates>
				<eventDate><![CDATA[".$dateTimeFrom1."#".$dateTimeEnd1."]]></eventDate>
				<eventDate><![CDATA[".$dateTimeFrom2."#".$dateTimeEnd2."]]></eventDate>
			</eventDates>
		</item>
		";
	
	$xml .= "
		<item>
			<title><![CDATA[".$title2."]]></title>
			<description><![CDATA[".$content."]]></description>
			<outGoingLink><![CDATA[".$outGoingLink."]]></outGoingLink>
			<thumb><![CDATA[".$thumb2."]]></thumb>
			<yakCats><![CDATA[".$yakCatId1."#".$yakCatId2."]]></yakCats>
			<yakType><![CDATA[".$yakType."]]></yakType>
			<freeTag><![CDATA[".$freeTag."]]></freeTag>
			<pubDate><![CDATA[".$pubDate."]]></pubDate>
			<address><![CDATA[".$address."]]></address>
			<geolocation><![CDATA[".$lat."#".$lng."]]></geolocation> 
			<eventDates>
				<eventDate><![CDATA[".$dateTimeFrom1."#".$dateTimeEnd1."]]></eventDate>
				<eventDate><![CDATA[".$dateTimeFrom2."#".$dateTimeEnd2."]]></eventDate>
			</eventDates>
		</item>
		";


$footer ="</items>";

//file_put_contents('/home/bitnami/stack/data/'.$file, $header.$xml.$footer); 
$fh = fopen('/usr/share/nginx/html/DATA/'.$file, 'w') or die("error");
fwrite($fh, $header.$xml.$footer);
fclose($fh);
   
?>


