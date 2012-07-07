<?php 

/* Read a csv file, take col 3 and 6 ( name and history ) 
 * Create the ontology xml file for EXALEAD
 * check if the streets exist in MONGO
 * if not get the XY with GMAP
 * Introduce in mongodb the place ( collection LIEU )
 * */

ini_set('display_errors',1);
$inputFile ='./input/voies.csv';
$outputFile ='./output/adresse.xml';
$templateFile ='./input/ontologyTemplate.xml';

$ontolgyXML = simplexml_load_file($templateFile);

$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$placeColl = $db->place;

$row = 0;

if (($handle = fopen($inputFile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        
        if($row == 0){
                $csvHead =  $data;
            }else{
            	
	            /*
	            for ($c=0; $c < $num; $c++) {
	                echo 'data'.$c.'-'.$data[$c] . "<br />\n";
	                echo '  row:'.$row;
	            }*/
	        
	            $tab[$row-1] = $data;
	            //$line = str_replace("\r\n", "", $line);
		        $streetName = utf8_encode($data[3]);
		        $streetHisto = utf8_encode($data[6]);
		        if(empty($streetName)){
		           echo '<br> Check Name line '.$row;
		        }
		        
		        //if(empty($streetHisto))
	            //   echo '<br> Check Histo line '.$row;
	
		        echo '<br> name'.$streetName;
		        
		        // CREATE ONTOLOGY
		        $entry = $ontolgyXML->Pkg[0]->addChild('Entry');
		        $entry->addAttribute('display', $streetName);
		        $form = $entry->addChild('Form');
		        $form->addAttribute('level','exact');
		        
		        // CHECK IN DB IF THE PLACE EXISTS
		        $query = array( "title" => $streetName);
	            $place = $placeColl->findOne($query);
		        if(empty($place)){
		        	/*
		        	// GET THE XY
		        	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$_GET['q']."&sensor=false";
					$ch = curl_init();
					curl_setopt($ch,CURLOPT_URL,$url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
					$result = curl_exec($ch);
					curl_close($ch);
					//echo $result;
					$json = json_decode($result);
					$res = json_encode($json->results[0]->geometry->location);
		        	*/
		        	$placeColl->save(array("title"=>$streetName,
		        	    "content"=>$streetHisto,
		        	    "origin"=>"http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47&portlet_id=121",
		        	    "yakCat"=> array(array("id"=>1,"name"=>"place","level"=>1),array("id"=>1,"name"=>"street","level"=>2)),
		        	     "creationDate" => mktime(),
		        	     "lastModifDate" => mktime(),
		        	    "lt"=>9,
                        "lg"=>7,
		        		"status" =>0,
		        	    "user" => 0
		        	 )	        	
		        	);
		        }
	           
            }
        
            
        $row++;
    }
    fclose($handle);
}


$res  = $ontolgyXML->asXML($outputFile);

if($res == 1)
	echo "<br>".$row." lines written. Ontology saved here : ".$outputFile;
else
	'error';




?>