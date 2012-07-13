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
 * arrondissementtitle : an arrondissement like VIe or 6�me arrondissement
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
$batchlogColl = $db->batchlog;
$logCallToGMap = 0;
$logLocationInDB = 0;
$logDataInserted = 0;
$logDataUpdated = 0;

$flagForceUpdate = (empty($_GET['forceUpdate']))?0:1;

if(!empty($_GET['q'])){
	
	$q = $_GET['q']; 
    switch( $q ){
    	case 'leparisien75':
        case 'concertandco_paris':
		$url = "http://ec2-46-137-24-52.eu-west-1.compute.amazonaws.com:62010/search-api/search?q=%23all+AND+source%3D".$q."&of=json&b=0&hf=1000&s=document_item_date";
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
			$geoloc = array();
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
			    if($meta->name == "url")
			          $outGoingLink = $meta->value;
		        
		    	  
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
	                    $quartiertitle[] = $category->title;
                     if($group->id == "quartiertext")
                        $quartiertext[] = $category->title;
                     if(sizeof($quartiertitle) == 0)
                        $quartier = $quartiertext; 
                           
                     /*ARRONDISSEMENT*/   
	                 if($group->id == "arrondissementtitle")
	                       $arrondissementtitle[] = $category->title;
	                 if($group->id == "arrondissementtext")
                           $arrondissementtext[] = $category->title;
                     if(sizeof($arrondissementtitle) == 0)
                        $arrondissement = $arrondissementtext; 
                        
                     /*YAKWALA DICO*/
                     if($group->id == "yakdicotitle")
                           $yakdicotitle[] = $category->title;
                     if($group->id == "yakdicotext")
                           $yakdicotext[] = $category->title;
                     if(sizeof($yakdicotitle) == 0)
                        $yakdico = $yakdicotext; 
                           
                        
                     /*OTHER CAT*/   
	                 if($group->id == "Person_People")
	                       $freeTag[]= $category->title;
	                                
	                 if($group->id == "Organization")
	                       $freeTag[]= $category->title;
	                                    
			     }
			}
			    
			
			
			//logical construction of the address :
			/*Priority to ADDRESSE after ARRONDISSEMENT and after QUARTIER*/
			if(sizeof($adresse)>0){
			    foreach($adresse as $ad){
			    	$locationTmp[] = $ad;
			    } 
			}else{
				if(sizeof($yakdico)>0){
					foreach($yakdico as $dico){
	                    $locationTmp[] = $dico;
	                }
				}else{    
				    if(sizeof($arrondissement)>0){
						foreach($arrondissement as $arr)
						   $locationTmp[] = rewriteArrondissementParis($arr);
					}else{
						if(sizeof($quartier)>0){
						   foreach($quartier as $quar)
						       $locationTmp[] = $quar;
						}
					}
				}
			}
			     
            // if there is a valid adresse, we get the location, first from db PLACE and if nothing in DB we use the gmap api
			if(sizeof($locationTmp ) > 0){
				foreach($locationTmp as $loc){
					echo "<br>Location: ".$loc;
					//check if in db
	                $place = $placeColl->findOne(array('title'=>$loc,"status"=>1));
					if($place && $flagForceUpdate != 1){ // FROM DB
						$logLocationInDB++;
				        
				        $geoloc[] = array($place['location']['lat'],$place['location']['lng']);
				        $status = 1;
                        $print = 1;
                            
				     }else{    // FROM GMAP
				     	$logCallToGMap++;
				        $resGMap = getLocationGMap($loc.', Paris, France','PHP',1);
				        //$resGMap =  array(48.884134,2.351761);
				        if(!empty($resGMap)){
				            $status = 1;
				            $print = 1;
				            $geoloc[] = $resGMap;
				        }else{
				            $status = 10;
				            $geoloc[] = "";
				            $print = 0;
				        } 
				        // we store the result in PLACE for next time
				        foreach($geoloc as $geolocItem){
					        $placeColl->save(
						        array(
								    "title"=> $loc,
								    "content" =>"",
								    "thumb" => "",
								    "origin"=>$q,    
								    "access"=> 2,
								    "licence"=> "Yakwala",
								    "outGoingLink" => "",
								    "creationDate" => new MongoDate(gmmktime()),
								    "lastModifDate" => new MongoDate(gmmktime()),
								    "location" => array("lat"=>$geolocItem[0],"lng"=>$geolocItem[1]),
								    "status" => $status,
								    "user" => 0,
								    "zone"=> 1
								  )
	                        ); 
					     }
				     }         
				       
				         
	            }
	            
			
            // NOTE WE CAN INTRODUCE MULTIPLE INFO IF WE HAVE MULTIPLE LOCATIONS
            $i = 0;
            
            foreach($geoloc as $geolocItem){
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
	            $info['yakCat'] = array("id"=>1,"name"=>utf8_encode("actualit�s"),"level"=>1);
	            $info['freeTag'] = $freeTag;
	            $info['creationDate'] = new MongoDate(gmmktime());
	            $info['lastModifDate'] = new MongoDate(gmmktime());
	            $info['dateEndPrint'] = new MongoDate(gmmktime()+2*86400); // + 2 days
	            $info['print'] = $print;
	            $info['status'] = $status;
	            $info['user'] = $_SERVER['PHP_SELF'];
	            $info['zone'] = 1;
	            $info['location'] = array("lat"=>$geolocItem[0],"lng"=>$geolocItem[1]);
	            $info['address'] = $locationTmp[$i++];
	            
	            // check if data is not in DB
	            $dataExists = $infoColl->findOne(array("title"=>$info['title'],"outGoingLink"=>$info['outGoingLink'],"location"=>$info['location']));
	            if(empty($dataExists)){
		            $infoColl->insert($info,array('fsync'=>true));
	                $infoColl->ensureIndex(array("location"=>"2d"));
	                $logDataInserted++;    
	            }else{
	            	if($flagForceUpdate == 1){
	            	  echo "<br>force update";
	            	  $info['lastModifDate'] = new MongoDate(gmmktime());
		              $infoColl->update(array("_id"=> $dataExists['_id']),$info);
	                  $infoColl->ensureIndex(array("location"=>"2d"));
	                  $logDataUpdated++;
	            	}    
	            }
            }
                
	            
			}else{
				if(sizeof($adresse)==0 && sizeof($arrondissement)==0 && sizeof($quartier)==0)
				    echo "No location detected by Exalead";
				else
                 echo "Address no significative enough to find a localization : 
                 <br>adresse= ".implode(',',$adresse)."
                 <br>arrondissement = ".implode(',',$arrondissement)."
                 <br>quartier = ".implode(',',$quartier);
			}
		  	
        }	
	    
        break;
    }
    
    $log = "<br>===BACTH SUMMARY====<br>Total data parsed : ".$item.".<br> Total Data inserted: ".$logDataInserted.".<br> Total Data updated :".$logDataUpdated." (call &forceUpdate=1 to update)   <br>Call to gmap:".$logCallToGMap.". <br>Locations found in Yakwala DB :".$logLocationInDB;

    echo $log;
    
