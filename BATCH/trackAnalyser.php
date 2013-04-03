<?php

/* 
	Analyse YAKTRACK and create yaktag on user profile
	
	This batch targets 2 db : yakwala and yaktrack
	
*/

echo "<!doctype html><html><head><meta charset='utf-8' /><title>YAKWALA BATCH</title></head><body>";
	
	
require_once("../LIB/conf.php");
$conf = new conf();

$db =$conf->mdb();
$dbTrack =$conf->mdbTrack();


$infoColl = $db->info;
$userColl = $db->user;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;
$trackColl = $dbTrack->tracks;

$q = (empty($_GET['q']))?"":$_GET['q']; 

if($q != ''){
	$user = $userColl->find(array('_id'=>new MongoId($q),"status"=>1));
}else{
	$user = $userColl->find(array("status"=>1));
}	

$tsNow = gmmktime();
$tsLastWeek = $tsNow - 7*24*60*60; 	
	
// actionId=>score	
$scoreMatrix = array(
	5=>1, // search
	8=>2, // share
	14=>2, // unappropriated
	7=>2, // like
	6=>3, // read
	14=>5, // comment
	10=>6, // post
	11=>7, // autotag
	12=>10, // alert
	0=>15, // static tags
);
if(!empty($user)){
	foreach($user as $u){
		$favTags = array();
		$favCats = array();
		echo '<br><b>'.$u['login'].' - id: </b>'.$u['_id'];
		$tracks = $trackColl->find(array('userid'=>$u['_id'],'actiondate'=>array('$gte'=>new Mongodate($tsLastWeek))));
		$tracks->sort(array('actiondate'=>-1));	
		foreach($tracks as $t){
			echo '<br>'.$t['actionid'].' : ';
			switch($t['actionid']){
				
				case 6:
				case 7:
				case 8:
				case 14:
				case 10:	
					$theInfo = $infoColl->findOne(array('_id'=>new MongoId($t['params']['infoId'])));
					foreach($theInfo['yakCatName'] as $cat){
						if(!array_key_exists($cat,$favCats))
							$favCats[$cat] = $scoreMatrix[$t['actionid']];
						else
							$favCats[$cat] = $favCats[$cat] + $scoreMatrix[$t['actionid']];
					}
					foreach($theInfo['freeTag'] as $tag){
						echo '<br>tag:'.$tag.'<br>';
						if($tag != ''){
							if(!array_key_exists($tag,$favTags))
								$favTags[$tag] = $scoreMatrix[$t['actionid']];
							else
								$favTags[$tag] = $favTags[$tag] + $scoreMatrix[$t['actionid']];
						}
					}
					
					break;
				case 5:	
					
					
					$tag = $t['params']['str'];
					echo '<br>tag:'.$t['params']['str'].'<br>';
					
					if($tag != '' && $tag != null && $tag != 'null'){
						if($tag[0]=="#")
							$tag = substr($tag,1,strlen($tag));
							
						if(!array_key_exists($tag,$favTags))
							$favTags[$tag] = $scoreMatrix[$t['actionid']];
						else
							$favTags[$tag] = $favTags[$tag] + $scoreMatrix[$t['actionid']];
					}
				
				
				case 11:
				case 12:
					if(!empty($t['params'])){
						$tags = $t['params']['tags'];
						
						if($tags != '' && $tags != null && $tags != 'null' && !empty($tags) && sizeof($tags)>0 ){
							foreach($tags as $tag){
								if(!array_key_exists($tag,$favTags))
									$favTags[$tag] = $scoreMatrix[$t['actionid']];
								else
									$favTags[$tag] = $favTags[$tag] + $scoreMatrix[$t['actionid']];
							}
							
						}
					}
					
					
				
					break;
				
			}
			
		}
		
		// STATIC DATA : 
		// get user's tags :
		if(!empty($u['tag'])){
			$tags = $u['tag'];
			
			if($tags != '' && $tags != null && $tags != 'null' && !empty($tags) && sizeof($tags)>0 ){
				foreach($tags as $tag){
					if(!array_key_exists($tag,$favTags))
						$favTags[$tag] = $scoreMatrix[0];
					else
						$favTags[$tag] = $favTags[$tag] + $scoreMatrix[0];
				}
				
			}
		}
		
		// get user substags
		if(!empty($u['tagsubs'])){
			$tags = $u['tagsubs'];
			
			if($tags != '' && $tags != null && $tags != 'null' && !empty($tags) && sizeof($tags)>0 ){
				foreach($tags as $tag){
					if(!array_key_exists($tag,$favTags))
						$favTags[$tag] = $scoreMatrix[0];
					else
						$favTags[$tag] = $favTags[$tag] + $scoreMatrix[0];
				}
				
			}
		}
		
		
		var_dump($favCats);
		var_dump($favTags);
		
		
		// SAVE
		$userColl->update(array("_id"=> $u['_id']), array('$set'=>array("stats"=>array("cats"=>$favCats,"tags"=>$favTags))));
		
	}
	
}
	
	
	

	
	
?>


