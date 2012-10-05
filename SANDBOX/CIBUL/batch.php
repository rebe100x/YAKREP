<?php 

require_once("../../LIB/conf.php");
require_once("../../LIB/place.php");

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
				
				$currentPlace->title = $location->placename;
				$currentPlace->origin = $origin;
				$currentPlace->licence = $licence;
				$currentPlace->setZoneParis();

				$currentPlace->setLocation($location->latitude, $location->longitude);
				/*
				$currentPlace->address["street"] = ;
				$currentPlace->address["zipcode"] = ;
				$currentPlace->address["city"] = ;
				$currentPlace->address["country"] = ;
				*/

				var_dump($currentPlace);
			}
		}

		break;
	}
}