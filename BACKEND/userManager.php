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


$users = array(
	"renaud"=>1,
	"tintin"=>2,
	"association"=>3,
	'entreprise'=>4,
	"institution"=>5,
);

foreach($users as $login=>$type){
	$salt = sha1(mktime());
	$records[] = array(
		//"_id" => new MongoId(""),
		"name"=>$login,
		"bio" =>"",
		"mail" =>"",
		"web" =>"",
		"address" => array(),
		"tag" => array(),
		"thumb" => "",
		"type" => $type,
		"login"=>$login,
		"hash"=> sha1($login."yakwala@secure".$salt),
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
}



var_dump($records);
$row1 = 0;	
$row2 = 0;	
foreach($records as $record){
	$res = $user->findOne(array('login'=>$record['login']));
	if(empty($res)){
		$row1++;
		$user->save($record);
		echo $record['login']. ' : ' .$record['_id']."<br>";                    
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