<?php

/* 
	Analyse YAKTRACK and create yaktag on user profile
	
	This batch access 2 dbs : yakwala and yaktrack
	
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
		$favTags = array("not tag"=>0);
		$favCats = array('no cat'=>0);
		echo '<br><hr>User: <b>'.$u['login'].' - id: </b>'.$u['_id'];
		
		$tracks = $trackColl->find(array('userid'=>$u['_id'],'actiondate'=>array('$gte'=>new Mongodate($tsLastWeek))));
		$tracks->sort(array('actiondate'=>-1));	
		foreach($tracks as $t){
			echo '<br> - '.$t['actionid'].' : ';
			
			switch($t['actionid']){
				
				case 6:
				case 7:
				case 8:
				case 14:
				case 10:	
					$theInfo = $infoColl->findOne(array('_id'=>new MongoId($t['params']['infoId'])));
					if(!empty($theInfo['yakCatName'])){
						foreach($theInfo['yakCatName'] as $cat){
							$cat = mb_strtolower($cat,'UTF-8');
							echo 'cat : '.$cat.'<br>';
							if(!array_key_exists($cat,$favCats))
								$favCats[$cat] = $scoreMatrix[$t['actionid']];
							else
								$favCats[$cat] = $favCats[$cat] + $scoreMatrix[$t['actionid']];
						}
					}
					if(!empty($theInfo['freeTag'])){
						foreach($theInfo['freeTag'] as $tag){
							$tag = mb_strtolower($tag,'UTF-8');
							echo 'tag : '.$tag.'<br>';
							if($tag != '' && $tag  != null && $tag  != 'null' && !empty($tag) && sizeof($tag)>0 && isset($tag)){
								if(!array_key_exists($tag,$favTags))
									$favTags[$tag] = $scoreMatrix[$t['actionid']];
								else
									$favTags[$tag] = $favTags[$tag] + $scoreMatrix[$t['actionid']];
							}
							var_dump($favTags);
						}
					}
					break;
				case 5:	
					
					$tags = mb_strtolower($t['params']['str'],'UTF-8');
					echo ' search string : '.$tags.'<br>';
					if($tags != '' && $tags != null && $tags != 'null' && !empty($tags) && sizeof($tags)>0 && isset($tags) ){
						if($tags[0]=="#")
							$tags = substr($tags,1,strlen($tags));
						
						if(!array_key_exists($tags,$favTags))
							$favTags[$tags] = $scoreMatrix[$t['actionid']];
						else
							$favTags[$tags] = $favTags[$tags] + $scoreMatrix[$t['actionid']];
					}
					break;
				case 11:
				case 12:
					if(!empty($t['params'])){
						$tags = mb_strtolower($t['params']['tags'],'UTF-8');
						echo ' tags : '.$tags.'<br>';
						if($tags != '' && $tags != null && $tags != 'null' && !empty($tags) && sizeof($tags)>0 && isset($tags)){
							foreach($tags as $tag){
								if(!array_key_exists($tag,$favTags))
									$favTags[$tag] = $scoreMatrix[$t['actionid']];
								else
									$favTags[$tag] = $favTags[$tag] + $scoreMatrix[$t['actionid']];
							}
							
						}
					}
					
					
				
					break;
				default:
					echo 'not taken';
			}
			
		}
		
			
		// STATIC DATA : 
		// get user's tags :
		if(!empty($u['tag'])){
			$tags = mb_strtolower($u['tag'],'UTF-8');
			if($tags != '' && $tags != null && $tags != 'null' && !empty($tags) && sizeof($tags)>0 && isset($tags) && $tags[0] != ''){
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
			if($tags != '' && $tags != null && $tags != 'null' && !empty($tags) && sizeof($tags)>0 && isset($tags) && $tags[0] != ''){
				foreach($tags as $tag){
					$tag = mb_strtolower($tag,'UTF-8');
					if(!array_key_exists($tag,$favTags))
						$favTags[$tag] = $scoreMatrix[0];
					else
						$favTags[$tag] = $favTags[$tag] + $scoreMatrix[0];
				}			
			}
		}
		
		echo '<br><hr><br>';
		echo '<b>favCats:</b><br>';
		var_dump($favCats);
		echo '<br><b>favTags:</b><br>';
		var_dump($favTags);
		
		
		// SAVE
		$userColl->update(array("_id"=> $u['_id']), array('$set'=>array("stats"=>array("cats"=>$favCats,"tags"=>$favTags))));
		
	}
	
}
	
	
	

	
	
?>


