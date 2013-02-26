<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>YAKWALA BATCH - OUSET FRANCE INFO</title>
</head>
<body>
<?php
/* Read file from ousetfrance , match cats and create infos
 * */

ini_set ('max_execution_time', 0);
set_time_limit(0);
require_once("../LIB/conf.php");
ini_set('display_errors',1);
$inputFile ='./input/ouestfrance_small.xml';
$fileTitle = "Ouest France";

// Init mongodb connection
$m = new Mongo(); 
$conf=new Conf();
$db = $m->selectDB($conf->db());
//print_r($db);
$countautre=0;
// UpdateFlag is a query parameter, if 1, force update
$updateFlag = empty($_GET['updateFlag'])?0:1;
$updateFlag =1;
// Array to store logs
$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());



//default stuff
$origin = "infolocale.fr";
$originLink ="http://infolocale.fr";
$licence = "Ouest France";


$catXML = simplexml_load_file($inputFile);
$status = 5000;
$i=0;

		
//------------------------Looping xml for attributes storing and processing-------------------------------
foreach($catXML as $key0 => $value){
 
//---------xml element id and genre--------------
  $id=(string)$value->attributes()->id ;
  $genre=(string)$value->attributes()->genre ;

 if($value->Etat->attributes()->statut !="V" ) continue ;
 
 else{	
		$urltest=(string)$value->Url; $infoQuery = array("outGoingLink" => $urltest);
		$dataExists = $db->info->findOne($infoQuery);
		// print_r($dataExists);

		if (!empty($dataExists)) {	// Data already exists in mongo
			if($updateFlag) {
				echo "Event ". $urltest . " already in DB, forcing update." . "<br />";
			}
			else {
				echo "Event already in db: ". $urltest . "<br />";
			}
		}

	if (empty($dataExists) || $updateFlag) {
    //------loop to stuck the xml attributes and values of each object------
    foreach($value as $key => $value2){
		$photo = array();
		$info= new Info();  
		switch ($key){

        case "Organisme":
          $Nom=(string) $value2->Nom;
          $Org_Commune=(string) $value2->Commune;
		  echo '<br>$Org_Commune'.$Org_Commune;
          $Org_Commune_type=(string) $value2->Commune['type'];
          $Org_Commune_insee=(string) $value2->Commune['insee'];
          $Org_Commune_cp=(string)$value2->Commune['cp'];
          $Site_Internet=(string)$value2->SiteInternet;
          break;

        case "Genre":
          $Genre=(string)$value2;
          $GenreId=(string)$value2->attributes()->id;
          break;

        case "Titre":
          $Titre=(string)$value2;
          break;

        case "Corps":
          $Corps_Debut=(string)$value2->Debut;
          $Corps_Descriptif=(string)$value2->Descriptif;
          $Corps_Fin=(string)$value2->Fin;
          
          break;


        case "Communes":
          $i=0;
          foreach( $value2->Commune as $value3) {
            $Commune[$i]['data']=(string) $value3;
            $Commune[$i]['type']=(string) $value3->attributes()->type;
            $Commune[$i]['insee']=(string) $value3->attributes()->insee;
            $Commune[$i]['cp']=(string)$value3->attributes()->cp; $i++;
          }
          break;


        case "Geolocalisation":
          $latitude=(float) $value2->Latitude; 
          $longitude=(float) $value2->Longitude;
          $precision=(string) $value2->Precision;
          break;

           
        case "Dates": 
			$i=0;
			foreach( $value2->Date as $value3) {
				$Date[$i]['type']=(string) $value3->attributes()->type;
				$Date[$i]['jour']=(string) $value3->attributes()->jour;
				$Date[$i]['mois']=(string)$value3->attributes()->mois;
				$Date[$i]['annee']=(string)$value3->attributes()->annee;    
				$i++; // 0 => CRE, 1 => MOD, 2 => VAL, 3 => PAR
        }
        break;

        case "DatesEvenement": 
          $eventDateTotal=array();
          //if not unique start and end
           
          foreach($value2->Date as $single) {
          	$datetype= $single->attributes()->type;
          	if($datetype!='unique' ){
          		$i=0;
          		foreach($single->Date as $value3){
					$Date_ev[$i]['type']=(string)$value3->attributes()->type; 
				$Date_ev[$i]['jour']=(string)$value3->attributes()->jour;
				$Date_ev[$i]['mois']=(string)$value3->attributes()->mois;
				$Date_ev[$i]['annee']=(string)$value3->attributes()->annee;  
				if($Date_ev[$i]['type']=="debut"){ 
					$eventDate = array();
					$dateTimeFrom = DateTime::createFromFormat('Y-m-d',$Date_ev[$i]['annee']."-".$Date_ev[$i]['mois']."-".$Date_ev[$i]['jour']);
					$eventDate['dateTimeFrom'] = new MongoDate(date_timestamp_get($dateTimeFrom));
					//$eventDate['dateTimeFrom'] = new MongoDate($dateUpdatedAt->getTimestamp()); 
				}
			   if($Date_ev[$i]['type']=="fin"){
						$dateTimeEnd = DateTime::createFromFormat('Y-m-d', $Date_ev[$i]['annee']."-".$Date_ev[$i]['mois']."-".$Date_ev[$i]['jour']);
						$eventDate['dateTimeEnd'] = new MongoDate(date_timestamp_get($dateTimeEnd));
							//$eventDate['dateTimeEnd'] = new MongoDate($dateUpdatedAt->getTimestamp()); 
					}
					$i++;
					}
				}
          //if unique or multiple unique
				else{
					$i=0;
					$eventDate = array();
					$lastDate = '';
					foreach($single->Date as $value3){
						$Date_ev[$i]['jour']=(string) $value3->attributes()->jour;
						$Date_ev[$i]['mois']=(string)$value3->attributes()->mois;
						$Date_ev[$i]['annee']=(string)$value3->attributes()->annee; 

						$dateTimeFrom = DateTime::createFromFormat('Y-m-d',$Date_ev[$i]['annee']."-".$Date_ev[$i]['mois']."-".$Date_ev[$i]['jour']);
						$eventDate['dateTimeFrom'] = $eventDate['dateTimeEnd'] = new MongoDate(date_timestamp_get($dateTimeFrom));//print_r($eventDate); echo"</br>";
						$lastDate = $dateTimeFrom;
						$i++; 
					}
					$dateTimeEnd = $lastDate;
				}
				$eventDateTotal[]=$eventDate;
			}  	
            $dateEndPrint = strtotime("+3 day", date_timestamp_get($dateTimeEnd));
			echo '<br>dateEndPrint'.date('Y m d',$dateEndPrint);
			
					 
          break;

        case "Url": $url=(string)$value2; 
         break;

        case "Photos" : if(isset($value2->Photo)){
          $i=0;
          foreach ($value2->Photo as $value3){
            $photo[$i]['Path']=(string)$value3->Path;
            $photo[$i]['Legende']=(string)$value3->Legende;
            $photo[$i]['Credit']=(string)$value3->Credit; $i++;}}
          break;
              }
                 }
//---------------------end of variable storing-----------------------------   
//-------------------------place setting-----------------------------------
//all text for cat search and precision

$txt=$Corps_Debut."".$Corps_Descriptif."".$Corps_Fin."".$url;
//genre formatting for cat
$cat=array("AGENDA");

/*
//catflag to reduce redundancy,if set 1 stops finding categories
$catflag=0;


//genre test and path for yakCat
 
	if(isset($Genre)){
	if ( $Genre!="Autre") {
		$genrepath = str_replace(",","",$Genre);
        $tag = yakcatPathN($genrepath,1 );  
		$cat[]="#".$tag;  }
	else{
	    $txt_format=yakcatPathN($txt,1);
	//danse
	    if(strpos_arr($txt_format,"DANSE")!==false && $catflag==0)
	          { $cat[]="DANSERVIENOCTURNE#DANSER#AUTRE";$catflag=1;	}
		 
	    //concert multiple choices
	    if(strpos_arr($txt_format,array("CONCERT","SPECTACLE","SHOW","SCENE"))!==false && $catflag==0){
         //related categories no $catflag condition
	      if(strpos_arr($txt_format,array("FESTIVAL","FETES"))!==false )
	        {$cat[]="CONCERTSSPECTACLES#FESTIVAL#AUTRE";$catflag=1;}  	
		    elseif(strpos_arr($txt_format,array("MUSIQUE","MUSIC"))!==false )
		    {$cat[]="CONCERTSSPECTACLES#CONCERTSPECTACLEMUSICAL#AUTRE";$catflag=1;} 
		      else {$cat[]="CONCERTSSPECTACLES#AUTRESSCENES#AUTRE";$catflag=1;} }	
		  
	//	sport
	    if(strpos_arr($txt_format,array("SPORT","LOISIRS ","ACTIVITE","CLUB","TOURNOI","CHAMPIONNAT"))!==false && $catflag==0){
		      if(strpos_arr($txt_format,array("PISCINE","BIBLIOTHEQUE"))!==false )
		      {$cat[]="LOISIRSETSPORTS#HORAIRESPISCINEBIBLIOTHEQUE#AUTRE";$catflag=1;}
		       elseif( strpos_arr($txt_format,array("ATELIER","ACTIVITE"))!==false){$cat[]="LOISIRSETSPORTS#ATELIERACTIVITEDELOISIRS#AUTRE";$catflag=1;}
		         elseif( strpos_arr($txt_format,array("JEUX","CONCOURS","RALLYE"))!==false){$cat[]="LOISIRSETSPORTS#JEUXCONCOURSRALLYE#AUTRE";$catflag=1;}
		      	  elseif(strpos_arr($txt_format,array("SOIREE","DINER","REPAS"))!==false){$cat[]="LOISIRSETSPORTS#REPASSOIREE#AUTRE";$catflag=1;}
		            elseif(strpos_arr($txt_format,array("FETES"))!==false){$cat[]="LOISIRSETSPORTS#FETES#AUTRE";$catflag=1;}
		             else{$cat[]="LOISIRSETSPORTS#SPORT#AUTRE";$catflag=1;}}
	//agriculture
	if(strpos_arr($txt_format,array("AGRICULTURE","ENVIRONNEMENT"))!==false && $catflag==0)
	{$cat[]="VIEQUOTIDIENNE#AGRICULTUREENVIRONNEMENT#AUTRE";$catflag=1;}		

	//MAIRIE ADMINISTRATION COMMUNIQUE
	if(strpos_arr($txt_format,array("MAIRIE","ADMINISTRATION","COMMUNIQUE"))!==false && $catflag==0)
	{$cat[]="VIEQUOTIDIENNE#AUTRESCOMMUNIQUESDESADMINISTRATIONSMAIRIE#AUTRE";$catflag=1;}		
	
		}
	}
	*/
	
	// find yakcat from ofcat in the db
	
	
	
	$currentPlace = new Place();
	$currentPlace->filesourceTitle = $fileTitle;
	$currentPlace->title = $Nom;
	$currentPlace->origin = $origin;
	$currentPlace->licence = $licence;
	$currentPlace->formatted_address =  $Org_Commune.", France,";
	$currentPlace->setLocation($latitude, $longitude);
	$currentPlace->status = 1;              
	$currentPlace->zone = 15;
	$currentPlace->address->city=$Org_Commune;
	$currentPlace->address->area= "Bretagne";
	$currentPlace->address->country= "France";
	$currentPlace->address->zip= $Org_Commune_cp;
	$currentPlace->setYakCat($cat);
	$currentPlace->outGoingLink=$Site_Internet;
	$currentPlace->access=2;
	$currentPlace->contact->web=$Site_Internet;
	$currentPlace->user=0;
	$currentPlace->filesourceId='50e42ac09bab884612000000';
					
	$newPlace = $currentPlace->saveToMongoDB('', 1,$updateFlag);	   		
	//info except info->eventDate since it needs arrays and conditions  
    echo '<br><b>PLACE:</b> <br>';  
	var_dump($newPlace);    
  
	
    $datpar=$Date[3]['annee']."-".$Date[3]['mois']."-".$Date[3]['jour'];	

  
//print_r($info);
//print_r($eventDate);

    $info->status=1;
    $info->title=$Titre;
    if($Corps_Debut!="" || $Corps_Descriptif!="" || $Corps_Finorps!="")
		$info->content=$Corps_Debut." ".$Corps_Descriptif." ".$Corps_Fin;
    
    $info->heat = 80;
    $info->location->lat=$latitude;
    $info->location->lng=$longitude;
    $info->originLink=$originLink;
    $info->origin=$origin;
    $info->user=0;
    $info->zone=15;
    $info->address = $Nom." ".$Org_Commune;

	
    $dateUpdatedAt = DateTime::createFromFormat('Y-m-d', $datpar);
	$info->pubDate = new MongoDate($dateUpdatedAt->getTimestamp());
	$info->eventDate = $eventDateTotal;
	echo '<br>dateEndPrint'.date('Y m d',$dateEndPrint);
	$info->dateEndPrint = new MongoDate($dateEndPrint); 
	
    
    $info->print=1;
    $info->outGoingLink=$url;
    $info->access=2;
    $info->yakType=2;
    if($photo[0]['Path']!=''){
		$info->thumb = "/thumb/".createImgThumb(ltrim($photo[0][Path], "/"), $conf);
    }
    
	$info->setYakCatOuest($cat);
	$info->filesourceId = '50e42ac09bab884612000000';
	$info->filesourceTitle = $fileTitle;
	$info->licence = $licence;
	$info->placeId = $newPlace['record']['_id'];
	$info->saveToMongoDB("", 1, $updateFlag);
	echo '<br><b>INFO:</b> <br>';  
	print_r($info);
  
    //------------------------------
    unset($Date_ev);
    unset($Date);
    unset($Commune);
 echo "fin ".$baba."</br>";
  }
}
}
