<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
/* Read a xml file : the ontology xml file for EXALEAD
 * Introduce in mongodb the place ( collection PLACE )
 * */
ini_set ('max_execution_time', 0);
set_time_limit(0);
require_once("../LIB/library.php");
require_once("../LIB/conf.php");
ini_set('display_errors',1);

 $categorie = new Cat("Erruer","Erreur A", "Erreur B");
 
 echo $parentID = $categorie ->getID_fromPathN('STATISTIQUES');
 



	//$log = "<br><br>=================<br><br>Data inserted in DB: 
//	<br>Inserted : ".$countInsert."
//	<br>Calls to GMap : ".$countGMap;
//echo $log;
