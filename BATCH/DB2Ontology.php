<?php 

/* READ DB AND CREATE THE ONTOLOGY FILE FOR EXALEAD
 * GET THE YAKCAT YAKDICO and the ZONE
 * 
 * A BATCH ./compileOntology.sh COMPILES EVERY NIGHT THE XML to a DICO (see crontab-e)
 * */
ini_set('display_errors',1);


$zone = empty($_GET['zone'])?0:$_GET['zone'];
if($zone == 0){
	echo "Zone is not set, please use ?zone=1 for Paris<br><b>BATCH FAILED</b><br>";
	exit;
}

$templateFile ='./input/ontologyTemplate.xml';

require_once("../LIB/conf.php");

$conf = new conf();

$placeColl = $conf->mdb()->place;

$query = array( "yakCat" => new MongoId("5056b7aafa9a95180b000000"), "status"=>1, "zone"=> (int)$zone); // ID YAKCAT = YAKDICO
$places = $placeColl->find($query);

	
$pkg = array('yakdicotitle','yakdicotext');
foreach($pkg as $p){
	$outputFile = './output/'.$p.'_zone'.$zone.'.xml';

	$row = 0;

	$ontolgyXML= '<Ontology xmlns="exa:com.exalead.mot.components.ontology">
		<Pkg path="'.$p.'" >';

		
	foreach($places as $place){
		$row++;
		echo '<br>place:'.indexForOntology($place['title']);
		$ontolgyXML.= '
			<Entry display="'.indexForOntology($place['title']).'"><Form level="normalized"/></Entry>';	
	}
	$ontolgyXML.= '
		</Pkg>
	</Ontology>';

	$res = file_put_contents($outputFile, $ontolgyXML);

	if($res){
		echo "<br>".$row." lines written.<br> Ontology saved here : ".$outputFile;
	}else
		echo 'error';


}


	
	
	
//$outputFile2 ='./output/yakdicotext_zone'.$zone.'.xml';
//copy($outputFile,$outputFile2);

?>