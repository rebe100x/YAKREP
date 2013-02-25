<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
/* 
 * */
ini_set ('max_execution_time', 0);
set_time_limit(0);
require_once("../LIB/conf.php");
ini_set('display_errors',1);
$inputFile ='./input/ouestfrance_categories.xml';

$catXML = simplexml_load_file($inputFile);
$status = 5000;
//print_r($catXML);
/*
$conf = new conf();

$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$placeColl = $db->place;
$batchlogColl = $db->batchlog;

$yakdicoYakCatId = "5056b7aafa9a95180b000000";

$countInsert = 0;
$row = 0;
$countGMap = 0;
*/$i=0;
foreach($catXML as $key0 => $value){
	foreach($value as $key => $value2){
		
	
	//	print_r($value);
	$txt = (string) $value2;

		switch ($key){
			case "Libelle": $three= $txt;
				break;
			case "Categorie": $two=$txt;
			break;
			case "Style": $one=$txt;
				break;
		}
	}
	
//==============fill first record===============--------------------------------------

	
	//trim space and comma
	$pathN=yakcatPathN($one);
	
	
	$categorie = new Cat($one,$one,$pathN,1,'',$status);
$succes =	$categorie -> saveToMongo($level = 1);



	
	
//================fill record two======================================
	$title2=$two;
	$path2=$one.", ".$two;
  $pathN2 = $pathN."#".yakcatPathN($title2);
  echo "<br>".$pathN;
  $categorie -> setAncestors($one,$pathN);
  $categorie -> setParent($one,$pathN);
  $categorie -> title = $title2;
  $categorie -> path = $path2;
  $categorie -> pathN = $pathN2;
      
  $categorie -> level = 2;
  $succes =	$categorie -> saveToMongo($level = 2);
    	//exit;
    
    //insert db results for ID--here---------------------------
     
    //---------------------------------------------------------

	
//==============fill third record=================================
	$title3=$three;
	$path3=$path2.", ".$title3;
  $pathN3 =  $pathN2."#".yakcatPathN($title3);
  
  $categorie -> title = $title3;
  $categorie -> path = $path3;
  $categorie -> pathN = $pathN3;
  $categorie -> level = 3;
  $categorie -> setAncestors2($path2,$pathN,$pathN2);
  $categorie -> setParent($title2,$pathN2);
  
 
  $categorie -> level = 3;
  $succes =	$categorie -> saveToMongo($level = 3);
  // print_r($categorie);

     //insert db results for ID--here---------------------------
     
    //---------------------------------------------------------
     
    

	
	
	
	$i++;
	
	
	
	
	//print_r($key);
	/*	foreach($value2 as $attributeskey => $streetNameArray){
			$streetName = (string)$catXML->$key0->$key->$streetNameArray;
		//	print_r($key);
			
			
			// CHECK IF DATA EXISTS IN DB     
		/*	$res = $placeColl->findOne(array('title'=>$streetName));
			
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
			$row++;*/
		
}

	//$log = "<br><br>=================<br><br>Data inserted in DB: 
//	<br>Inserted : ".$countInsert."
//	<br>Calls to GMap : ".$countGMap;
//echo $log;
