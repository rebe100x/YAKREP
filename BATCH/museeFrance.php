<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Etablissements culturels de Montpellier"
 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$filenameInput = "./input/museeFrance_small.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Liste-des-Mus%C3%A9es-de-France-30382165";
$fileTitle = "Liste des musées de France";
$licence = "licence ouverte";
$debug = 0;
			
$row = 0;
$insert = 0;
$update = 0;
$locError = 0;
$doublon = 0;

$etsCulturels = array('');
$fieldsProcessed = array('');
$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        
		if($row > 0){
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			$currentPlace = new Place();

			$currentPlace->title = $data[4];

			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle;
			$currentPlace->licence = $licence;
			$currentPlace->address["street"] = $data[5];
			$currentPlace->address["zipcode"] = $data[6];
			$currentPlace->address["city"] = $data[7];
			$currentPlace->address["country"] = "France";
			$currentPlace->setWeb($data[8]);
			
			//Gestion des horaires et fermetures des musées
			if ($data[2] != "NON" && empty($data[9]) && empty($data[10]))
			{
				if ($data[2] == "OUI")
					$currentPlace->contact["closing"] = "Fermé";
				else
					$currentPlace->contact["closing"] = $data[2];
			}
			else
			{
				$currentPlace->contact["closing"] = $data[9];
				$currentPlace->contact["opening"] = $data[10];
				if (!empty($data[11]))
					$currentPlace->contact["special opening"] = "Nocturnes : ". $data[11];
			}

			$currentPlace->setTagIndoor();

			// YakCat
			$cat = array("CULTURE", "GEOLOCALISATION", "GEOLOCALISATION#YAKDICO", "CULTURE#MUSEE");
			$currentPlace->setYakCat($cat);
			
			if ($data[1] == "PARIS")
				$currentPlace->setZoneParis();
			elseif ($data[1] == "HERAULT")
				$currentPlace->setZoneMontpellier();
			else
				$currentPlace->setZoneOther();
			
			print "<b>$currentPlace->title</b> : ";

			$locationQuery = $query = $currentPlace->title .' ' . $currentPlace->address["street"] . ' ' . $currentPlace->address["zipcode"] . ' ' . $currentPlace->address["city"] . ', ' . $currentPlace->address["country"];
				
			//echo $locationQuery;
			switch ($currentPlace->saveToMongoDB($locationQuery, $debug, true)) {
					case '1':
						$insert++;
						$locError++;
						break;
					case '2':
						print "updated <br>";
						$update++;
						break;
					case '3':
						print "doublon <br>";
						$doublon++;
						break;
					default :
						print "insert (1 call to gmap)<br>";
						$insert++;
						break;
				}
				//var_dump($currentPlace);
			}

		$row++;
		$i++;

    }

    fclose($handle);

    print "<br>________________________________________________<br>
    		museeFrance : done <br>";
    print "Rows : " . ($row-1) . "<br>";
    print "Call to gmap : " . $insert . "<br>";
    print "Location error (call gmap) : " . $locError . "<br>";
    print "Insertions : " . $insert . "<br>";
    print "Updates : " . $update . "<br>";
    print "Doublons : " . $doublon . "<br>";
    
}

