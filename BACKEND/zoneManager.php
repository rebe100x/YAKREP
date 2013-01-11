<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH - ZONE MANAGER</title></head><body>
<?php
ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/conf.php");

$conf = new conf();
$m = new Mongo();
$db = $m->selectDB($conf->db());

$zone = $db->zone;



$records = array();



/*MARSEILLE*/	
$records[] = array(
	"_id" => new MongoId("50c5e0601d22b34e440010a7"),
	"name"=>"Provence: 04,05,84,13",
	"location" => array('lat'=>43.297198,'lng'=>5.365997),
	"num"=>14,
	"formatted_address"=> "Marseille, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>"",
				'area'=>"Provence-Alpes-Côte-d'Azur",
				'country'=>'France',
				'zip'=>'13000',
			),
	"box"=>array(
			'tl'=>array('lat'=>43.854336,'lng'=>4.514557),
			'br'=>array('lat'=>43.197167,'lng'=>5.651642),
			),
	"status" => 1
	);

	
/* BRETAGNE */
$records[] = array(
	"_id" => new MongoId("50c879b49bab883f11000000"),
	"name"=>"Bretagne",
	"location" => array('lat'=>48.259427,'lng'=>-2.925568),
	"num"=>15,
	"formatted_address"=> "Bretagne, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>"",
				'area'=>'Bretagne',
				'country'=>'France',
				'zip'=>'',
			),
	"box"=>array(
			'tl'=>array('lat'=>48.839413,'lng'=>-4.878754),
			'br'=>array('lat'=>47.309034,'lng'=>-1.181854),
			),
	"status" => 1
);


/*BANLIEUE PARIS*/
$records[] = array(
	"_id" => new MongoId("507ff2f6fa9a95e80c00048d"),
	"name"=>"Val-d'Oise",
	"location" => array('lat'=>49.06159010,'lng'=>2.15813510),
	"num"=>13,
	"formatted_address"=> "Val-d'Oise, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>"Val-d'Oise",
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'95000',
			),
	"box"=>array(
			'tl'=>array('lat'=>49.2415040,'lng'=>1.60873310),
			'br'=>array('lat'=>48.90867490,'lng'=>2.59497910),
	),
	"status" => 1
);
$records[] = array(
	"_id" => new MongoId("507fed53fa9a95e80c000483"),
	"name"=>"Val-de-Marne",
	"location" => array('lat'=>48.79314260,'lng'=>2.47403370),
	"num"=>12,
	"formatted_address"=> "Val-de-Marne, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'Val-de-Marne',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'94000',
			),
	"box"=>array(
			'tl'=>array('lat'=>48.8614840,'lng'=>2.30867590),
			'br'=>array('lat'=>48.68764300000001,'lng'=>2.61564190),
	),
	"status" => 1
);
$records[] = array(
	"_id" => new MongoId("507fed53fa9a95e80c000481"),
	"name"=>"Seine-Saint-Denis",
	"location" => array('lat'=>48.91374550,'lng'=>2.48457290),
	"num"=>11,
	"formatted_address"=> "Seine-Saint-Denis, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'Seine-Saint-Denis',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'93000',
			),
	"box"=>array(
			'tl'=>array('lat'=>49.0123290,'lng'=>2.28831090),
			'br'=>array('lat'=>48.8072480,'lng'=>2.60329190),
	),
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("507fed53fa9a95e80c000482"),
	"name"=>"Hauts-de-Seine",
	"location" => array('lat'=>48.8285080,'lng'=>2.21880680),
	"num"=>10,
	"formatted_address"=> "Hauts-de-Seine, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'Hauts-de-Seine',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'92000',
			),
	"box"=>array(
			'tl'=>array('lat'=>48.95096190,'lng'=>2.1457020),
			'br'=>array('lat'=>48.7293510,'lng'=>2.3369410),
	),
	"status" => 1
);



$records[] = array(
	"_id" => new MongoId("507fed53fa9a95e80c000484"),
	"name"=>"Essonne",
	"location" => array('lat'=>48.45856980,'lng'=>2.15694160),
	"num"=>9,
	"formatted_address"=> "Essonne, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'Essonne',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'91000',
			),
	"box"=>array(
			'tl'=>array('lat'=>48.77613190,'lng'=>1.91451310),
			'br'=>array('lat'=>48.28455599999999,'lng'=>2.58563310),
	),
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("507fed53fa9a95e80c000485"),
	"name"=>"Yvelines",
	"location" => array('lat'=>48.78509390,'lng'=>1.82565720),
	"num"=>8,
	"formatted_address"=> "Yvelines, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'Yvelines',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'78000',
			),
	"box"=>array(
			'tl'=>array('lat'=>49.08544810,'lng'=>1.446170),
			'br'=>array('lat'=>48.43855689999999,'lng'=>2.22912690),
	),
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("507fed53fa9a95e80c000486"),
	"name"=>"Seine-et-Marne",
	"location" => array('lat'=>48.8410820,'lng'=>2.9993660),
	"num"=>7,
	"formatted_address"=> "Seine-et-Marne, France",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'Seine et Marne',
				'area'=>'Ile-de-France',
				'country'=>'France',
				'zip'=>'77000',
			),
	"box"=>array(
			'tl'=>array('lat'=>49.11789790,'lng'=>2.39232610),
			'br'=>array('lat'=>48.12008110,'lng'=>3.55900690),
	),
	"status" => 1
);



