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
		echo $record['_id']."<br>";                    
	}else{
		if($record["_id"]){
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
