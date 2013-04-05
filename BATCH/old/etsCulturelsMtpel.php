<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Etablissements culturels de Montpellier"
 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$filenameInput = "./input/ets_culturels_mtpel.csv";
$origin = "http://data.montpellier-agglo.com/?q=node/200";
$fileTitle = "Horaires des établissements culturels";
$license = "licence ouverte";

$row = 0;
$insert = 0;
$update = 0;
$locError = 0;
$doublon = 0;
$debug = 1;

$etsCulturels = array('');
$fieldsProcessed = array('');
$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        
		if($row > 1){
			foreach ($data as &$value) {
				$value = utf8_encode($value);
			}
			
			$currentPlace = new Place();

			$address = $data[2]. " " . $data[4];
			
			$currentPlace->title = $data[1];
			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle; 
			$currentPlace->license = $license;
			$currentPlace->address["street"] = $address;
			$currentPlace->address["zipcode"] = $data[5];
			$currentPlace->address["city"] = $data[6];
			$currentPlace->address["country"] = "France";
			$currentPlace->setTel($data[8], "tel");
			$currentPlace->contact["mail"] = $data[9];
			$currentPlace->setZoneMontpellier();

			$currentPlace->contact["opening"] = "L: " . $data[12] .
													", M: " . $data[13] .
													", M: " . $data[14] .
													", J: " . $data[15] .
													", V: " . $data[16] .
													", S: " . $data[17] .
													", D: " . $data[18];
			$currentPlace->setTagIndoor();

			
			// Get location with gmap
			$query = $data[1] . ", " . $data[5] . " " . $data[6];
			
			// YakCat
			$cat = array("CULTURE", "GEOLOCALISATION#YAKDICO");
			$currentPlace->setYakCat($cat);

			if (stristr($data[1], "Médiathèque")) {
				//echo "media : $data[1] <br/>";
				$currentPlace->setYakCat(array("CULTURE#MEDIATHEQUE"));
			}
			elseif (stristr($data[1], "Planétarium")) {
				//echo "Planétarium : $data[1] <br/>";
				$currentPlace->setYakCat(array('CULTURE#PLANETARIUM'));
				$currentPlace->yakTagChildren();
			}
			elseif (stristr($data[1], "Aquarium")) {
				//echo "Aquarium : $data[1] <br/>";
				$currentPlace->setYakCat(array('CULTURE#AQUARIUM'));
				$currentPlace->yakTagChildren();
			}
			else {
				//echo "autres (musée) : $data[1] <br/>";
				$currentPlace->setYakCat(array('CULTURE#MUSEE'));
			}

	    	$locationQuery = $query = $currentPlace->title .' ' . $currentPlace->address["street"] . ' ' . $currentPlace->address["zipcode"] . ' ' . $currentPlace->address["city"] . ', ' . $currentPlace->address["country"];
			
			print '<hr><b>'.$currentPlace->title.'</b><br>';
			switch ($currentPlace->saveToMongoDB($locationQuery, $debug, true, true)) {
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
		$i++;
		$row++;
    }
	print "<br>________________________________________________<br>
    		museeFrance : done <br>";
    print "Rows : " . ($row-1) . "<br>";
    print "Call to gmap : " . $insert . "<br>";
    print "Location error (call gmap) : " . $locError . "<br>";
    print "Insertions : " . $insert . "<br>";
    print "Updates : " . $update . "<br>";
    print "Doublons : " . $doublon . "<br>";

    fclose($handle);
}

