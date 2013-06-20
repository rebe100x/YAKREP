<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);

require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$yakcat = $db->yakcat;

$records = array();

/*LEVEL 1*/

/*
$records[] = array(
	"_id" => new MongoId("51246d43fa9a95080b000000"),
	"title"=> "Météo",
	"path" => "METEO",
	"pathN" => "METEO",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);*/

/*
$records[] = array(
	"_id" => new MongoId("50f00cecfa9a957c0c000001"),
	"title"=> "Administratif",
	"path" => "ADMINISTRATIF",
	"pathN" => "ADMINISTRATIF",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('211','214','215','270','271','212','213','278','99','100','244','224','203','205','204','225','98')	
	)
);*/



$records[] = array(
	"_id" => new MongoId("50efebbffa9a95b40c000000"),
	"title"=> "Politique",
	"path" => "POLITIQUE",
	"pathN" => "POLITIQUE",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('330','283','284','332','331')	
	)
);

$records[] = array(
	"_id" => new MongoId("51c17f1dfa9a95080c000002"),
	"title"=> "Petites Annonces",
	"path" => "PETITESANNONCES",
	"pathN" => "PETITESANNONCES",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50efebbffa9a95b40c000001"),
	"title"=> "Economie",
	"path" => "ECONOMIE",
	"pathN" => "ECONOMIE",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

/*
$records[] = array(
	"_id" => new MongoId("50f025f7fa9a957c0c000048"),
	"title"=> "Commerce",
	"path" => "Commerce",
	"pathN" => "COMMERCE",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);*/

$records[] = array(
	"_id" => new MongoId("50923b9afa9a95d409000000"),
	"title"=> "Sortir",
	"path" => "SORTIR",
	"pathN" => "SORTIR",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('119','120','121','265','269','4','154','195','276','30','153',)	
	)
);

$records[] = array(
	"_id" => new MongoId("50923b9afa9a95d409000001"),
	"title"=> "Infos pratiques",
	"path" => "INFOSPRATIQUES",
	"pathN" => "INFOSPRATIQUES",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('211','214','215','270','271','212','213','278','99','100','244','224','203','205','204','225','98')		
	)
);
/*
$records[] = array(
	"_id" => new MongoId("5092390bfa9a95f40c000000"),
	"title"=> "Discussion",
	"path" => "DISCUSSION",
	"pathN" => "DISCUSSION",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("508fc6ebfa9a95680b000029"),
	"title"=> "Immobilier",
	"path" => "IMMOBILIER",
	"pathN" => "IMMOBILIER",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);


$records[] = array(
	"_id" => new MongoId("5077ec30fa9a95000f000028"),
	"title"=> "Statistiques",
	"path" => "STATISTIQUES",
	"pathN" => "STATISTIQUES",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);*/

$records[] = array(
	"_id" => new MongoId("50696022fa9a95501400000a"),
	"title"=> "Religion",
	"path" => "RELIGION",
	"pathN" => "RELIGION",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('107','108','109','110','111','273','280','128')	
	)
);
$records[] = array(
	"_id" => new MongoId("5077ebb1fa9a95600d0001dc"),
	"title"=> "Transport",
	"path" => "TRANSPORT",
	"pathN" => "TRANSPORT",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('72','73','74','75','185','226')	
	)
);

$records[] = array(
	"_id" => new MongoId("512b6dbcfa9a95300c000027"),
	"title"=> "Environnement",
	"path" => "ENVIRONNEMENT",
	"pathN" => "ENVIRONNEMENT",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('187','194','77','78','275','79','76','188','193','190','191','192',)	
	)
);

$records[] = array(
	"_id" => new MongoId("512b6dbcfa9a95300c000028 "),
	"title"=> "Santé",
	"path" => "SANTE",
	"pathN" => "SANTE",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('85','86')	
	)
);

$records[] = array(
	"_id" => new MongoId("512b6dbcfa9a95300c000029 "),
	"title"=> "Solidarité",
	"path" => "SOLIDARITE",
	"pathN" => "SOLIDARITE",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('91','92','93','94','96','97',)	
	)
);

