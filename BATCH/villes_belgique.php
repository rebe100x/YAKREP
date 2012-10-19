<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse ville de Belgique
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$filenameInput = "./input/villes-belgique_small.csv";
$origin = "Wikipedia Yakwala";
$fileTitle = "Villes de Belgique";
$licence = "Yakwala";
$debug = 1;
			
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;


$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
       
	   if($row  > 0){
		
			foreach ($data as $key => &$value) {
				$value = trim($value);
			}
			
			$currentPlace = new Place();

			$currentPlace->title = $data[1];

			
			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle;
			$currentPlace->licence = $licence;
			$currentPlace->content = "Superficie ".$data[5]."km² <br>Population (2008):".$data[6]."<br>Densité hab./km²".$data[7]."<br>Sections:".$data[8];
			$currentPlace->freeTag = array($data[2]);
			// YakCat
			$cat = array("GEOLOCALISATION#VILLE", "GEOLOCALISATION", "GEOLOCALISATION#YAKDICO");
			$currentPlace->setYakCat($cat);
			
			if (trim($data[0]) == "Région de Bruxelles")
				$zone = 4;
			elseif (trim($data[0]) == "Région wallonne")
				$zone = 5;
			elseif (trim($data[0]) == "Région flamande")
				$zone = 6;
			else{
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}
			
			$currentPlace->zone = $zone;
			print "<br><b>$currentPlace->title</b>";

			$locationQuery = $query = $data[1]. ', Belgique';
				
			
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

