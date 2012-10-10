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
			foreach ($result->data->locations as $location) {
				$currentPlace = new Place();
				$currentPlace->filesourceTitle = $fileTitle;
				$currentPlace->title = $location->placename;
				$currentPlace->outGoingLink = $location->slug;
				$currentPlace->origin = $origin;
				$currentPlace->licence = $licence;
				$currentPlace->setZone("PARIS");
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
					echo "<br>".$location->address." <b>not in your zone -> skip it !</b> ";
					$results['rejected'] ++;	
					$results['row'] ++;	
					continue;
				}
				
				if($debug)
					echo  "<br>TRY TO INSERT : <b>".$currentPlace->title."</b>".$location->address." ==> Zone : ".$currentPlace->zone."<br>";
					
				$cat = array("GEOLOCALISATION","GEOLOCALISATION#YAKDICO","CULTURE");
				$currentPlace->setYakCat($cat);
				
				$res = $currentPlace->saveToMongoDB('', $debug,$updateFlag);
				foreach ($res as $k=>$v) {
					if(isset($v))
						$results[$k]+=$v;
				}
			}
			$results['parse'] ++;
		}
		$results['row'] ++;	
		$row++;
		if($row >50)
			break;
	}
}
prettyLog($results);
?>
</body>
</html>