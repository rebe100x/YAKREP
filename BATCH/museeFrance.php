<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Etablissements culturels de Montpellier"
 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$filenameInput = "./input/museeFrance.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Liste-des-Mus%C3%A9es-de-France-30382165";
$license = "licence ouverte";

$row = 0;
$callGmap = 0;
$etsCulturels = array('');
$fieldsProcessed = array('');
$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        
		if($row > 0){
			foreach ($data as $key => $value) {
				$data[$key] = utf8_encode($value);
			}
			
			$currentObject = new Place;

			$currentObject->title = $data[4];
			$currentObject->origin = $origin;
			$currentObject->license = $license;
			$currentObject->address["street"] = $data[5];
			$currentObject->address["zipcode"] = $data[6];
			$currentObject->address["city"] = $data[7];
			$currentObject->address["country"] = "France";
			$currentObject->contact["web"] = $data[8];
			
			if ($data[2] != "NON" && empty($data[9]) && empty($data[10]))
			{
				if ($data[2] == "OUI")
					$currentObject->contact["closing"] = "Fermé";
				else
					$currentObject->contact["closing"] = $data[2];
			}
			else
			{
				$currentObject->contact["closing"] = $data[9];
				$currentObject->contact["opening"] = $data[10];
				if (!empty($data[11]))
					$currentObject->contact["special opening"] = "Nocturnes : ". $data[11];
			}

			$currentObject->yakTag["couvert, intérieur"] = 1;
			
			// Get location with gmap
			$query = $data[4] . ", " . $data[6] . " " . $data[7];
			$debug = 0;
			if (!$currentObject->getLocation($query, $debug))
			{
				$query = $data[5] . ", " . $data[6] . " " . $data[7];
				$currentObject->getLocation($query, $debug);
			}
			$callGmap++;

			// YakCat
			$currentObject->setCatCulture();
			$currentObject->setCatYakdico();
			$currentObject->setCatGeoloc();
			$currentObject->setCatMusee();
			
			if ($data[1] == "PARIS")
				$currentObject->setZoneParis();
			elseif ($data[1] == "HERAULT")
				$currentObject->setZoneMontpellier();
			else
				$currentObject->setZoneOther();

			print "<pre>";
	    	print_r($currentObject);
	    	print "</pre>";
			
			print $data[4] . " : ". $currentObject->saveToMongoDB() . "<br>";
		}

		$i++;
		$row++;

    }

    print "Call to gmap : " . $callGmap . "<br>";
   
    fclose($handle);
}

