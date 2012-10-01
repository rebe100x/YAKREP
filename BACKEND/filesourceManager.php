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

$filesource = $db->filesource;


$records = array();

$records[] = array(
	"title"=>"Effectifs scolaires",
	"content" =>"Cette donnée renseigne les effectifs scolaires pour les écoles maternelles et primaires de la commune de Montpellier. En plus des informations classiques comme le nom et l’adresse, pour chaque établissement le fichier renseigne le nombre d’enfants maximum, le nombre de classes et enfin le nombre de classes supplémentaires planifiées.",
	"origin" => "http://opendata.montpelliernumerique.fr/Effectifs-scolaires",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("éducation" , "effectif scolaire" , "école" , "école primaire" , "école maternelle" , "enfant" , "montpellier"),

	);	




$row = 0;	
foreach($records as $record){
	$res = $filesource->findOne(array('mail'=>$record['title']));
	if(empty($res)){
		$row++;
		$filesource->save($record);
		$filesource->ensureIndex('title');
		$filesource->ensureIndex('content');
		$filesource->ensureIndex('tag');
		echo $record['_id'].'<br>';                    
	}
}
echo "<br>".$row." records added.";          
                                     

?>