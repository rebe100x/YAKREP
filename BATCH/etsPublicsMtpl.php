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
/* batch to parse "Etablissements publics de Montpellier"
 * */

include_once "../LIB/place.php";
ini_set('display_errors',1);
$filenameInput = "./input/VilleMTP_MTP_EtabliPublic_2011.csv";
$origin = "http://opendata.montpelliernumerique.fr/Etablissements-publics";
$fileTitle = "Etablissements publics";
$licence = "licence ouverte";

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
	print_r("<h3>Input : " . $filenameInput . "</h3>\n");
	
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {
        $num = count($data);
        
		if($row > 1)
		{
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			$place = new Place;

			$place->title = $data[5];
			$place->origin = $origin;
			$place->filesourceTitle = $fileTitle;
			$place->licence = $licence;
			$place->address["street"] = $data[10];
			$place->address["zipcode"] = $data[11];
			$place->address["city"] = $data[12];
			$place->address["country"] = "France";
			$place->setTel($data[16], "tel");
			$place->setMail($data[18]);
			$place->setZoneMontpellier();
			$place->location["lat"] = $data[6];
			$place->location["lng"] = $data[7];
		
			$cat = array("GEOLOCALISATION#YAKDICO");
			$place->setYakCat($cat);

			// YakCat
			$place->setYakCat(array($data[9]));

			/*if (stristr($data[9], "#LOISIR#SPORT#PATINOIRE")) 
			{
				$place->setCatSport();
				$place->setCatPatinoire();
			}
			elseif (stristr($data[9], "#LOISIR#CULTURE#PLANETARIUM")) 
			{
				$place->setCatCulture();
				$place->setCatPlanetarium();
				$place->setTagChildren();
			}
			elseif (stristr($data[9], "#EDUCATION#CRECHE")) 
			{
				$place->setCatEducation();
				$place->setCatCreche();
				$place->setTagChildren();
			}
			elseif (stristr($data[9], "#EDUCATION#ECOLE#PRIMAIRE")) 
			{
				$place->setCatEducation();
				$place->setCatPrimaire();
				$place->setTagChildren();
			}
			else if (stristr($data[9], "#EDUCATION#MAISONDEQUARTIER")) 
			{
				$place->setCatEducation();
				$place->setCatMaisonDeQuartier();
			}
			else if (stristr($data[9], "#CULTURE#THEATRE")) 
			{
				$place->setCatCulture();
				$place->setCatTheatre();
			}
			else if (stristr($data[9], "#CULTURE#EXPOSITION"))
			{
				$place->setCatCulture();
				$place->setCatExpo();
			}
			else if (stristr($data[9], "#CULTURE#MUSEE"))
			{
				$place->setCatCulture();
				$place->setCatMusee();
			}
			else if (stristr($data[9], "#CULTURE#CONCERT"))
			{
				$place->setCatCulture();
				$place->setCatConcert();
			}
			else if (stristr($data[9], "#CULTURE#MEDIATHEQUE"))
			{
				$place->setCatCulture();
				$place->setCatMediatheque();
			}
			else if (stristr($data[9], "#SPORT#VOLLEY"))
			{
				$place->setCatSport();
				$place->setCatVolley();
			}
			else if (stristr($data[9], "#SPORT#PISCINE"))
			{	
				$place->setCatSport();
				$place->setCatPiscine();
			}
			else if (stristr($data[9], "#SPORT#GYMNASE"))
			{
				$place->setCatSport();
				$place->setCatGymnase();
			}
			else if (stristr($data[9], "#SPORT#FOOTBALL"))
			{
				$place->setCatSport();
				$place->setCatFootball();
			}
			else if (stristr($data[9], "#SPORT#RUBGBY"))
			{
				$place->setCatSport();
				$place->setCatRugby();
			}
			else if (stristr($data[9], "#SPORT#TENNIS"))
			{
				$place->setCatSport();
				$place->setCatTennis();
			}
			else if (stristr($data[9], "#SPORT"))
			{
				$place->setCatSport();
			}
			else if (stristr($data[9], "#SPORT#PETANQUE"))
			{
				$place->setCatSport();
				$place->setCatPetanque();
			}*/
			
			$locationQuery = $place->title . ' ' . $place->address['street'] . ' ' . $place->address['zipcode'] . ' ' . $place->address['city'] . ', ' . $place->address['country'];
		
			$debug = 0;
			switch ($place->saveToMongoDB($locationQuery, $debug, false)) 
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
					$insert++;
					print_r($place->prettyPrint() . "\n<hr/>\n");
					break;
			}
			$i++;
		}
		$row++;
    }
	print "<br/> doublon : $doublon - insert : $insert - update : $update - error loc : $locError <br>";
    fclose($handle);
    print_r("etsPublicsMtpl done.\n");
}

?>
</body>
</html>