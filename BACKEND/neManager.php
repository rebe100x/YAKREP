<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH - ENTITEES NOMMEES</title></head><body>
<?php 
/*
 NAMED ENTITIES FOR TAGS AND CATS DETECTION
 DETECTION IS NOT CASE SENSITIVE
*/

ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);

require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$NE = $db->yakNE;

$records = array();


$records[] = array(
	"_id" => new MongoId("512db21ffa9a95d409000000"),
	"title"=> "Gratuit",
	"match"=>array(
		array(
			"title"=>"gratuit",
			"level"=>"normalized"
		),
		array(
			"title"=>"gratis",
			"level"=>"normalized"
		),
		array(
			"title"=>"free",
			"level"=>"exact"
		),
		array(
			"title"=>"entrée gratuite",
			"level"=>"normalized"
		)
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512db21ffa9a95d409000001"),
	"title"=> "Fête de la Musique",
	"match"=>array(
		array(
			"title"=>"fête de la Musique",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('50696022fa9a955014000008'),new MongoId('504d89cffa9a957004000001')),
	"yakCatName"=>array("Musique","Culture"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000070"),
	"title"=> "Foot",
	"match"=>array(
		array(
			"title"=>"foot",
			"level"=>"normalized"
		),
		array(
			"title"=>"football",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('506479f54a53042191000000')),
	"yakCatName"=>array("Sport"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000071"),
	"title"=> "Tennis",
	"match"=>array(
		array(
			"title"=>"tennis",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('506479f54a53042191000000')),
	"yakCatName"=>array("Sport"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000072"),
	"title"=> "Météo",
	"match"=>array(
		array(
			"title"=>"météo",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('')),
	"yakCatName"=>array(""),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000073"),
	"title"=> "MP2013",
	"match"=>array(
		array(
			"title"=>"MP2013",
			"level"=>"normalized"
		),
		array(
			"title"=>"MP 2013",
			"level"=>"normalized"
		),
		array(
			"title"=>"Marseille-Provence 2013",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('')),
	"yakCatName"=>array(""),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);


$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000074"),
	"title"=> "Midem",
	"match"=>array(
		array(
			"title"=>"midem",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('50696022fa9a955014000008')),
	"yakCatName"=>array("Musique"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000075"),
	"title"=> "St-Valentin",
	"match"=>array(
		array(
			"title"=>"la Saint-Valentin",
			"level"=>"normalized"
		),
		array(
			"title"=>"la Saint Valentin",
			"level"=>"normalized"
		),
		array(
			"title"=>"la St Valentin",
			"level"=>"normalized"
		),
		array(
			"title"=>"la St-Valentin",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('')),
	"yakCatName"=>array(""),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000076"),
	"title"=> "Journées du patrimoine",
	"match"=>array(
		array(
			"title"=>"les journées du patrimoine",
			"level"=>"normalized"
		),
		array(
			"title"=>"la journée du patrimoine",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(new MongoId('504d89cffa9a957004000001')),
	"yakCatName"=>array("Culture"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512e452d1d22b3836e015f05"),
	"title"=> "FaitsDivers",
	"match"=>array(
		array(
			"title"=>"un mort",
			"level"=>"normalized"
		),
		array(
			"title"=>"morts",
			"level"=>"normalized"
		),
		array(
			"title"=>"blessé",
			"level"=>"normalized"
		),
		array(
			"title"=>"blessés",
			"level"=>"normalized"
		),
		array(
			"title"=>"blessée",
			"level"=>"normalized"
		),
		array(
			"title"=>"blessées",
			"level"=>"normalized"
		),
		array(
			"title"=>"criminel",
			"level"=>"normalized"
		),
		array(
			"title"=>"crime",
			"level"=>"normalized"
		),
		array(
			"title"=>"poignardé",
			"level"=>"normalized"
		),
		array(
			"title"=>"assassin",
			"level"=>"normalized"
		),
		array(
			"title"=>"assassiner",
			"level"=>"normalized"
		),
		array(
			"title"=>"incendie",
			"level"=>"normalized"
		),
		array(
			"title"=>"incendiaire",
			"level"=>"normalized"
		),
		array(
			"title"=>"attaque à main armée",
			"level"=>"normalized"
		),
		array(
			"title"=>"braquage",
			"level"=>"normalized"
		),
		array(
			"title"=>"blessé",
			"level"=>"normalized"
		),
		array(
			"title"=>"victime",
			"level"=>"normalized"
		),
		array(
			"title"=>"cambrioleur",
			"level"=>"normalized"
		),
		array(
			"title"=>"cambriolé",
			"level"=>"normalized"
		),
		array(
			"title"=>"cambriolage",
			"level"=>"normalized"
		),
		array(
			"title"=>"vol à main armée",
			"level"=>"normalized"
		),
		array(
			"title"=>"empoisonné",
			"level"=>"normalized"
		),
		array(
			"title"=>"empoisonnée",
			"level"=>"normalized"
		),
		array(
			"title"=>"carambolage",
			"level"=>"normalized"
		),
		array(
			"title"=>"été dérobé",
			"level"=>"normalized"
		),
		array(
			"title"=>"été dérobée",
			"level"=>"normalized"
		),
		array(
			"title"=>"été dérobés",
			"level"=>"normalized"
		),
		array(
			"title"=>"été dérobées",
			"level"=>"normalized"
		),
		array(
			"title"=>"meurt",
			"level"=>"exact"
		),

	),
	"yakCatId"=>array(),
	"yakCatName"=>array(""),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);


$records[] = array(
	"_id" => new MongoId("512f00b0fa9a95980b000003"),
	"title"=> "Restaurant",
	"match"=>array(
		array(
			"title"=>"restaurant",
			"level"=>"normalized"
		),
		array(
			"title"=>"restau",
			"level"=>"normalized"
		),
		array(
			"title"=>"bistro",
			"level"=>"exact"
		),
		array(
			"title"=>"brasserie",
			"level"=>"normalized"
		)
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512f0cdb1d22b32e7a014b7b"),
	"title"=> "Education",
	"match"=>array(
		array(
			"title"=>"ouvertures de classe",
			"level"=>"normalized"
		),
		array(
			"title"=>"enseignants",
			"level"=>"normalized"
		),
		array(
			"title"=>"instituteurs",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512f0d7dfa9a956c0b000003"),
	"title"=> "Solidarité",
	"match"=>array(
		array(
			"title"=>"MSF",
			"level"=>"exact"
		),
		array(
			"title"=>"médecins sans frontières",
			"level"=>"normalized"
		),
		array(
			"title"=>"ONG",
			"level"=>"exact"
		),
		array(
			"title"=>"Emmaüs",
			"level"=>"exact"
		),
		array(
			"title"=>"restaus du coeur",
			"level"=>"normalized"
		),
		array(
			"title"=>"restos du cœur",
			"level"=>"normalized"
		),
		array(
			"title"=>"Handicap International",
			"level"=>"normalized"
		),
		array(
			"title"=>"CARE",
			"level"=>"exact"
		),
		array(
			"title"=>"Cimade",
			"level"=>"exact"
		),
		array(
			"title"=>"Croix-Rouge",
			"level"=>"exact"
		),
		array(
			"title"=>"Fondation Abbé-Pierre",
			"level"=>"exact"
		),
		array(
			"title"=>"Mmédecins du monde",
			"level"=>"normalized"
		),
		array(
			"title"=>"MDM",
			"level"=>"exact"
		),
		array(
			"title"=>"Fondation d'Auteuil",
			"level"=>"exact"
		),
		array(
			"title"=>"Première Urgence",
			"level"=>"exact"
		),
		array(
			"title"=>"Samu",
			"level"=>"exact"
		),
		array(
			"title"=>"Secours catholique",
			"level"=>"normalized"
		),
		array(
			"title"=>"Sidaction",
			"level"=>"exact"
		),
		array(
			"title"=>"Secours populaire",
			"level"=>"normalized"
		),
		array(
			"title"=>"Act Up",
			"level"=>"exact"
		),
		array(
			"title"=>"Act Up-Paris",
			"level"=>"exact"
		),
		array(
			"title"=>"Greenpeace",
			"level"=>"exact"
		),
		array(
			"title"=>"Greenpeace",
			"level"=>"exact"
		),

	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512f0d7dfa9a956c0b000004"),
	"title"=> "Environnement",
	"match"=>array(
		array(
			"title"=>"respect de l’environnement",
			"level"=>"normalized"
		),
		array(
			"title"=>"Greenpeace",
			"level"=>"normalized"
		),
		array(
			"title"=>"pollution",
			"level"=>"normalized"
		),
		array(
			"title"=>"marée noire",
			"level"=>"normalized"
		),
		array(
			"title"=>"écologie",
			"level"=>"normalized"
		),
		array(
			"title"=>"écologistes",
			"level"=>"normalized"
		),
		array(
			"title"=>"catastrophe écologique",
			"level"=>"normalized"
		),
		array(
			"title"=>"agriculture biologique",
			"level"=>"normalized"
		),
		array(
			"title"=>"préserver la biodiversité",
			"level"=>"normalized"
		),
		array(
			"title"=>"préserver les écosystèmes",
			"level"=>"normalized"
		),
		array(
			"title"=>"préservation de l'écosystème",
			"level"=>"normalized"
		),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

						

$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $NE->findOne(array('title'=>$record['title']));
	if(empty($res)){
		$row1++;
		$NE->save($record);
		echo $record['title']. ' : ' .$record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$NE->update(array("_id"=>$record["_id"]),$record);
		}
	}
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
                    
echo "<br><hr><br>";

            
$NE->ensureIndex(array("title"=>1));
$NE->ensureIndex(array("match"=>1));
					
?>