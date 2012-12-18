<?php 

/* Read a csv file, 
 * Create the ontology xml file for EXALEAD
 * check if the streets exist in MONGO
 * if not get the XY with GMAP
 * Introduce in mongodb the place ( collection LIEU )
 * */

include_once "../LIB/conf.php";

$inputFile ='./input/voies_small.csv';
$outputFile ='./output/adresse.xml';
$templateFile ='./input/ontologyTemplate.xml';

$ontolgyXML = simplexml_load_file($templateFile);

$origin = "http://opendata.paris.fr/opendata/jsp/site/Portal.jsp?document_id=47&portlet_id=121";
$fileTitle = "rues de Paris";
$licence = "ODBL Paris";
$debug = 1;
			
$row = 0;
$updateFlag = empty($_GET['updateFlag'])?0:1;

$results = array('row'=>0,'parse'=>0,'rejected'=>0,'duplicate'=>0,'insert'=>0,'locErr'=>0,'update'=>0,'callGMAP'=>0,"error"=>0,'record'=>array());

if (($handle = fopen($inputFile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        
        if($row == 0){
                $csvHead =  $data;
            }else{
            	
	           	if($row < 500 && $row >=0){
                    
					sleep(1);
					
					
		            $tab[$row-1] = $data;
		            //$line = str_replace("\r\n", "", $line);
			        $streetName = trim($data[3]);
			        $streetHisto = (!empty($data[6]))?$data[6]:"";
		            $streetArr = (!empty($data[29]))?$data[29]:"";
		            
		            $streetArr = getZipCodeFromParisArr($streetArr);
		            
			        if(empty($streetName)){
			           echo '<br> Check Name line '.$row;
			        }else{
			        	$streetNameClean = suppr_accents($streetName);
			            
			        }
			        
			        //echo '<br><b>Name :</b>'.utf8_decode($streetName);
			        //echo '<br><b>Name :</b>'.utf8_decode($streetNameClean);
			        
					/*
			        // CREATE ONTOLOGY
			        $entry = $ontolgyXML->Pkg[0]->addChild('Entry');
			        $entry->addAttribute('display', $streetName);
			        $form = $entry->addChild('Form');
			        $form->addAttribute('level','exact');
			        */
					// CREATE INSERT IN DB
					$currentPlace = new Place();
					$currentPlace->title = $streetName;
					$currentPlace->content = $streetHisto;
					$currentPlace->origin = $origin;
					$currentPlace->filesourceTitle = $fileTitle;
					$currentPlace->licence = $licence;
					
					
					
					// YakCat
					$cat = array("GEOLOCALISATION#RUE", "GEOLOCALISATION");
					$currentPlace->setYakCat($cat);
					
					$currentPlace->zone = 1;

					$locationQuery = $streetName.',Paris, France';
				
			
					$res = $currentPlace->saveToMongoDB($locationQuery, $debug,$updateFlag);
					
					foreach ($res as $k=>$v) {
						if(isset($v))
							$results[$k]+=$v;
					}
					$results['parse'] ++;	
			
			
			
			
                }
           }
		$results['row'] ++;	
		$row++;            
    }
    
    fclose($handle);
}


//$res  = $ontolgyXML->asXML($outputFile);
$currentPlace->prettyLog($results);    


?>