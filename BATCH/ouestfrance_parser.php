<?php
/* Read file from oueestfrance , match cats and create the xml file
 * */

ini_set ('max_execution_time', 0);
set_time_limit(0);
require_once("../LIB/conf.php");
ini_set('display_errors',1);
$inputFile ='./input/OF2/infolocale.xml';
$fileTitle = "Ouest France";
$file = "ouest-france.xml";

// Init mongodb connection
$m = new Mongo(); 
$conf=new Conf();
$db = $m->selectDB($conf->db());

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
$xml = "";
$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><items>";
$footer ="</items>";			
		
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

		
		if (empty($dataExists) || $updateFlag) {
			//------loop to stuck the xml attributes and values of each object------
			foreach($value as $key => $value2){
				$photo = array();
				$info= new Info();  
				switch ($key){

				case "Organisme":
				  $Nom=(string) $value2->Nom;
				  $Org_Commune=(string) $value2->Commune;
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
				  
				  //if not unique start and end
				   
				foreach($value2->Date as $single) {
					$eventDate = "";
					$eventDateTotal = "";
					$datetype= $single->attributes()->type;
					if($datetype!='unique' ){
						$i=0;
						foreach($single->Date as $value3){
							$Date_ev[$i]['type']=(string)$value3->attributes()->type; 
							$Date_ev[$i]['jour']=(string)$value3->attributes()->jour;
							$Date_ev[$i]['mois']=(string)$value3->attributes()->mois;
							$Date_ev[$i]['annee']=(string)$value3->attributes()->annee;  
							if($Date_ev[$i]['type']=="debut"){ 
								$dateTimeFrom = DateTime::createFromFormat('Y-m-d',$Date_ev[$i]['annee']."-".$Date_ev[$i]['mois']."-".$Date_ev[$i]['jour']);
								$eventDate = $dateTimeFrom->format(DateTime::ISO8601);
							}
						   if($Date_ev[$i]['type']=="fin"){
								$dateTimeEnd = DateTime::createFromFormat('Y-m-d', $Date_ev[$i]['annee']."-".$Date_ev[$i]['mois']."-".$Date_ev[$i]['jour']);
								$eventDate .= "#".$dateTimeEnd->format(DateTime::ISO8601);
							}else
								$eventDate .= "#".$dateTimeFrom->format(DateTime::ISO8601);
							$i++;
						}
						$eventDateTotal = $eventDate;
					}
				  //if unique or multiple unique
					else{
						$i=0;
						foreach($single->Date as $value3){
							$Date_ev[$i]['jour']=(string) $value3->attributes()->jour;
							$Date_ev[$i]['mois']=(string)$value3->attributes()->mois;
							$Date_ev[$i]['annee']=(string)$value3->attributes()->annee; 

							$dateTimeFrom = DateTime::createFromFormat('Y-m-d',$Date_ev[$i]['annee']."-".$Date_ev[$i]['mois']."-".$Date_ev[$i]['jour']);
							$eventDate = $dateTimeFrom->format(DateTime::ISO8601)."#".$dateTimeFrom->format(DateTime::ISO8601); // no enddate in this file
							$i++; 
							$eventDateTotal .= $eventDate."|";
						}
						$eventDateTotal = substr($eventDateTotal,0,strlen($eventDateTotal)-1);
					}
				}  	
					
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
		
			// find yakcat from ofcat in the db
			$cat = '';
			$yakcatColl = $db->yakcat;
			$res = $yakcatColl->find(
				array('ext_id.of'=>(string)$GenreId)
			);
			foreach($res as $acat){
				$cat .= "#". $acat['_id'];
			}
			 
		  
			
			$datpar=$Date[3]['annee']."-".$Date[3]['mois']."-".$Date[3]['jour'];	
			$pubDate = DateTime::createFromFormat('Y-m-d', $datpar);
			
			$thumb = '';
			if(!empty($photo[0]) && $photo[0]['Path']!=''){
				$thumb = $conf->originalurl().$photo[0]['Path'];
			}
			
			
			
			$itemArray = array(
				'title'=>$Titre,
				'content'=>$Corps_Debut." ".$Corps_Descriptif." ".$Corps_Fin,
				'outGoingLink'=>$url,
				'thumb'=>$thumb,
				'yakCats'=>$cat,
				'freeTag'=>'',
				'pubDate'=>$pubDate->format(DateTime::ISO8601),
				'address'=>$Nom.", ".$Org_Commune.", Bretagne, France",
				'place'=>$Nom.", ".$Org_Commune,
				'longitude'=>$longitude,
				'latitude'=>$latitude,
				'telephone'=>'',
				'mobile'=>'',
				'mail'=>'',
				'transportation'=>'',
				'web'=>$Site_Internet,
				'opening'=>'',
				'eventDate'=> $eventDateTotal,
			);
			
			unset($Date_ev);
			unset($Date);
			unset($Commune);
			
			$xml .= buildXMLItem($itemArray);
		}
	}
}
// echo or write the file according to env var
if(substr($conf->deploy,0,3) == 'dev'){
	header("Content-Type: application/rss+xml; charset=utf-8");
	echo  $header.$xml.$footer;
}else{		
	$fh = fopen('/usr/share/nginx/html/DATA/'.$file, 'w') or die("error");
	fwrite($fh, $header.$xml.$footer);
	fclose($fh);
	echo '<br>File Saved '.$file;
}

