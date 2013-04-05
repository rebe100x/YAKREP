<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Cinéma de France"
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$filenameInput = "./input/cinemaFrance_small.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Liste-des-%C3%A9tablissements-cin%C3%A9matographiques-en-2010-avec-leur-adresse-30382098";
$fileTitle = "Etablissements cinématographiques";
$licence = "licence ouverte";
$debug = 0;
			
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;


$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
       
	   if($row  > 0){
		
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			$currentPlace = new Place();

			$currentPlace->title = $data[3];

			
			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle;
			$currentPlace->licence = $licence;
			
			$currentPlace->setTagIndoor();

			// YakCat
			$cat = array("CULTURE", "GEOLOCALISATION", "GEOLOCALISATION#YAKDICO", "CULTURE#CINEMA");
			$currentPlace->setYakCat($cat);
			
			if (substr(trim($data[7]), 0, 2) == "75")
				$zoneName = "PARIS";
			elseif (substr(trim($data[7]), 0, 2) == "34")
				$zoneName = "MONTPELLIER";
			else{
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}
			
			$currentPlace->setZone($zoneName);
			print "<b>$currentPlace->title</b><br>";

			$locationQuery = $query = $data[4]. " " . $data[5] . ' ' . $data[7] . ' ' . $data[6] . ', France';
				
			//echo $locationQuery;
			$res = $currentPlace->saveToMongoDB($locationQuery, $debug,$updateFlag);
			
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

   $currentPlace->prettyLog($results);
    
}

