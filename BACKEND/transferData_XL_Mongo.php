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


  /*INFO STATUS*/
 /*
 1 : OK
 2 : Waiting for validation
 3 : blacklisted by operator
 10 : GMAP did not succeed to find a location
 11 : if the place the info is mapped to has been blacklisted by the operator
 12 : Location found is not in the feed zone
 13 : info not geolocalized and refused by parser defaultPrintFlag (= 0 )
 
 */

 /*FEED DEFAULT PRINT FLAG*/
// "defaultPrintFlag" => 0, if not geolocalized, we localize at the default location but we don't print on the map ( only in the text feed )
// "defaultPrintFlag" => 1,// if not geolocalized, we localize at the default location and we print on the map
// "defaultPrintFlag" => 2,// do not perform a geoloc and locate on the default location of the feed
// "defaultPrintFlag" => 3, if not geolocalized, we don't take the info -> stored in status 13
 

 
require_once("../LIB/conf.php");
$conf = new conf();

$m = new Mongo(); 
$db = $conf->mdb();

$infoColl = $db->info;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$yakNEColl = $db->yakNE;
$tagColl = $db->tag;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;
$feedColl = $db->feed;
$logCallToGMap = 0;
$logInfoInserted  = 0;
$logPlaceInserted  = 0;
$logInfoAlreadyInDB = 0;
$logPlaceAlreadyInDB = 0;
$logPrint = 0;
$logStatus10 = 0;
$logStatus11 = 0;
$logStatus12 = 0;
$logCallToApercite = 0;
$logPushToS3 = 0;

