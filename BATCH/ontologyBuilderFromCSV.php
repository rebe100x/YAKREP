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

$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$placeColl = $db->place;
$batchlogColl = $db->batchlog;


$row = 0;


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
            	echo '<br>row:'.$row."<br>";
            	
            	if($row > 1000)
                    exit;
                
	            	if($row <1000 && $row >=150){
	            	  
		            $tab[$row-1] = $data;
		            //$line = str_replace("\r\n", "", $line);
			        $streetName = utf8_encode($data[3]);
			        $streetHisto = utf8_encode($data[6]);
			        if(empty($streetName)){
			           echo '<br> Check Name line '.$row;
			        }
			        
			        //if(empty($streetHisto))
		            //   echo '<br> Check Histo line '.$row;
		
			        echo '<br> name :'.utf8_decode($streetName);
			        
			        // CREATE ONTOLOGY
			        $entry = $ontolgyXML->Pkg[0]->addChild('Entry');
			        $entry->addAttribute('display', $streetName);
			        $form = $entry->addChild('Form');
			        $form->addAttribute('level','exact');
			        
			        
			        $location = getLocationGMap(utf8_decode($streetName));
			        
			        if(!empty($location))
	                    $status = 1;
	                 else
	                     $status = 10;
	                     
			        $res = $placeColl->findOne(array('title'=>$streetName));
			        if(empty($res)){
				    $placeColl->save(array(
				        	    "title"=>$streetName,
				        	    "content"=>$streetHisto,
				        	    "origin"=>"http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47&portlet_id=121",
				        	    "access"=>1,  
		                        "licence"=> "ODBL Paris" ,
		                        "outGoingLink" =>"",
		                        "creationDate" => mktime(),
				        	    "lastModifDate" => mktime(),
				        	    "location"=>array('lat'=>$location[0],'lng'=>$location[1]),
				        		"status" =>$status,
				        	    "user" => 0,
				        	    "zone"=>1
				        	 ));
			        }else{
				        $placeColl->update(array("_id"=> $res['_id']),array(
		                            "title"=>$streetName,
		                            "content"=>$streetHisto,
		                            "origin"=>"http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47&portlet_id=121",
		                            "access"=>1,  
		                            "licence"=> "ODBL Paris" ,
		                            "outGoingLink" =>"",
		                            "creationDate" => mktime(),
		                            "lastModifDate" => mktime(),
		                            "location"=>array('lat'=>$location[0],'lng'=>$location[1]),
		                            "status" =>$status,
		                            "user" => 0,
		                            "zone"=>1
		                         ));
			          	
	                }
                }
           }	    
        $row++;
            
    }
    
    fclose($handle);
}


$res  = $ontolgyXML->asXML($outputFile);

if($res == 1){
	$log = $row." lines written. Ontology saved here : ".$outputFile;
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
    "datePassage"=>mktime(), // now
    "dateNextPassage"=>2143152000, // far future = one shot batch
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