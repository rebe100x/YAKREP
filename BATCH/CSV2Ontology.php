<?php 

/* Read a csv file, take col 3 and 6 ( name and history ) 
 * Create the ontology xml file for EXALEAD
 * check if the streets exist in MONGO
 * if not get the XY with GMAP
 * Introduce in mongodb the place ( collection LIEU )
 * */
ini_set ('max_execution_time', 0);
set_time_limit(0);
require_once("../LIB/library.php");
ini_set('display_errors',1);
$inputFile ='./input/voies.csv';
$outputFile ='./output/adresse.xml';
$templateFile ='./input/ontologyTemplate.xml';

$ontolgyXML = simplexml_load_file($templateFile);

$conf = new conf();

$m = new Mongo(); 
$db = $m->selectDB($conf->db());
$placeColl = $db->place;
$batchlogColl = $db->batchlog;

$countInsert = 0;
$countUpdate = 0;
$countGMap = 0;
$row = 0;
$flagForceUpdate = (empty($_GET['forceUpdate']))?0:1;

if (($handle = fopen($inputFile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        
        if($row == 0){
                $csvHead =  $data;
            }else{
            	
	            /*debug
	            for ($c=0; $c < $num; $c++) {
	                echo 'data'.$c.'-'.$data[$c] . "<br />\n";
	                echo '  row:'.$row;
	            }*/
	        
            	//debug
            	echo '<br><b>--- row:'.$row."</b>";
            	
            	
	            	if($row < 500 && $row >=0){
                    
		            $tab[$row-1] = $data;
		            //$line = str_replace("\r\n", "", $line);
			        $streetName = ($data[3]);
			        $streetHisto = ($data[6]);
		            $streetArr = ($data[29]);
		            
		            $streetArr = getZipCodeFromParisArr($streetArr);
		            
			        if(empty($streetName)){
			           echo '<br> Check Name line '.$row;
			        }else{
			        	$streetNameClean = suppr_accents($streetName);
			            
			        }
			        //if(empty($streetHisto))
		            //   echo '<br> Check Histo line '.$row;
		
			        echo '<br><b>Name :</b>'.utf8_decode($streetName);
			        echo '<br><b>Name :</b>'.utf8_decode($streetNameClean);
			        
			        // CREATE ONTOLOGY
			        $entry = $ontolgyXML->Pkg[0]->addChild('Entry');
			        $entry->addAttribute('display', $streetName);
			        $form = $entry->addChild('Form');
			        $form->addAttribute('level','exact');
			        
			       
			        $res = $placeColl->findOne(array('title'=>$streetName));

			        if(empty($res) && $flagForceUpdate == 0){
                        echo "<br>Location not found in DB, we start inserting...";
			        	$location = getLocationGMap(urlencode(utf8_decode($streetNameClean)),'PHP',1);
                        $countGMap++;
	                    if(!empty($location)){
	                        $status = 1;
	                        echo "<br>Found location with GMap<br>";
	                    }
	                     else{
	                     	echo "<br>GMap failed to find location. The place is stored in status 10.<br>";
	                         $status = 10;	
	                     }
	                         $countInsert++;  
	                         $placeColl->save(array(
				        	    "title"=>$streetName,
				        	    "content"=>$streetHisto,
				        	    "origin"=>"http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47&portlet_id=121",
				        	    "access"=>1,  
		                        "licence"=> "ODBL Paris" ,
		                        "outGoingLink" =>"",
	                            "yakCat" => array('idCat'=>1, 'name'=>'adresse', 'level'=>1),   
		                        "creationDate" => new MongoDate(gmmktime()),
				        	    "lastModifDate" => new MongoDate(gmmktime()),
				        	    "location"=>array('lat'=>$location[0],'lng'=>$location[1]),
	                            "address"=> $streetName.", ".$streetArr.", Paris, France",
				        		"status" =>$status,
				        	    "user" => 0,
				        	    "zone"=>1
				        	 ));
			        }else{
			        	if($flagForceUpdate == 1){
				        	$countUpdate++;
				        	echo "<br>Location found in DB, but running with forceUpdate => we update.";
				        	$location = getLocationGMap(urlencode(utf8_decode($streetName)),'PHP',1);
	                        $countGMap++;
	                        if(!empty($location)){
	                            $status = 1;
	                            echo "<br>Found location with GMap<br>";
	                        }
	                         else{
	                            echo "<br>GMap failed to find location. The place is stored in status 10.<br>";
	                             $status = 10;  
	                         }
					        $placeColl->update(array("_id"=> $res['_id']),array(
			                            "title"=>$streetName,
			                            "content"=>$streetHisto,
			                            "origin"=>"http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47&portlet_id=121",
			                            "access"=>1,  
			                            "licence"=> "ODBL Paris" ,
			                            "outGoingLink" =>"",
					                    "yakCat" => array('idCat'=>1, 'name'=>'adresse', 'level'=>1),
			                            "creationDate" => new MongoDate(gmmktime()),
			                            "lastModifDate" => new MongoDate(gmmktime()),
			                            "location"=>array('lat'=>$location[0],'lng'=>$location[1]),
					                    "address"=> $streetName.", ".$streetArr.", Paris, France",
			                            "status" =>$status,
			                            "user" => 0,
			                            "zone"=>1
			                         ));
			        	}else
			        	    echo "<br>Location found in DB, we don't do anything.";
			          	
	                }
                }
           }	    
        $row++;
            
    }
    
    fclose($handle);
}


$res  = $ontolgyXML->asXML($outputFile);

if($res == 1){
	$log = "<br><br>=================<br>Success :".$row." lines written. Ontology saved here : ".$outputFile."<br><br>Data inserted in DB: 
	<br>Inserted : ".$countInsert."
	<br>Updated : ".$countUpdate."
	<br>Calls to GMap : ".$countGMap;
	$status = 0;
}
else{
    $log .= "Error";
    $status = 0;
}
	
echo "<br>".$log;

$batchlogColl->save(
    array(
    "batchName"=>$_SERVER['PHP_SELF'],
    "datePassage"=>new MongoDate(gmmktime()), // now
    "dateNextPassage"=>new MongoDate(2143152000), // far future = one shot batch
    "log"=>$log,
    "status"=>$status
    ));
    

/*
array({
    title:"place Maurice Couve de Murville",
    content :"",
    thumb : ""
    origin:"http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47&portlet_id=121",   
    access: 1
    licence: "ODBL Paris"
    outGoingLink : ""
    creationDate : 132154654,
    lastModifDate : 132132165,
    location : [2.032545,48.132165]
    status : 1,
    user : 0,
    zone: 1
    }
)   
    


?>