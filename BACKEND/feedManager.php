﻿<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$feed = $db->feed;


$records = array();


// CULTURE

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000042"),
	"name"=>"telerama",
	"yakCatNameArray" => array('Actualités','Culture','Agenda'),
	"persistDays" => 10,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);		


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000043"),
	"name"=>"concertandco",
	"yakCatNameArray" => array('Actualités','Culture','Musique','Concert','Agenda'),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);		


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000044"),
	"name"=>"expo-a-paris",
	"yakCatNameArray" => array('Actualités','Culture','Exposition','Agenda'),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);		

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000045"),
	"name"=>"paris-bouge",
	"yakCatNameArray" => array('Actualités','Culture','Agenda'),
	"persistDays" => 180,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);		


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000046"),
	"name"=>"sortir-a-paris",
	"yakCatNameArray" => array('Actualités','Culture','Agenda'),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);		


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000047"),
	"name"=>"figaro-culture",
	"yakCatNameArray" => array('Actualités','Culture','Agenda'),
	"persistDays" => 7,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);		



$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000048"),
	"name"=>"exponaute",
	"yakCatNameArray" => array('Actualités','Culture','Exposition','Agenda'),
	"persistDays" => 14,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);				


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000049"),
	"name"=>"agenda-culturel-75",
	"yakCatNameArray" => array('Actualités','Culture','Agenda'),
	"persistDays" => 14,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 2,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);	


// INFO ACTU

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004a"),
	"name"=>"france3-faitsdivers",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);	


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004b"),
	"name"=>"rtbf_namur",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('507e814dfa9a95e00c000000'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);	

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004c"),
	"name"=>"sudinfo_namur",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('507e814dfa9a95e00c000000'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);	


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004d"),
	"name"=>"rtbf_bruxelles",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('507e9ce11d22b3944e00005a'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);	

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004e"),
	"name"=>"zone2",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('507eaca21d22b3954e0000e0'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);	

$records[] = array(
	"_id" => new MongoId("509b6150fa9a95a40b000000"),
	"name"=>"parisien75",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 1,
	"defaultPrintFlag" => 0,// if not geolocalized, we localize at the default location but we don't print on the map ( only in the text feed )
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b00004f"),
	"name"=>"parisien77",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('507eaca21d22b3954e0000e0'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);	


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000050"),
	"name"=>"parisien78",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('50813b26fa9a950c14000004'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000051"),
	"name"=>"parisien91",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('50813b26fa9a950c14000003'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);			

$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000052"),
	"name"=>"parisien92",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000019'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);			


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000053"),
	"name"=>"parisien93",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000018'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);			


$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000054"),
	"name"=>"parisien94",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000017'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);			

$records[] = array(
	"_id" => new MongoId("509b6178fa9a95a40b000002"),
	"name"=>"parisien95",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 3,
	"defaultPlaceId" => new MongoId('5087def6fa9a951c0d000016'),
	"type" => 1,
	"defaultPrintFlag" => 1,// if not geolocalized, we localize at the default location and we print on the map
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 5,
);

// IMMOBILIER
$records[] = array(
	"_id" => new MongoId("509b6178fa9a95a40b000003"),
	"name"=>"PARIS_ATT_VENTE",
	"yakCatNameArray" => array('Immobilier'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 1,
	"defaultPrintFlag" => 2,// if not geolocalized, we ignore the news 
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 50,
);
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000055"),
	"name"=>"century_75014",
	"yakCatNameArray" => array('Immobilier'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 1,
	"defaultPrintFlag" => 0,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 50,
);
$records[] = array(
	"_id" => new MongoId("509bb30efa9a95c40b000056"),
	"name"=>"rebe100x",
	"yakCatNameArray" => array('Actualités'),
	"persistDays" => 5,
	"defaultPlaceId" => new MongoId('50517fe1fa9a95040b000007'),
	"type" => 1,
	"defaultPrintFlag" => 1,
	"creationDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"daysBack" => 50,
);					
				
$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $feed->findOne(array('name'=>$record['name']));
	if(empty($res)){
		$row1++;
		$feed->save($record);
		echo $record['name']. ' : ' .$record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$feed->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
                    

					
$feed->ensureIndex(array("name"=>1,"login"=>1,'status'=>1));
?>