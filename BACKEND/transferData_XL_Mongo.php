<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
/* Access Exalead index in GET, and return a JSON object
 * 
 * if an address is detected by EXALEAD, we apply a logic to find out the more significant address
 * The input from Exalead has the following facets : all are ARRAYS (more than one location can be detected in the news)
 * adressetitle : an address : 3, rue Sufflot in the title of the news
 * adressetext : same but in the text of the news
 * yakdicotitle : an entity found in the yakdico ( bois de Boulogne .... )
 * yakdicotext :  same in the text of the news
 * arrondissementtitle : an arrondissement like VIe or 6鮥 arrondissement
 * arrondissemementtext : same in the text of the news
 * quartiertitle : like : "quartier Latin"
 * quartiertext : same in the text of the news 
 * Persons and Organizations are stored has freeTags 
 * 
 * 
 * the logic is the following :
 * the title has the priority. We take the text only if title is empty.
 * first we check the adresse, if empty we look at the yakdico than at the arrondissement and finally the quartier.
 * 
 * Data enrichment:
 * first we make a screenshot of the article with apercite api
 * second we get the XY with a call to GMAP
 * we call GMAP only if we do not have the location in our PLACE collection ( to spare calls to gmap)
 * after a call to gmap we store in our db the result for next time
 * 
 * Every unsuccefull call to gmap is logged with a status 10 in the INFO => to go in the admin interface
 * 
 * 
 * 
 *  we check in db if we have the address
 * if not, we call gmal and we store the result in db 
 * 
 * 
 * */

ini_set ('max_execution_time', 0);
set_time_limit(0);
ini_set('display_errors',1);
require_once("../LIB/library.php");