$yakCatId = array(); 
$placeArray = array(); // array of goeloc : ['lat'=>,'lng'=>,'_id'=>]
$flagShowAllText = (empty($_GET['showAllText']))?0:1;
$geolocYakCatId = "504d89f4fa9a958808000001"; // YAKCAT GEOLOC : @TODO softcode this

    
              
		$q = (empty($_GET['q']))?"":$_GET['q']; 
	
		if(!empty($q) || $q == 'all'){
		
		if($q == 'all') // we parse all valid feeds
			$query = array('status'=>1);
		else
			$query = array('name'=>$q,'status'=>1);
		
		$feeds = $feedColl->find($query);
		
		foreach ($feeds as $feed) {
			
			// get default PLACE
			$defaultPlace = $placeColl->findOne(array('_id'=>$feed['defaultPlaceId']));
			$defaultPlaceTitle = (empty($feed['defaultPlaceSearchName'])?$defaultPlace['title']:$feed['defaultPlaceSearchName']);		
			
			//var_dump($defaultPlace);
			echo '<br> Parsing feed: <b>'.$feed['name'].'</b>';
			echo '<br> Default location of the feed : <b>'.$defaultPlaceTitle.'</b>';
			$searchDate = date('Y/m/d',(mktime()-86400*$feed['daysBack']));
			if($feed['XLconnector']=='parser')
				$origin ="+AND+file_name%3D".$feed['name'].'.xml';
			else
				$origin ="";
				
			$url = "http://ec2-54-246-84-102.eu-west-1.compute.amazonaws.com:62010/search-api/search?q=%23all+AND+document_item_date%3E%3D".$searchDate."+AND+source%3D".$feed['XLconnector'].$origin."&of=json&b=0&hf=512000&s=document_item_date";
			
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
				//if($item > 100)
				//	exit;
				$item ++;
				$lieu = array();
				$lieuTmp = array();
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
				$yakdicoTmp = array();
				$yakdicotitle = array();
				$yakdicotext = array();
				$yakNE = array();
				$ville = array();
				$villetitle = array();
				$villetext = array();
				$enclosure = "";
				$geolocationInput = array();
				$addressInput = array();
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
				$contact = array();
				$tel = "";
				$mobile = "";
				$mail = "";
				$transportation = "";
				$web = "";
				$opening = "";
				$thumb = "";
				$placeArray = array();	
				$laville = '';
				
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
					$outGoingLink = empty($outGoingLink)?$feed['linkSource']:$outGoingLink;
					
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
					if($meta->name == "item_freetag"){
						  $freetagInput = explode('#',trim($meta->value));
						  foreach($freetagInput as $tagInput)
							$freeTag[] = $tagInput;
					}
					if($meta->name == "item_tel")
						  $telInput = trim($meta->value);
					if($meta->name == "item_mobile")
						  $mobileInput = trim($meta->value);
					if($meta->name == "item_mail")
						  $mailInput = trim($meta->value);
					if($meta->name == "item_transportation")
						  $transportationInput = trim($meta->value);
					if($meta->name == "item_web")
						  $webInput = trim($meta->value);
					if($meta->name == "item_opening")
						  $openingInput = trim($meta->value);
						
					
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
								foreach($villetitle as $vtitle){
									foreach($villetext as $vtext){
										if( preg_match("/".$vtitle."/",$vtext) > 0)
											$villetitleTMP[] = $vtext;  
									}
								}
							if(sizeof($villetitleTMP) > 0)
							   $villetitle = $villetitleTMP;
							}
							$ville = $villetitle;
						 }else
							$ville = $villetext;
						
						/*// filter in our zone
						$villeTmp = array();
						foreach($ville as $v){
							$isInZone = $placeColl->findOne(array('title'=>$v,"zone"=>$defaultPlace['zone']));
							if($isInZone)
								$villeTmp[] = $v;								
						}
						$ville = $villeTmp;	
						*/
	
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
							 
						/*
						$quartier = str_replace('quartier','',$quartier);			
						$quartier = str_replace('quartiers','',$quartier);			
						$quartier = str_replace('secteur','',$quartier);			
						$quartier = str_replace('secteurs','',$quartier);			
						*/
						
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
							$yakdicoTmp = $yakdicotext; 
						 else
							$yakdicoTmp = $yakdicotitle; 
						
						$isInZone = $placeColl->findOne(array('title'=>$yakdicoTmp,"zone"=>$defaultPlace['zone']));
						if($isInZone)
							$yakdico = $yakdicoTmp;
						
						/*YAKWALA NAMED ENTITIES*/
						if($group->id == "yakNE")
							$yakNE[] = $category->title;
						 	
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
					
				// if the news must be mapped on the default feed's location 	
				if($feed['defaultPrintFlag'] == 2){
					$print = 1;
					$geoloc = array($defaultPlace['location']);
					$status = 1;
					$contact = array("tel"=>"","mobile"=>"","mail"=>"","transportation"=>"","web"=>"","opening"=>"",);
					$placeArray[] = array('_id'=>$defaultPlace['_id'],'lat'=>$defaultPlace['location']['lat'],'lng'=>$defaultPlace['location']['lng'],'address'=>$defaultPlaceTitle,'status'=>$status,'print'=>$print,'contact'=>$contact);	
				}else{
				
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
					
					if(sizeof($ville) > 0){
						if(is_array($ville))
							$laville = $ville[0];
						else 
							$laville = $ville;
					}
					
					
					 
					// if there is a valid address, we get the location, first from db PLACE and if nothing in DB we use the gmap api
					if(sizeof($locationTmp ) > 0){
						
						foreach($locationTmp as $loc){
							
							echo "<br><b style='background-color:#00FF00;'>Location found by XL :</b> ".(empty($lieu))?$loc:$lieu;
							
							//check if in db if the place exists
							$place = $placeColl->findOne(array("title"=>$loc,"status"=>1,"zone"=>$defaultPlace['zone']));
							//var_dump($place);
							if($place){ // FROM DB
								echo "<br> Location found in DB !";
								if($place['status'] == 3){ // if the place has been blacklisted by the operator
									$status = 11; // alert status
									$print = 0; // don't print on the map, but can be printed on the news feed
								}else{
									$status = 1;
									$print = 1;
								}
								
								$placeArray[] = array('_id'=>$place['_id'],'lat'=>$place['location']['lat'],'lng'=>$place['location']['lng'],'address'=>$place['formatted_address'],'status'=>$status,'print'=>$print,'contact'=>$place['contact']);	
							}else{ // the place is not in db
								
								// FROM THE INPUT
								if(!empty($geolocationInput) && !empty($addressInput)){
									$status = 1;
									$geolocGMAP = array((float)$geolocationInput[0],(float)$geolocationInput[1]);
									$addressGMAP = array("street"=>"","arr"=>"","city"=>"","state"=>"","area"=>"","country"=>"","zip"=>"");
									$print = 1;
									$formatted_addressGMAP = $addressInput;
								}else{ // FROM GMAP
									
									echo "<br> Call to GMAP: ".$loc.', '.$defaultPlaceTitle.', '.$defaultPlace['address']['country'];
									$logCallToGMap++;
									
									echo '<br>loc'.$loc;
									echo '<br>laville'.$laville;
									echo '<br>$defaultPlace title'.$defaultPlaceTitle;
									echo '<br>$defaultPlace country'.$defaultPlace['address']['country'];		
									echo '<br>$lieu'.$lieu;
									
									
									//$gQuery = urlencode(utf8_decode(suppr_accents($loc.( (strlen($laville)> 0 && $laville != $defaultPlaceTitle && !in_array($loc,$ville) ) ? ', '.$laville:'').', '.$defaultPlaceTitle.'. '.$defaultPlace['address']['country'])));
									$gQuery = urlencode(utf8_decode(suppr_accents($loc.( (strlen($laville)> 0 && $laville != $defaultPlaceTitle && !in_array($loc,$ville) && preg_match('/^'.$loc.'$/',$laville) === FALSE ) ? ', '.$laville:'').', '.$defaultPlaceTitle.' '.$defaultPlace['address']['country'])));
									//echo 'LIEU'.sizeof($lieu);
									if(sizeof($lieu)==0)
										$resGMap = getLocationGMap($gQuery,'PHP',1,$conf);
									else
										$resGMap = getPlaceGMap($gQuery,'PHP',1,$conf);
									echo '___<br>';
									if(!empty($resGMap) &&  $resGMap['formatted_address'] != $defaultPlaceTitle.', '.$defaultPlace['address']['country']){
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
								
								$contact = array(
													"tel"=>$tel,
													"mobile"=>$mobile,
													"mail"=>$mail,
													"transportation"=>$transportation,
													"web"=>$web,
													"opening"=>$opening,
												);
								
								$place = array(
											"title"=> (empty($lieu))?$loc:$lieu,
											"content" =>"",
											"thumb" => "",
											"origin"=>$feed['humanName'],    
											"access"=> 2,
											"licence"=> "Yakwala",
											"outGoingLink" => $outGoingLink,
											"yakCat" => array(new MongoId($geolocYakCatId)), 
											"creationDate" => new MongoDate(gmmktime()),
											"lastModifDate" => new MongoDate(gmmktime()),
											"location" => array("lat"=>$geolocGMAP[0],"lng"=>$geolocGMAP[1]),
											"status" => $status,
											"user" => 0,
											"zone"=> $defaultPlace['zone'],
											"address" => $addressGMAP,
											"formatted_address" => $formatted_addressGMAP,
											"contact"=>$contact,
											"debugCallGmap" => $gQuery,
										  );
										  
										  
								$res = $placeColl->findOne(array("title"=>(empty($lieu))?$loc:$lieu,"zone"=>$defaultPlace['zone']));
								if(empty($res)){// The place is not in db
									echo "<br> The location does not exist in db, we create it.";
									$logPlaceInserted++;
									$test = $placeColl->save($place); 
									$placeColl->ensureIndex(array("location"=>"2d"));
									$res['_id'] = $place['_id'];
								}else{ // The place already in DB,
									$logPlaceAlreadyInDB++;
									echo "<br> The location exists in db => doing nothing.";
								}
								$placeArray[] = array('_id'=>$res['_id'],'lat'=>$geolocGMAP[0],'lng'=>$geolocGMAP[1],'address'=>$formatted_addressGMAP,'status'=>$status,'print'=>$print,'contact'=>$contact);
							 
							 }         
						}
						
					
						
					}else{
						// if the parser config tells us to forget about the info if not geolocalized
							if($feed['defaultPrintFlag'] == 3){
								echo "<br> NO location found, info is send with status 13";
								$print = 0;
								$geoloc = array($defaultPlace['location']);
								$status = 13;
								$contact = array("tel"=>"","mobile"=>"","mail"=>"","transportation"=>"","web"=>"","opening"=>"",);
								$placeArray[] = array('_id'=>$defaultPlace['_id'],'lat'=>$defaultPlace['location']['lat'],'lng'=>$defaultPlace['location']['lng'],'address'=>$defaultPlaceTitle,'status'=>$status,'print'=>$print,'contact'=>$contact);	
							}
							
						// if feedflag tell us to put the info on the default feed location	
						if($feed['defaultPrintFlag'] == 1 || $feed['defaultPrintFlag'] == 0){
							$print = $feed['defaultPrintFlag'] ;
							$geoloc = array($defaultPlace['location']);
							$status = 1;
							$contact = array("tel"=>"","mobile"=>"","mail"=>"","transportation"=>"","web"=>"","opening"=>"",);
							$placeArray[] = array('_id'=>$defaultPlace['_id'],'lat'=>$defaultPlace['location']['lat'],'lng'=>$defaultPlace['location']['lng'],'address'=>$defaultPlaceTitle,'status'=>$status,'print'=>$print,'contact'=>$contact);	
						}
					
					}
				}
			
				
				// catch keyword words in title
				if(!empty($title)){
					// catch twitter hashtag in title
					$matches = array();
					if (preg_match_all('/#([^\s]+)/', $title, $matches)) {
							$freeTag = array_merge($freeTag,$matches[1]);
					}
				
				}
				
				// YAKCATS & TAGS from NE
				$yakCatIdFromNE = array();
				foreach($yakNE as $ne){
					echo "<br>NE=".$ne;
					$regexObj = new MongoRegex("/^$ne$/i"); 
					//$ynes = $yakNEColl->find(array('status'=>1,'match.title'=>$regexObj));
					$ynes = $yakNEColl->find(array('status'=>1,'match.title'=>$ne));
					foreach( $ynes as $yne){
						$freeTag[] =  $yne['title'];
						if(sizeof($yne['yakCatId'])>0)
							$yakCatIdFromNE = array_merge($yakCatIdFromNE, $yne['yakCatId']);
					}
				}
				
				
				
				
				/* YAKCATS */
				$yakCatIdArray = array();
				$yakCatId = array();
				$yakCatName = array();
				$yakCatIdArray = array_merge($yakcatInput,$feed['yakCatId'],$yakCatIdFromNE);
				$yakCatIdArray = array_unique($yakCatIdArray);
				foreach ($yakCatIdArray as $id) {
					$yc = ($yakcatColl->findOne(array('_id'=>new MongoId($id))));
					if(!empty($yc)){
						$yakCatId[] = new MongoId($yc['_id']);
						$yakCatName[] = $yc['title'];
					}
				}
			
				
				
				/* EVENT DATE */
				$eventDate = array();
				$i=0;
				foreach ($eventDateInput as $date) {
					
					$fixedDate = str_replace('.0Z','Z',$date[0]);
					$dateTimeFrom = DateTime::createFromFormat(DateTime::ISO8601, $fixedDate);
					$eventDate[$i]['dateTimeFrom'] = new MongoDate($dateTimeFrom->gettimestamp());

					$fixedDate = str_replace('.0Z','Z',$date[1]);
					$dateTimeEnd = DateTime::createFromFormat(DateTime::ISO8601, $fixedDate);
					$eventDate[$i]['dateTimeEnd'] = new MongoDate(date_timestamp_get($dateTimeEnd));
					
					$i++;
				}
			
				
				
				// clean duplicates cats and tags
				$freeTag = array_unique($freeTag);
				$yakCatName = array_unique($yakCatName);
				$yakCatId = array_unique($yakCatId);
				if(sizeof($freeTag)>0)
					$freeTag = array_diff($freeTag,$yakCatName);
				
				$freeTag = (array) $freeTag;
				$freeTagNew = array_values($freeTag);
				$freeTag = $freeTagNew;
				
				// clean :
				$content = (!empty($content))?strip_tags($content,"<br><b><strong>"):"";
				$title = strip_tags(trim($title));			
				
				
				// NOTE:  WE INTRODUCE MULTIPLE INFO IF WE HAVE MULTIPLE LOCATIONS
				$i = 0;
				$geolocItem = array();
				foreach($placeArray as $geolocItem){
					
					
					if( ( !empty($title) && !empty($geolocItem['lat']) && !empty($geolocItem['lng']) ) || $status == 10){
						
						
						$datePubArray1 = explode(' ',$datePub);
						$datePubArrayD = explode('/',$datePubArray1[0]);
						$datePubArrayT = explode(':',$datePubArray1[1]);
						
						
						if($feed['yakType'] == 2){
							$tsEnd = date_timestamp_get($dateTimeFrom) + $feed['persistDays']*86400;
							$tsPub = $tsEnd - 15 * 86400;
						}	
						else{
							$tsPub = gmmktime($datePubArrayT[0],$datePubArrayT[1],$datePubArrayT[2],$datePubArrayD[0],$datePubArrayD[1],$datePubArrayD[2]);
							$tsEnd = $tsPub + $feed['persistDays']*86400;
						}
					
						echo "<br>----LOC-----<br>";	
						echo "adresse";var_dump($adresse);
						echo "<br>lieu";var_dump($lieu);
						echo "<br>yakdico";var_dump($yakdico);
						echo "<br>quartier";var_dump($quartier);
						echo "<br>arr";var_dump($arrondissement);
						echo "<br>geoinput";var_dump($geolocationInput);
						echo "<br>addressInput";var_dump($addressInput);
						echo "<br>placeinput";var_dump($placeInput);
						echo "<br>ville";var_dump($ville);
						echo "<br>----CAT-----<br>";
						echo "yakcat";var_dump($yakCatName);
						echo "<br>tag";var_dump($freeTag);
						// VERIFICATION : if the GMAP gives us a big city, we don't print it on the map
						if( sizeof($adresse) == 0 
							&& sizeof($lieu) == 0 
							&& sizeof($yakdico) == 0 
							&& sizeof($quartier) == 0
							&& sizeof($arrondissement) == 0
							&& sizeof($geolocationInput) == 0
							&& sizeof($addressInput) == 0
							&& sizeof($placeInput) == 0
							&& ($laville == "Marseille" || $laville == "Paris")
							&& $feed['defaultPrintFlag'] != 2
						){
							echo "<br> GEOLOC NOT PRECISE => NOT PRINTED ON THE MAP";
							$geolocItem['print'] = 0;
						}
				
						echo "<br>time: ".$datePubArrayT[0]."-".$datePubArrayT[1]."-".$datePubArrayT[2]."-".$datePubArrayD[0]."-".$datePubArrayD[1]."-".$datePubArrayD[2];
						$info = array();
						echo 'TITLE'.$title;
						$info['title'] = $title;
						$info['content'] = $content;
						$info['outGoingLink'] = $outGoingLink;
						$info['origin'] = $feed['humanName'];
						$info['originLink'] = $feed['linkSource'];
						$info['access'] = 2;
						$info['licence'] = (!empty($feed['licence']))?$feed['licence']:"Yakwala";
						$info['heat'] = "80";
						$info['yakCat'] = array_unique($yakCatId);
						$info['yakCatName'] = array_unique($yakCatName);
						$info['yakType'] = $feed['yakType'];
						$info['freeTag'] = array_unique($freeTag);
						$info['pubDate'] = new MongoDate($tsPub);
						$info['eventDate'] = $eventDate;
						$info['creationDate'] = new MongoDate(gmmktime());
						$info['lastModifDate'] = new MongoDate(gmmktime());
						$info['dateEndPrint'] = new MongoDate($tsEnd); // 
						$info['print'] = $geolocItem['print'];
						$info['status'] = $geolocItem['status'];
						$info['user'] = 0;
						$info['feed'] = $feed['_id'];
						$info['zone'] = $defaultPlace['zone'];
						$info['location'] = array("lat"=>$geolocItem['lat'],"lng"=>$geolocItem['lng']);
						$info['address'] = $geolocItem['address'];
						$info['placeId'] = new MongoId($geolocItem['_id']);
						$info['contact'] = $geolocItem['contact'];
						
						// LOG
						if($info['print'] == 1)
							$logPrint++;
						if($info['status'] == 10)
							$logStatus10++;
						if($info['status'] == 11)
							$logStatus11++;
						if($info['status'] == 12)
							$logStatus12++;
						
						
						// check if data is not in DB
						//$dataExists = $infoColl->findOne(array("title"=>$title,"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035),"status"=>1,"pubDate"=>new MongoDate($tsPub),"zone"=>$defaultPlace['zone']));
						$dataExists = $infoColl->findOne(array("title"=>$title,"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035),"status"=>1,"zone"=>$defaultPlace['zone']));

						if(empty($dataExists) || $status == 10){
							echo "<br> The info does not exist in DB, we insert it.";
							
							/* THUMB  */
							// create thumb and  push the image to S3
							$thumbFlag = 0;
							echo "<br>enclosure:".$enclosure;
							if(!empty($enclosure)){
								$res = createImgThumb($enclosure,$conf);
								$logPushToS3 = $logPushToS3+3;
								if($res == false){
									if(!empty($outGoingLink) && ($outGoingLink[0] != "")){
										$thumb = getApercite($outGoingLink,$conf);
										$logCallToApercite++;
										$logPushToS3++;
										$thumbFlag = 1;	
									}else{
										$thumb = "";
										$thumbFlag = 0;	
									}
								}
								else{
									$thumb = $res;
									$size = getimagesize($enclosure);
									if($size[0] > 320)
										$thumbFlag = 2;	
									else
										$thumbFlag = 1;	
								}
							}else{
								if(!empty($content)){
									$img = array();
									$dom = new domDocument;
									if($dom->loadHTML($content)){
										$dom->preserveWhiteSpace = false;
										$images = $dom->getElementsByTagName('img');
										foreach ($images as $image) {
											$img[] =  $image->getAttribute('src');
										}
										if(sizeof($img) > 0 && $img[0] != '' ){
											$res = createImgThumb($img[0],$conf);
											$logPushToS3 = $logPushToS3+3;
											if($res == false){
												if(!empty($outGoingLink) && ($outGoingLink[0] != "")){
													$thumb = getApercite($outGoingLink,$conf);
													$logPushToS3++;
													$logCallToApercite++;
													$thumbFlag = 1;
												}else{
													$thumb = "";
													$thumbFlag = 0;
												}		
											}
											else{
												$size = getimagesize($img[0]);
												if($size[0] > 320)
													$thumbFlag = 2;	
												else
													$thumbFlag = 1;	
													
												$thumb = $res;
											}
										}else{
											if(!empty($outGoingLink) && ($outGoingLink[0] != "")){
												$thumb = getApercite($outGoingLink,$conf);
												$logPushToS3++;
												$logCallToApercite++;
												$thumbFlag = 1;	
											}else{
												$thumb = "";
												$thumbFlag = 0;
											}
										}
									}
								}
							}
							
							// overwrite if set in the feed
							if(!empty($feed['thumbFlag']))
								$thumbFlag = $feed['thumbFlag'];
							
							$info['thumb'] = $thumb;
							$info['thumbFlag'] = $thumbFlag;						
						
						
							// add tags to the top collection
							foreach($freeTag as $theTag){
								if($info['yakType']==2)
									$tagDate = $eventDate[0]['dateTimeFrom'];
								else
									$tagDate = new MongoDate($tsPub);
									
								$beginOfDay = strtotime("midnight", $tagDate->sec);
								$endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;
								$dataExists = $tagColl->findOne(array("title"=>$theTag,"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.5),"usageDate"=>array('$gte'=>new MongoDate($beginOfDay),'$lte'=>new MongoDate($endOfDay))));
								
								if(!$dataExists){
									echo '<br>Tag does not ->exit we insert it as a hot tag';
									$tagColl->save(array("title"=>$theTag,"numUsed"=>1,"location"=>$info['location'],"usageDate"=>$tagDate,"print"=>$geolocItem['print']));
								}else{
									echo '<br>Tag does exist -> we increment numUsed';
									$tagColl->update(array("_id"=> $dataExists['_id']), array('$set'=>array("title"=>$theTag,"location"=>$info['location'],"usageDate"=>$tagDate)));
									$tagColl->update(array("_id"=> $dataExists['_id']), array('$inc'=>array("numUsed"=>1)));
								}
								$tagColl->ensureIndex(array("location"=>"2d"));
							}
							
							// we check if there is another info printed at this point :
							$dataCount = 0;
							// here we take only 30 days of max history
							if($status == 1){
								$dataCount = $infoColl->count(array(
									"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035),
									"pubDate"=>array('$gte'=>new MongoDate(gmmktime()-86400*30)),
									"print"=>1,
									"status"=>1	
									)
								); 
								// if more than one info on the same location
								if($dataCount > 0 && $print ==  1){
									$lepas = ceil($dataCount/12);
									$info['location'] = array("lat"=>(0.000015*sin(3.1415*$dataCount/6)+$geolocItem['lat']),"lng"=>(0.00002*cos(3.1415*$dataCount/6)+$geolocItem['lng']));
								}
							}	
							$infoColl->insert($info,array('fsync'=>true));
							$infoColl->ensureIndex(array("location"=>"2d"));
							$infoColl->ensureIndex(array("location"=>"2d","pubDate"=>-1,"yakType"=>1,"print"=>1,"status"=>1));
							$logInfoInserted++;
								
						}else{
							$logInfoAlreadyInDB++;
							echo "<br> The info exists in DB => doing nothing.";    
						}
					}
				}
			
			}
			
			
			echo "<br><hr><hr><br><br>";
		}
		
		
		$log = "<br><br><br><br><br>
		===BACTH SUMMARY====
		<br>Total data parsed : ".$item."
		<br> Total info already in db:".$logInfoAlreadyInDB.".
		<br> Total Info inserted: ".$logInfoInserted.".
		<br> Total place already in db:".$logPlaceAlreadyInDB.".
		<br> Total place inserted: ".$logPlaceInserted.".
		<br>Call to gmap:".$logCallToGMap.".
		<br><br><br>";

		echo $log;

		
		
		$statColl->save(
			array(
			"batchName"=>$_SERVER['PHP_SELF'],
			"argument"=>$q,
			"datePassage"=>new MongoDate(gmmktime()),
			"parsed"=>$item,
			"infoAlreadyInDb"=>$logInfoAlreadyInDB,
			"placeAlreadyInDb"=>$logPlaceAlreadyInDB,
			"infoInserted"=>$logInfoInserted,
			"placeInserted"=>$logPlaceInserted,
			"callGMPA"=>$logCallToGMap,
			"pushS3"=>$logPushToS3,
			"callAPERCITE"=>$logCallToApercite,
			
			"logPrint"=>$logPrint,
			"logStatus10"=>$logStatus10,
			"logStatus11"=>$logStatus11,
			"logStatus12"=>$logStatus12,
			
			"daysBack"=>$feed['daysBack'],
			));
			
			

			
		$batchlogColl->save(
			array(
			"batchName"=>$_SERVER['PHP_SELF'],
			"datePassage"=>new MongoDate(gmmktime()),
			"dateNextPassage"=>new MongoDate(gmmktime()+3600), // every hour
			"log"=>$log,
			"status"=>1
			));
			   
		
		
		   

	}
    echo "<br><br><hr><b>FEEDS:</b><br>";
	echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=all\">ALL FEEDS</a>" ;
	$feeds = $feedColl->find()->sort(array('humanName'=>'desc'));
	
	foreach ($feeds as $feed) {
		echo "<br>".(($feed['status']==1)?"ACTIVE":"DISABLED")."--- <a href=\"".$_SERVER['PHP_SELF']."?q=".$feed['name']."\"/>".$feed['humanName']."</a> " ;
	
	}
	
	
   echo "<br><br><hr><b>OPTIONS:</b><br>";
   echo "<br>To print all text of the info add ti url : <a href=\"".$_SERVER["REQUEST_URI"]."&showAllText=1\">&showAllText=1</a>";    
   echo "<br>To force update, call <a href=\"".$_SERVER["REQUEST_URI"]."&forceUpdate=1\">&forceUpdate=1</a>";    
   
    


?>
</body>
</html>
