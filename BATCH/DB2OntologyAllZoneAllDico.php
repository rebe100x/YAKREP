<?php 

/* READ DB AND CREATE THE ONTOLOGY FILE FOR EXALEAD
 * FOR ALL ZONES AND ALL yakcat of the GEOLOCALIZED
 * A BATCH ./compileOntology.sh COMPILES EVERY NIGHT THE XML to a DICO (see crontab-e)
 * */
ini_set('display_errors',1);


$yakcats = array('ville'=>new MongoId("507e5a9a1d22b30c44000068"),'yakdico'=>new MongoId("5056b7aafa9a95180b000000"));
$zones = array(1,2,3,4,5,6,7,8,9,10,11,12,13); 


$templateFile ='./input/ontologyTemplate.xml';
require_once("../LIB/conf.php");
$conf = new conf();
$placeColl = $conf->mdb()->place;


foreach($yakcats as $yakcatName=>$yakcatId) {
	$pkg = array($yakcatName.'title',$yakcatName.'text');
	foreach($zones as $zone) {
		$query = array( "yakCat" => $yakcatId, "status"=>1, "zone"=> (int)$zone); 
		$places = $placeColl->find($query);

		foreach($pkg as $p){
			$outputFile = './output/ontologies/'.$p.'_zone'.$zone.'.xml';

			$row = 0;

			$ontolgyXML= '<Ontology xmlns="exa:com.exalead.mot.components.ontology">
				<Pkg path="'.$p.'" >';

				
			foreach($places as $place){
				$row++;
				var_dump($place['yakCat']);
				
				if($yakcatName=="ville"){
					$level = "exact";
					echo "<br>LEVEL".$level;
				}
				else{
					$level = "normalized";
					echo "<br>LEVEL".$level;
					$place['title'] = mb_strtolower($place['title'], 'UTF-8');
				}
				
				echo '<br>place:'.indexForOntology($place['title']);
				$ontolgyXML.= '
					<Entry display="'.indexForOntology($place['title']).'"><Form level="'.$level.'"/></Entry>';	
			}
			$ontolgyXML.= '
				</Pkg>
			</Ontology>';

			$res = file_put_contents($outputFile, $ontolgyXML);
			chmod($outputFile,0777);
			/*if(file_exists($outputFile)){
				@unlink($outputFile);
			}
			$fp = fopen($outputFile,'x');
			fwrite($fp, $ontolgyXML);
			fclose($fp);*/
			
			if($res){
				echo "<br>".$row." lines written.<br> Ontology saved here : ".$outputFile;
			}else
				echo 'error';


		}
	}
}	




	
	
	

?>