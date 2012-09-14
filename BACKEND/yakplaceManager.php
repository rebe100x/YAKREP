<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/library.php");

$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$place = $db->place;


$record = array(
	"_id"=>"50517fe1fa9a95040b000007",
	"title"=>"Paris",
	"content" =>"",
	"thumb" => "",
	"origin"=>"operator",	
	"access"=> 1,
	"licence"=> "Yakwala",
	"outGoingLink" => "",
	"yakCat" => array(new MongoId("504d89f4fa9a958808000001")),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"status" => 1,
	"user" => 0, 
	"zone"=> 1
	
);	
$place->ensureIndex(array("location"=>"2d"));
$place->save($record);
                    
echo $record['_id'];                    
                    

?>