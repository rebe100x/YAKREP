<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/library.php");
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$client = $db->client;

$res = $client->ensureIndex(array("status"=>1));




$records = array();

$records[] = array(
	"_id" => new MongoId("50a0e2c4fa9a95240b000001"),
	"name"=>"Yakwala Mobile",
	"secret"=>"5645a25f963bd0ac846b17eb517cd638754f1a7b",
	"link" => "dev.backend.yakwala.com/",
	"status" => 1,
);


$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $client->findOne(array('name'=>$record['name']));
	if(empty($res)){
		$row1++;
		$client->save($record);
		echo $record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$client->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
 

?>