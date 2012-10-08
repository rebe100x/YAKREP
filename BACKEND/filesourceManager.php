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
	"title"=>"Parcs et jardins de Paris",
	"content" =>"",
	"origin" => "operator",
	"licence" => "Yakwala",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("parcs","espaces verts","jardins de Paris"),
	"zone" => array(1),
	);
	
$records[] = array(
	"title"=>"Effectifs scolaires",
	"content" =>"Cette donnée renseigne les effectifs scolaires pour les écoles maternelles et primaires de la commune de Montpellier. En plus des informations classiques comme le nom et l’adresse, pour chaque établissement le fichier renseigne le nombre d’enfants maximum, le nombre de classes et enfin le nombre de classes supplémentaires planifiées.",
	"origin" => "http://opendata.montpelliernumerique.fr/Effectifs-scolaires",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("éducation" , "effectif scolaire" , "école" , "école primaire" , "école maternelle" , "enfant" , "montpellier"),
	"zone" => array(2),

	);
	
$records[] = array(
	"title"=>"Etablissements publics",
	"content" =>"Ce jeu de données détient la liste de la majeure partie des établissements recevants du public dans la Ville de Montpellier. Par exemple, on y retrouve les hôpitaux, les maisons pour tous, les bibliothèques, etc. Cette données a été mise à jour en collaboration avec l’association Mandarine et la communauté OpenStreetMap.",
	"origin" => "http://opendata.montpelliernumerique.fr/Etablissements-publics",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("établissement" , "public" , "montpellier"),
	"zone" => array(2),
	);	
	
$records[] = array(
	"title"=>"Trafic annuel entrant par station",
	"content" =>"Trafic annuel des stations du réseau ferré parisien, en terme de nombre de voyageurs entrants. Pour chaque station, les informations suivantes sont communiquées: - le trafic annuel 2011 des entrants directs uniquement - le classement par réseau (Métro et RER) de chaque gare et station - les lignes de correspondances RATP Métro et RER - La ville de rattachement et pour Paris, l'arrondissement (pour les stations à cheval sur plusieurs villes / arrondissements, c'est l'adresse postale de la station qui est retenue).",
	"origin" => "http://www.data.gouv.fr/donnees/view/Trafic-annuel-entrant-par-station-564116",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("trafic" , "metro" , "rer" , "transport" , "Paris", "Ile-de-France", "RATP"),
	"zone" => array(1),
	);	
	
$records[] = array(
	"title"=>"Etablissements cinématographiques",
	"content" =>"Liste des établissements cinématographiques en 2010 avec leur adresse. Données : région / ville /numéro d'autorisation / enseigne / adresse / adresse complémentaire / commune / code postal",
	"origin" => "http://www.data.gouv.fr/donnees/view/Liste-des-établissements-cinématographiques-en-2010-avec-leur-adresse-30382098",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("cinéma" , "ciné" , "cinoche"),
	"zone" => array(1,2,3,4),
	);	
	
$records[] = array(
	"title"=>"Musées de France",
	"content" =>"Liste des Musées de France.Libellés des colonnes : Régions ; Départements ; Fermé (oui si le musée est fermé) ; Annexe (si annexe au musée) ; Nom du musée ; Coordonnées postale (Adresse, Ville, CP) ; Siteweb ; périodes d'ouverture et de fermeture annuelle ; Jours nocturnes",
	"origin" => "http://www.data.gouv.fr/donnees/view/Liste-des-Musées-de-France-30382165",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("musée" , "musées" , "france"),
	"zone" => array(1,2,3,4),
	);	
	
$records[] = array(
	"title"=>"Offres culturelles",
	"content" =>"Liste des évènements culturels et des organismes producteurs d'événements en France et des grandes manifestations en France et à l'étranger. Liste des champs : Identifiant de l’organisme ; Nom de l’organisme ; Autre nom ; Type d’organisme ; Sous-type de l’organisme ; Adresse ; Nom du lieu ; Adresse ; Accès handicapés ; Classé au titre des Monuments historiques ; Date de classement du site ; Inscrit à l’inventaire supplémentaire des Monuments historiques ; Date d’inscription ; Elément classé ou inscrit ; Etendu de la protection ; Situé dans un espace protégé ; Intitulé de l’offre culturelle ; Nature de l’offre ; Liste des thèmes associés à l’offre ; Dates, horaires, conditions d’accès et lieu d’accueil de cette offre ;Liste des dates et tarifs de l’offre ;, date début ; date fin.",
	"origin" => "http://www.data.gouv.fr/donnees/view/Agenda---Offres-culture-2011-30382214",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("offre" , "culture" , "france"),
	"zone" => array(1,2,3,4),
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