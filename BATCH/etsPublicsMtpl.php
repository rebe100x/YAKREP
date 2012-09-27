<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Etablissements publics de Montpellier"
 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EtabliPublic_2011.csv";
$origin = "http://opendata.montpelliernumerique.fr/Etablissements-publics";
$license = "licence ouverte";

$row = 0;
$insert = 0;
$update = 0;
$locError = 0;
$doublon = 0;
$callGmap = 0;
$etsPublics = array('');
$fieldsProcessed = array('');
$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) 
{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {
        $num = count($data);
        
		if($row > 1)
		{
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			$currentObject = new Place;

			$currentObject->setTitle($data[5]);
			$currentObject->origin = $origin;
			$currentObject->license = $license;
			$currentObject->address["street"] = $data[10];
			$currentObject->address["zipcode"] = $data[11];
			$currentObject->address["city"] = $data[12];
			$currentObject->address["country"] = "France";
			$currentObject->setTel($data[16], "tel");
			$currentObject->setMail($data[18]);
			$currentObject->setZoneMontpellier();
			$currentObject->location["lat"] = $data[6];
			$currentObject->location["lng"] = $data[7];

			// YakCat

			if (stristr($data[9], "#LOISIR#SPORT#PATINOIRE")) 
			{
				$currentObject->setCatPatinoire();
			}
			elseif (stristr($data[9], "#LOISIR#CULTURE#PLANETARIUM")) 
			{
				$currentObject->setCatPlanetarium();
				$currentObject->yakTag["enfants"] = 1;
			}
			elseif (stristr($data[9], "#EDUCATION#CRECHE")) 
			{
				$currentObject->setCatCreche();
				$currentObject->yakTag["enfants"] = 1;
			}
			elseif (stristr($data[9], "#EDUCATION#ECOLE#PRIMAIRE")) 
			{
				$currentObject->setCatPrimaire();
				$currentObject->yakTag["enfants"] = 1;
			}
			else if (stristr($data[9], "#EDUCATION#MAISONDEQUARTIER")) 
			{
				$currentObject->setCatMaisonDeQuartier();
			}
			else if (stristr($data[9], "#CULTURE#THEATRE")) 
			{
				$currentObject->setCatTheatre();
			}
			else if (stristr($data[9], "#CULTURE#EXPOSITION"))
			{
				$currentObject->setCatExpo();
			}
			else if (stristr($data[9], "#CULTURE#MUSEE"))
			{
				$currentObject->setCatMusee();
			}
			else if (stristr($data[9], "#CULTURE#CONCERT"))
			{
				$currentObject->setCatConcert();
			}
			else if (stristr($data[9], "#CULTURE#MEDIATHEQUE"))
			{
				$currentObject->setCatMediatheque();
			}
			else if (stristr($data[9], "#SPORT#VOLLEY"))
			{
				$currentObject->setCatVolley();
			}
			else if (stristr($data[9], "#SPORT#PISCINE"))
			{
				$currentObject->setCatPiscine();
			}
			else if (stristr($data[9], "#SPORT#GYMNASE"))
			{
				$currentObject->setCatGymnase();
			}
			else if (stristr($data[9], "#SPORT#FOOTBALL"))
			{
				$currentObject->setCatFootball();
			}
			else if (stristr($data[9], "#SPORT#RUBGBY"))
			{
				$currentObject->setCatRugby();
			}
			else if (stristr($data[9], "#SPORT#TENNIS"))
			{
				$currentObject->setCatTennis();
			}
			else if (stristr($data[9], "#SPORT"))
			{
				$currentObject->setCatSport();
			}
			else if (stristr($data[9], "#SPORT#PETANQUE"))
			{
				$currentObject->setCatPetanque();
			}
			
			$locationQuery = $currentObject->title . ' ' . $currentObject->address['street'] . ' ' . $currentObject->address['zipcode'] . ' ' . $currentObject->address['city'] . ', ' . $currentObject->address['country'];
		
			$debug = 0;
			switch ($currentObject->saveToMongoDB($locationQuery, $debug)) 
			{
				case '1':
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
				default:
					print "insert (1 call to gmap)<br>";
					$insert++;
					break;
			}
			$i++;
		}
		$row++;
    }

	print "Data parsed: " . $i . "<br>";
   
    fclose($handle);
    
    print "<br>________________________________________________<br>
    		etsPublicsMtpl : done <br>";
    print "Rows : " . ($row-1) . "<br>";
    print "Call to gmap : " . ($insert+$locError) . "<br>";
    print "Location error (call gmap) : " . $locError . "<br>";
    print "Insertions : " . $insert . "<br>";
    print "Updates : " . $update . "<br>";
    print "Doublons : " . $doublon . "<br>";
}

