<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Yakwala Batch</title>
	<meta http-equiv="content-type" 
	content="text/html;charset=utf-8" />
</head>

<body>
	<?php 

	require_once("../../LIB/conf.php");

/*
 * Global batch variables
 */

// true to refresh SiteMap.xml from cibul.net
$refreshSiteMap = false;

$sitemap = "";
$sitemapUrl = "http://cibul.net/sitemap.xml";
$localSitemap = "./sitemap.xml";

// Cibul api details
$cibulApiUrl = 'https://api.cibul.net/v1/events/';
$cibulApiKey = "22640375947e8efe580bbe056e4c7b60";

// Default inserted places settings
$origin = "Cibul";
$originLink ="http://cibul.net";
$licence = "CIBUL";
$fileTitle = "Cibul Sitemap";
$debug = 1;
$row = 0;
$access = 1;
$user = 0;

// UpdateFlag is a query parameter, if 1, force update
$updateFlag = empty($_GET['updateFlag'])?0:1;

// Array to store logs
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

// Open conf for environment variables
$conf = new Conf();

// Init mongodb connection
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

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
	echo "<br>URL CALLED : "+$url+"<br>";
	curl_setopt($chuid, CURLOPT_URL, $url);	
	curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);

	$json = trim(curl_exec($chuid));
	curl_close($chuid);

	return json_decode($json);
}

/**
*	Print message if $debug is set to true.
*/
function print_debug($message) {
	global $debug;

	if ($debug)
		echo $message;
}

/* Begin refreshSiteMap */
if ($refreshSiteMap) {
	print_debug("Getting sitemap.xml from " . $sitemapUrl .  "<br />");

	$sitemap = file_get_contents("http://cibul.net/sitemap.xml");
	file_put_contents($localSitemap, $sitemap);

	print_debug("Done, saved in " . realpath($localSitemap) .  "<br />");
}
else {
	print_debug("Skipping sitemap update" .  "<br />");
}

print_debug("Loading local file located in " . realpath($localSitemap) . "<br />");

if (! file_exists($localSitemap)) {
	print_debug("Failed to load file. Terminating now." .  "<br />");
	return;	// Exit batch
}
else {
	$sitemap = file_get_contents($localSitemap);
	print_debug("Load successful" . "<br />");
}
/* End refreshSiteMap */

print_debug("<hr />");

