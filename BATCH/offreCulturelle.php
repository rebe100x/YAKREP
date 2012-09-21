<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* 
 * 
 * */
 
require_once("../LIB/place.php");
require_once("../LIB/library.php");

ini_set('display_errors',1);
$filenameInput = "./input/offreCulturelle_testDoublons.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Agenda---Offres-culture-2011-30382214";
$licence = "Licence ouverte";

$row = 0;
$currentPlace;

if (($handle = fopen($filenameInput, "r")) !== FALSE) {
	print_r("Input : " . $filenameInput . "\n");
    while (($data = fgetcsv($handle, 2000, ";")) !== FALSE) {
		
		if ($row > 0) {
			foreach ($data as $key => $value) {
				$data[$key] = utf8_encode($value);
			}

			// Field 60 is the country where the event takes place but
			// it seems that everyone misuses it and fill only field 5
			if ($data[4] == "France") {
				$currentPlace = new Place();

				if ($data[38] !== '') {
					$currentPlace->title = $data[38];
				}
				else if ($data[5] !== '') {
					$currentPlace->title = $data[5];
				}
				else {
					$currentPlace->title = $data[0];
				}
				$currentPlace->title = ucwords(strtolower($currentPlace->title));

				$currentPlace->origin = $origin;
				$currentPlace->licence = $licence;

				$currentPlace->address["street"] = $data[6] . " " . $data[7] . " " . $data[8] . " " . $data[9];
				$currentPlace->address["zipcode"] = $data[13];
				$currentPlace->address["city"] = $data[14];
				$currentPlace->address["country"] = $data[5];

				$currentPlace->setZoneOther();

				if ($data[2] == "Musées") {
					$currentPlace->setCatMusee();
				}
				else if ($data[2] == "Monument ou site") {
					$currentPlace->setCatGeoloc();
				}
				else {
					$currentPlace->setCatCulture();
				}

				var_dump($currentPlace);

				$query = $currentPlace->title .' ' . $currentPlace->address["street"] . ' ' . $currentPlace->address["zipcode"] . ' ' . $currentPlace->address["city"] . ', ' . $currentPlace->address["country"];

				print_r("GMap query: " . $query . "\n");

				$currentPlace->getLocation($query, 0);
				
				$currentPlace->saveToMongoDB();
			}
		}
		
		$row++;
    }
    fclose($handle);
    print_r("offreCulturelle done.\n");
}