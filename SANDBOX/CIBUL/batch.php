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
 * Variables
 */
$refreshSiteMap = false;

$sitemap = "";
$sitemapUrl = "http://cibul.net/sitemap.xml";
$localSitemap = "./sitemap.xml";

$cibulApiUrl = 'https://api.cibul.net/v1/events/';
$cibulApiKey = "22640375947e8efe580bbe056e4c7b60";

$origin = "Cibul.net";
$licence = "CIBUL";
$fileTitle = "Cibul Sitemap";
$debug = 1;
$row = 0;
$access = 1;
$user = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

$conf = new Conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

function getJSONfromUID($uid)
{
	global $cibulApiKey, $cibulApiUrl;
	$chuid = curl_init();

	curl_setopt($chuid, CURLOPT_URL, $cibulApiUrl . $uid . "?key=" . $cibulApiKey);	
	curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);

	$json = trim(curl_exec($chuid));
	curl_close($chuid);

	return json_decode($json);
}

/*
 * sitemap.xml
 * If refreshSiteMap is set to True, will get a new sitemap.xml from cibul,
 * otherwise it will load the local one.
 */
if ($refreshSiteMap) {
	echo "Getting sitemap.xml from ", $sitemapUrl, "<br />";

	$sitemap = file_get_contents("http://cibul.net/sitemap.xml");
	file_put_contents($localSitemap, $sitemap);

	echo "Done, saved in ", realpath($localSitemap), "<br />";
}
else {
	echo "Skipping sitemap update", "<br />";
	echo "Loading local file located in ", realpath($localSitemap), "<br />";

	if (! file_exists($localSitemap)) {
		echo "Failed to load file.", "<br />";
		return;
	}
	else {
		$sitemap = file_get_contents($localSitemap);
		echo "Load successful <br />";
	}
}

echo "<hr />";
/*
 * Xml parsing with simpleXml
 */
$urlset = simplexml_load_string($sitemap);
$currentPlace;

foreach ($urlset->url as $url) {

	if ($url->uid && $url->loc) {
		$infoQuery = array("outGoingLink" => $url->loc);
		$dataExists = $db->info->findOne($infoQuery);

		if (!empty($dataExists)) {
			if ($updateFlag) {
				echo "Event ". $url->loc . " already in DB, forcing update.<br />";
			}
			else {
				echo "Ignored event ". $url->loc . "<br />";
			}
		}

		if (empty($dataExists) || $updateFlag) {
			if($debug) {
				echo "Call to cibul Api for Uid: ". $url->uid . "<br />";
			}

			$result = getJSONfromUID($url->uid);
			if ($result->data == FALSE) {
				echo "<b>Api call unsuccessful</b><br />";
			}
			else {
				foreach ($result->data->locations as $location) {

					$currentPlace = new Place();
					$currentPlace->filesourceTitle = $fileTitle;
					$currentPlace->title = $location->placename;
					$currentPlace->origin = $origin;
					$currentPlace->licence = $licence;
					$currentPlace->formatted_address = $location->address;
					$currentPlace->setLocation($location->latitude, $location->longitude);
					$currentPlace->origin = $origin;
					
					
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
					echo $location->address." <b>not in your zone -> skipped.</b><br />";
					$results['rejected'] ++;	
					$results['row'] ++;	
					continue;
				}
				
				$currentPlace->zone = $zone;

				if($debug)
					echo  "TRYING TO INSERT: <b>".$currentPlace->title."</b>: ".$location->address." -> Zone : ".$currentPlace->zone."<br />";
				
					$cat = array("GEOLOCALISATION", "GEOLOCALISATION#YAKDICO","CULTURE"); // FOR THE PLACE : need a YAKDICO
					$currentPlace->setYakCat($cat);
					
					$res = $currentPlace->saveToMongoDB('', $debug,$updateFlag);
					
					if($res['record']['_id'])
						echo 'Place saved ID = '.$res['record']['_id']."<br />";
					foreach ($res as $k=>$v) {
						if(isset($v))
							$results[$k]+=$v;
					}

					/* Info */
					$info = new Info();
					$info->title = $result->data->title->fr;
					$info->content = $result->data->description->fr;

					$info->thumb = "/thumb/".createImgThumb(ltrim($result->data->imageThumb, "/"), $conf);

					$info->origin = $origin;
					$info->filesourceTitle = $fileTitle;
					$info->access = $access;
					$info->licence = $licence;

					// Link to cibul description
					$info->outGoingLink = $url->loc;

					// Set timezone
					if( ! ini_get('date.timezone') ) {
						date_default_timezone_set('Europe/Paris');
					}

					$dateUpdatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $result->data->updatedAt);
					$info->pubDate = new MongoDate($dateUpdatedAt->getTimestamp());
					$info->heat = 1;

					$cat = array("CULTURE","AGENDA");  // FOR THE INFO

					$freeTag = array();
					foreach (explode(",", $result->data->tags->fr) as $tag) {
						$freeTag[] = $tag;
						$temp_tag = suppr_accents($tag);
						if (preg_match("/THEATRE/i", $temp_tag)) {
							$cat[] = "CULTURE#THEATRE";
						}
						else if (preg_match("/CONCERT/i", $temp_tag)) {
							$cat[] = "CULTURE#MUSIQUE";
						}
						else if (preg_match("/OPERA/i", $temp_tag)) {
							$cat[] = "CULTURE#MUSIQUE";
							$info->yakTag[] = "Classique";
						}
					}

					$info->setYakCat($cat);
					$info->freeTag = $freeTag;
					$info->status = 1;
					$info->print = 1;
					$info->yakType = 2;
					$info->zone = $zone;
					$info->placeName = $currentPlace->title;

					// Duplicate info for each date
					foreach ($location->dates as $date) {
						$eventDate = array();
						$dateDay = DateTime::createFromFormat('Y-m-d', $date->date);

						$dateFrom = DateTime::createFromFormat('Y-m-d H:i:s', $date->date . " " . $date->timeStart);
						var_dump($dateFrom);
						$eventDate['dateFrom'] = new MongoDate(date_timestamp_get($dateFrom));

						$dateEnd = DateTime::createFromFormat('Y-m-d H:i:s', $date->date . " " . $date->timeEnd);
						$eventDate['dateEnd'] = new MongoDate(date_timestamp_get($dateEnd));

						// hreventdate format example 2012-04-13 21:30
						$eventDate['hreventdate'] = $date->date . " - " . mb_substr($date->timeStart, 0, -3);
						
						$info->eventDate = $eventDate;

						$dateEndPrint = strtotime("+7 days", date_timestamp_get($dateEnd));
						$info->dateEndPrint = new MongoDate($dateEndPrint);

						$res = $info->saveToMongoDB('', $debug,$updateFlag);
					}
				}

				$results['parse'] ++;

				$results['row'] ++;
				$row++;
			}
		}


		echo "<hr />";
	}

	if($row > 2)
		break;
}

$currentPlace->prettyLog($results);

?>
</body>
</html>