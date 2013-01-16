<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 
/* Access Exalead index in GET, and return a JSON object
 * 
 * if an location is detected by EXALEAD, we apply a logical process to find out the more significant address
 * The input from Exalead has the following facets : all are ARRAYS (more than one location can be detected in the news)
 * adressetitle : an address : 3, rue Sufflot in the title of the news
 * adressetext : same but in the text of the news
 * yakdicotitle : an entity found in the yakdico ( bois de Boulogne .... )
 * yakdicotext :  same in the text of the news
 * arrondissementtitle : an arrondissement like VIe or 6鮥 arrondissement
 * arrondissemementtext : same in the text of the news
 * quartiertitle : like : "quartier Latin"
 * quartiertext : same in the text of the news 
 * villetitle 
 * villetext
 * Persons and Organizations are stored has freeTags 
 * 
 * 
 * the logic is the following :
 * the title has the priority. We take the text only if title is empty.
 * The smaller entity ( = the more precise ) is more significant : 
 * first we check the adresse, if empty we look at the yakdico than at the quartierand finally the arrondissement  and after the city.
 * if no address found by xl, we try the place
 * Data process :
 * we get the enclosed image and resize it.
 * if no image, we make a screenshot of the article with apercite api
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


require_once("../LIB/conf.php");
$conf = new conf();

$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$infoColl = $db->info;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;
$feedColl = $db->feed;
$logCallToGMap = 0;
$logLocationInDB = 0;
$logDataInserted = 0;
$logDataUpdated = 0;
$logDataAlreadyInDB = 0;

$yakCatId = array(); 
$placeArray = array(); // array of goeloc : ['lat'=>,'lng'=>,'_id'=>]
$flagForceUpdate = (empty($_GET['forceUpdate']))?0:1;
$flagShowAllText = (empty($_GET['showAllText']))?0:1;
$geolocYakCatId = "504d89f4fa9a958808000001"; // YAKCAT GEOLOC : @TODO softcode this

    
    if($flagForceUpdate)
        echo "<br> <b>WARNING :</b> You are forcing update : we will always call GMAP for the location and any record in INFO will be updated.";
            
              
    $q = (empty($_GET['q']))?"":$_GET['q']; 
	
		if(!empty($q) || $_GET['q'] == 'all'){
		
		if($q == 'all') // we parse all valid feeds
			$query = array('status'=>1);
		else
			$query = array('name'=>$q,'status'=>1);
		
		$feeds = $feedColl->find($query);
		
		foreach ($feeds as $feed) {
			
			// get default PLACE
			$defaultPlace = $placeColl->findOne(array('_id'=>$feed['defaultPlaceId']));
			
			//var_dump($defaultPlace);
			echo '<br> Parsing feed: <b>'.$feed['name'].'</b>';
			echo '<br> Default location of the feed : <b>'.$defaultPlace['title'].'</b>';
			$searchDate = date('Y/m/d',(mktime()-86400*$feed['daysBack']));
			$url = "http://ec2-54-246-84-102.eu-west-1.compute.amazonaws.com:62010/search-api/search?q=%23all+AND+document_item_date%3E%3D".$searchDate."+AND+source%3D".$feed['name']."&of=json&b=0&hf=1000&s=document_item_date";
			
			echo '<br> Days back : <b>'.$feed['daysBack'].'</b>';
			echo '<br> Url called : <b>'.$url.'</b>';
			
			
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
			$result = curl_exec($ch);
			curl_close($ch);
			$json = json_decode($result);
			$hits = $json->hits;
			
			echo '<br><br>Fetching news...<br>';
			$item = 0;
			foreach($hits as $hit){
				
				$item ++;
				$lieu = array();
				$lieutitle = array();
				$lieutext = array();
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
				$ville = array();
				$villetitle = array();
				$villetext = array();
				$enclosure = "";
				$geolocationInput = array();
				$addressInput = "";
				$yakcatInput = array();
				$placeInput = array();
				$locationTmp = array();
				$eventDateInput = array();
				$geolocGMAP = array();
				$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
				$freeTag = array();
				$title = "";
				$content = "";
				$titleXL = "";
				$contentXL = "";
				
				
				$metas = $hit->metas;
				$groups = $hit->groups;
				// fetch metas
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
					
					if($meta->name == "item_eventdate"){
						  $eventDateInputTMP = explode('|',trim($meta->value));
						  foreach($eventDateInputTMP as $val)
							$eventDateInput[]= explode('#',$val);
					}
					
					if($meta->name == "publicurl")
						  $outGoingLink = $meta->value;
					if($meta->name == "image_enclosure")
						  $enclosure = $meta->value;
					if($meta->name == "item_geolocation")
						  $geolocationInput = explode('#',trim($meta->value));
					if($meta->name == "item_address")
						  $addressInput = $meta->value;
					if($meta->name == "item_yakcat")
						  $yakcatInput = explode('#',trim($meta->value));
					if($meta->name == "item_place")
						  $placeInput = trim($meta->value);
						
					
				}
				
				
				
				echo "<br><br>*******************************************************************************<br>";
				echo "<b>".$title."</b> ( ".$datePub." )<br>";
				
				if($flagShowAllText == 1){
				  echo "<br><b>Title XL : </b>".$titleXL."<br><b>Content : </b>".$content."<br><b>Content XL : </b>".$contentXL."<br><a target='_blank' href='".$outGoingLink."'>More</a><br> ";
				}
				
				
				// fetch annotations
				//echo "<br><br>----annotations:<br>";
				foreach($groups as $group){
					 //echo '<br><b>'.$group->id.'</b>='.sizeof($group->categories);
					 foreach($group->categories as $category){
						 /*city*/
						 if($group->id == "villetitle"){
							$flagIncluded = 0;
							foreach($villetitle as $vl){
							   if( preg_match("/".$vl."/",$category->title) > 0 || preg_match("/".$category->title."/",$vl) > 0)
								   $flagIncluded++;
							}
							if($flagIncluded == 0)
							   $villetitle[]= $category->title;
						 }
						 
						 if($group->id == "villetext"){
							$flagIncluded = 0;
							foreach($villetext as $vl){
							   if( preg_match("/".$vl."/",$category->title) > 0 || preg_match("/".$category->title."/",$vl) > 0)
								   $flagIncluded++;
							}
							if($flagIncluded == 0)
							   $villetext[]= $category->title;
						 }
						 
						 // any city in the title has the priority,
						 if(sizeof($villetitle) > 0){
							if(sizeof($villetext) > 0){//  but need to check if the text has not the same address but more precise
								$villetitleTMP = array();
								foreach($villetitle as $adrtitle){
									foreach($villetext as $adrtext){
										if( preg_match("/".$adrtitle."/",$adrtext) > 0)
											$villetitleTMP[] = $adrtext;  
									}
								}
							if(sizeof($villetitleTMP) > 0)
							   $villetitle = $villetitleTMP;
							}
							$ville = $villetitle;
						 }else
							$ville = $villetext;
						  
						 /*address*/
						 if($group->id == "adressetitle3"){
							$flagIncluded = 0;
							foreach($adressetitle as $adr){
							   if( preg_match("/".$adr."/",$category->title) > 0 || preg_match("/".$category->title."/",$adr) > 0)
								   $flagIncluded++;
							}
							if($flagIncluded == 0)
							   $adressetitle[]= $category->title;
						 }

						 if($group->id == "adressetext3"){
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
						 if($group->id == "quartiertitle2")
							$quartiertitle = $category->title;
						 if($group->id == "quartiertext2")
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
						
						 /*LIEU*/
						 if(empty($placeInput)){
							 if($group->id == "placetitle")
								   $lieutitle = $category->title;
							 if($group->id == "placetext")
								   $lieutext = $category->title;
							 if(empty($lieutitle))
								$lieu = $lieutext; 
							 else
								$lieu = $lieutitle; 
						 }else
							$lieu = $placeInput;
							
						 /*OTHER CAT*/   
						 if($group->id == "Person_People")
							   $freeTag[]= yakcatPathN($category->title,0);
										
						 if($group->id == "Organization")
							   $freeTag[]= yakcatPathN($category->title,0);
						 
						 if($group->id == "Event")
							   $freeTag[]= yakcatPathN($category->title,0);
						
						
					 }
				}
					
				
				
				//logical construction of the address :
				/*Priority :  ADDRESSINPUT -> ADDRESSE -> YAKDICO -> ARRONDISSEMENT -> QUARTIER -> VILLE*/
				 // set address from input or from semantic factory
				 if(!empty($addressInput) && !empty($geolocationInput)){
					$locationTmp[] = $addressInput;
				 }else{
					if(!empty($adresse)){
						if(is_array($adresse))
						   foreach($adresse as $ad)
							   $locationTmp[] = $ad;
						else   
							$locationTmp[] = $adresse;
					}else{	
						if(!empty($lieu)){
								if(is_array($lieu))
									foreach($lieu as $l)
										$locationTmp[] = $l;
								else   
									$locationTmp[] = $lieu;
						}else{
							if(!empty($yakdico)){
								if(is_array($yakdico))
									foreach($yakdico as $dico)
										$locationTmp[] = $dico;
								else   
									$locationTmp[] = $yakdico;
							}else{    
								if(!empty($quartier)){
										if(is_array($quartier))
											foreach($quartier as $quar)
												$locationTmp[] = $quar;
										else 
											$locationTmp[] = $quartier;
								}else{
									if(!empty($arrondissement)){
									if(is_array($arrondissement))
										foreach($arrondissement as $arr)
											$locationTmp[] = rewriteArrondissement($arr);
									else   
										$locationTmp[] = rewriteArrondissement($arrondissement);
									}else{
										if(is_array($ville))
											foreach($ville as $vil)
												$locationTmp[] = $vil;
										else 
											$locationTmp[] = $ville;
									}
								}
							}
						}
					}
				}
				
				// la ville
				$laville = '';
				if(sizeof($ville) > 0){
					if(is_array($ville))
						$laville = $ville[0];
					else 
						$laville = $ville;
				}
				
				
				
				$placeArray = array();	 
				// if there is a valid address, we get the location, first from db PLACE and if nothing in DB we use the gmap api
				if(sizeof($locationTmp ) > 0){
					
					foreach($locationTmp as $loc){
						echo "<br><b style='background-color:#00FF00;'>Location found by XL :</b> ".$loc;
						
						//check if in db if the place exists
						$place = $placeColl->findOne(array('title'=>$loc,"zone"=>$defaultPlace['zone']));
						//var_dump($place);
						if($place && $flagForceUpdate != 1){ // FROM DB
							echo "<br> Location found in DB !";
							$logLocationInDB++;
							if($place['status'] == 3){ // if the place has been blacklisted by the operator
								$status = 11; // alert status
								$print = 0; // don't print on the map, but can be printed on the news feed
							}else{
								$status = 1;
								$print = 1;
							}
							$placeArray[] = array('_id'=>$place['_id'],'lat'=>$place['location']['lat'],'lng'=>$place['location']['lng'],'address'=>$place['formatted_address'],'status'=>$status,'print'=>$print);	
						 }else{ // the place is not in db
							
							// FROM THE INPUT
							if(!empty($geolocationInput) && !empty($addressInput)){
								$status = 1;
								$geolocGMAP = array((float)$geolocationInput[0],(float)$geolocationInput[1]);
								$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
								$print = 1;
								$formatted_addressGMAP = $addressInput;
							}else{ // FROM GMAP
								echo "<br> Call to GMAP: ".$loc.', '.$defaultPlace['title'].', '.$defaultPlace['address']['country'];
								$logCallToGMap++;
								if(empty($lieu))
									$resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($loc.( (strlen($laville)> 0 && $laville != $defaultPlace['title'] ) ? ', '.$laville:'').', '.$defaultPlace['title'].'. '.$defaultPlace['address']['country']))),'PHP',1);
								else
									$resGMap = getPlaceGMap(urlencode(utf8_decode(suppr_accents($loc.( (strlen($laville)> 0 && $laville != $defaultPlace['title'] ) ? ', '.$laville:'').', '.$defaultPlace['title'].'. '.$defaultPlace['address']['country']))),'PHP',1);
								echo '___<br>';
								if(!empty($resGMap) &&  $resGMap['formatted_address'] != $defaultPlace['title'].', '.$defaultPlace['address']['country']){
									echo "<br> GMAP found the coordinates of this location ! ";
									// check if the result is in the zone
									$zoneObj = new Zone();
									$zoneNums = $zoneObj->findNumByLocation(array('lat'=>$resGMap['location'][0],'lng'=>$resGMap['location'][1]));
									if(!in_array($feed['zone'],$zoneNums)){
										echo "<br><b>Err:</b>Location found is not in the feed zone ( ".$feed['zone']." )";
										$status = 12;
										$print = 0;
									}else{
										$status = 1;
										$print = 1;
									}
									$geolocGMAP = $resGMap['location'];
									$addressGMAP = $resGMap['address'];
									$formatted_addressGMAP = $resGMap['formatted_address'];
								}else{
									echo "<br> GMAP did not succeed to find a location, we store the INFO in db with status 10.";
									$status = 10;
									$geolocGMAP = array(0,0);
									$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
									$print = 0;
									$formatted_addressGMAP = "";
								} 
							}	
							
							/*ONLY FOR TEST DELETE IN PRODUCTION THE SAVE OF THE PLACE FROM ADDRESS*/
							// we store the result in PLACE for next time
							
							$place = array(
										"title"=> $loc,
										"content" =>"",
										"thumb" => "",
										"origin"=>$feed['humanName'],    
										"access"=> 2,
										"licence"=> "Yakwala",
										"outGoingLink" => $feed['link'],
										"yakCat" => array(new MongoId($geolocYakCatId)), 
										"creationDate" => new MongoDate(gmmktime()),
										"lastModifDate" => new MongoDate(gmmktime()),
										"location" => array("lat"=>$geolocGMAP[0],"lng"=>$geolocGMAP[1]),
										"status" => $status,
										"user" => 0,
										"zone"=> $defaultPlace['zone'],
										"address" => $addressGMAP,
										"formatted_address" => $formatted_addressGMAP,
									  );
									  
									  
							$res = $placeColl->findOne(array('title'=>$loc,"status"=>1,"zone"=>$defaultPlace['zone']));
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
							$placeArray[] = array('_id'=>$res['_id'],'lat'=>$geolocGMAP[0],'lng'=>$geolocGMAP[1],'address'=>$formatted_addressGMAP,'status'=>$status,'print'=>$print);
						 
						 }         
					}
					
				
					
				}else{
					/*
					//is there a place found by XL ?
					if(!empty($lieu)){
						
						echo "<br><b style='background-color:#00FF00;'>Place found by XL :</b> ".$lieu;
						
						//check if in db if the place exists
						$place = $placeColl->findOne(array('title'=>$lieu,"zone"=>$defaultPlace['zone']));
						//var_dump($place);
						if($place && $flagForceUpdate != 1){ // FROM DB
							echo "<br> Place found in DB !";
							$logLocationInDB++;
							if($place['status'] == 3){ // if the place has been blacklisted by the operator
								$status = 11; // alert status
								$print = 0; // don't print on the map, but can be printed on the news feed
							}else{
								$status = 1;
								$print = 1;
							}
							$placeArray[] = array('_id'=>$place['_id'],'lat'=>$place['location']['lat'],'lng'=>$place['location']['lng'],'address'=>$place['formatted_address'],'status'=>$status,'print'=>$print);	
						 }else{ // the place is not in db
							// FROM THE INPUT
							if(!empty($geolocationInput) && !empty($placeInput)){
								$status = 1;
								$geolocGMAP = array((float)$geolocationInput[0],(float)$geolocationInput[1]);
								$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
								$print = 1;
								$formatted_addressGMAP = $addressInput;
							}else{ // FROM GMAP PLACE API
								echo "<br> Call to GOOGLE PLACE API: ".$lieu;
								$logCallToGMap++;
								$resGMap = getPlaceGMap(urlencode(utf8_decode(suppr_accents($lieu.( (strlen($laville)> 0 && $laville != $defaultPlace['title'] ) ? ', '.$laville:'').', '.$defaultPlace['title'].'. '.$defaultPlace['address']['country']))),'PHP',1);
								echo '___<br>';
								if(!empty($resGMap) &&  $resGMap['formatted_address'] != $defaultPlace['title'].', '.$defaultPlace['address']['country']){
									echo "<br> GMAP found the coordinates of this location ! ";
									// check if the result is in the zone
									$zoneObj = new Zone();
									$zoneNums = $zoneObj->findNumByLocation(array('lat'=>$resGMap['location'][0],'lng'=>$resGMap['location'][1]));
									if(!in_array($feed['zone'],$zoneNums)){
										echo "<br><b>Err:</b>Location found is not in the feed zone ( ".$feed['zone']." )";
										$status = 12;
										$print = 0;
									}else{
										$status = 1;
										$print = 1;
									}
									$geolocGMAP = $resGMap['location'];
									$addressGMAP = $resGMap['address'];
									$formatted_addressGMAP = $resGMap['formatted_address'];
								}else{
									echo "<br> GMAP did not succeed to find a location, we store the INFO in db with status 10.";
									$status = 10;
									$geolocGMAP = array(0,0);
									$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
									$print = 0;
									$formatted_addressGMAP = "";
								} 
							}	
								// we store the result in PLACE for next time
							
							$place = array(
										"title"=> $lieu,
										"content" =>"",
										"thumb" => "",
										"origin"=>$feed['humanName'],    
										"access"=> 2,
										"licence"=> "Yakwala",
										"outGoingLink" => $feed['link'],
										"yakCat" => array(new MongoId($geolocYakCatId)), 
										"creationDate" => new MongoDate(gmmktime()),
										"lastModifDate" => new MongoDate(gmmktime()),
										"location" => array("lat"=>$geolocGMAP[0],"lng"=>$geolocGMAP[1]),
										"status" => $status,
										"user" => 0,
										"zone"=> $defaultPlace['zone'],
										"address" => $addressGMAP,
										"formatted_address" => $formatted_addressGMAP,
									  );
									  
									  
							$res = $placeColl->findOne(array('title'=>$lieu,"status"=>1,"zone"=>$defaultPlace['zone']));
							if(empty($res)){// The place is not in db
								echo "<br> The Place does not exist in db, we create it.";
								$placeColl->save($place); 
								
								$placeColl->ensureIndex(array("location"=>"2d"));
							}else{ // The place already in DB, we update if the flag tells us to
								if($flagForceUpdate ==  1){
									echo "<br> The place exists in db and we update it.";
									$placeColl->update(array("_id"=> $res['_id']),$place); 
									$placeColl->ensureIndex(array("location"=>"2d"));
								}else
								   echo "<br> The place exists in db => doing nothing.";
							}
							$placeArray[] = array('_id'=>$res['_id'],'lat'=>$geolocGMAP[0],'lng'=>$geolocGMAP[1],'address'=>$formatted_addressGMAP,'status'=>$status,'print'=>$print);
						 
						 }  
					}else*/
						{ // Nothing found by XL
						if(sizeof($lieu)==0 && sizeof($ville)==0 && sizeof($adresse)==0 && sizeof($yakdico)==0 && sizeof($arrondissement)==0 && sizeof($quartier)==0){
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
						
						// if feedflag tell us to put the info on the default feed location	
						if($feed['defaultPrintFlag'] != 2){
							$print = $feed['defaultPrintFlag'] ;
							$geoloc = array($defaultPlace['location']);
							$status = 1;
							$placeArray[] = array('_id'=>$defaultPlace['_id'],'lat'=>$defaultPlace['location']['lat'],'lng'=>$defaultPlace['location']['lng'],'address'=>$defaultPlace['title'],'status'=>$status,'print'=>$print);	
						}
					
					}

					
				}
				
			
				
				$yakCatIdArray = array();
				$yakCatId = array();
				$yakCatName = array();
				$yakCatIdArray = array_merge($yakcatInput,$feed['yakCatId']);
				foreach ($yakCatIdArray as $id) {
					$yc = ($yakcatColl->findOne(array('_id'=>new MongoId($id))));
					if(!empty($yc))$yakCatId[] = new MongoId($yc['_id']);
					$yakCatName[] = $yc['title'];
				}
			
				
				$eventDate = array();
				
				var_dump($eventDateInput);
				$i=0;
				foreach ($eventDateInput as $date) {
					
					
					
					$fixedDate = str_replace('.0Z','Z',$date[0]);
					$dateTimeFrom = DateTime::createFromFormat(DateTime::ISO8601, $fixedDate);
					$eventDate[$i]['dateTimeFrom'] = new MongoDate(date_timestamp_get($dateTimeFrom));

					$fixedDate = str_replace('.0Z','Z',$date[1]);
					$dateTimeEnd = DateTime::createFromFormat(DateTime::ISO8601, $fixedDate);
					$eventDate[$i]['dateTimeEnd'] = new MongoDate(date_timestamp_get($dateTimeEnd));
					
					$i++;
				}
			
				// get image
				
				if(!empty($enclosure)){
					$res = createImgThumb($enclosure,$conf);
					if($res == false)
							$thumb = getApercite($outGoingLink);
						else
							$thumb = 'thumb/'.$res;
				}else{
					if(!empty($content)){
						$img = array();
						$dom = new domDocument;
						$dom->loadHTML($content);
						$dom->preserveWhiteSpace = false;
						$images = $dom->getElementsByTagName('img');
						foreach ($images as $image) {
									
						$img[] =  $image->getAttribute('src');
						}
						if(sizeof($img) > 0 && $img[0] != '' ){
							$res = createImgThumb($img[0],$conf);
							
							if($res == false)
								$thumb = getApercite($outGoingLink);
							else
								$thumb = 'thumb/'.$res;
							
						}else
							$thumb = getApercite($outGoingLink);
					}
				}
				
				// catch keyword words in title
				if(!empty($title)){
					if (preg_match("/FOOT/i", $title) || preg_match("/FOOTBALL/i", $title)) {
							$yakCatId[] = new MongoId("50647e2d4a53041f91040000");
						}
					if (preg_match("/Tennis/i", $title)) {
							$yakCatId[] = new MongoId("50647e2d4a53041f91060000");
						}	
						
					if (preg_match("/MP 2013/i", $title) ) {
							$freeTag[] = "MP2013";
						}	
					// catch twitter hashtag in title
					$matches = array();
					if (preg_match_all('/#([^\s]+)/', $title, $matches)) {
							$freeTag = array_merge($freeTag,$matches[1]);
					}
				}
				
				
				
			
				
				// clean :
				$content = (!empty($content))?strip_tags($content):"";
				$title = strip_tags(trim($title));			
				
				
				// NOTE:  WE INTRODUCE MULTIPLE INFO IF WE HAVE MULTIPLE LOCATIONS
				$i = 0;
				foreach($placeArray as $geolocItem){
					
					
					if(!empty($title) &&!empty($geolocItem['lat']) && !empty($geolocItem['lng'])){
						
						
						$datePubArray1 = explode(' ',$datePub);
						$datePubArrayD = explode('/',$datePubArray1[0]);
						$datePubArrayT = explode(':',$datePubArray1[1]);
						
						$tsPub = gmmktime($datePubArrayT[0],$datePubArrayT[1],$datePubArrayT[2],$datePubArrayD[0],$datePubArrayD[1],$datePubArrayD[2]);
						echo "<br>time: ".$datePubArrayT[0]."-".$datePubArrayT[1]."-".$datePubArrayT[2]."-".$datePubArrayD[0]."-".$datePubArrayD[1]."-".$datePubArrayD[2];
						$info = array();
						$info['title'] = $title;
						$info['content'] = $content;
						$info['outGoingLink'] = $outGoingLink;
						$info['thumb'] = $thumb;
						$info['origin'] = $feed['humanName'];
						$info['originLink'] = $feed['link'];
						$info['access'] = 2;
						$info['licence'] = "reserved";
						$info['heat'] = "80";
						$info['yakCat'] = $yakCatId;
						$info['yakCatName'] = $yakCatName;
						$info['yakType'] = $feed['yakType']; // actu
						$info['freeTag'] = $freeTag;
						$info['pubDate'] = new MongoDate($tsPub);
						$info['eventDate'] = $eventDate;
						$info['creationDate'] = new MongoDate(gmmktime());
						$info['lastModifDate'] = new MongoDate(gmmktime());
						$info['dateEndPrint'] = new MongoDate($tsPub+$feed['persistDays']*86400); // 
						$info['print'] = $geolocItem['print'];
						$info['status'] = $geolocItem['status'];
						$info['user'] = 0;
						$info['zone'] = $defaultPlace['zone'];
						$info['location'] = array("lat"=>$geolocItem['lat'],"lng"=>$geolocItem['lng']);
						//$info['address'] = (!empty($locationTmp[$i++])?$locationTmp[$i++]:"");
						$info['address'] = $geolocItem['address'];
						$info['placeId'] = new MongoId($geolocItem['_id']);
						
						// check if data is not in DB
						$dataExists = $infoColl->findOne(array("title"=>$title,"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035),"status"=>1,"zone"=>$defaultPlace['zone']));
						//var_dump($dataExists);
						if(empty($dataExists)){
							echo "<br> The info does not exist in DB, we insert it.";
							// we check if there is another info printed at this point :
							$dataCount = 0;
							// here we take only 30 days of max history
							$dataCount = $infoColl->count(array(
																"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035),
																"pubDate"=>array('$gte'=>new MongoDate(gmmktime()-86400*30)),
																"print"=>1,
																"status"=>1	
																)
														); 
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
							$infoColl->ensureIndex(array("location"=>"2d","pubDate"=>-1,"yakType"=>1,"print"=>1,"status"=>1));
							$logDataInserted++;    
						}else{
							$logDataAlreadyInDB++;
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
			}
			
			
			
			
			echo "<br><hr><hr><br><br>";
		}
		
		
		$log = "<br><br><br><br><br>===BACTH SUMMARY====<br>Total data parsed : ".$item."<br> Total already in db:".$logDataAlreadyInDB.".<br> Total Data inserted: ".$logDataInserted.".<br> Total Data updated :".$logDataUpdated." (call &forceUpdate=1 to update)   <br>Call to gmap:".$logCallToGMap.". <br>Locations found in Yakwala DB :".$logLocationInDB."<br><br><br>";

		echo $log;

		
		
		$statColl->save(
			array(
			"batchName"=>$_SERVER['PHP_SELF'],
			"argument"=>$q,
			"datePassage"=>new MongoDate(gmmktime()),
			"parsed"=>$item,
			"alreadyInDb"=>$logDataAlreadyInDB,
			"inserted"=>$logCallToGMap,
			"callGMPA"=>$logDataUpdated,
			"foundInDb"=>$logLocationInDB,
			"daysBack"=>$feed['daysBack'],
			));
			
			

			
		$batchlogColl->save(
			array(
			"batchName"=>$_SERVER['PHP_SELF'],
			"datePassage"=>new MongoDate(gmmktime()),
			"dateNextPassage"=>new MongoDate(2143152000), // far future = one shot batch
			"log"=>$log,
			"status"=>1
			));
			   
		
		
		   

	}
    echo "<br><br><hr><b>FEEDS:</b><br>";
	echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=all\">ALL FEEDS</a>" ;
	$feeds = $feedColl->find()->sort(array('name'=>'desc'));
	
	foreach ($feeds as $feed) {
		echo "<br>".(($feed['status']==1)?"ACTIVE":"DISABLED")."--- <a href=\"".$_SERVER['PHP_SELF']."?q=".$feed['name']."\"/>".$feed['name']."</a> " ;
	
	}
	
	
   echo "<br><br><hr><b>OPTIONS:</b><br>";
   echo "<br>To print all text of the info add ti url : <a href=\"".$_SERVER["REQUEST_URI"]."&showAllText=1\">&showAllText=1</a>";    
   echo "<br>To force update, call <a href=\"".$_SERVER["REQUEST_URI"]."&forceUpdate=1\">&forceUpdate=1</a>";    
   
    


?>
</body>
</html>
