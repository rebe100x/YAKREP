<?php
/* 
 * 
 * */
require_once("../TOOLS/place.php");
require_once("../LIB/library.php");

ini_set('display_errors',1);
$filenameInput = "./input/offreCulturelle.csv";
$filenameOutput = "./output/offreCulturelle.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Agenda---Offres-culture-2011-30382214";
$licence = "Licence ouverte";

$row = 0;
$currentPlace;

if (($handle = fopen($filenameInput, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 2000, ";")) !== FALSE) {
		
		if ($row > 0) {
			// Field 60 is the country where the event takes place but
			// it seems that everyone misuses it and fill only field 5
			if ($data[5] == "France") {
				$currentPlace = new Place();

				$currentPlace->title = $data[38];
				$currentPlace->origin = $origin;
				$currentPlace->licence = $licence;

				$currentPlace->address["street"] = $data[6] . " " . $data[7] . " " . $data[8] . " " . $data[9];
				$currentPlace->address["zipcode"] = $data[14];
				$currentPlace->address["city"] = $data[15];
				$currentPlace->address["country"] = $data[5];

				$resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($currentPlace->title .' ' . $currentPlace->address["street"] . ' ' . $currentPlace->address["zipcode"] . ' ' . $currentPlace->address["city"] . ' ' . $currentPlace->address["country"]))));

				print "<pre>";
				var_dump($resGMap);
				print "</pre>";
				break;
			}
		}
		
		$row++;
    }
    fclose($handle);
}