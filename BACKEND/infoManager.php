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

$info = $db->info;

$res = $info->ensureIndex(array("location"=>"2d"));
$res = $info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1));
$res = $info->ensureIndex(array("yakType"=>1,"print"=>1,"status"=>1,"pubDate"=>-1,"user"=>1,"freeTag"=>1));




$records = array();
/*
$records[] = array(
	"_id" => new MongoId("506aa338fa9a956c0f00000d"),
	"title"=> "La tour Triangle menace le Mondial de l’auto",
	"content" => "Rififi au Mondial de l’auto. Alors que les allées du parc des Expositions de la porte de Versailles (XVe) ne désemplissent pas depuis samedi, date de l’ouverture du plus grand salon automobile du...",
	"outGoingLink" => "http://www.leparisien.fr/paris-75/paris-75005/la-tour-triangle-menace-le-mondial-de-l-auto-02-10-2012-2196557.php",
	"thumb" => "thumb/361d658337371b903a1002ca6215f2f5.jpeg",
	"origin" => "leparisien75",
	"access" => 2,
	"licence" => "reserved",
	"heat" => 80
	"yakCat" => array(new MongoId("504dbb06fa9a95680b000211"),new MongoId("504d89c5fa9a957004000000")),
	"yakType" => 1,
	"pubDate" => new MongoDate(gmmktime()),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"dateEndPrint" => new MongoDate(gmmktime()),
	"print" => 1,
	"status" => 1,
	"user" => 0,
	"zone" => 1,
	"location" => array('lat'=>48.881242,'lng'=>2.30586),
	"address" => "parc des Expositions de la porte de Versailles", 
	"placeId" => new MongoId("506a9e011d22b3457800001e"),

);


$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $info->findOne(array('title'=>$record['title']));
	if(empty($res)){
		$row1++;
		$info->save($record);
		echo $record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$info->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
   */
//$info->update(array("_id"=>new MongoId("506aa4d9fa9a956c0f00001c")),array('$set'=>array('location'=>array('lat'=>48.881242,'lng'=>2.30586))));
//$info->update(array("_id"=>new MongoId("506aa501fa9a956c0f000022")),array('$set'=>array('location'=>array('lat'=>48.881242,'lng'=>2.30586))));
 
$start = new MongoDate(strtotime("2012-10-12 00:00:00"));
$end = new MongoDate(strtotime("2012-10-16 00:00:00"));
$cursor = $info->find(array('pubDate'=> array('$gt' => $start, '$lte' => $end)))->sort(array("pubDate"=>-1));
//$cursor = $info->find()->sort(array("pubDate"=>-1));
//var_dump(iterator_to_array($cursor));
foreach ($cursor as $doc) {
    //echo $doc['pubDate']->sec;
	echo date('d-m-Y',$doc['pubDate']->sec) .'  '.	$doc['title']."<br>";
}
//$info->remove(array('pubDate'=> array('$gt' => $start, '$lte' => $end)));
//$info->remove(array('zone'=>5));
//$info->remove(array('zone'=>6));



?>