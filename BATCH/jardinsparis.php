<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "PARCS, SQUARES et JARDINS DE PARIS"

* original file provides LAT and LNG => we keep them since gmpa does not localise well those places

 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$origin = "operator";
$licence = "yakwala";
$debug = 1;
$fileTitle = "Parcs et jardins de Paris";
			
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;

$results = array('row'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0);

require_once('./input/jardins.php');


foreach($jardins as $jardinJSON){
		
	$jardin = json_decode($jardinJSON[0],1);
	$currentPlace = new Place();
	$currentPlace->title = $jardin['name'];
	echo $jardin['name']."<br>";

	$currentPlace->filesourceTitle = $fileTitle;
	$currentPlace->location = array('lat'=>$jardin['lat'],'lng'=>$jardin['lng']);
	$currentPlace->licence = $licence;
	$currentPlace->origin = $origin;
	$currentPlace->address["street"] = $jardin['address'];
	$currentPlace->address["state"] = "Paris";
	$currentPlace->address["area"] = "Ile-de-France";
	$currentPlace->address["zip"] = $jardin['zipcode'];
	$currentPlace->address["city"] = "Paris";
	$currentPlace->address["country"] = "France";

	$currentPlace->setTagOutdoor();
	$cat = array("#GEOLOCALISATION", "GEOLOCALISATION#YAKDICO","LOISIR", "LOISIR#ESPACEVERT");
	$currentPlace->setYakCat($cat);
	$currentPlace->setZoneParis();
		
	
	$res = $currentPlace->saveToMongoDB('', $debug,$updateFlag);
			
	foreach ($res as $k=>$v) {
		if(isset($v))
			$results[$k]+=$v;
	
	}
	
	
	$results['row'] ++;	
	$row++;
}

prettyLog($results);



