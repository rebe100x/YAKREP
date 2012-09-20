<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Etablissements culturels de Montpellier"
 * */

include_once "../LIB/place.php";
include_once "../LIB/library.php";
ini_set('display_errors',1);
$filenameInput = "./input/ets_culturels_mtpel.csv";
$origin = "http://data.montpellier-agglo.com/?q=node/200";
$licence = "Licence ouverte";

$row = 0;
$callGmap = 0;
$etsCulturels = array('');
$fieldsProcessed = array('');
$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        
		if($row > 1){
			foreach ($data as $key => $value) {
				$data[$key] = utf8_encode($value);
			}

			$address = $data[2]. " " . $data[4];
			
			$currentObject = new Place;

			$currentObject->title = $data[1];
			$currentObject->origin = $origin;
			$currentObject->licence = $licence;
			$currentObject->address["street"] = $address;
			$currentObject->address["zipcode"] = $data[5];
			$currentObject->address["city"] = $data[6];
			$currentObject->address["country"] = "France";
			$currentObject->contact["tel"] = $data[8];
			$currentObject->contact["mail"] = $data[9];
			$currentObject->zone = 2;
			$currentObject->contact["opening"] = "L: " . $data[12] .
													", M: " . $data[13] .
													", M: " . $data[14] .
													", J: " . $data[15] .
													", V: " . $data[16] .
													", S: " . $data[17] .
													", D: " . $data[18];
			$currentObject->yakTag["couvert, intérieur"] = 1;
			$currentObject->yakTag["enfants"] = 1;

			
			// Get location with gmap
			$query = $data[1] . ", " . $data[5] . " " . $data[6];
			$debug = 0;
			if (!$currentObject->getLocation($query, $debug))
			{
				$query = $address . ", " . $data[5] . " " . $data[6];
				$currentObject->getLocation($query, $debug);
			}
			$callGmap++;

			// YakCat
			$currentObject->setCatCulture();
			$currentObject->setCatYakdico();
			$currentObject->setCatGeoloc();

			if (stristr($data[1], "Médiathèque")) {
				//echo "media : $data[1] <br/>";
				$currentObject->setCatMediatheque();
			}
			elseif (stristr($data[1], "Planétarium")) {
				//echo "Planétarium : $data[1] <br/>";
				$currentObject->setCatPlanetarium();
			}
			elseif (stristr($data[1], "Aquarium")) {
				//echo "Aquarium : $data[1] <br/>";
				$currentObject->setCatAquarium();
			}
			else {
				//echo "autres (musée) : $data[1] <br/>";
				$currentObject->setCatMusee();
			}

			//$currentObject->saveToMongoDB();
		}

		$i++;
		$row++;
    }

    print "Call to gmap : " . $callGmap . "<br>";
    print "<pre>";
    print_r($currentObject);
    print "</pre>";

    fclose($handle);
}

