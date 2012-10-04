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
	
$records[] = array(
	"title"=>"Etablissements publics",
	"content" =>"Ce jeu de données détient la liste de la majeure partie des établissements recevants du public dans la Ville de Montpellier. Par exemple, on y retrouve les hôpitaux, les maisons pour tous, les bibliothèques, etc. Cette données a été mise à jour en collaboration avec l’association Mandarine et la communauté OpenStreetMap.",
	"origin" => "http://opendata.montpelliernumerique.fr/Etablissements-publics",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("établissement" , "public" , "montpellier"),

	);	
	
$records[] = array(
	"title"=>"Trafic annuel entrant par station",
	"content" =>"Trafic annuel des stations du réseau ferré parisien, en terme de nombre de voyageurs entrants. Pour chaque station, les informations suivantes sont communiquées: - le trafic annuel 2011 des entrants directs uniquement - le classement par réseau (Métro et RER) de chaque gare et station - les lignes de correspondances RATP Métro et RER - La ville de rattachement et pour Paris, l'arrondissement (pour les stations à cheval sur plusieurs villes / arrondissements, c'est l'adresse postale de la station qui est retenue).",
	"origin" => "http://www.data.gouv.fr/donnees/view/Trafic-annuel-entrant-par-station-564116",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("trafic" , "metro" , "rer" , "transport" , "Paris", "Ile-de-France", "RATP"),

	);	





$row = 0;	
foreach($records as $record){
	$res = $filesource->findOne(array('title'=>$record['title']));
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