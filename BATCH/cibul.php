<?php 
require_once("../LIB/conf.php");

/*
 * GET DATA FROM CIBUL API
 * GENERATE THE XML FOR EXALEAD
 */

// Cibul api details
$cibulApiUrl = 'https://api.cibul.net/v1/events/';
$cibulApiKey = "22640375947e8efe580bbe056e4c7b60";



// Default inserted places settings
$origin = "Cibul";
$originLink ="http://cibul.net";
$licence = "CIBUL";

$localSitemap = "./CIBUL/sitemap.xml";

$debug = 1;
$conf = new Conf();

$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$feedColl = $db->feed;

$xml = "";
$callCibull = 0;
$cibulTags = array();

/**
* Calls the Api with the pre-defined key.
* Param: 
*	$uid - event id from sitemap.xml
* Returns result data in JSON from Cibul.net api
*/
function getJSONfromUID($uid) {
	global $cibulApiKey, $cibulApiUrl;
	$chuid = curl_init();
	$url = $cibulApiUrl . $uid . "?key=" . $cibulApiKey;
	curl_setopt($chuid, CURLOPT_URL, $url);	
	curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);

	$json = trim(curl_exec($chuid));
	curl_close($chuid);

	return json_decode($json);
}


$feed = $feedColl->findOne(array('name'=>'CIBUL'));

if($debug == 1){
	$data = file_get_contents($localSitemap);
}else{
	$url = $feed["linkSource"][0];
	$chuid = curl_init();
	curl_setopt($chuid, CURLOPT_URL, $url);	
	curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);
	$data = trim(curl_exec($chuid));
	curl_close($chuid);
}

/* Begin sitemap parsing with simpleXml */
$urlset = simplexml_load_string($data);
header("Content-Type: application/rss+xml; charset=utf-8");
$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><items>";

$row= 0;
foreach ($urlset->url as $url) {
	
	if ($url->uid && $url->loc &&  $url->lastmod ) {	// Only parse events with an uid and a direct link and a modif date 
		
		$lastModificationDate = DateTime::createFromFormat('Y-m-d', $url->lastmod);
		$fromDate = DateTime::createFromFormat('Y-m-d', date('Y-m-d',mktime()-30*24*60*60)); // last 30 days
		//var_dump($lastModificationDate);
		if(!$lastModificationDate)
			continue;
		if( $lastModificationDate->getTimestamp() < $fromDate->getTimestamp() ) // take only last 30 days
			continue;
		
		
		$result = getJSONfromUID($url->uid);
		//var_dump($result);
		// Error on api call
		if ($result->data == FALSE) {
			echo "<b>Api call unsuccessful</b>" . "<br />";
		}
		else {
				$row++;
				// Temporary break
				if($row > 5)
					break;
					
				foreach ($result->data->locations as $location) {
					// Set zone or skip
					if (preg_match("/paris/i", $location->address) 
						|| preg_match("/75[0-9]{3}/i", $location->address) ) {
						$zone = 1;
					}else{
						
						// Skip the rest of the loop
						continue;
					}
					
					$geolocation = $location->latitude."#".$location->longitude;
					
					if (isset($result->data->title->fr)) {
						$title = $result->data->title->fr;
					}
					else {
						$title = $result->data->title->en;
					}
					
					if (isset($result->data->description->fr)) {
						$content = html_entity_decode($result->data->description->fr);
					}
					else {
						$content = html_entity_decode($result->data->description->en);
					}
					
					if (isset($result->data->freeText->fr)) {
						$content .= "<br>".html_entity_decode($result->data->freeText->fr);
					}
					else {
						$content .= "<br>".html_entity_decode($result->data->freeText->en);
					}
					if (isset($result->data->pricingInfo->fr)) {
						$content .= "<br>".html_entity_decode($result->data->pricingInfo->fr);
					}
					
					if (isset($result->data->imageThumb)) {
						$thumb = $result->data->imageThumb;
					}
					else {
						$thumb = '';
					}
					
					if (isset($result->data->tags->fr)) {
						$cibulTags = explode(",", $result->data->tags->fr);
					}
					else {
						$cibulTags = explode(",", $result->data->tags->en);
					}
					
					
					if(!empty($cibulTags) && sizeof($cibulTags)){
						foreach ($cibulTags as $tag) {
							$freeTag[] = to_camel_case($tag);
							$temp_tag = yakcatPathN($tag,1);
							if (preg_match("/THEATRE/i", $temp_tag)) {
									$cat[] = "CULTURE#THEATRE";
									$catName[] = "Théatre";
							}
							else if (preg_match("/CONCERT/i", $temp_tag)) {
									$cat[] = "CULTURE#MUSIQUE";
									$catName[] = "Musique";
							}
							else if (preg_match("/OPERA/i", $temp_tag)) {
									$cat[] = "CULTURE#MUSIQUE";
									$catName[] = "Musique";
								
								$freeTag[] = "Classique";
								$freeTag[] = "Opéra";
							}
						}
					}
					
					$eventDate = '';
					
					foreach ($location->dates as $date) {
						$dateTimeFrom = $date->date . "T" . $date->timeStart."+0100";
						$dateTimeEnd = $date->date . "T" . $date->timeEnd."+0100";
						$tsDateTimeEnd = DateTime::createFromFormat('Y-m-d H:i:s', $date->timeEnd);
						$eventDate .= $dateTimeFrom."#".$dateTimeEnd;
					}
					
					$dateEndPrint = strtotime("+1 day", $tsDateTimeEnd);
					
					
						
					$pubDateTmp = explode(' ',$result->data->updatedAt);
					$pubDate = $pubDateTmp[0] ."T".$pubDateTmp[1]."+0100";
					
					$xml .= "
						<item>
							<title><![CDATA[".$title."]]></title>
							<description><![CDATA[".$content."]]></description>
							<outGoingLink><![CDATA[".(string)($url->loc)."]]></outGoingLink>
							<thumb><![CDATA[".$thumb."]]></thumb>
							<yakCats><![CDATA[]]></yakCats>
							<freeTag><![CDATA[".implode('#',$freeTag)."]]></freeTag>
							<pubDate><![CDATA[".$pubDate."]]></pubDate>
							<address><![CDATA[".$location->address."]]></address>
							<place><![CDATA[".$location->placename."]]></place>
							<geolocation><![CDATA[".$geolocation."]]></geolocation> 
							<eventDate><![CDATA[".$eventDate."]]></eventDate>
						</item>
						";
						
						
				}
			}
		}
	}
	
$footer ="</items>";
	
echo $header.$xml.$footer;
	

	



?>