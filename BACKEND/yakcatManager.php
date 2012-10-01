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


/*LEVEL 1*/

$records[] = array(
	"_id" => new MongoId("50696022fa9a95501400000a"),
	"title"=> "Religion",
	"path" => "RELIGION",
	"pathN" => "RELIGION",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
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
	"status" => 1
);
$records[] = array(
	"_id" => new MongoId("50596c9cfa9a953c14000000"),
	"title"=> "Loisir",
	"path" => "Loisir",
	"pathN" => "LOISIR",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
);

/*LEVEL 2*/


$records[]= array(
	"_id" => new MongoId("50535d5bfa9a95ac0d0000b6"),
	"title"=> "Musée",
	"path" => "Culture, Musée",
	"pathN" => "CULTURE#MUSEE",
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
	"status" => 1
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
	"status" => 1
);


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
	"status" => 1
);



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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
);


$records[] = array(
	"_id" => new MongoId("50596cdafa9a95401400004f"),
	"title"=> "Espace vert",
	"path" => "Loisir, Espace vert",
	"pathN" => "LOISIR#EXPACEVERT",
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


$records[] = array(
	"_id" => new MongoId("506479f54a53042191000000"),
	"title"=> "Sport",
	"path" => "SPORT",
	"pathN" => "SPORT",
	"level" => 1,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
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
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("506479f54a53042191020000"),
	"title"=> "Station",
	"path" => "Géolocalisation, Station",
	"pathN" => "GEOLOCALISATION#STATION",
	"ancestors" => array("Géolocalisation"),
	"parent" => "Géolocalisation",
	"ancestorsId" => array(new MongoId("504d89f4fa9a958808000001")),
	"parentId" => new MongoId("504d89f4fa9a958808000001"),
	"level" => 2,
	"thumb" => "",
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1
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
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("50696022fa9a95501400000b"),
	"title"=> "Archive",
	"path" => "Education, Archive",
	"pathN" => "EDUCATION#ARCHIVE",
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
	"status" => 1
);


/*LEVEL 3*/

$records[] = array(
	"_id" => new MongoId("5061a0d3fa9a95f009000000"),
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

$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $yakcat->findOne(array('title'=>$record['title']));
	if(empty($res)){
		$row1++;
		$yakcat->save($record);
		echo $record['_id']."<br>";                    
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