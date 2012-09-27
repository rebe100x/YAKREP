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


$yakcat = $db->yakcat;

$records = array();


$record[]= array(
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
);


$record[] = array(
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

$record[] = array(
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
);

$record[] = array(
	"title"=> "Yakdico",
	"path" => "Géolocalisation, Yakdico",
	"pathN" => "GEOLOCALISATION#YAKDICO",
	"ancestors" => array("Géolocalisation"),
	"parent" => "Géolocalisation",
	"ancestorsId" => array(new MongoId("504d89f4fa9a958808000001")),
	"parentId" => new MongoId("504d89f4fa9a958808000001"),
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$record[] = array(
	"_id"=>"504d89f4fa9a958808000001",
	"title"=> "Géolocalisation",
	"path" => "Géolocalisation",
	"pathN" => "GEOLOCALISATION",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$record[] = array(
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
	"status" => 1
);



$record[] = array(
	"title"=> "Primaire",
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
	"status" => 1
);




$record[] = array(
	"title"=> "Médiathèque",
	"path" => "CULTURE, Médiathèque",
	"pathN" => "CULTURE, MEDIATHEQUE",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"tag"=> array("Biblithèque"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$record[] = array(
	"title"=> "Planétarium",
	"path" => "CULTURE, Planétarium",
	"pathN" => "CULTURE, PLANETARIUM",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);
$record[] = array(
	"title"=> "Aquarium",
	"path" => "CULTURE, Aquarium",
	"pathN" => "CULTURE, AQUARIUM",
	"ancestors" => array("Culture"),
	"parent" => "Culture",
	"ancestorsId" => array(new MongoId("504d89cffa9a957004000001")),
	"parentId" => new MongoId("504d89cffa9a957004000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$record[] = array(
	"title"=> "Loisir",
	"path" => "LOISIR",
	"pathN" => "LOISIR",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$record[] = array(
	"title"=> "Espace vert",
	"path" => "Loisir, Espace vert",
	"pathN" => "LOISIR, EXPACE VERT",
	"ancestors" => array("Loisir"),
	"parent" => "Loisir",
	"ancestorsId" => array(new MongoId("50596c9cfa9a953c14000000")),
	"parentId" => new MongoId("50596c9cfa9a953c14000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$record[] = array(
	"title"=> "Sport",
	"path" => "SPORT",
	"pathN" => "SPORT",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$record[] = array(
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
	"status" => 1
);

$record[] = array(
	"title"=> "Sport",
	"path" => "Loisir, Sport",
	"pathN" => "LOISIR#SPORT",
	"ancestors" => array("Loisir"),
	"parent" => "Loisir",
	"ancestorsId" => array(new MongoId("50596c9cfa9a953c14000000")),
	"parentId" => new MongoId("50596c9cfa9a953c14000000"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$row = 0;	
foreach($records as $record){
	$res = $yakcat->findOne(array('title'=>$record['title']));
	if(empty($res)){
		$row++;
		$yakcat->save($record);
		echo $record['_id'];                    
	}
}
echo "<br>".$row." records added.";
                    
                                     

?>