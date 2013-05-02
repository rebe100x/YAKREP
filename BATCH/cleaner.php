<?php

/* 
	clean blacklist history older than 10 days
	clean hottag batch older than 10 days
*/

echo "<!doctype html><html><head><meta charset='utf-8' /><title>YAKWALA BATCH CLEANER</title></head><body>";
	
	
require_once("../LIB/conf.php");
$conf = new conf();

$db =$conf->mdb();
$dbTrack =$conf->mdbTrack();
$trackColl = $dbTrack->tracks;

$infoColl = $db->info;
$tagColl = $db->tag;
$userColl = $db->user;
$blackListColl = $dbTrack->blacklist;

echo '<hr>***** CLEAN BLACKLIST ******<br>';

$q = (empty($_GET['q']))?"":$_GET['q']; 

if($q != ''){
	$user = $userColl->find(array('_id'=>new MongoId($q),"status"=>1));
}else{
	$user = $userColl->find(array("status"=>1));
}	

$tsNow = gmmktime();
$tsTenDaysAgo = $tsNow - 10*24*60*60; 	
	

if(!empty($user)){
	foreach($user as $u){
		echo '<hr><br><b>User: '.$u['login'].'</b><br>';		
		$BLkeep = array('user'=>array(),'info'=>array(),'feed'=>array());
		$BLerease = array('user'=>array(),'info'=>array(),'feed'=>array());
		
		if(!empty($u['listeNoire'])){
			foreach($u['listeNoire'] as $key=>$bl){
				foreach($bl as $b){
					
					if($b['date']->sec > $tsTenDaysAgo){
						echo '<br>   <i>'.$b['login'].'</i>--'.$b['date']->sec.' KEEP IT';
						$BLkeep[$key][] = $b;
					}
					else{	
						echo '<br>   <i>'.$b['login'].'</i>--'.$b['date']->sec.' EREASE IT';
						$BLerease[$key][] = $b;
					}
				}
			}
		}else
			echo '<br> Empty blacklist';
		
		
		// SAVE
		$userColl->update(array("_id"=> $u['_id']), array('$set'=>array("listeNoire"=>$BLkeep)));
		
	}
	
}
echo '<br>***** END CLEAN BLACKLIST ******<br><br><br>';	
	
// CLEAN TAGS
echo '<hr>***** CLEAN TAGS ******<br>';
$tags = $tagColl->remove(array('creationDate'=>array('$lte'=>new Mongodate($tsTenDaysAgo))));
if($tags)
	echo '<br>Delete history OK<br>';
echo "<br>***** END CLEAN TAGS *****";
	
	

	
	
?>



