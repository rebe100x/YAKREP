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
/* 
 * 
 * */
 
require_once("../LIB/place.php");
require_once("../LIB/library.php");

ini_set('display_errors',1);
$filenameInput = "./input/offreCulturelle.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Agenda---Offres-culture-2011-30382214";
$licence = "Licence ouverte";

$row = 0;
$update = 0;
$doublon = 0;
$insert = 0;
$locError = 0;

$currentPlace;

if (($handle = fopen($filenameInput, "r")) !== FALSE) {

	print_r("<h3>Input : " . $filenameInput . "</h3>\n");

    while (($data = fgetcsv($handle, 2000, ";")) !== FALSE) {
		
		if ($row > 0) {
			

			// Field 60 is the country where the event takes place but
			// it seems that everyone misuses it and fill only field 5
			if ($data[4] == "France") {
				$currentPlace = new Place();

				if (!isset($data[38]) || NULL == $data[38] || trim($data[38]) == "") {
					continue;
				}

				$currentPlace->setTitle($data[38]);

				$currentPlace->origin = $origin;
				$currentPlace->licence = $licence;

				// If no street address, skip this element
				if (trim($data[8]) == "") {
					continue;
				}

				$currentPlace->address["street"] = $data[6] . " " . $data[7] . " " . $data[8] . " " . $data[9];
				$currentPlace->address["zipcode"] = $data[13];
				$currentPlace->address["city"] = $data[14];
				$currentPlace->address["country"] = $data[5];

				// If zone isn't Paris or Montpellier, skip
				if (preg_match("/paris/i", $currentPlace->address["city"])) {
					$currentPlace->setZoneParis();
				}
				else if (preg_match("/montpellier/i", $currentPlace->address["city"])) {
					$currentPlace->setZoneMontpellier();
				}
				else {
					continue;
				}

				// Useful for regex matches
				$softTitle = suppr_accents($currentPlace->title);

				// Finding yakcats
				if ($data[2] == "Musées" || preg_match("/musee/i", $softTitle) || preg_match("/musee/i", suppr_accents($data[20])) ) {
					$currentPlace->setCatMusee();
				}
				else if (preg_match("/aquarium/i", $softTitle)) {
					$currentPlace->setCatAquarium();
				}
				else if (preg_match("/opera/i", $softTitle)) {
					$currentPlace->setCatMusique();
					$currentPlace->yakTag[] = "Classique";
				}
				else if (preg_match("/jardin/i", $softTitle)) {
					$currentPlace->setCatEspaceVert();
					$currentPlace->setCatExposition();
					$currentPlace->yakTag[] = "Plein air";
				}
				else if (preg_match("/eglise/i", $softTitle) || preg_match("/abbaye/i", $softTitle)) {
					$currentPlace->setCatReligion();
				}
				else if ($data[2] == "Organisme de Spectacle") {
					$currentPlace->setCatTheatre();
				}
				else if ($data[2] == "Organisme d'Arts plastiques") {
					$currentPlace->setCatExposition();
				}
				else if (preg_match("/archives/i", $data[20])) {
					$currentPlace->setCatArchive();
				}
				else if ($data[2] == "Monument ou site") {
					$currentPlace->setCatGeoloc();
				}
				else {
					$currentPlace->setCatCulture();
				}
				
				$query = $currentPlace->title .' ' . $currentPlace->address["street"] . ' ' . $currentPlace->address["zipcode"] . ' ' . $currentPlace->address["city"] . ', ' . $currentPlace->address["country"];
				$debug = 1;

				// Only for debug
				// $currentPlace->dropAllPlaces();

				switch ($currentPlace->saveToMongoDB($query, $debug, false)) {
					case '1':
						$locError++;
						break;
					case '2':
						$update++;
						break;
					case '3':
						$doublon++;
						break;
					default:
						$insert++;
						print_r($currentPlace->prettyPrint() . "\n<hr/>\n");
						break;
				}
				
				//var_dump($currentPlace);

				//print_r("GMap query: " . $query . "\n");

				//$currentPlace->getLocation($query, 0);
			}
		}
		
		$row++;
    }
    print "<br/> doublon : $doublon - insert : $insert - update : $update - error loc : $locError <br>";
    fclose($handle);
    print_r("offreCulturelle done.\n");
}

?>
</body>
</html>