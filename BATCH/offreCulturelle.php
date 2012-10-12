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
$fileTitle = "Offres culturelles";
$licence = "Licence ouverte";
$debug = 0;
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());


if (($handle = fopen($filenameInput, "r")) !== FALSE) {

	print_r("<h3>Input : " . $filenameInput . "</h3>\n");

    while (($data = fgetcsv($handle, 2000, ";")) !== FALSE) {
		
		if ($row > 0) {
			

			// Field 60 is the country where the event takes place but
			// it seems that everyone misuses it and fill only field 5
		
			$currentPlace = new Place();

			if ($data[4] != "France") {
				echo "<br>".$data[0]." ".$data[4]." =><b>Not in France</b>";
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}
			
			// if no valid address, we skip
			$street = trim($data[6] . " " . $data[7] . " " . $data[8] . " " . $data[9]);
			if(strlen($street) == 0) {
				echo "<br>".$data[0]." <b>no address</b> ".$street;
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}
			
			// If zone isn't Paris or Montpellier, skip
			if (preg_match("/paris/i", $data[14])) {
				$currentPlace->setZone("PARIS");
			}
			else if (preg_match("/montpellier/i", $data[14])) {
				$currentPlace->setZone("MONTPELLIER");
			}
			else {
				echo "<br>".$data[0]." <b>not in your zone</b> ";
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}
			
			/* title logic :
				- we take field 38 if not empty
				- if 38 is empty , we take field 0 but first par goes in title and second goes in content
			*/
			$title = "";
			$content = "";
			
			if(!empty($data[38]))
				$title = $data[38];
			else
				$title = $data[0];
				
			$tmp = explode(' - ',$data[0]);
			if(sizeof($tmp)>1){
				$title = $tmp[0];
				for($i=1;$i<sizeof($tmp);$i++)
					$content .=  $tmp[$i];
			}
			
		
			
			$currentPlace->title  = $title;
			$currentPlace->content  = $content;
			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle;
			$currentPlace->licence = $licence;
			
			$queryGMAP = $street;
			if(!empty($data[13]))
				$queryGMAP .= ', '.$data[13];
			if(!empty($data[14]))
				$queryGMAP .= ', '.$data[14];
			$queryGMAP .= ", France";

			

			// Useful for regex matches
			$softTitle = suppr_accents($currentPlace->title);

			$cat = array("CULTURE", "GEOLOCALISATION", "GEOLOCALISATION#YAKDICO");
		
			// Finding yakcats
			if ($data[2] == "Musées" || preg_match("/musee/i", $softTitle) || preg_match("/musee/i", suppr_accents($data[20])) ) {
				$cat[] = "CULTURE#MUSEE";
			}
			else if (preg_match("/aquarium/i", $softTitle)) {
				$cat[] = "CULTURE#AQUARIUM";
			}
			else if (preg_match("/opera/i", $softTitle)) {
				$cat[] = "CULTURE#MUSIQUE";
				$currentPlace->yakTag[] = "Classique";
			}
			else if (preg_match("/jardin/i", $softTitle)) {
				$cat[] = "LOISIR";
				$cat[] = "LOISIR#ESPACEVERT";
				$cat[] = "CULTURE#EXPOSITION";
				 
				$currentPlace->yakTag[] = "Plein air";
			}
			else if (preg_match("/eglise/i", $softTitle) || preg_match("/abbaye/i", $softTitle)) {
				$cat[] = "RELIGION";
			}
			else if ($data[2] == "Organisme de Spectacle") {
				$cat[] = "CULTURE#THEATRE";
			}
			else if ($data[2] == "Organisme d'Arts plastiques") {
				$cat[] = "CULTURE#EXPOSITION";
			}
			else if (preg_match("/archives/i", $data[20])) {
				$cat[] = "EDUCATION#ARCHIVE";
			}
			
			$currentPlace->setYakCat($cat);
			
			

			echo '<br><b>OK, lets insert:</b>'.$title;	
			$res = $currentPlace->saveToMongoDB($queryGMAP, $debug,$updateFlag);
			foreach ($res as $k=>$v) {
				if(isset($v))
					$results[$k]+=$v;
			}
			
			$results['parse'] ++;
			
		}
		$results['row'] ++;	
		$row++;
    }
    
    fclose($handle);
    
}
   $currentPlace->prettyLog($results);
?>
</body>
</html>