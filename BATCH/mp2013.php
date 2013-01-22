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

$feed = $feedColl->findOne(array('name'=>'MP2013'));
	
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
			
			$address = '';	
			$place = '';
			$geolocation ='';
			$thumb = "";
			$freeTag = array();
			$yakcats = array();
			
		if(!empty($item['name']) && $item['canceled'] == 'False' && !empty($item['startDate']) 
			&& ( (!empty($item['event:location']['place:geo']['geo:latitude']) && !empty($item['event:location']['place:geo']['geo:longitude'])) 
				  || (!empty($item['event:location']['place:address']['address:addressLocality']) && !empty($item['event:location']['place:address']['address:name']) )	) 
				){
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
				
			
			if(!empty($item['image']) && sizeof($item['image']) > 0)
				$thumb = $item['image'];
			
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


	
   
?>


