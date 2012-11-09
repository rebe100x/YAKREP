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
			$url = "http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com:62010/search-api/search?q=%23all+AND+document_item_date%3E%3D".$searchDate."+AND+source%3D".$feed['name']."&of=json&b=0&hf=1000&s=document_item_date";
			
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
				$locationTmp = array();
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
					if($meta->name == "url")
						  $outGoingLink = $meta->value;
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
				/*Priority :  ADDRESSE -> YAKDICO -> ARRONDISSEMENT -> QUARTIER -> VILLE*/
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
				
				
					
				$placeArray = array();	 
				// if there is a valid address, we get the location, first from db PLACE and if nothing in DB we use the gmap api
				if(sizeof($locationTmp ) > 0){
					
					foreach($locationTmp as $loc){
						echo "<br><b style='background-color:#00FF00;'>Location found by XL :</b> ".$loc;
						//check if in db
						
						$place = $placeColl->findOne(array('title'=>$loc,"status"=>1,"zone"=>$defaultPlace['zone']));
						//var_dump($place);
						if($place && $flagForceUpdate != 1){ // FROM DB
							echo "<br> Location found in DB !";
							$logLocationInDB++;
							//$geoloc[] = array($place['location']['lat'],$place['location']['lng']);
							$status = 1;
							$print = 1;
							$placeArray[] = array('_id'=>$place['_id'],'lat'=>$place['location']['lat'],'lng'=>$place['location']['lng'],'address'=>$place['formatted_address'],'status'=>$status,'print'=>$print);	
						 }else{    // FROM GMAP
							echo "<br> Call to GMAP: ".$loc.', '.$defaultPlace['title'].', '.$defaultPlace['address']['country'];
							$logCallToGMap++;
							$resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($loc.', '.$defaultPlace['title'].'. '.$defaultPlace['address']['country']))),'PHP',1);
							//$resGMap =  array(48.884134,2.351761);
							//var_dump($resGMap);
							echo '___<br>';
							if(!empty($resGMap) &&  $resGMap['formatted_address'] != $defaultPlace['title'].', '.$defaultPlace['address']['country']){
								echo "<br> GMAP found the coordinates of this location ! ";
								$status = 1;
								$print = 1;
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
					if(sizeof($ville)==0 && sizeof($adresse)==0 && sizeof($yakdico)==0 && sizeof($arrondissement)==0 && sizeof($quartier)==0){
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
					
					
					
					if($feed['defaultPrintFlag'] != 2){
						$print = $feed['defaultPrintFlag'] ;
						$geoloc = array($defaultPlace['location']);
						$status = 1;
						$placeArray[] = array('_id'=>$defaultPlace['_id'],'lat'=>$defaultPlace['location']['lat'],'lng'=>$defaultPlace['location']['lng'],'address'=>$defaultPlace['title'],'status'=>$status,'print'=>$print);	
					}
					
					
					
					
				}
				
			
				// CONVERT YAKCAT TO AN ARRAY OF _ID
				$yakCatId = array();
				foreach($feed['yakCatNameArray'] as $cat){
					$catId = $yakcatColl->findOne(array('title'=>$cat));
					$yakCatId[] = new MongoId($catId['_id']);
				}
			
				
			
				// get image
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
							$thumb = 'thumb/'.createImgThumb($img[0],$conf);
						
					}else
						$thumb = getApercite($outGoingLink);
				}
				
				// catch keyword words in title
				if(!empty($title)){
					if (preg_match("/FOOT/i", $title) || preg_match("/FOOTBALL/i", $title)) {
							$yakCatId[] = new MongoId("50647e2d4a53041f91040000");
						}
				}
				
				// catch twitter hashtag in title
				if(!empty($title)){
					$matches = array();
					if (preg_match_all('/#([^\s]+)/', $title, $matches)) {
					var_dump($matches);
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
						$info['yakCatName'] = $feed['yakCatNameArray'];
						$info['yakType'] = $feed['type']; // actu
						$info['freeTag'] = $freeTag;
						$info['pubDate'] = new MongoDate($tsPub);
						$info['creationDate'] = new MongoDate(gmmktime());
						$info['lastModifDate'] = new MongoDate(gmmktime());
						$info['dateEndPrint'] = new MongoDate(gmmktime()+$feed['persistDays']*86400); // 
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