$records[] = array(
	"_id" => new MongoId("504d89f4fa9a958808000001"),
	"title"=> "Géolocalisation",
	"path" => "Géolocalisation",
	"pathN" => "GEOLOCALISATION",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 0, // not found in the autocomplete
	"ext_id"=>array(
		"of"=>array()	
	)
);



$records[]= array(
	"_id" => new MongoId("504d89c5fa9a957004000000"),
	"title"=> "Actualités",
	"path" => "Actualités",
	"pathN" => "ACTUALITES",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[]= array(
	"_id" => new MongoId("504d89cffa9a957004000001"),
	"title"=> "Culture",
	"path" => "Culture",
	"pathN" => "CULTURE",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('112','125','126','124','123','132','133','134','129','135','138','139','140','136','137','141','1','218','143','197','198','199','200','202','268','146','150','148','147','267','223','171','175','177','219','8','5','15','10','12','206','310','222','245','176','274','320','178','179','180','181','182','287','288','183','184')	
	)
);

$records[]= array(
	"_id" => new MongoId("504dbb06fa9a95680b000211"),
	"title"=> "Education",
	"path" => "Education",
	"pathN" => "EDUCATION",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('105','104','106',)	
	)
);

$records[] = array(
	"_id" => new MongoId("506479f54a53042191000000"),
	"title"=> "Sport",
	"path" => "SPORT",
	"pathN" => "SPORT",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('112','113','114','115','116','117','118','289','201','217')	
	)
);

/*
$records[]= array(
	"_id" => new MongoId("50896423fa9a954c01000000"),
	"title"=> "Restaurant",
	"path" => "Restaurant",
	"pathN" => "RESTAURANT",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[]= array(
	"_id" => new MongoId("510bcee3fa9a95cc0c000003"),
	"title"=> "Hôtel",
	"path" => "Hôtel",
	"pathN" => "HOTEL",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[]= array(
	"_id" => new MongoId("50f7f315fa9a95880b0001e7"),
	"title"=> "Energie",
	"path" => "Energie",
	"pathN" => "ENERGIE",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);
*/
/*********/
/*LEVEL 2*/
/********/
/*
$records[]= array(
	"_id" => new MongoId("50f7f334fa9a958c0b000000"),
	"title"=> "Centrale nucléaire",
	"path" => "Energie, Centrale nucléaire",
	"pathN" => "ENERGIE#CENTRALENUCLEAIRE",
	"ancestors" => array("Energie"),
	"parent" => "Immobilier",
	"ancestorsId" => array(new MongoId("50f7f315fa9a95880b0001e7")),
	"parentId" => new MongoId("50f7f315fa9a95880b0001e7"),
	"tag" => array("Centre d'essais nucléaires"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[]= array(
	"_id" => new MongoId("50f01dcefa9a95bc0c00005f"),
	"title"=> "Location",
	"path" => "Immobilier, Location",
	"pathN" => "IMMOBILIER#LOCATION",
	"ancestors" => array("Immobilier"),
	"parent" => "Immobilier",
	"ancestorsId" => array(new MongoId("508fc6ebfa9a95680b000029")),
	"parentId" => new MongoId("508fc6ebfa9a95680b000029"),
	"tag" => array(""),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[]= array(
	"_id" => new MongoId("50f00d35fa9a95b40c000003"),
	"title"=> "Mairie",
	"path" => "Administration, Mairie",
	"pathN" => "ADMINISTRATION#MAIRIE",
	"ancestors" => array("Administration"),
	"parent" => "Administration",
	"ancestorsId" => array(new MongoId("50f00cecfa9a957c0c000001")),
	"parentId" => new MongoId("50f00cecfa9a957c0c000001"),
	"tag" => array(""),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);



$records[]= array(
	"_id" => new MongoId("50895efefa9a95dc07000000"),
	"title"=> "Aéroport",
	"path" => "Transport, Aéroport",
	"pathN" => "TRANSPORT#AEROPORT",
	"ancestors" => array("Transport"),
	"parent" => "Transport",
	"ancestorsId" => array(new MongoId("5077ebb1fa9a95600d0001dc")),
	"parentId" => new MongoId("5077ebb1fa9a95600d0001dc"),
	"tag" => array(""),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[]= array(
	"_id" => new MongoId("50535d5bfa9a95ac0d0000b6"),
	"title"=> "Musée",
	"path" => "Culture, Musée",
	"pathN" => "CULTURE#MUSEE",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"tag" => array(""),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array(246)	
	)
);



$records[]= array(
	"_id" => new MongoId("504df6b1fa9a957c0b000004"),
	"title"=> "Théâtre",
	"path" => "Culture, Théâtre",
	"pathN" => "CULTURE#THEATRE",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"tag" => array("théâtre"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);


$records[] = array(
	"_id" => new MongoId("504df728fa9a957c0b000007"),
	"title"=> "Cinéma",
	"path" => "Culture, Cinéma",
	"pathN" => "CULTURE#CINEMA",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"tag" => array("ciné","cinoche"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('7','144')	
	)
);*/

