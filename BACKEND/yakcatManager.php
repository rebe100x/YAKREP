<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/library.php");

$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$yakcat = $db->yakcat;

/*
$record = array(
	"title"=> "Théâtre",
	"path" => "Culture, Théâtre",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"tag" => array("théâtre"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);*/

/*
$record = array(
	"title"=> "Cinéma",
	"path" => "Culture, Cinéma",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"tag" => array("ciné","cinoche"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);
*/
/*
$record = array(
	"title"=> "Exposition",
	"path" => "Culture, Exposition",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"tag" => array("Expositions"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);*/


$record = array(
	"title"=> "Musée",
	"path" => "Culture, Musée",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"tag" => array("musées"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$yakcat->save($record);
var_dump($record);
                    
                                     

?>