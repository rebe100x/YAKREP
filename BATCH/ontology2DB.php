<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
/* Read a xml file : the ontology xml file for EXALEAD
 * Introduce in mongodb the place ( collection PLACE )
 * */
ini_set ('max_execution_time', 0);
set_time_limit(0);
require_once("../LIB/library.php");
ini_set('display_errors',1);
$inputFile ='./input/ontology.xml';

$ontolgyXML = simplexml_load_file($inputFile);

$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$placeColl = $db->place;
$batchlogColl = $db->batchlog;

$yakdicoYakCatId = "5056b7aafa9a95180b000000";

$countInsert = 0;
$row = 0;
$countGMap = 0;

foreach($ontolgyXML as $key0 => $value){
	foreach($value as $key => $value2){
		foreach($value2->attributes() as $attributeskey => $streetNameArray){
			$streetName = (string)$streetNameArray;
			echo "<br>".$attributeskey."=". $streetName;
			
			// CHECK IF DATA EXISTS IN DB
			$res = $placeColl->findOne(array('title'=>$streetName));
			
			if(empty($res)){
				echo "<br>Location not found in DB, we start inserting...";
				$resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($streetName.', Paris, France'))),'PHP',1);
				$geolocGMAP = $resGMap['location'];
				$addressGMAP = $resGMap['address'];
				$statusGMAP = $resGMap['status'];
				echo 'STATUS'.$statusGMAP;
				$countGMap++;
				if(!empty($geolocGMAP) && $statusGMAP == "OK"){
					$status = 1;
					echo "<br>Found location with GMap<br>";
				}
				 else{
					echo "<br>GMap failed to find location. The place is stored in status 10.<br>GMAP STATUS = ".$statusGMAP;
					 $status = 10;	
				 }
					 $countInsert++;  
					 
					 var_dump($streetName);
				$place = array(
						"title"=> $streetName,
						"content" =>"",
						"thumb" => "",
						"origin"=>"ontology yakdico",   
						"access"=> 2,
						"licence"=> "Yakwala",
						"outGoingLink" => "",
						"yakCat" => array(new MongoId($yakdicoYakCatId)), 
						"creationDate" => new MongoDate(gmmktime()),
						"lastModifDate" => new MongoDate(gmmktime()),
						"location" => array("lat"=>$geolocGMAP[0],"lng"=>$geolocGMAP[1]),
						"status" => 1,
						"user" => 0,
						"zone"=> 1,
						"address" => $addressGMAP,
					  );
					  
				$placeColl->save($place); 
				$placeColl->ensureIndex(array("location"=>"2d"));
				echo "<br> The location does not exist in db. We insert it.";
			}
			$row++;
		}
	}
}

	$log = "<br><br>=================<br><br>Data inserted in DB: 
	<br>Inserted : ".$countInsert."
	<br>Calls to GMap : ".$countGMap;
echo $log;
