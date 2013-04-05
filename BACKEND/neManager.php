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
		array("title"=>"gratuit","level"=>"normalized"),
		array("title"=>"gratuitement","level"=>"normalized"),
		array("title"=>"séance gratuite","level"=>"normalized"),
		array("title"=>"gratis","level"=>"normalized"),
		array("title"=>"entrée libre","level"=>"normalized"),
		array("title"=>"entrée gratuite","level"=>"normalized")
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000")),
	"yakCatName"=>array("Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);



$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000070"),
	"title"=> "Foot",
	"match"=>array(
		array("title"=>"foot","level"=>"normalized"),
		array("title"=>"football","level"=>"normalized"),
		array("title"=>"mercato","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId('506479f54a53042191000000')),
	"yakCatName"=>array("Sport"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);



$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000073"),
	"title"=> "MP2013",
	"match"=>array(
		array("title"=>"MP2013","level"=>"normalized"),
		array("title"=>"MP 2013","level"=>"normalized"),
		array("title"=>"Marseille-Provence 2013","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000")),
	"yakCatName"=>array("Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId(""),
	"title"=> "Transport",
	"match"=>array(
		array("title"=>"réseau ferré","level"=>"normalized"),
		array("title"=>"réseau ferroviaire","level"=>"normalized"),
		array("title"=>"SNCF","level"=>"exact"),
		array("title"=>"RER","level"=>"exact"),
		array("title"=>"ligne de métro","level"=>"normalized"),
		array("title"=>"station de métro","level"=>"normalized"),
		array("title"=>"Thalys","level"=>"exact"),
		array("title"=>"Intercités","level"=>"exact"),
		array("title"=>"voyages-sncf.com","level"=>"exact"),
		array("title"=>"Transiliens","level"=>"exact"),
		
	),
	"yakCatId"=>array(new MongoId("5077ebb1fa9a95600d0001dc")),
	"yakCatName"=>array("Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);



$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000075"),
	"title"=> "St-Valentin",
	"match"=>array(
		array("title"=>"la Saint-Valentin","level"=>"normalized"),
		array("title"=>"la Saint Valentin","level"=>"normalized"),
		array("title"=>"la St Valentin","level"=>"normalized"),
		array("title"=>"la St-Valentin","level"=>"normalized"),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512dfb71fa9a95240c000076"),
	"title"=> "JournéesDuPatrimoine",
	"match"=>array(
		array("title"=>"les journées du patrimoine","level"=>"normalized"),
		array("title"=>"la journée du patrimoine","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId('504d89cffa9a957004000001'),new MongoId("50923b9afa9a95d409000000")),
	"yakCatName"=>array("Culture","Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512e452d1d22b3836e015f05"),
	"title"=> "FaitsDivers",
	"match"=>array(
		array("title"=>"met fin à ses jours","level"=>"normalized"),
		array("title"=>"condamné à","level"=>"normalized"),
		array("title"=>"condamnée à","level"=>"normalized"),
		array("title"=>"condamnées à","level"=>"normalized"),
		array("title"=>"condamnés à","level"=>"normalized"),
		array("title"=>"squatteurs","level"=>"normalized"),
		array("title"=>"squatteur","level"=>"normalized"),
		array("title"=>"un mort","level"=>"normalized"),
		array("title"=>"morts","level"=>"normalized"),
		array("title"=>"légèrement blesséé","level"=>"normalized"),
		array("title"=>"légèrement blesséés","level"=>"normalized"),
		array("title"=>"légèrement blessés","level"=>"normalized"),
		array("title"=>"légèrement blessé","level"=>"normalized"),
		array("title"=>"criminel","level"=>"normalized"),
		array("title"=>"crime","level"=>"normalized"),
		array("title"=>"poignardé","level"=>"normalized"),
		array("title"=>"assassin","level"=>"normalized"),
		array("title"=>"assassiner","level"=>"normalized"),
		array("title"=>"incendie","level"=>"normalized"),
		array("title"=>"incendiaire","level"=>"normalized"),
		array("title"=>"attaque à main armée","level"=>"normalized"),
		array("title"=>"braquage","level"=>"normalized"),
		array("title"=>"victime","level"=>"normalized"),
		array("title"=>"cambrioleur","level"=>"normalized"),
		array("title"=>"cambriolé","level"=>"normalized"),
		array("title"=>"cambriolage","level"=>"normalized"),
		array("title"=>"vol à main armée","level"=>"normalized"),
		array("title"=>"empoisonné","level"=>"normalized"),
		array("title"=>"empoisonnée","level"=>"normalized"),
		array("title"=>"carambolage","level"=>"normalized"),
		array("title"=>"été dérobé","level"=>"normalized"),
		array("title"=>"été dérobée","level"=>"normalized"),
		array("title"=>"été dérobés","level"=>"normalized"),
		array("title"=>"été dérobées","level"=>"normalized"),
		array("title"=>"mort accidentelle","level"=>"exact"),
	),
	"yakCatId"=>array(new MongoId('504d89c5fa9a957004000000')),
	"yakCatName"=>array("Actualités"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);


$records[] = array(
	"_id" => new MongoId("512f00b0fa9a95980b000003"),
	"title"=> "Restaurant",
	"match"=>array(
		array("title"=>"restaurant","level"=>"normalized"),
		array("title"=>"restau","level"=>"normalized"),
		array("title"=>"resto","level"=>"normalized"),
		array("title"=>"bistro","level"=>"exact"),
		array("title"=>"brasserie","level"=>"normalized"),
		array("title"=>"épicerie-resto","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000")),
	"yakCatName"=>array("Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512f0cdb1d22b32e7a014b7b"),
	"title"=> "Education",
	"match"=>array(
		array("title"=>"ouvertures de classe","level"=>"normalized"),
		array("title"=>"ouverture de classe","level"=>"normalized"),
		array("title"=>"ouverture de classes","level"=>"normalized"),
		array("title"=>"rentrée scolaire","level"=>"normalized"),
		array("title"=>"enseignants","level"=>"normalized"),
		array("title"=>"instituteurs","level"=>"normalized"),
		array("title"=>"rythmes scolaires","level"=>"normalized"),
		array("title"=>"parents des élèves","level"=>"normalized"),
		array("title"=>"Apeep","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId("504dbb06fa9a95680b000211")),
	"yakCatName"=>array("Education"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512f0d7dfa9a956c0b000003"),
	"title"=> "Solidarité",
	"match"=>array(
		
		array("title"=>"téléthon","level"=>"normalized"),
		array("title"=>"sidaction","level"=>"normalized"),
		array("title"=>"MSF","level"=>"exact"),
		array("title"=>"médecins sans frontières","level"=>"normalized"),
		array("title"=>"ONG","level"=>"exact"),
		array("title"=>"Emmaüs","level"=>"exact"),
		array("title"=>"restaus du coeur","level"=>"normalized"),
		array("title"=>"restos du cœur","level"=>"normalized"),
		array("title"=>"Handicap International","level"=>"normalized"),
		array("title"=>"CARE","level"=>"exact"),
		array("title"=>"Cimade","level"=>"exact"),
		array("title"=>"Croix-Rouge","level"=>"exact"),
		array("title"=>"Fondation Abbé-Pierre","level"=>"exact"),
		array("title"=>"Mmédecins du monde","level"=>"normalized"),
		array("title"=>"MDM","level"=>"exact"),
		array("title"=>"Fondation d'Auteuil","level"=>"exact"),
		array("title"=>"Première Urgence","level"=>"exact"),
		array("title"=>"Samu","level"=>"exact"),
		array("title"=>"Secours catholique","level"=>"normalized"),
		array("title"=>"Sidaction","level"=>"exact"),
		array("title"=>"Secours populaire","level"=>"normalized"),
		array("title"=>"Act Up","level"=>"exact"),
		array("title"=>"Act Up-Paris","level"=>"exact"),
		array("title"=>"Greenpeace","level"=>"exact"),
		array("title"=>"Greenpeace","level"=>"exact"),

	),
	"yakCatId"=>array(new MongoId("512b6dbcfa9a95300c000029 ")),
	"yakCatName"=>array("Solidarité"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("512f0d7dfa9a956c0b000004"),
	"title"=> "Environnement",
	"match"=>array(
		array("title"=>"respect de l’environnement","level"=>"normalized"),
		array("title"=>"Greenpeace","level"=>"normalized"),
		array("title"=>"pollution","level"=>"normalized"),
		array("title"=>"marée noire","level"=>"normalized"),
		array("title"=>"écologistes","level"=>"normalized"),
		array("title"=>"catastrophe écologique","level"=>"normalized"),
		array("title"=>"agriculture biologique","level"=>"normalized"),
		array("title"=>"préserver la biodiversité","level"=>"normalized"),
		array("title"=>"préserver les écosystèmes","level"=>"normalized"),
		array("title"=>"préservation de l'écosystème","level"=>"normalized"),
		array("title"=>"jardiner","level"=>"normalized"),
		array("title"=>"jardinage","level"=>"normalized"),
		array("title"=>"déchetterie","level"=>"normalized"),
		array("title"=>"déchets verts","level"=>"normalized"),
		array("title"=>"écocentre","level"=>"normalized"),
		array("title"=>"gaspillage alimentaire","level"=>"normalized"),
		array("title"=>"tri des déchets","level"=>"normalized"),
		array("title"=>"ambassadeurs du tri","level"=>"normalized"),
		array("title"=>"collecte d’encombrants","level"=>"normalized"),
		array("title"=>"AMAP","level"=>"exact"),
		array("title"=>"recyclage des déchets","level"=>"normalized"),
		array("title"=>"écologie","level"=>"normalized"),
		array("title"=>"économie d'énergie","level"=>"normalized"),
		array("title"=>"énergies propres","level"=>"normalized"),
		array("title"=>"énérgie solaire","level"=>"normalized"),
		array("title"=>"énergie éolienne","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId("512b6dbcfa9a95300c000027")),
	"yakCatName"=>array("Environnement"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("514c283afa9a95380a000001"),
	"title"=> "Elections",
	"match"=>array(
		array("title"=>"élections","level"=>"normalized"),
		array("title"=>"référendum","level"=>"normalized"),
		array("title"=>"Régionales:","level"=>"exact"),
		array("title"=>"Cantonales:","level"=>"exact"),
		array("title"=>"Municipales:","level"=>"exact"),
		array("title"=>"Législatives:","level"=>"exact"),
		array("title"=>"Présidentielles:","level"=>"exact"),
		array("title"=>"Sénatoriales:","level"=>"exact"),
		array("title"=>"Européennes:","level"=>"exact"),
	),
	"yakCatId"=>array(new MongoId('50efebbffa9a95b40c000000')),
	"yakCatName"=>array('Politique'),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000002"),
	"title"=> "DroitsDesFemmes",
	"match"=>array(
		array("title"=>"journée de la femme","level"=>"normalized"),
		array("title"=>"journée des femmes","level"=>"normalized"),
		array("title"=>"journée internationale des femmes","level"=>"normalized"),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000003"),
	"title"=> "Sport",
	"match"=>array(
		array("title"=>"piscine","level"=>"normalized"),
		array("title"=>"tennis","level"=>"normalized"),
		array("title"=>"football","level"=>"normalized"),
		array("title"=>"foot","level"=>"normalized"),
		array("title"=>"alpinisme","level"=>"normalized"),
		array("title"=>"gym","level"=>"normalized"),
		array("title"=>"gymnastique","level"=>"normalized"),
		array("title"=>"gymnaste","level"=>"normalized"),
		array("title"=>"arts martiaux","level"=>"normalized"),
		array("title"=>"athlétisme","level"=>"normalized"),
		array("title"=>"aviron","level"=>"normalized"),
		array("title"=>"badminton","level"=>"normalized"),
		array("title"=>"boxe","level"=>"normalized"),
		array("title"=>"cyclisme","level"=>"normalized"),
		array("title"=>"handball","level"=>"normalized"),
		array("title"=>"hockey","level"=>"normalized"),
		array("title"=>"judo","level"=>"normalized"),
		array("title"=>"karaté","level"=>"normalized"),
		array("title"=>"rafting","level"=>"normalized"),
		array("title"=>"randonnée","level"=>"normalized"),
		array("title"=>"rando","level"=>"normalized"),
		array("title"=>"natation","level"=>"normalized"),
		array("title"=>"activités aquatiques","level"=>"normalized"),
		array("title"=>"parapente","level"=>"normalized"),
		array("title"=>"patinage artisique","level"=>"normalized"),
		array("title"=>"pelote basque","level"=>"normalized"),
		array("title"=>"pétanque","level"=>"normalized"),
		array("title"=>"équitation","level"=>"normalized"),
		array("title"=>"déltaplane","level"=>"normalized"),
		array("title"=>"parachutisme","level"=>"normalized"),
		array("title"=>"rugby","level"=>"normalized"),
		array("title"=>"escrime","level"=>"normalized"),
		array("title"=>"yoga","level"=>"normalized"),
		array("title"=>"triathlon","level"=>"normalized"),
		array("title"=>"biathlon","level"=>"normalized"),
		array("title"=>"pentathlon","level"=>"normalized"),
		
	),
	"yakCatId"=>array(new MongoId("506479f54a53042191000000")),
	"yakCatName"=>array("Sport"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000004"),
	"title"=> "Cinéma",
	"match"=>array(
		array("title"=>"ciné","level"=>"normalized"),
		array("title"=>"cinoche","level"=>"normalized"),
		array("title"=>"cinéma","level"=>"normalized"),
		array("title"=>"projection du film","level"=>"normalized"),
		array("title"=>"réalisateur","level"=>"normalized"),
		array("title"=>"avant-première","level"=>"normalized"),
		array("title"=>"film documentaire","level"=>"normalized"),
		array("title"=>"cinématographique","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000"),new MongoId("504d89cffa9a957004000001")),
	"yakCatName"=>array("Culture","Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000005"),
	"title"=> "Spectacle",
	"match"=>array(
		array("title"=>"spectacle ","level"=>"normalized"),
		array("title"=>"festival","level"=>"normalized"),
		array("title"=>"Café-théâtre","level"=>"normalized"),
		array("title"=>"poésie","level"=>"normalized"),
		array("title"=>"mise en scène","level"=>"normalized"),
		array("title"=>"metteur en scène","level"=>"normalized"),
		array("title"=>"chorégraphique","level"=>"normalized"),
		array("title"=>"chorégraphie","level"=>"normalized"),
		array("title"=>"théâtral","level"=>"normalized"),
		array("title"=>"scénique","level"=>"normalized"),
		array("title"=>"cirque","level"=>"normalized"),
		array("title"=>"animation ludique ","level"=>"normalized"),
		array("title"=>"marionnettes ","level"=>"normalized"),
		array("title"=>"Ciné-concert ","level"=>"normalized"),
		
		
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000"),new MongoId("504d89cffa9a957004000001")),
	"yakCatName"=>array("Culture","Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("5151469afa9a95940d000001"),
	"title"=> "Exposition",
	"match"=>array(
		array("title"=>"exposition ","level"=>"normalized"),
		array("title"=>"galerie d'art","level"=>"normalized"),
		array("title"=>"salon","level"=>"normalized"),
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000"),new MongoId("504d89cffa9a957004000001")),
	"yakCatName"=>array("Culture","Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000006"),
	"title"=> "Musique",
	"match"=>array(
		array("title"=>"musique","level"=>"normalized"),
		array("title"=>"musicien","level"=>"normalized"),
		array("title"=>"musiciens","level"=>"normalized"),
		array("title"=>"musiciennes","level"=>"normalized"),
		array("title"=>"musicienne","level"=>"normalized"),
		array("title"=>"musicale","level"=>"normalized"),
		array("title"=>"musicales","level"=>"normalized"),		
		array("title"=>"Midem","level"=>"normalized"),
		array("title"=>"Musiques traditionnelles","level"=>"normalized"),
		array("title"=>"Musiques du Monde","level"=>"normalized"),
		array("title"=>"fête de la Musique","level"=>"normalized"),
		array("title"=>"percussionnistes","level"=>"normalized"),
		array("title"=>"percussionniste","level"=>"normalized"),
		array("title"=>"trompettiste","level"=>"normalized"),
		array("title"=>"trompettistes","level"=>"normalized"),
		array("title"=>"pianiste","level"=>"normalized"),
		array("title"=>"pianistes","level"=>"normalized"),
		array("title"=>"violoniste","level"=>"normalized"),
		array("title"=>"violonistes","level"=>"normalized"),
		array("title"=>"contrebassiste","level"=>"normalized"),
		array("title"=>"contrebassistes","level"=>"normalized"),
		array("title"=>"guitariste","level"=>"normalized"),
		array("title"=>"guitaristes","level"=>"normalized"),
		array("title"=>"bassiste","level"=>"normalized"),
		array("title"=>"bassistes","level"=>"normalized"),
		array("title"=>"clarinettiste","level"=>"normalized"),
		array("title"=>"clarinettistes","level"=>"normalized"),
		array("title"=>"concert","level"=>"normalized"),
		array("title"=>"chorale","level"=>"normalized"),
		array("title"=>"slam","level"=>"normalized"),
		array("title"=>"orchestre","level"=>"normalized"),
		array("title"=>"chanteur","level"=>"normalized"),
		array("title"=>"chanteuse","level"=>"normalized"),
		array("title"=>"Brahms","level"=>"exact"),
		array("title"=>"Mozart","level"=>"exact"),
		array("title"=>"Purcell","level"=>"exact"),
		array("title"=>"Debussy","level"=>"exact"),
		array("title"=>"Berlioz","level"=>"exact"),
		array("title"=>"Schubert","level"=>"exact"),
		array("title"=>"Haydn","level"=>"exact"),
		array("title"=>"Beethoven","level"=>"exact"),
		array("title"=>"Vivaldi","level"=>"exact"),
		array("title"=>"Bach","level"=>"exact"),
		array("title"=>"Debussy","level"=>"exact"),
		array("title"=>"Verdi","level"=>"exact"),
		array("title"=>"Liszt","level"=>"exact"),
		array("title"=>"Chopin","level"=>"exact"),
		array("title"=>"Dvorak","level"=>"exact"),
		array("title"=>"Puccini","level"=>"exact"),
		array("title"=>"Tchaikovski","level"=>"exact"),
		array("title"=>"Maurice Ravel",	"level"=>"exact"),
		array("title"=>"George Gershwin","level"=>"exact"),
		array("title"=>"Pierre Boulez","level"=>"exact"),
		array("title"=>"Dj","level"=>"exact"),
		array("title"=>"DJ","level"=>"exact"),
		array("title"=>"récital","level"=>"normalized"),
		array("title"=>"conservatoire de","level"=>"normalized"),
		array("title"=>"ciné-concert","level"=>"normalized"),
		array("title"=>"électro","level"=>"normalized"),
		array("title"=>"rock","level"=>"normalized"),
		array("title"=>"rappeur","level"=>"normalized"),		
		array("title"=>"pop","level"=>"normalized"),
		array("title"=>"rap","level"=>"normalized"),
		array("title"=>"blues","level"=>"normalized"),
		array("title"=>"raggae","level"=>"normalized"),
		array("title"=>"jazz","level"=>"normalized"),
		
		
		
		
		
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000"),new MongoId("504d89cffa9a957004000001")),
	"yakCatName"=>array("Culture","Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);



$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000007"),
	"title"=> "Brocante",
	"match"=>array(
		array("title"=>"brocante","level"=>"normalized"),
		array("title"=>"vide-grenier","level"=>"normalized"),
		array("title"=>"kermesse","level"=>"normalized"),
		array("title"=>"bourse aux livres","level"=>"normalized"),
		array("title"=>"bourse aux vêtements","level"=>"normalized"),
		array("title"=>"foire","level"=>"normalized"),
		array("title"=>"braderie","level"=>"normalized"),
		array("title"=>"marché de Noël","level"=>"normalized"),
		
	),
	"yakCatId"=>array(new MongoId("50923b9afa9a95d409000000")),
	"yakCatName"=>array("Sortir"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);

$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000008"),
	"title"=> "Enfants",
	"match"=>array(
		array("title"=>"le tout-petit","level"=>"normalized"),
		array("title"=>"les tout-petits","level"=>"normalized"),
		array("title"=>"ludothèque","level"=>"normalized"),
		array("title"=>"à destination des enfants","level"=>"normalized"),
		array("title"=>"ludique","level"=>"normalized"),
		array("title"=>"scolaire","level"=>"normalized"),
		array("title"=>"jeu de l’oie","level"=>"normalized"),
		array("title"=>"parcours de motricité","level"=>"normalized"),
		array("title"=>"pour le jeune enfant","level"=>"normalized"),
		array("title"=>"atelier d'éveil","level"=>"normalized"),
		array("title"=>"atelier éveil","level"=>"normalized"),
		array("title"=>"marionnettes ","level"=>"normalized"),
		array("title"=>"cirque","level"=>"normalized"),
		array("title"=>"contes","level"=>"normalized"),
		array("title"=>"gratuit pour les moins de","level"=>"normalized"),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);



$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b000009"),
	"title"=> "Emploi",
	"match"=>array(
		array("title"=>"Pôle Emploi","level"=>"exact"),
		array("title"=>"formation continue","level"=>"normalized"),
		array("title"=>"les métiers du","level"=>"normalized"),
		array("title"=>"les métiers et formations du","level"=>"normalized"),
		array("title"=>"les formations du","level"=>"normalized"),
		array("title"=>"formation d'apprentis","level"=>"normalized"),
		array("title"=>"formation à la","level"=>"normalized"),
		array("title"=>"formation de","level"=>"normalized"),
		
	),
	"yakCatId"=>array(new MongoId("504dbb06fa9a95680b000211")),
	"yakCatName"=>array("Education"),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);




$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b00000a"),
	"title"=> "Arts",
	"match"=>array(
		array("title"=>"peinture","level"=>"normalized"),
		array("title"=>"dessin","level"=>"normalized"),
		array("title"=>"sculpture","level"=>"normalized"),
		array("title"=>"céramique","level"=>"normalized"),
		array("title"=>"peintures","level"=>"normalized"),
		array("title"=>"dessins","level"=>"normalized"),
		array("title"=>"sculptures","level"=>"normalized"),
		array("title"=>"céramiques","level"=>"normalized"),
		array("title"=>"poterie","level"=>"normalized"),
		array("title"=>"poteries","level"=>"normalized"),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);


$records[] = array(
	"_id" => new MongoId("514bfaddfa9a95100b00000b"),
	"title"=> "Breizh",
	"match"=>array(
		array("title"=>"symboles celtiques","level"=>"normalized"),
		array("title"=>"Cercle celtique","level"=>"normalized"),
		array("title"=>"danse bretonne","level"=>"normalized"),
		array("title"=>"fest-noz","level"=>"normalized"),
		array("title"=>"Festoù-noz","level"=>"normalized"),
		array("title"=>"culture bretonne","level"=>"normalized"),
		array("title"=>"Keltiek","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);













/*
$records[] = array(
	//"_id" => new MongoId(""),
	"title"=> "",
	"match"=>array(
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
		array("title"=>"","level"=>"normalized"),
	),
	"yakCatId"=>array(),
	"yakCatName"=>array(),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"status" => 1,
);
*/
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