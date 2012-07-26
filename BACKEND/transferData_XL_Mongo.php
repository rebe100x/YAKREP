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
 * arrondissementtitle : an arrondissement like VIe or 6ème arrondissement
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
$flagShowAllText = (empty($_GET['showAllText']))?0:1;

if(!empty($_GET['q'])){
    
    if($flagForceUpdate)
        echo "<br> <b>WARNING :</b> You are forcing update : we will always call GMAP for the location and any record in INFO will be updated.";
            
              
    $q = $_GET['q']; 
    switch( $q ){
        case 'leparisien75':
        case 'telerama':
        case 'concertandco':
        case 'expo-a-paris':
        case 'paris-bouge':
        case 'sortir-a-paris':
        case 'figaro-culture':
        case 'exponaute':
        case 'agenda-culturel-75':
        
        $url = "http://ec2-54-247-18-97.eu-west-1.compute.amazonaws.com:62010/search-api/search?q=%23all+AND+source%3D".$q."&of=json&b=0&hf=1000&s=document_item_date";
        echo '<br> URL CALLED : '.$url.'<br>';
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
                 
            // if there is a valid address, we get the location, first from db PLACE and if nothing in DB we use the gmap api
            if(sizeof($locationTmp ) > 0){
                foreach($locationTmp as $loc){
                    echo "<br>Location found by XL : ".$loc;
                    //check if in db
                    $place = $placeColl->findOne(array('title'=>$loc,"status"=>1));
                    if($place && $flagForceUpdate != 1){ // FROM DB
                        echo "<br> Location found in DB !";
                        $logLocationInDB++;
                        $geoloc[] = array($place['location']['lat'],$place['location']['lng']);
                        $status = 1;
                        $print = 1;
                            
                     }else{    // FROM GMAP
                        echo "<br> Call to GMAP: ".$loc.', Paris, France';
                        $logCallToGMap++;
                        $resGMap = getLocationGMap(urlencode(utf8_decode(suppr_accents($loc.', Paris, France'))),'PHP',1);
                        //$resGMap =  array(48.884134,2.351761);
                        
                        if(!empty($resGMap)){
                            echo "<br> GMAP found the coordinates of this location ! ";
                            $status = 1;
                            $print = 1;
                            $geoloc[] = $resGMap;
                        }else{
                            echo "<br> GMAP did not succeed to find a location, we store the INFO in db with status 10.";
                            $status = 10;
                            $geoloc[] = "";
                            $print = 0;
                        } 
                        // we store the result in PLACE for next time
                        foreach($geoloc as $geolocItem){
                            $place = array(
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
                                      );
                                      
                            $res = $placeColl->findOne(array('title'=>$loc));
                            if(empty($res)){// The place is not in db
                                echo "<br> The location does not exist in db, we create it.";
                                $placeColl->save($place); 
                            }else{ // The place already in DB, we update if the flag tells us to
                                if($flagForceUpdate ==  1){
                                    echo "<br> The location exists in db and we update it.";
                                    $placeColl->update(array("_id"=> $res['_id']),$place); 
                                }else
                                   echo "<br> The location exists in db => doing nothing.";
                            }
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
                    $info['yakCat'] = array(array("id"=>1,"name"=>utf8_encode("actualités"),"level"=>1));// doit etre un objet
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
                    $dataExists = $infoColl->findOne(array("title"=>$title,"location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035)));
                    var_dump($dataExists);
                    if(empty($dataExists)){
                        echo "<br> The info does not exist in DB, we insert it.";
                        // we check if there is another info printed at this point :
                        $dataCount = 0;
                        $dataCount = $infoColl->count(array("location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035)));
                        $dataDebug = $infoColl->find(array("location"=>array('$near'=>$info['location'],'$maxDistance'=>0.000035)));
                        var_dump(iterator_to_array($dataDebug));
                        
                        echo $dataCount.'azerty<br>';  
                        if($dataCount > 0){
                            
                            $info['location'] = array("lat"=>(0.00003*sin(3.1415*$dataCount/4)+$geolocItem[0]),"lng"=>(0.00003*cos(3.1415*$dataCount/4)+$geolocItem[1]));
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
            }else{
                if(sizeof($adresse)==0 && sizeof($yakdico)==0 && sizeof($arrondissement)==0 && sizeof($quartier)==0){
                    echo "No interesting location detected by Exalead. The info is not transfered to Mongo.";
                    // here we can choose to add the info in the db for the fils d'actu...
                }else{
                 echo "Address no significative enough to find a localization : 
                 <br>adresse= ".implode(',',$adresse)."
                 <br>yakdico= ".implode(',',$yakdico)."
                 <br>arrondissement = ".implode(',',$arrondissement)."
                 <br>quartier = ".implode(',',$quartier);

                }
            }
            
        }   
        
        break;
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
    echo "<br>To print all text of the info add &showAllText=1";    
    



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