/* Begin sitemap parsing with simpleXml */
$urlset = simplexml_load_string($sitemap);
$currentPlace;
$callCibull = 0;
foreach ($urlset->url as $url) {
	
	$lastModificationDate = DateTime::createFromFormat('Y-m-d', $url->lastmod);
	$fromDate = DateTime::createFromFormat('Y-m-d', "2012-12-01");
	if ($url->uid && $url->loc &&  $lastModificationDate->getTimestamp() > $fromDate->getTimestamp() ) {	// Only parse events with an uid and a direct link and a modif date 
		echo '<br>LAST MODIF :'+$url->lastmod;
		$infoQuery = array("outGoingLink" => $url->loc);
		$dataExists = $db->info->findOne($infoQuery);

		if (!empty($dataExists)) {	// Data already exists in mongo
			if($updateFlag) {
				print_debug("Event ". $url->loc . " already in DB, forcing update." . "<br />");
			}
			else {
				print_debug("Evetn already in db: ". $url->loc . "<br />");
			}
		}

		if (empty($dataExists) || $updateFlag) {	// data doesn't exist OR we need to force update
			print_debug( "Call to cibul Api for Uid: ". $url->uid . "<br />");

			$result = getJSONfromUID($url->uid);
			$callCibull++;
			// Error on api call
			if ($result->data == FALSE) {
				print_debug("<b>Api call unsuccessful</b>" . "<br />");
			}
			else {

				/* Begin place insertion in mongodb */
				foreach ($result->data->locations as $location) {

					$currentPlace = new Place();
					$currentPlace->filesourceTitle = $fileTitle;
					$currentPlace->title = $location->placename;
					$currentPlace->origin = $origin;
					$currentPlace->licence = $licence;
					$currentPlace->formatted_address = $location->address;
					$currentPlace->setLocation($location->latitude, $location->longitude);
					$currentPlace->origin = $origin;
					$currentPlace->status = 1;
					
					// Set zone or skip
					if (preg_match("/paris/i", $location->address) 
						|| preg_match("/75[0-9]{3}/i", $location->address) ) {
						$zone = 1;
					}else if (preg_match("/montpellier/i", $location->address)
						|| preg_match("/34[0-9]{3}/i", $location->address)) {
						$zone = 2;
					}else if (preg_match("/EGHEZEE/i", $location->address)
						|| preg_match("/5310/i", $location->address)) {
						$zone = 3;
					}else{
						print_debug($location->address . " <b>not in your zone -> skipped.</b>" . "<br />");
						$results['rejected'] ++;	
						$results['row'] ++;

						// Skip the rest of the loop
						continue;
					}
					$currentPlace->zone = $zone;

					print_debug("TRYING TO INSERT: <b>".$currentPlace->title."</b>: ".$location->address." -> Zone : ".$currentPlace->zone."<br />");
					
					// Set default yakCats
					$cat = array("GEOLOCALISATION", "GEOLOCALISATION#YAKDICO","CULTURE");

					$currentPlace->setYakCat($cat);
					
					$res = $currentPlace->saveToMongoDB('', $debug,$updateFlag);
					
					if($res['record']['_id'])
						print_debug('Place saved ID = '.$res['record']['_id']."<br />");
					foreach ($res as $k=>$v) {
						if(isset($v))
							$results[$k]+=$v;
					}

					/* Begin info insertion in mongodb */
					$info = new Info();

					if (isset($result->data->title->fr)) {
						$info->title = $result->data->title->fr;
					}
					else {
						$info->title = $result->data->title->en;
					}

					if (isset($result->data->description->fr)) {
						$info->content = html_entity_decode($result->data->description->fr);
					}
					else {
						$info->content = html_entity_decode($result->data->description->en);
					}
					
					if (isset($result->data->freeText->fr)) {
						$info->content .= html_entity_decode($result->data->freeText->fr);
					}
					else {
						$info->content .= html_entity_decode($result->data->freeText->en);
					}
					// Using createImgThumb from /lib/library.php
					$thumbFlag = 0;
					if(file_exists($result->data->imageThumb))
						$res = createImgThumb(ltrim($result->data->imageThumb, "/"), $conf);
					else
						$res = 0;
					if($res == false){
						$info->thumb = "";		
					}else{
						$thumbFlag = 1;
						$size = getimagesize('http://'.ltrim($result->data->imageThumb, "/"));
						if($size[0] > 320)
							$thumbFlag = 2;	
						else
							$thumbFlag = 1;	
						$info->thumb = "thumb/".$res;
					}

					$info->origin = $origin;
					$info->filesourceTitle = $fileTitle;
					$info->access = $access;
					$info->licence = $licence;

					// Link to cibul description
					$info->outGoingLink = (string)($url->loc);
					// Set timezone if not already set in php.ini
					date_default_timezone_set('Europe/Paris');
					$dateUpdatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $result->data->updatedAt);
					$info->pubDate = new MongoDate($dateUpdatedAt->getTimestamp());
					
					// Heat set to 1 for new infos
					$info->heat = 80;

					// Default yakCat
					$cat = array("CULTURE","AGENDA");
					$catName = array("Culture", "Agenda"); 
					/* Begin regex to find yakCats */
					$freeTag = array();
					$cibulTags = array();

					if (isset($result->data->tags->fr)) {
						$cibulTags = explode(",", $result->data->tags->fr);
					}
					else {
						$cibulTags = explode(",", $result->data->tags->en);
					}

					foreach ($cibulTags as $tag) {
						$freeTag[] = yakcatPathN($tag,0);
						$temp_tag = yakcatPathN($tag,1);
						if (preg_match("/THEATRE/i", $temp_tag)) {
							if(!in_array("CULTURE#THEATRE",$cat))
								$cat[] = "CULTURE#THEATRE";
							if(!in_array("Théatre",$catName))	
								$catName[] = "Théatre";
						}
						else if (preg_match("/CONCERT/i", $temp_tag)) {
							if(!in_array("CULTURE#MUSIQUE",$cat))
								$cat[] = "CULTURE#MUSIQUE";
							if(!in_array("Musique",$catName))
								$catName[] = "Musique";
						}
						else if (preg_match("/OPERA/i", $temp_tag)) {
							if(!in_array("CULTURE#MUSIQUE",$cat))
								$cat[] = "CULTURE#MUSIQUE";
							if(!in_array("Musique",$catName))
								$catName[] = "Musique";
							
							$info->freeTag[] = "Classique";
							$info->freeTag[] = "Opéra";
						}
					}

					$info->setYakCat($cat);
					$info->yakCatName = $catName;
					$info->freeTag = $freeTag;
					/* End regex to find yakCats */

					$info->status = 1;
					$info->print = 1;
					$info->yakType = 2;
					$info->zone = $zone;
					$info->placeName = $currentPlace->title;
					if(sizeof($location->dates) > 1)
						echo '<div style=\'background-color:#00FF00\'>MULTIPLE DATE</div>';
					$eventDate = array();
					
					foreach ($location->dates as $date) {
						$eventDate = array();
						$dateTimeFrom = DateTime::createFromFormat('Y-m-d H:i:s', $date->date . " " . $date->timeStart);
						$eventDate['dateTimeFrom'] = new MongoDate(date_timestamp_get($dateTimeFrom));
						
						
						$dateTimeEnd = DateTime::createFromFormat('Y-m-d H:i:s', $date->date . " " . $date->timeEnd);
						$eventDate['dateTimeEnd'] = new MongoDate(date_timestamp_get($dateTimeEnd));
						$info->eventDate[] = $eventDate;
						
					}
					
					$dateEndPrint = strtotime("+1 day", date_timestamp_get($dateTimeEnd));
					$info->dateEndPrint = new MongoDate($dateEndPrint);  
					//$info->dateEndPrint = new MongoDate(mktime()+7*86400); // FOR DEBUG
					
					$res = $info->saveToMongoDB('', $debug,$updateFlag);
					echo 'INFO SAVED';
					/*
					// Duplicate info for each date
					foreach ($location->dates as $date) {
					echo '<hr>date';
					var_dump($date);
						$eventDate = array();
						$dateDay = DateTime::createFromFormat('Y-m-d', $date->date);

						$dateFrom = DateTime::createFromFormat('Y-m-d H:i:s', $date->date . " " . $date->timeStart);
						$eventDate['dateFrom'] = new MongoDate(date_timestamp_get($dateFrom));

						$dateEnd = DateTime::createFromFormat('Y-m-d H:i:s', $date->date . " " . $date->timeEnd);
						$eventDate['dateEnd'] = new MongoDate(date_timestamp_get($dateEnd));

						// hreventdate format example 2012-04-13 21:30
						$eventDate['hreventdate'] = $date->date . " - " . mb_substr($date->timeStart, 0, -3);
						
						$info->eventDate = $eventDate;

						$dateEndPrint = strtotime("+1 day", date_timestamp_get($dateEnd));
						$info->dateEndPrint = new MongoDate($dateEndPrint);  
						//$info->dateEndPrint = new MongoDate(mktime()+7*86400); // FOR DEBUG
								
						$res = $info->saveToMongoDB('', $debug,$updateFlag);
						echo 'INFO SAVED';
					}
					*/
					
					
					
					/* End info insertion in mongodb */
				}
				/* End place insertion in mongodb */

				$results['parse'] ++;

				$results['row'] ++;
				$row++;
			}
		}
		print_debug("<hr />");
	}

	// Temporary break
	//if($row > 5)
	//	break;
}
/* End sitemap parsing with simpleXml */

echo "<br>call to cibul"+$callCibull+"<br>";
$currentPlace->prettyLog($results);

?>
</body>
</html>