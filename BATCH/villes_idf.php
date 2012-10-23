<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse ville d'ILE DE FRANCE
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$filenameInput = "./input/villes-idf_small.csv";
$origin = "Wikipedia Yakwala";
$fileTitle = "Villes d'Ile-de-France";
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

			$currentPlace->title = $data[3];

			
			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle;
			$currentPlace->licence = $licence;
			$content = (!empty($data[5]))?"Superficie ".$data[4]."km² <br>":"";
			$content .= (!empty($data[5]))?"Population :".$data[5]."<br>":"";
			$content .= (!empty($data[6]))?"Densité : ".$data[6]."hab./km²<br>":"";
			$currentPlace->content = $content;
			
			// YakCat
			$cat = array("GEOLOCALISATION#VILLE", "GEOLOCALISATION");
			$currentPlace->setYakCat($cat);
			
			if (trim($data[0]) == "77")
				$zone = 7;
			elseif (trim($data[0]) == "78")
				$zone = 8;
			elseif (trim($data[0]) == "91")
				$zone = 9;
			elseif (trim($data[0]) == "92")
				$zone = 10;
			elseif (trim($data[0]) == "93")
				$zone = 11;
			elseif (trim($data[0]) == "94")
				$zone = 12;
			elseif (trim($data[0]) == "95")
				$zone = 13;
			else{
				$results['rejected'] ++;	
				$results['row'] ++;	
				continue;
			}
			
			$currentPlace->zone = $zone;
			print "<br><b>$currentPlace->title</b>";

			$locationQuery = $query = $data[3].' '. $data[2].', France';
				
			
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

