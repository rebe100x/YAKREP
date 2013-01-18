<?php

/* 
	read mp2013 feed and generate a formated xml for exalead
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

$feed = $feedColl->findOne(array('name'=>'mp2013'));
	
	$url= "http://api.mp2013.fr/events?from=".date('Y')."-".date('m')."-".date('d')."&to=2013-12-31&lang=fr&format=json&offset=0&limit=2000";
	$url= "http://api.mp2013.fr/events?from=2013-01-01&to=2013-12-31&lang=fr&format=json&offset=0&limit=2000";
	
	$chuid = curl_init();
	curl_setopt($chuid, CURLOPT_URL, $url);	
	curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);

	$data = trim(curl_exec($chuid));
	curl_close($chuid);
	$data = object_to_array(json_decode($data));
		
	$xml = "";
	$file = $feed['name'].".xml";
	if(substr($conf->deploy,0,3) == 'dev')
		header("Content-Type: application/rss+xml; charset=utf-8");
	$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><items>";

	foreach($data['rdf:Description'] as $item){
		if(!empty($item['name']) && $item['canceled'] == 'False' ){
			//var_dump($item);
			$itemId = substr($item['@attributes']['rdf:about'],strpos($item['@attributes']['rdf:about'],'#')+1);
			$startYear = substr($item['startDate'],0,4);
			$startMonth = substr($item['startDate'],5,2);
			$normalizedName = str_replace("'","",str_replace(" ","-",strtolower(suppr_accents($item['name']))));
			
			$freeTag = array('MP2013');
			$yakcats = array('50923b9afa9a95d409000000','504d89cffa9a957004000001');
			$description = substr($item['description'],0,strpos($item['description'],'==='));
			
			//$description = str_replace('â','A',$item['description']);
			//$description = yakcatPathN($item['description'],1);
			//$description = str_replace('...','DOTDOTDOT',$item['description']);
			//$description = substr($description,0,2110);
			if(!empty($item['event:location']['place:address']['address:name']))
				$place = $item['event:location']['place:address']['address:name'];
				
			$thumb = "";
			if(!empty($item['image']) && sizeof($item['image']) > 0)
				$thumb = $item['image'];
			$address = '';	
			if(!empty($item['event:location']['place:address']['address:name']))
				$address .= $item['event:location']['place:address']['address:name'].", ";
			
			if(!empty($item['event:location']['place:address']['address:streetAddress']))
				$address .= $item['event:location']['place:address']['address:streetAddress'].", ";
			
			if(!empty($item['event:location']['place:address']['address:postalCode']))
				$address .= $item['event:location']['place:address']['address:postalCode'].", ";
			
			if(!empty($item['event:location']['place:address']['address:addressLocality']))
				$address .= $item['event:location']['place:address']['address:addressLocality'];
			
			if( !empty($item['event:location']['place:address']['address:streetAddress']) || !empty($item['event:location']['place:address']['address:postalCode']) || !empty($item['event:location']['place:address']['address:addressLocality']))
				$address .= ", France";
			
			$geolocation ='';
			if(!empty($item['event:location']['place:geo']['geo:latitude']) && !empty($item['event:location']['place:geo']['geo:longitude']))
				$geolocation = $item['event:location']['place:geo']['geo:latitude']."#".$item['event:location']['place:geo']['geo:longitude'];
			
			$startDate = $item['startDate'];
			$endDate = $item['endDate'];
			
			$mp2013Type = $item['type']['@attributes']['resource'];
			$tmp = explode('/',$mp2013Type);
			$tag = $tmp[sizeof($tmp)-1];
			
			switch($tag){
				case 'VisualArtsEvent':
					$freeTag[] = 'Exposition';
					$yakcats[] = '504df70ffa9a957c0b000006'; // exposition
				break;
				case 'MusicEvent':
					$freeTag[] = 'Evènement';
					$yakcats[] = "50696022fa9a955014000008"; // musique
					$yakcats[] = "506479f54a53042191010000"; // concert
				break;
				case 'TheaterEvent':
					$freeTag[] = 'Evènement';
					$yakcats[] = "504df6b1fa9a957c0b000004"; // theatre
					$yakcats[] = "50696022fa9a955014000009"; // spectacle
				break;
				case 'DanceEvent':
					$freeTag[] = 'Evènement';
					$yakcats[] = "50696022fa9a955014000009"; // spectacle
					$yakcats[] = "50f90b48fa9a953809000000"; // danse
				break;
				case 'ComedyEvent':
					$freeTag[] = 'Evènement';
					$yakcats[] = "50696022fa9a955014000009"; // spectacle
				break;
				case 'SocialEvent':
					$freeTag[] = 'Inauguration';
					$freeTag[] = 'Animation';
				break;
				case 'BusinessEvent':
					$freeTag[] = 'Evènement';
				break;
				case 'Festival':
					$freeTag[] = 'Festival';
				break;
				
			}
			
			if( !empty($item['free']) && $item['free'] == 'True')
				$freeTag[] = 'gratuit';
			
			$xml .= "
				<item>
					<title><![CDATA[".$item['name']."]]></title>
					<description><![CDATA[".$description."]]></description>
					<outGoingLink><![CDATA[http://www.mp2013.fr/evenements/".$startYear."/".$startMonth."/".$normalizedName."]]></outGoingLink>
					<thumb><![CDATA[".$thumb."]]></thumb>
					<yakCats><![CDATA[".implode('#',$yakcats)."]]></yakCats>
					<freeTag><![CDATA[".implode('#',$freeTag)."]]></freeTag>
					<pubDate><![CDATA[2013-01-01T00:00:00+0100]]></pubDate>
					<address><![CDATA[".$address."]]></address>
					<place><![CDATA[".$place."]]></place>
					<geolocation><![CDATA[".$geolocation."]]></geolocation> 
					<eventDate><![CDATA[".$startDate."#".$endDate."]]></eventDate>
				</item>
				";
			

		
		}
			
	}
	$footer ="</items>";
	
	if(substr($conf->deploy,0,3) == 'dev')
		echo  $header.$xml.$footer;
	else{	
		
		$fh = fopen('/usr/share/nginx/html/DATA/'.$file, 'w') or die("error");
		fwrite($fh, $header.$xml.$footer);
		fclose($fh);
	}


	/*
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
*/
/*
$fh = fopen('/usr/share/nginx/html/DATA/'.$file, 'w') or die("error");
fwrite($fh, $header.$xml.$footer);
fclose($fh);*/
   
?>


