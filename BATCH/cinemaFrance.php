<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Cinéma de France"
 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$filenameInput = "./input/cinemaFrance.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Liste-des-%C3%A9tablissements-cin%C3%A9matographiques-en-2010-avec-leur-adresse-30382098";
$licence = "licence ouverte";
$debug = 0;
			
$row = 0;
$insert = 0;
$update = 0;
$locError = 0;
$doublon = 0;

$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        
		if($row > 0){
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			$currentPlace = new Place();

			var_dump($data);
			
			$currentPlace->title = $data[3];

			$currentPlace->origin = $origin;
			$currentPlace->licence = $licence;
			$currentPlace->address["street"] = $data[4]. " " . $data[5];
			$currentPlace->address["zipcode"] = $data[7];
			$currentPlace->address["city"] = $data[6];
			$currentPlace->address["country"] = "France";

			$currentPlace->setTagIndoor();

			// YakCat
			$cat = array("CULTURE", "GEOLOCALISATION", "GEOLOCALISATION#YAKDICO", "CULTURE#CINEMA");
			$currentPlace->setYakCat($cat);
			
			if (substr($data[7], 0, 2 == "75"))
				$currentPlace->setZoneParis();
			elseif (substr($data[7], 0, 2 == "34"))
				$currentPlace->setZoneMontpellier();
			else
				$currentPlace->setZoneOther();
			
			print "<b>$currentPlace->title</b> : ";

			$locationQuery = $query = $currentPlace->address["street"] . ' ' . $currentPlace->address["zipcode"] . ' ' . $currentPlace->address["city"] . ', ' . $currentPlace->address["country"];
				
			//echo $locationQuery;
			switch ($currentPlace->saveToMongoDB($locationQuery, $debug, true)) {
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

		var_dump($currentPlace);
		}
		
		$row++;
		$i++;

    }

    fclose($handle);

    print "<br>________________________________________________<br>
    		Cinémas de France: done <br>";
    print "Rows : " . ($row-1) . "<br>";
    print "Call to gmap : " . $insert . "<br>";
    print "Location error (call gmap) : " . $locError . "<br>";
    print "Insertions : " . $insert . "<br>";
    print "Updates : " . $update . "<br>";
    print "Doublons : " . $doublon . "<br>";
    
}

