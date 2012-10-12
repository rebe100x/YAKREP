<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse "Etablissements culturels de Montpellier"
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$filenameInput = "./input/museeFrance_small.csv";
$origin = "http://www.data.gouv.fr/donnees/view/Liste-des-Mus%C3%A9es-de-France-30382165";
$fileTitle = "Musées de France";
$licence = "licence ouverte";
$debug = 0;
			
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;
$results = array('row'=>0,'rejected'=>0,'parse'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,"record"=>array());

if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        
		if($row > 0){
			foreach ($data as $key => &$value) {
				$value = utf8_encode($value);
			}
			
			
			$currentPlace = new Place();
			
			$currentPlace->title = $data[4];			
			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle;
			$currentPlace->licence = $licence;
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
				$currentPlace->contact->closing = $data[9];
				$currentPlace->contact->opening = $data[10];
				if (!empty($data[11]))
					$currentPlace->contact->sopening = "Nocturnes : ". $data[11];
			}

			$currentPlace->setTagIndoor();

			// YakCat
			$cat = array("CULTURE", "GEOLOCALISATION", "GEOLOCALISATION#YAKDICO", "CULTURE#MUSEE");
			$currentPlace->setYakCat($cat);
			
			
			
			if ($data[1] == "PARIS")
					$zoneName = "PARIS";
			elseif ($data[1] == "HERAULT")
				$zoneName = "MONTPELLIER";	
			else{
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}	
			
			$currentPlace->setZone($zoneName);
			print "<b>$currentPlace->title</b><br> ";

			preg_replace("/B.P./i", "", $data[5]);	
			
			$locationQuery =  $data[5] . ', ' . $data[6] . ', ' . $data[7] . ', France';
				
			//echo $locationQuery;
			$res = $currentPlace->saveToMongoDB($locationQuery, $debug,$updateFlag);
			
			
			if(!empty($res['error'])){
				echo $res['error'];
				echo '<br><b>BATCH FAILLED</b><br>';
				exit;
			}
			
			foreach ($res as $k=>$v) {
				if(isset($v))
					$results[$k]+=$v;
			}
			
			
			
			$results['parse'] ++;	
		
		}
		$row++;
		$results['row'] ++;	
		

    }

    fclose($handle);

    $currentPlace->prettyLog($results);
    
}

