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

$user = $db->user;


$records = array();


$records[] = array(
	"name"=>"Tintin Reporter",
	"bio" =>"",
	"mail" =>"tintin@gmail.com",
	"web" =>"http://tintin.com",
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"tag" => array("Hergé","Bande dessinée", "BD"),
	"thumb" => "",
	"type" => 2,
	"login"=>"tintin",	
	"password"=> "tintin",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array("faits divers"),
	"home" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	



$records[] = array(
	"name"=>"Renaud Bess",
	"bio" =>"ma bio",
	"mail" =>"renaud@gmail.com",
	"web" =>"http://bessieres.biz",
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"tag" => array(),
	"thumb" => "",
	"type" => 1,
	"login"=>"renaud",	
	"password"=> "renaud",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"home" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	

$records[] = array(
	"name"=>"Association",
	"bio" =>"",
	"mail" =>"asso@gmail.com",
	"web" =>"http://asso.com",
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"tag" => array(),
	"thumb" => "",
	"type" => 3,
	"login"=>"asso",	
	"password"=> "asso",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"home" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	

$records[] = array(
	"name"=>"Entreprise",
	"bio" =>"",
	"mail" =>"etp@gmail.com",
	"web" =>"http://etp.com",
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"tag" => array(),
	"thumb" => "",
	"type" => 4,
	"login"=>"etp",	
	"password"=> "etp",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"home" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	

$records[] = array(
	"name"=>"Institution",
	"bio" =>"",
	"mail" =>"inst@gmail.com",
	"web" =>"http://inst.com",
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"tag" => array(),
	"thumb" => "",
	"type" => 5,
	"login"=>"inst",	
	"password"=> "inst",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"home" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$records[] = array(
	"name"=>"L'été est fini",
	"bio" =>"",
	"mail" =>"toto@gmail.com",
	"web" =>"http://toto.com",
	"address" => array(
				'street'=>'',
				'zipcode'=>'75000',
				'city'=>'Paris',
				'country'=>'France',
			),
	"tag" => array(),
	"thumb" => "",
	"type" => 1,
	"login"=>"l'été",	
	"password"=> "inst",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"home" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$row = 0;	
foreach($records as $record){
	$res = $user->findOne(array('mail'=>$record['mail']));
	if(empty($res)){
		$row++;
		$user->save($record);
		$user->ensureIndex(array("home"=>"2d"));
		echo $record['_id'].'<br>';                    
	}
}
echo "<br>".$row." records added.";          
                                     

?>