<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);

require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$filesource = $db->filesource;


$records = array();

$records[] = array(
	"_id" => new MongoId("508fc3b6fa9a95680b000000"),
	"title"=>"rues de Paris",
	"content" =>"",
	"origin" => "Yakwala",
	"licence" => "Yakwala",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array(""),
	"zone" => array(1),
	);
	
$records[] = array(
	"_id" => new MongoId("508e7eb0fa9a954c0900006c"),
	"title"=>"Villes de l'Hérault",
	"content" =>"",
	"origin" => "Wikipedia - Yakwala",
	"licence" => "Wikipedia - Yakwala",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array(""),
	"zone" => array(2),
	);
	
$records[] = array(
	"_id" => new MongoId("507ff6e7fa9a95e80c000497"),
	"title"=>"Villes d'Ile-de-France",
	"content" =>"",
	"origin" => "Wikipedia - Yakwala",
	"licence" => "Wikipedia - Yakwala",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array(""),
	"zone" => array(7,8,9,10,11,12,13),
	);
	
$records[] = array(
	"_id" => new MongoId("507e74defa9a95f00c000027"),
	"title"=>"Villes de Belgique",
	"content" =>"",
	"origin" => "Wikipedia - Yakwala",
	"licence" => "Wikipedia - Yakwala",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array(""),
	"zone" => array(3,4,5,6),
	);
	
$records[] = array(
	"_id" => new MongoId("5078092e1d22b31a050000aa"),
	"title"=>"Cibul Sitemap",
	"content" =>"",
	"origin" => "http://cibul.net/sitemap.xml",
	"licence" => "Cibul",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("lieux culturels"),
	"zone" => array(1),
	);
	
$records[] = array(
	"_id" => new MongoId("5078092e1d22b31a050000ae"),
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
	"_id" => new MongoId("5078092e1d22b31a050000b2"),
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
	"_id" => new MongoId("5078092e1d22b31a050000b6"),
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
	"_id" => new MongoId("5078092e1d22b31a050000ba"),
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
	"_id" => new MongoId("5078092e1d22b31a050000be"),
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
	"_id" => new MongoId("5078092e1d22b31a050000c2"),
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
	"_id" => new MongoId("5078092e1d22b31a050000c6"),
	"title"=>"Offres culturelles",
	"content" =>"Liste des évènements culturels et des organismes producteurs d'événements en France et des grandes manifestations en France et à l'étranger. Liste des champs : Identifiant de l’organisme ; Nom de l’organisme ; Autre nom ; Type d’organisme ; Sous-type de l’organisme ; Adresse ; Nom du lieu ; Adresse ; Accès handicapés ; Classé au titre des Monuments historiques ; Date de classement du site ; Inscrit à l’inventaire supplémentaire des Monuments historiques ; Date d’inscription ; Elément classé ou inscrit ; Etendu de la protection ; Situé dans un espace protégé ; Intitulé de l’offre culturelle ; Nature de l’offre ; Liste des thèmes associés à l’offre ; Dates, horaires, conditions d’accès et lieu d’accueil de cette offre ;Liste des dates et tarifs de l’offre ;, date début ; date fin.",
	"origin" => "http://www.data.gouv.fr/donnees/view/Agenda---Offres-culture-2011-30382214",
	"licence" => "Licence ouverte",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"tag" => array("offre" , "culture" , "france"),
	"zone" => array(1,2,3,4),
	);	





$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $filesource->findOne(array('title'=>$record['title']));
	if(empty($res)){
		$row1++;
		$filesource->save($record);
		echo $record['title']." : ".$record['_id']."<br>";                    
	}else{
	echo $record['title'] .' is in db<br>';
		if(!empty($record["_id"])){
			$row2++;
			$filesource->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}

echo "<br>".$row1." records added.";
echo "<br>".$row2." records updated.";
$filesource->ensureIndex(array('title'=>1,'content'=>1,'tag'=>1));
$filesource->ensureIndex(array("title"=>1));

?>