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