$m = new Mongo(); 
$db = $m->selectDB("yakwala");
$infoColl = $db->info;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$logCallToGMap = 0;
$logLocationInDB = 0;
$logDataInserted = 0;
$logDataUpdated = 0;
$yakType = 1; // actu
$yakCatName = array('Actualiés'); // actu
$yakCatId = array(); 
$placeArray = array(); // array of goeloc : ['lat'=>,'lng'=>,'_id'=>]
$persistDays =  3;
$flagForceUpdate = (empty($_GET['forceUpdate']))?0:1;
$flagShowAllText = (empty($_GET['showAllText']))?0:1;
$daysBack = 15;//We only get the last X days
if(!empty($_GET['q'])){
    
    if($flagForceUpdate)
        echo "<br> <b>WARNING :</b> You are forcing update : we will always call GMAP for the location and any record in INFO will be updated.";
            
              
    $q = $_GET['q']; 
    switch( $q ){
        case 'leparisien75':
			$yakType =  1;
			$yakCatName = array('Actualités');
			$persistDays =  3;
		break;
        case 'telerama':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture');
			$persistDays =  10;
        break;
		case 'concertandco':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture','Musique','Concert');
			$persistDays =  7;
		break;
        case 'expo-a-paris':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture','Exposition');
			$persistDays =  7;
		break;
        case 'paris-bouge':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture');
			$persistDays =  180;
		break;
        case 'sortir-a-paris':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture');
			$persistDays =  7;
		break;
        case 'figaro-culture':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture');
			$persistDays =  7;
		break;
        case 'exponaute':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture','Exposition');
			$persistDays =  14;
		break;
        case 'agenda-culturel-75':
			$yakType =  2; // agenda
			$yakCatName = array('Actualités','Culture');
			$persistDays =  14;
        break;
		case 'france3-faitsdivers':
			$yakType =  1; // actu
			$yakCatName = array('Actualités');
			$persistDays =  3;
        break;
	}
	
	$defaultGeoloc = array(48.851875,2.356374);  // PARIS location @TODO softcode this
	$defaultPlaceId = '50517fe1fa9a95040b000007';  // PARIS PLACE ID @TODO softcode this
	
	$geolocYakCatId = "504d89f4fa9a958808000001"; // GEOLOC : @TODO softcode this
	
	echo '<br> Default location of the feed : Paris';
	$searchDate = date('Y/m/d',(mktime()-86400*$daysBack));
	$url = "http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com:62010/search-api/search?q=%23all+AND+document_item_date%3E%3D".$searchDate."+AND+source%3D".$q."&of=json&b=0&hf=1000&s=document_item_date";
	
	echo '<br>Days back : '.$daysBack.'<br> URL CALLED : '.$url.'<br>';
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	$result = curl_exec($ch);
	curl_close($ch);
	//var_dump($result);
	$json = json_decode($result);
	//print_r(json_encode($json->hits[0]));
	$hits = $json->hits;
	
	$item = 0;
	foreach($hits as $hit){
		//if($item > 8 )
		// exit;
		$item ++;
		
		$adresse = array();
		$adressetitle = array();
		$adressetitleTMP = array();
		$adressetext = array();
		$arrondissement = array();
		$arrondissementtitle = array();
		$arrondissementtext = array();
		$quartier = array();
		$quartiertitle = array();
		$quartiertext = array();
		$yakdico = array();
		$yakdicotitle = array();
		$yakdicotext = array();
		$locationTmp = array();
		$geolocGMAP = array();
		$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
		$freeTag = "";
		echo '<hr>';
		$metas = $hit->metas;
		$groups = $hit->groups;
		// fetch metas
		//echo "----metas:<br>";
		foreach($metas as $meta){
			//echo '<br><b>'.$meta->name.'</b>='.$meta->value;
			if($meta->name == "item_title")
				  $title = $meta->value;
			if($meta->name == "item_desc")
				  $content = $meta->value;
			if($meta->name == "title")
				  $titleXL = $meta->value;
			if($meta->name == "text")
				  $contentXL = $meta->value;
			if($meta->name == "item_date")
				  $datePub = $meta->value;
			if($meta->name == "url")
				  $outGoingLink = $meta->value;
			
			  
		}
		echo "<br><b>".$title."</b> ( ".$datePub." )<br>";
		
		if($flagShowAllText == 1){
		  echo "<br><b>Title XL : </b>".$titleXL."<br><b>Content : </b>".$content."<br><b>Content XL : </b>".$contentXL."<br><a target='_blank' href='".$outGoingLink."'>More</a><br> ";
		}
		
		// fetch annotations
		//echo "<br><br>----annotations:<br>";
		foreach($groups as $group){
			 //echo '<br><b>'.$group->id.'</b>='.sizeof($group->categories);
			 foreach($group->categories as $category){
				//var_dump($category);
				 //echo '<br>'.$category->title;
				 if($group->id == "adressetitle"){
					$flagIncluded = 0;
					foreach($adressetitle as $adr){
					   if( preg_match("/".$adr."/",$category->title) > 0 || preg_match("/".$category->title."/",$adr) > 0)
						   $flagIncluded++;
					}
					if($flagIncluded == 0)
					   $adressetitle[]= $category->title;
				 }

				 if($group->id == "adressetext"){
					$flagIncluded = 0;
					foreach($adressetext as $adr){
					   if( preg_match("/".$adr."/",$category->title) > 0 || preg_match("/".$category->title."/",$adr) > 0)
						   $flagIncluded++;
					}
					if($flagIncluded == 0)
					   $adressetext[]= $category->title;
				 }
				 
				 // any address in the title has the priority,
				 if(sizeof($adressetitle) > 0){
					if(sizeof($adressetext) > 0){//  but need to check if the text has not the same address but more precise
						$adressetitleTMP = array();
						foreach($adressetitle as $adrtitle){
							foreach($adressetext as $adrtext){
								if( preg_match("/".$adrtitle."/",$adrtext) > 0)
									$adressetitleTMP[] = $adrtext;  
							}
						}
					if(sizeof($adressetitleTMP) > 0)
					   $adressetitle = $adressetitleTMP;
					}
					$adresse = $adressetitle;
				 }else
					$adresse = $adressetext;
				 
				 /*QUARTIER*/
				 if($group->id == "quartiertitle")
					$quartiertitle = $category->title;
				 if($group->id == "quartiertext")
					$quartiertext = $category->title;
				 if(empty($quartiertitle))
					$quartier = $quartiertext; 
				 else
					$quartier = $quartiertitle; 
					  
				 /*ARRONDISSEMENT*/   
				 if($group->id == "arrondissementtitle")
					   $arrondissementtitle = $category->title;
				 if($group->id == "arrondissementtext")
					   $arrondissementtext = $category->title;
				 if(empty($arrondissementtitle))
					$arrondissement = $arrondissementtext; 
				 else
					$arrondissement = $arrondissementtitle; 
					
				 /*YAKWALA DICO*/
				 if($group->id == "yakdicotitle")
					   $yakdicotitle = $category->title;
				 if($group->id == "yakdicotext")
					   $yakdicotext = $category->title;
				 if(empty($yakdicotitle))
					$yakdico = $yakdicotext; 
				 else
					$yakdico = $yakdicotitle; 
					
				 /*OTHER CAT*/   
				 if($group->id == "Person_People")
					   $freeTag[]= $category->title;
								
				 if($group->id == "Organization")
					   $freeTag[]= $category->title;
				 
				 if($group->id == "Event")
					   $freeTag[]= $category->title;
									
			 }
		}
			
		
		
		//logical construction of the address :
		/*Priority :  ADDRESSE -> YAKDICO -> ARRONDISSEMENT -> QUARTIER*/
		if(!empty($adresse)){
			if(is_array($adresse))
			   foreach($adresse as $ad)
				   $locationTmp[] = $ad;
			else   
				$locationTmp[] = $adresse;
		}else{
			if(!empty($yakdico)){
				if(is_array($yakdico))
					foreach($yakdico as $dico)
						$locationTmp[] = $dico;
			else   
					$locationTmp[] = $yakdico;
			}else{    
				if(!empty($arrondissement)){
					if(is_array($arrondissement))
						foreach($arrondissement as $arr)
							$locationTmp[] = rewriteArrondissementParis($arr);
					else   
						$locationTmp[] = rewriteArrondissementParis($arrondissement);
				}else{
					if(!empty($quartier)){
						if(is_array($quartier))
							foreach($quartier as $quar)
								$locationTmp[] = $quar;
					else 
					 $locationTmp[] = $quartier;
					}
				}
			}
		}
			 
		$placeArray = array();	 
		// if there is a valid address, we get the location, first from db PLACE and if nothing in DB we use the gmap api
		if(sizeof($locationTmp ) > 0){
			
			foreach($locationTmp as $loc){
				echo "<br>Location found by XL : ".$loc;
				//check if in db
				$place = $placeColl->findOne(array('title'=>$loc,"status"=>1));
				if($place && $flagForceUpdate != 1){ // FROM DB
					echo "<br> Location found in DB !";
					$logLocationInDB++;
					//$geoloc[] = array($place['location']['lat'],$place['location']['lng']);
					$status = 1;
					$print = 1;
					$placeArray[] = array('_id'=>$place['_id'],'lat'=>$place['location']['lat'],'lng'=>$place['location']['lng']);	
				 }else{    // FROM GMAP
					echo "<br> Call to GMAP: ".$loc.', Paris, France';
					$logCallToGMap++;
					$resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($loc.', Paris, France'))),'PHP',1);
					//$resGMap =  array(48.884134,2.351761);
					//var_dump($resGMap);
					echo '___<br>';
					if(!empty($resGMap)){
						echo "<br> GMAP found the coordinates of this location ! ";
						$status = 1;
						$print = 1;
						$geolocGMAP = $resGMap['location'];
						$addressGMAP = $resGMap['address'];
					}else{
						echo "<br> GMAP did not succeed to find a location, we store the INFO in db with status 10.";
						$status = 10;
						$geolocGMAP = array(0,0);
						$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
						$print = 0;
					} 
					// we store the result in PLACE for next time
					
					$place = array(
								"title"=> $loc,
								"content" =>"",
								"thumb" => "",
								"origin"=>$q,    
								"access"=> 2,
								"licence"=> "Yakwala",
								"outGoingLink" => "",
								"yakCat" => array(new MongoId($geolocYakCatId)), 
								"creationDate" => new MongoDate(gmmktime()),
								"lastModifDate" => new MongoDate(gmmktime()),
								"location" => array("lat"=>$geolocGMAP[0],"lng"=>$geolocGMAP[1]),
								"status" => $status,
								"user" => 0,
								"zone"=> 1,
								"address" => $addressGMAP,
							  );
							  
							  
					$res = $placeColl->findOne(array('title'=>$loc));
					//echo '---<br>';
					//var_dump($res);
					//echo '---<br>';
					if(empty($res)){// The place is not in db
						echo "<br> The location does not exist in db, we create it.";
						$placeColl->save($place); 
						$placeColl->ensureIndex(array("location"=>"2d"));
					}else{ // The place already in DB, we update if the flag tells us to
						if($flagForceUpdate ==  1){
							echo "<br> The location exists in db and we update it.";
							$placeColl->update(array("_id"=> $res['_id']),$place); 
							$placeColl->ensureIndex(array("location"=>"2d"));
						}else
						   echo "<br> The location exists in db => doing nothing.";
					}
					$placeArray[] = array('_id'=>$res['_id'],'lat'=>$geolocGMAP[0],'lng'=>$geolocGMAP[1]);
				 
				 }         
			}
			
		
			
		}else{
			if(sizeof($adresse)==0 && sizeof($yakdico)==0 && sizeof($arrondissement)==0 && sizeof($quartier)==0){
				//echo "No interesting location detected by Exalead. The info is not transfered to Mongo.";
				// here we can choose to add the info in the db for the fils d'actu...
				echo "No interesting location detected by Exalead. The info is transfered to Mongo with the feed's default location and the print flag to 0.";
			}else{
			 echo "Address no significative enough to find a localization : 
			 <br>adresse= ".implode(',',$adresse)."
			 <br>yakdico= ".implode(',',$yakdico)."
			 <br>arrondissement = ".implode(',',$arrondissement)."
			 <br>quartier = ".implode(',',$quartier);

			}
			$print = 0;
			$geoloc = array($defaultGeoloc);
			$status = 1;
			$placeArray[] = array('_id'=>$defaultPlaceId,'lat'=>$defaultGeoloc[0],'lng'=>$defaultGeoloc[1]);	
		}
		
	
		// CONVERT YAKCAT TO AN ARRAY OF _ID
		$yakCatId = array();
		foreach($yakCatName as $cat){
			$catId = $yakcatColl->findOne(array('title'=>$cat));
			$yakCatId[] = new MongoId($catId['_id']);
		}
	
		// NOTE:  WE INTRODUCE MULTIPLE INFO IF WE HAVE MULTIPLE LOCATIONS
		$i = 0;
		
		//var_dump($placeArray);
	
		foreach($placeArray as $geolocItem){
			
			$datePubArray1 = explode(' ',$datePub);
			$datePubArrayD = explode('/',$datePubArray1[0]);
			$datePubArrayT = explode(':',$datePubArray1[1]);
			
			$tsPub = gmmktime($datePubArrayT[0],$datePubArrayT[1],$datePubArrayT[2],$datePubArrayD[0],$datePubArrayD[1],$datePubArrayD[2]);
			echo "<br>time: ".$datePubArrayT[0]."-".$datePubArrayT[1]."-".$datePubArrayT[2]."-".$datePubArrayD[0]."-".$datePubArrayD[1]."-".$datePubArrayD[2];
			$info = array();
			$info['title'] = $title;
			$info['content'] = $content;
			$info['outGoingLink'] = $outGoingLink;
			$thumb = getApercite($outGoingLink);
			$info['thumb'] = $thumb;
			$info['origin'] = $q;
			$info['access'] = 2;
			$info['licence'] = "reserved";
			$info['heat'] = "80";
			$info['yakCat'] = $yakCatId;
			$info['yakType'] = $yakType; // actu
			$info['freeTag'] = $freeTag;
			$info['pubDate'] = new MongoDate($tsPub);
			$info['creationDate'] = new MongoDate(gmmktime());
			$info['lastModifDate'] = new MongoDate(gmmktime());
			$info['dateEndPrint'] = new MongoDate(gmmktime()+$persistDays*86400); // 
			$info['print'] = $print;
			$info['status'] = $status;
			$info['user'] = 0;
			$info['zone'] = 1;
			$info['location'] = array("lat"=>$geolocItem['lat'],"lng"=>$geolocItem['lng']);
			$info['address'] = (!empty($locationTmp[$i++])?$locationTmp[$i++]:"");
			$info['placeId'] = new MongoId($geolocItem['_id']);
			
			// check if data is not in DB
			$dataExists = $infoColl->findOne(array("title"=>$title,"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035)));
			//var_dump($dataExists);
			if(empty($dataExists)){
				echo "<br> The info does not exist in DB, we insert it.";
				// we check if there is another info printed at this point :
				$dataCount = 0;
				$dataCount = $infoColl->count(array("location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035)));
				//$dataDebug = $infoColl->find(array("location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035)));
				//var_dump(iterator_to_array($dataDebug));
				
				//echo $dataCount.'azerty<br>';  
				// if more than one info on the same location
				if($dataCount > 0 && $print ==  1){
					$lepas = ceil($dataCount/12);
					$info['location'] = array("lat"=>(0.000015*sin(3.1415*$dataCount/6)+$geolocItem['lat']),"lng"=>(0.00002*cos(3.1415*$dataCount/6)+$geolocItem['lng']));
				}
				   
				$infoColl->insert($info,array('fsync'=>true));
				$infoColl->ensureIndex(array("location"=>"2d"));
				$logDataInserted++;    
			}else{
				if($flagForceUpdate == 1){
				  echo "<br> The info exists in DB, we force the update.";
				  $info['lastModifDate'] = new MongoDate(gmmktime());
				  $infoColl->update(array("_id"=> $dataExists['_id']),$info);
				  $infoColl->ensureIndex(array("location"=>"2d"));
				  $logDataUpdated++;
				}else
				  echo "<br> The info exists in DB => doing nothing.";    
			}
		}
	}   
	
	
    
    $log = "<br><br><br><br><br>===BACTH SUMMARY====<br>Total data parsed : ".$item.".<br> Total Data inserted: ".$logDataInserted.".<br> Total Data updated :".$logDataUpdated." (call &forceUpdate=1 to update)   <br>Call to gmap:".$logCallToGMap.". <br>Locations found in Yakwala DB :".$logLocationInDB."<br><br><br>";

    echo $log;
    
$batchlogColl->save(
    array(
    "batchName"=>$_SERVER['PHP_SELF'],
    "datePassage"=>new MongoDate(gmmktime()),
    "dateNextPassage"=>new MongoDate(2143152000), // far future = one shot batch
    "log"=>$log,
    "status"=>1
    ));
    
    
}

    echo "no request<br>try this :";
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=leparisien75\"/>".$_SERVER['PHP_SELF']."?q=leparisien75</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=concertandco\"/>".$_SERVER['PHP_SELF']."?q=concertandco</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=expo-a-paris\"/>".$_SERVER['PHP_SELF']."?q=expo-a-paris</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=telerama\"/>".$_SERVER['PHP_SELF']."?q=telerama</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=figaro-culture\"/>".$_SERVER['PHP_SELF']."?q=figaro-culture</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=sortir-a-paris\"/>".$_SERVER['PHP_SELF']."?q=sortir-a-paris</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=paris-bouge\"/>".$_SERVER['PHP_SELF']."?q=paris-bouge</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=exponaute\"/>".$_SERVER['PHP_SELF']."?q=exponaute</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=agenda-culturel-75\"/>".$_SERVER['PHP_SELF']."?q=agenda-culturel-75</a>" ;
	echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=france3-faitsdivers\"/>".$_SERVER['PHP_SELF']."?q=france3-faitsdivers</a>" ;
    echo "<br>To print all text of the info add &showAllText=1";    
    


?>
</body>
</html>
