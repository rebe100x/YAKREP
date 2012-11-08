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




/*
$records[] = array(
	"_id" => new MongoId("506c0323fa9a95740b000000"),
	"name"=>"Tintin Reporter",
	"bio" =>"",
	"mail" =>"tintin@gmail.com",
	"web" =>"http://tintin.com",
	"address" => array(),
	"tag" => array("Hergé","Bande dessinée", "BD"),
	"thumb" => "",
	"type" => 2,
	"login"=>"tintin",	
	"password"=> "tintin",
	"usersubs" => array(),
	"placesubs" => array(),
	"favplace" => array(),
	"tagsubs" => array("faits divers"),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	



$records[] = array(
	"_id" => new MongoId("50939deafa9a95140b000000"),
	"name"=>"Renaud Bess",
	"bio" =>"ma bio",
	"mail" =>"renaud@gmail.com",
	"web" =>"http://bessieres.biz",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 1,
	"login"=>"renaud",	
	"password"=> "9409083fa88379a7ed6301554c1e3d62",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	

$records[] = array(
	"_id" => new MongoId("506c0323fa9a95740b000004"),
	"name"=>"Association",
	"bio" =>"",
	"mail" =>"asso@gmail.com",
	"web" =>"http://asso.com",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 3,
	"login"=>"asso",	
	"password"=> "asso",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	

$records[] = array(
	"_id" => new MongoId("506c0323fa9a95740b000006"),
	"name"=>"Entreprise",
	"bio" =>"",
	"mail" =>"etp@gmail.com",
	"web" =>"http://etp.com",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 4,
	"login"=>"etp",	
	"password"=> "etp",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);	

$records[] = array(
	"_id" => new MongoId("506c0323fa9a95740b000008"),
	"name"=>"Institution",
	"bio" =>"",
	"mail" =>"inst@gmail.com",
	"web" =>"http://inst.com",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 5,
	"login"=>"inst",	
	"password"=> "inst",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("506c0323fa9a95740b00000a"),
	"name"=>"L'été est fini",
	"bio" =>"",
	"mail" =>"toto@gmail.com",
	"web" =>"http://toto.com",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 1,
	"login"=>"l'été",	
	"password"=> "inst",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);



$records[] = array(
	"_id" => new MongoId("506c0323fa9a95740b00000c"),
	"name"=>"Julien",
	"bio" =>"",
	"mail" =>"jlebot.info@gmail.com",
	"web" =>"",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 1,
	"login"=>"julien",	
	"password"=> "julien",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);

$records[] = array(
	"_id" => new MongoId("506c0323fa9a95740b00000e"),
	"name"=>"Damien",
	"bio" =>"",
	"mail" =>"",
	"web" =>"",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 1,
	"login"=>"damien",	
	"password"=> "damien",
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);
*/

$salt = sha1(mktime());

$records[] = array(
	"_id" => new MongoId("5098b8a7fa9a95300b000000"),
	"name"=>"Renaud",
	"bio" =>"",
	"mail" =>"",
	"web" =>"",
	"address" => array(),
	"tag" => array(),
	"thumb" => "",
	"type" => 1,
	"login"=>"renaud",
		
	//"password"=> "damien",
	"hash"=> sha1("renaudyakwala@secure".$salt),
	"salt"=> $salt,
	"token"=>sha1(mktime()),
	"usersubs" => array(),
	"placesubs" => array(),
	"tagsubs" => array(),
	"favplace" => array(),
	"location" => array('lat'=>48.851875,'lng'=>2.356374),
	"creationDate" => new MongoDate(gmmktime()),
	"lastModifDate" => new MongoDate(gmmktime()),
	"lastLoginDate" => new MongoDate(gmmktime()),
	"status" => 1
);
$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $user->findOne(array('mail'=>$record['mail']));
	if(empty($res)){
		$row1++;
		$user->save($record);
		echo $record['mail']. ' : ' .$record['_id']."<br>";                    
	}else{
		if($record["_id"]){
			$row2++;
			$user->update(array("_id"=>$record["_id"]),$record);
		}
	
	}
	
}
echo "<br>".$row1." records added.";
echo "<br>".$row2." updated added.";
                    

					
$user->ensureIndex(array("name"=>1,"login"=>1,'status'=>1));
?>