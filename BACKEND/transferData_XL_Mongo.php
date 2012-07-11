<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>YAKWALA BATCH</title>
</head>
<body>
<?php 
/* Access Exalead index in GET, and return a JSON object
 * check if the data is in db and insert it 
 * if an address is detected, we check in db if we have the address
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

		$url = "http://ec2-46-137-24-52.eu-west-1.compute.amazonaws.com:62010/search-api/search?q=%23all+AND+source%3Dleparisien75&of=json&b=0&hf=1000&s=document_item_date";
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
			$arrondissement = array();
			$quartier = array();
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
			         if($group->id == "adresse"){
			         	$flagIncluded = 0;
			         	foreach($adresse as $adr){
			         	   if( preg_match("/".$adr."/",$category->title) > 0 || preg_match("/".$category->title."/",$adr) > 0)
			         	       $flagIncluded++;
			         	}
			         	if($flagIncluded == 0)
			         	   $adresse[]= $category->title;
	                       
			         }               
	                 if($group->id == "quartier")
	                       $quartier[] = $category->title;
	                                
	                 if($group->id == "arrondissement")
	                       $arrondissement[] = $category->title;
	
	                 if($group->id == "Person_People")
	                       $freeTag .= $category->title.' # ';
	                                
	                 if($group->id == "Organization")
	                       $freeTag .= $category->title.' # ';
	                                    
			     }
			}
			    
			
			
			//logical construction of the address :
			/*Priority to ADDRESSE after ARRONDISSEMENT and after QUARTIER*/
			if(sizeof($adresse)>0){
			    foreach($adresse as $ad){
			    	$locationTmp[] = $ad;
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
			     
            // if there is a valid adresse, we get the location, first from db PLACE and if nothing in DB we use the gmap api
			if(sizeof($locationTmp ) > 0){
				foreach($locationTmp as $loc){
					echo "<br>Location: ".$loc;
					//check if in db
	                $place = $placeColl->findOne(array('title'=>$loc,"status"=>1));
					if($place){ // FROM DB
						$logLocationInDB++;
				        
				        $geoloc[] = array($place['location']['lat'],$place['location']['lng']);
				        $status = 1;
                        $print = 1;
                            
				     }else{    // FROM GMAP
				     	$logCallToGMap++;
				        //$resGMap = getLocationGMap($loc.', Paris, France','PHP',1);
				        $resGMap =  array(48.884134,2.351761);
				        if(!empty($resGMap)){
				            $status = 1;
				            $print = 1;
				            $geoloc[] = $resGMap;
				        }else{
				            $status = 10;
				            $geoloc[] = "";
				            $print = 0;
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
	            $info['origin'] = "http://rss.leparisien.fr/leparisien/rss/paris-75.xml";
	            $info['access'] = 2;
	            $info['licence'] = "reserved";
	            $info['heat'] = "80";
	            $info['yakCat'] = array("id"=>1,"name"=>utf8_encode("actualités"),"level"=>1);
	            $info['freeTag'] = $freeTag;
	            $info['creationDate'] = mktime();
	            $info['lastModifDate'] = mktime();
	            $info['dateEndPrint'] = mktime()+2*86400; // + 2 days
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
	                $infoColl->ensureIndex("location");
	                $logDataInserted++;    
	            }else{
	            	if($flagForceUpdate == 1){
		              $infoColl->update(array("_id"=> $dataExists['_id']),$info);
	                   $infoColl->ensureIndex("location");
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
    echo "no request<br>try this :<br><a href=\"".$_SERVER['PHP_SELF']."?q=leparisien75\"/>".$_SERVER['PHP_SELF']."?q=leparisien75</a>" ;



/*
 array({
    title:"Le tramway se raccroche à son dernier tronçon",
    content :"Encore quelques mois et les riverains et usagers du T3 pourront récolter les fruits de leur patience : le tramway parisien circulera alors du pont du Garigliano (XVe) à la porte de la Chapelle...",
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