$records[] = array(
	"_id" => new MongoId("5056b7aafa9a95180b000000"),
	"title"=> "Yakdico",
	"path" => "Géolocalisation, Yakdico",
	"pathN" => "GEOLOCALISATION#YAKDICO",
	"ancestors" => array("Géolocalisation"),
	"parent" => "Géolocalisation",
	"ancestorsId" => array(new MongoId("504d89f4fa9a958808000001")),
	"parentId" => new MongoId("504d89f4fa9a958808000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 0, // not found in the autocomplete
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("51c00669fa9a95b40b000036"),
	"title"=> "Feed",
	"path" => "Géolocalisation, Feed",
	"pathN" => "GEOLOCALISATION#FEED",
	"ancestors" => array("Géolocalisation"),
	"parent" => "Géolocalisation",
	"ancestorsId" => array(new MongoId("504d89f4fa9a958808000001")),
	"parentId" => new MongoId("504d89f4fa9a958808000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 0, // not found in the autocomplete
	"ext_id"=>array(
		"of"=>array()	
	)
);


$records[] = array(
	"_id" => new MongoId("507e5a9a1d22b30c44000068"),
	"title"=> "Ville",
	"path" => "Géolocalisation, Ville",
	"pathN" => "GEOLOCALISATION#VILLE",
	"ancestors" => array("Géolocalisation"),
	"parent" => "Géolocalisation",
	"ancestorsId" => array(new MongoId("504d89f4fa9a958808000001")),
	"parentId" => new MongoId("504d89f4fa9a958808000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50900846fa9a958c09000000"),
	"title"=> "Rue",
	"path" => "Géolocalisation, Rue",
	"pathN" => "GEOLOCALISATION#RUE",
	"ancestors" => array("Géolocalisation"),
	"parent" => "Géolocalisation",
	"ancestorsId" => array(new MongoId("504d89f4fa9a958808000001")),
	"parentId" => new MongoId("504d89f4fa9a958808000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 0,
	"ext_id"=>array(
		"of"=>array()	
	)
);

/*
$records[] = array(
	"_id" => new MongoId("5056b89bfa9a95180b000001"),
	"title"=> "Ecole",
	"path" => "Education, Ecole",
	"pathN" => "EDUCATION#ECOLE",
	"ancestors" => array("Education"),
	"parent" => "Education",
	"ancestorsId" => array(new MongoId("504dbb06fa9a95680b000211")),
	"parentId" => new MongoId("504dbb06fa9a95680b000211"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50896395fa9a950007000000"),
	"title"=> "Lycée",
	"path" => "Education, Lycée",
	"pathN" => "EDUCATION#LYCEE",
	"ancestors" => array("Education"),
	"parent" => "Education",
	"ancestorsId" => array(new MongoId("504dbb06fa9a95680b000211")),
	"parentId" => new MongoId("504dbb06fa9a95680b000211"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);






$records[] = array(
	"_id" => new MongoId("5056bf35fa9a95180b000004"),
	"title"=> "Médiathèque",
	"path" => "CULTURE, Médiathèque",
	"pathN" => "CULTURE#MEDIATHEQUE",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"tag"=> array("Bibliothèque"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("5056bf3ffa9a95180b000005"),
	"title"=> "Planétarium",
	"path" => "Culture, Planétarium",
	"pathN" => "CULTURE#PLANETARIUM",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50696022fa9a955014000008"),
	"title"=> "Musique",
	"path" => "Culture, Musique",
	"pathN" => "CULTURE#MUSIQUE",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('22','25','6','17','157','159','168','247','248','281','161','162','163','164','166','167','208')	
	)
);

$records[] = array(
	"_id" => new MongoId("506479f54a53042191010000"),
	"title"=> "Concert",
	"path" => "Culture, Concert",
	"pathN" => "CULTURE#CONCERT",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);


$records[] = array(
	"_id" => new MongoId("504df70ffa9a957c0b000006"),
	"title"=> "Exposition",
	"path" => "Culture, Exposition",
	"pathN" => "CULTURE#EXPOSITION",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('149')	
	)
);

$records[] = array(
	"_id" => new MongoId("50696022fa9a955014000009"),
	"title"=> "Spectacle",
	"path" => "Culture, Spectacle",
	"pathN" => "CULTURE#SPECTACLE",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array('207')	
	)
);

$records[] = array(
	"_id" => new MongoId("5056bf28fa9a95180b000003"),
	"title"=> "Aquarium",
	"path" => "Culture, Aquarium",
	"pathN" => "CULTURE#AQUARIUM",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);*/

/*
$records[] = array(
	"_id" => new MongoId("50596cdafa9a95401400004f"),
	"title"=> "Espace vert",
	"path" => "Loisir, Espace vert",
	"pathN" => "LOISIR#ESPACEVERT",
	"ancestors" => array("Loisir"),
	"parent" => "Loisir",
	"ancestorsId" => array(new MongoId("50596c9cfa9a953c14000000")),
	"parentId" => new MongoId("50596c9cfa9a953c14000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);
*/


/*
$records[] = array(
	"_id" => new MongoId("50c5e0c91d22b3bf38000fb0"),
	"title"=> "Cyclisme",
	"path" => "Sport, Cyclisme",
	"pathN" => "SPORT#CYCLISME",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);


$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91000000"),
	"title"=> "Patinoire",
	"path" => "Sport, Patinoire",
	"pathN" => "SPORT#PATINOIRE",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91010000"),
	"title"=> "Volley",
	"path" => "Sport, Volley",
	"pathN" => "SPORT#VOLLEY",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91020000"),
	"title"=> "Piscine",
	"path" => "Sport, Piscine",
	"pathN" => "SPORT#PISCINE",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"tag"=> array("piscine, natation"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91030000"),
	"title"=> "Gymnase",
	"path" => "Sport, Gymnase",
	"pathN" => "SPORT#GYMNASE",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91040000"),
	"title"=> "Football",
	"path" => "Sport, Football",
	"pathN" => "SPORT#FOOTBALL",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"tag"=> array("football, foot"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("51094f75fa9a95280c000000"),
	"title"=> "Handball",
	"path" => "Sport, Handball",
	"pathN" => "SPORT#HANDBALL",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("51094f75fa9a95280c000001"),
	"title"=> "Basket",
	"path" => "Sport, Basket",
	"pathN" => "SPORT#BASKET",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91050000"),
	"title"=> "Rugby",
	"path" => "Sport, Rugby",
	"pathN" => "SPORT#RUGBY",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91060000"),
	"title"=> "Tennis",
	"path" => "Sport, Tennis",
	"pathN" => "SPORT#TENNIS",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50647e2d4a53041f91070000"),
	"title"=> "Pétanque",
	"path" => "Sport, Pétanque",
	"pathN" => "SPORT#PETANQUE",
	"ancestors" => array("Sport"),
	"parent" => "Sport",
	"ancestorsId" => array(new MongoId("506479f54a53042191000000")),
	"parentId" => new MongoId("506479f54a53042191000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);



$records[] = array(
	"_id" => new MongoId("506479f54a53042191020000"),
	"title"=> "Station de métro",
	"path" => "Transport, Station de métro",
	"pathN" => "TRANSPORT#STATIONDEMETRO",
	"ancestors" => array("Transport"),
	"parent" => "Transport",
	"ancestorsId" => array(new MongoId("5077ebb1fa9a95600d0001dc")),
	"parentId" => new MongoId("5077ebb1fa9a95600d0001dc"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50f049e6fa9a95d40c000004"),
	"title"=> "Gare",
	"path" => "Transport, Gare",
	"pathN" => "TRANSPORT#GARE",
	"ancestors" => array("Transport"),
	"parent" => "Transport",
	"ancestorsId" => array(new MongoId("5077ebb1fa9a95600d0001dc")),
	"parentId" => new MongoId("5077ebb1fa9a95600d0001dc"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("50f049e6fa9a95d40c000005"),
	"title"=> "Port",
	"path" => "Transport, Port",
	"pathN" => "TRANSPORT#PORT",
	"ancestors" => array("Transport"),
	"parent" => "Transport",
	"ancestorsId" => array(new MongoId("5077ebb1fa9a95600d0001dc")),
	"parentId" => new MongoId("5077ebb1fa9a95600d0001dc"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("506479f54a53042191030000"),
	"title"=> "Crèche",
	"path" => "Education, Crèche",
	"pathN" => "EDUCATION#CRECHE",
	"ancestors" => array("Education"),
	"parent" => "Education",
	"ancestorsId" => array(new MongoId("504dbb06fa9a95680b000211")),
	"parentId" => new MongoId("504dbb06fa9a95680b000211"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);*/


/*
$records[] = array(
	"_id" => new MongoId("506479f54a53042191040000"),
	"title"=> "Maison de Quartier",
	"path" => "Education, Maison de Quartier",
	"pathN" => "EDUCATION#MAISONDEQUARTIER",
	"ancestors" => array("Education"),
	"parent" => "Education",
	"ancestorsId" => array(new MongoId("504dbb06fa9a95680b000211")),
	"parentId" => new MongoId("504dbb06fa9a95680b000211"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);*/


/*LEVEL 3*/

/*
$records[] = array(
	"_id" => new MongoId("50f90b48fa9a953809000000"),
	"title"=> "Chorégraphie",
	"path" => "Culture, Spectacle, Chorégraphie",
	"pathN" => "CULTURE#SPECTACLE#CHOREGRAPHIE",
	"ancestors" => array("Culture", "Spectacle"),
	"parent" => "Spectacle",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001"),new MongoId("50696022fa9a955014000009")),
	"parentId" => new MongoId("50696022fa9a955014000009"),
	"level" => 3,
	"thumb" => "",
	"tag"=> array("Chorégraphie"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);

$records[] = array(
	"_id" => new MongoId("5061a0d3fa9a95f009000000"),
	"title"=> "Ecole primaire",
	"path" => "Education, Ecole, Primaire",
	"pathN" => "EDUCATION#ECOLE#PRIMAIRE",
	"ancestors" => array("Education", "Ecole"),
	"parent" => "Ecole",
	"ancestorsId" => array(new MongoId("504dbb06fa9a95680b000211"),new MongoId("5056b89bfa9a95180b000001")),
	"parentId" => new MongoId("5056b89bfa9a95180b000001"),
	"level" => 3,
	"thumb" => "",
	"tag"=> array("Elémentaire","Maternelle"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
	"ext_id"=>array(
		"of"=>array()	
	)
);*/

$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $yakcat->findOne(array('title'=>$record['title']));
	if(empty($res)){
		$row1++;
		$yakcat->save($record);
		echo $record['title']. ' : ' .$record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$yakcat->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
                    
echo "<br><hr><br>";

getChilYakcat($yakcat);
            
$yakcat->ensureIndex(array("title"=>1));
			
function getChilYakcat($yakcat,$id='',$level=1){
	
	if($level < 5){
		if($id != '')
			$res = $yakcat->find(array('level'=>$level,"parentId"=>new MongoId($id)));
		else
			$res = $yakcat->find(array('level'=>$level));
			
		$level++;
		$res->sort(array('level' => 1,'title' => 1));
		foreach($res as $child){
			echo "<br>";
			
			$margin = ($level * 60) ."px";
			echo "<div style='margin-left:".$margin."'> <b>".$child['title']."</b> ( ".$child['_id']." ) <br>".$child['pathN']."</div>";
	
			getChilYakcat($yakcat,$child['_id'],$level);
		}
	}
}				

?>