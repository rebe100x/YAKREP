<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "PARCS, SQUARES et JARDINS DE PARIS"
 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$origin = "http:/yakwala.fr";
$licence = "yakwala";
$debug = 1;
			
$row = 0;
$insert = 0;
$update = 0;
$locError = 0;
$doublon = 0;

$i=0;
$j=0;

require_once('./input/jardins.php');
var_dump(sizeof($jardins));

foreach($jardins as $jardinJSON){
	if($row == 11)
		exit;
	$jardin = json_decode($jardinJSON[0],1);
	var_dump($jardin);
	$currentPlace = new Place();
	$currentPlace->title = $jardin['name'];
	echo $jardin['name']."<br>";

	$currentPlace->location = array('lat'=>$jardin['lat'],'lng'=>$jardin['lng']);
	$currentPlace->licence = $licence;
	$currentPlace->address["street"] = $jardin['address'];
	$currentPlace->address["zipcode"] = $jardin['zipcode'];
	$currentPlace->address["city"] = "Paris";
	$currentPlace->address["country"] = "France";

	$currentPlace->setTagOutdoor();
	$cat = array("#GEOLOCALISATION", "GEOLOCALISATION#YAKDICO","LOISIR", "LOISIR#ESPACEVERT");
	$currentPlace->setYakCat($cat);
	$currentPlace->setZoneParis();
		
	
	switch ($currentPlace->saveToMongoDB($locationQuery, $debug, false)) {
				case '1':
					$insert++;
					$locError++;
					break;
				case '2':
					//print "updated <br>";
					$update++;
					break;
				case '3':
					//print "doublon <br>";
					$doublon++;
					break;
				default :
					//print "insert (1 call to gmap)<br>";
					$insert++;
					break;
	}
	$row++;
}

print "<br>________________________________________________<br>
		JARDINS DE PARIS: done <br>";
print "Rows : " . ($row-1) . "<br>";
print "Call to gmap : " . $insert . "<br>";
print "Location error (call gmap) : " . $locError . "<br>";
print "Insertions : " . $insert . "<br>";
print "Updates : " . $update . "<br>";
print "Doublons : " . $doublon . "<br>";



