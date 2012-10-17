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

$cibulApiKey = "22640375947e8efe580bbe056e4c7b60";
$cibulApiUrl = 'https://api.cibul.net/v1/events/';

$origin = "Cibul.net";
$licence = "CIBUL";
$fileTitle = "Cibul Sitemap";
$debug = 1;
$row = 0;
$access = 1;
$user = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0);

/*
 * sitemap.xml
 * If refreshSiteMap is set to True, will get a new sitemap.xml from cibul,
 * otherwise it will load the local one.
 */
if ($refreshSiteMap) {
	echo "Getting sitemap.xml from ", $sitemapUrl, "<br />";

	$sitemap = file_get_contents("http://cibul.net/sitemap.xml");
	file_put_contents($localSitemap, $sitemap);

	echo "Done, saved in ", $localSitemap, "<br />";
}
else {
	echo "Skipping sitemap update", "<br />";
	echo "Loading local file located at ", $localSitemap, "<br />";

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
	if ($url->uid) {

		if($debug)
			echo "Call to cibul Api for Uid: ", $url->uid, "<br />";

		$chuid = curl_init();

		curl_setopt($chuid, CURLOPT_URL, $cibulApiUrl . $url->uid . "?key=" . $cibulApiKey);

		curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, FALSE);

		$json = trim(curl_exec($chuid));
		curl_close($chuid);

		$result = json_decode($json);

		if ($result->data == FALSE) {
			echo "<b>Api call unsuccessfull </b><br />";
		}
		else {
			var_dump($result->data);

			foreach ($result->data->locations as $location) {
				$currentPlace = new Place();
				$currentPlace->filesourceTitle = $fileTitle;
				$currentPlace->title = $location->placename;
				$currentPlace->outGoingLink = $location->slug;
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
					echo  "TRYING TO INSERT: <b>".$currentPlace->title."</b> ".$location->address." -> Zone : ".$currentPlace->zone."<br />";
					
				$cat = array("GEOLOCALISATION","GEOLOCALISATION#YAKDICO","CULTURE");
				$currentPlace->setYakCat($cat);
				
				$res = $currentPlace->saveToMongoDB('', $debug,$updateFlag);

				/* Info */
				$info = new Info();
				$info->title = $result->data->title->fr;

				$info->content = $result->data->description->fr;
				
				// e.g: //cibul.s3.amazonaws.com/evtbevent_rencontre-avec-christophe-botti-auteur-de-th-tre_00.jpg
				// $info->thumb = $result->data->imageThumb;
				$info->origin = $origin;
				$info->filesourceTitle = $fileTitle;
				$info->access = $access;
				$info->licence = $licence;
				$info->pubDate = gmmktime();
				// A recuperer de $result;
				$info->dateEndPrint = gmmktime(0, 0, 0, 9, 1, 2013);
				$info->heat = 1;
				$cat = array("GEOLOCALISATION","GEOLOCALISATION#YAKDICO","CULTURE");
				$info->setYakCat($cat);
				$info->status = 1;
				$info->print = 1;
				$info->yakType = 2;
				$info->zone = $zone;
				$info->placeName = $currentPlace->title;
				$info->address = $currentPlace->address;
				
				$res = $info->saveToMongoDB("", $debug, $updateFlag);
				
				/*
				foreach ($res as $k=>$v) {
					if(isset($v))
						$results[$k]+=$v;
				}
				*/
			}

			/*
			$results['parse'] ++;
			*/
		}
		/*
		$results['row'] ++;	
		*/
		$row++;
		if($row > 1)
			break;
		echo "<hr />";
	}
}
//prettyLog($results);
?>
</body>
</html>