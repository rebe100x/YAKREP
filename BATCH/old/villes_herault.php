<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />

<?php
/* batch to parse ville de l'HERAULT ( zone2 MOntpellier )
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$filenameInput = "./input/villes-herault.csv";
$origin = "Wikipedia Yakwala";
$fileTitle = "Villes de l'Hérault";
$licence = "Yakwala";
$debug = 1;
			
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;


$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

if (($handle = fopen($filenameInput, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
       
	   if($row  > 0){
		
			foreach ($data as $key => &$value) {
				$value = trim($value);
			}
			
			//usleep(rand(rand(0,2),rand(3,11))*100);
			sleep(1);
			
			$currentPlace = new Place();

			$currentPlace->title = $data[3];

			
			$currentPlace->origin = $origin;
			$currentPlace->filesourceTitle = $fileTitle;
			$currentPlace->licence = $licence;
		
			
			// YakCat
			$cat = array("GEOLOCALISATION#VILLE", "GEOLOCALISATION");
			$currentPlace->setYakCat($cat);
			$zone = 2;
			
			
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

