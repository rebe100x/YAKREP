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

function resizeImage($path, $cropWidth, $cropHeight)
{
	$filename = basename($path);
	$info = pathinfo($filename);
	$dirname = dirname($path);

	switch (strtolower($info['extension']))
	{
		case 'jpg':
		case 'jpeg':
			$source = imagecreatefromjpeg($path);
			break;
		case 'png':
			$source = imagecreatefrompng($path);
			break;
		case 'gif':
			$source = imagecreatefromgif($path);
			break;
		default:
			$source = false;
			break;
	}
	if ($source)
	{
		list($width, $height) = getimagesize($path);

		$a = $cropWidth/$cropHeight;
		$b = $width/$height;

		if(($a > $b))
		{
			$src_rect_width  = $a * $height;
			$src_rect_height = $height;
		}
		else
		{
			$src_rect_height = $width/$a;
			$src_rect_width  = $width;
		}

		$destination_thumb = imagecreatetruecolor($cropWidth, $cropHeight);
		$src_rect_xoffset = ($width - $src_rect_width)/2;
		$src_rect_yoffset = ($height - $src_rect_height)/2;

		imagecopyresized($destination_thumb, $source, 0, 0, $src_rect_xoffset, $src_rect_yoffset, $cropWidth, $cropHeight, $src_rect_width, $src_rect_height);

		$dir_for_thumbs = $dirname.'/resized';

		if (!file_exists($dir_for_thumbs))
			mkdir($dir_for_thumbs, 0755);

		$path_thumb = $dir_for_thumbs.'/'.$filename;

		switch (strtolower($info['extension']))
		{
			case 'jpg':
			case 'jpeg':
				imagejpeg($destination_thumb, $path_thumb, 100);
				break;
			case 'png':
				imagepng($destination_thumb, $path_thumb, 100);
				break;
			case 'gif':
				imagegif($destination_thumb, $path_thumb);
				break;
			default:
				break;

		}

		// Destruction des objets temporaires
		imagedestroy($destination_thumb);
		imagedestroy($source);
	}
}

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
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

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
			echo "Call to cibul Api for Uid: ", $url->uid . "<br />";

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
				var_dump($location->latitude);
				
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
					
				$cat = array("GEOLOCALISATION","GEOLOCALISATION#YAKDICO","CULTURE");
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
				
				// e.g: //cibul.s3.amazonaws.com/evtbevent_rencontre-avec-christophe-botti-auteur-de-th-tre_00.jpg
				// $info->thumb = $result->data->imageThumb;

				$fullPath = 'thumbs/'.basename($result->data->imageThumb);

				if (file_exists($fullPath))
				{
					// A verifier si on garde la version deja existante ou si on met a jour
					unlink($fullPath);
				}
				
				$fp = fopen($fullPath, 'x');

				$cleanUrl = ltrim($result->data->imageThumb, "/");

				echo "Gettimg thumbnail from URL = ".$cleanUrl."<br />";

				$ch = curl_init($cleanUrl);
				curl_setopt($ch, CURLOPT_HEADER, 0);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   				curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
				$rawdata = curl_exec($ch);
				curl_close($ch);
				fwrite($fp, $rawdata);
				fclose($fp);

				if (getimagesize($fullPath))
					echo $fullPath . ' Downloaded OK<br />';
				else
					echo $fullPath . ' Download Failed<br />';

				resizeImage($fullPath, 300, 300);

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
				
				$res = $info->saveToMongoDB('', $debug,$updateFlag);
			}

			$results['parse'] ++;
			
		}
		
		$results['row'] ++;	

		$row++;
		if($row > 2)
			break;
		echo "<hr />";
	}
}

$currentPlace->prettyLog($results);

?>
</body>
</html>