/*BELGIQUE*/
$records[] = array(
	"_id" => new MongoId("507e6d6cfa9a95f00c000002"),
	"name"=>"Région flamande",
	"location" => array('lat'=>51.09502440,'lng'=>4.44778090),
	"num"=>6,
	"formatted_address"=> "Région flamande, Belgique",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'',
				'area'=>'Région flamande',
				'country'=>'Belgique',
				'zip'=>'',
			),
	"box"=>array(
			'tl'=>array('lat'=>51.50510,'lng'=>2.54494060),
			'br'=>array('lat'=>50.68736000000001,'lng'=>5.911010099999999),
	),
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("507e6d6cfa9a95f00c000003"),
	"name"=>"Région wallonne",
	"location" => array('lat'=>50.4005010,'lng'=>5.13351250),
	"num"=>5,
	"formatted_address"=> "Région wallone, Belgique",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'',
				'area'=>'Région wallone',
				'country'=>'Belgique',
				'zip'=>'',
			),
	"box"=>array(
			'tl'=>array('lat'=>50.811920,'lng'=>2.84212990),
			'br'=>array('lat'=>49.497010,'lng'=>6.407820),
	),
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("507e6d6cfa9a95f00c000004"),
	"name"=>"Région de Bruxelles-Capitale",
	"location" => array('lat'=>50.850364,'lng'=>4.351699),
	"num"=>4,
	"formatted_address"=> "Région de Bruxelles-Capitale, Belgique",
	"address" => array(
				'arr'=>'',
				'city'=>'',
				'state'=>'',
				'area'=>'Région de Bruxelles',
				'country'=>'Belgique',
				'zip'=>'',
			),
	"box"=>array(
			'tl'=>array('lat'=>50.91370999999999,'lng'=>4.31380),
			'br'=>array('lat'=>50.79624010,'lng'=>4.43697990),
	),
	"status" => 1
);


$records[] = array(
	"_id" => new MongoId("50091badfa9a95d408000000"),
	"name"=>"Paris",
	"location" => array('lat'=>48.857487,'lng'=>2.352791),
	"num"=>1,
	"formatted_address"=> "Paris, France",
	"address" => array(
				'arr'=>'',
				'city'=>'Paris',
				'state'=>'Paris',
				'area'=>'Ile-de-france',
				'country'=>'France',
				'zip'=>'75000',
			),
	"box"=>array(
			'tl'=>array('lat'=>43.624768,'lng'=>2.252541),
			'br'=>array('lat'=>48.815907,'lng'=>2.413902),
	),
	"status" => 1
);


$records[] = array(
	"_id" => new MongoId("50091cb5fa9a95d408000001"),
	"name"=>"Montpellier",
	"location" => array('lat'=>43.610974,'lng'=>3.876801),
	"num"=>2,
	"formatted_address"=> "Montpellier, France",
	"address" => array(
				'arr'=>'',
				'city'=>'Montpellier',
				'state'=>'Hérault',
				'area'=>'Languedoc-Roussillon',
				'country'=>'France',
				'zip'=>'34000',
			),
	"box"=>array(
			'tl'=>array('lat'=>43.618928,'lng'=>3.862038),
			'br'=>array('lat'=>43.59643,'lng'=>3.89843),
	),
	"status" => 1
);


$records[] = array(
	"_id" => new MongoId("500923f9fa9a95d408000003"),
	"name"=>"Éghezée",
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"num"=>3,
	"formatted_address"=> "Éghezée, Belgique",
	"address" => array(
				'arr'=>'',
				'city'=>'Éghezée',
				'state'=>'Namur',
				'area'=>'Région wallonne',
				'country'=>'Belgique',
				'zip'=>'5310',
			),
	"box"=>array(
			'tl'=>array('lat'=>50.623984,'lng'=>4.814072),
			'br'=>array('lat'=>50.528488,'lng'=>4.995689),
	),
	"status" => 1
);

$row1 = 0;
$row2 = 0;
foreach($records as $record){
	$res = $zone->findOne(array('name'=>$record['name']));
	if(empty($res)){
		$row1++;
		$zone->save($record);
		echo $record['name']." : ".$record['_id']."<br>";
	}else{
		if(!empty($record["_id"])){
			$row2++;
			$zone->update(array("_id"=>$record["_id"]),$record);
		}

	}

}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";

$zone->ensureIndex(array("location"=>"2d"));
$zone->ensureIndex(array("box"=>"2d"));
$zone->ensureIndex(array("num"=>"unique"));

?>