$batchlogColl->save(
    array(
    "batchName"=>$_SERVER['PHP_SELF'],
    "datePassage"=>mktime(), // now
    "dateNextPassage"=>2143152000, // far future = one shot batch
    "log"=>$log,
    "status"=>1
    ));
    
    
}else
    echo "no request<br>try this :";
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=leparisien75\"/>".$_SERVER['PHP_SELF']."?q=leparisien75</a>" ;
    echo "<br><a href=\"".$_SERVER['PHP_SELF']."?q=concertandco_paris\"/>".$_SERVER['PHP_SELF']."?q=concertandco_paris</a>" ;
    



/*
 array({
    title:"Le tramway se raccroche � son dernier tron�on",
    content :"Encore quelques mois et les riverains et usagers du T3 pourront r�colter les fruits de leur patience : le tramway parisien circulera alors du pont du Garigliano (XVe) � la porte de la Chapelle...",
    thumb : ""
    origin:"leparisien.fr", 
    access: 1 
    licence: ""
    outGoingLink : "http://www.leparisien.fr/paris-75/paris-75005/le-tramway-se-raccroche-a-son-dernier-troncon-09-07-2012-2083234.php"
    heat : 80
    print : 1
    yakCat : array({id:1,name:"transport",level:1})
    yakTag : [{}]
    freeTag : "tramway"
    creationDate : 132154654,
    lastModifDate : 132132165,
    dateEndPrint : 132152165
    location : [48.839032,2.268741]
    status : 1,
    user : 0,
    zone: 1
    }
)
*/
?>
</body>
</html>