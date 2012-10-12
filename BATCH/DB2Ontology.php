<?php 

/* READ DB AND CREATE THE ONTOLOGY FILE FOR EXALEAD
 * GET THE YAKCAT YAKDICO and the ZONE
 * 
 * A BATCH ./compileOntology.sh COMPILES EVERY NIGHT THE XML to a DICO (see crontab-e)
 * */

$zone = empty($_GET['zone'])?0:$_GET['zone'];
if($zone == 0){
	echo "Zone is not set, please use ?zone=1 for Paris<br><b>BATCH FAILED</b><br>";
	exit;
}
 
$pkg = 'yakdicotitle_zone'.$zone;

ini_set('display_errors',1);
$outputFile ='./output/'.$pkg.'.xml';
$templateFile ='./input/ontologyTemplate.xml';

require_once("../LIB/conf.php");

$conf = new conf();

$placeColl = $conf->mdb()->place;

$query = array( "yakCat" => new MongoId("5056b7aafa9a95180b000000"), "status"=>1, "zone"=> (int)$zone); // ID YAKCAT = YAKDICO
$places = $placeColl->find($query);

var_dump($query);
var_dump($places);
$row = 0;

$ontolgyXML= '<Ontology xmlns="exa:com.exalead.mot.components.ontology">
	<Pkg path="'.$pkg.'" >';

	
foreach($places as $place){
//var_dump($place);
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

$pkg2 = 'yakdicotext_zone'.$zone;
$outputFile2 ='./output/'.$pkg2.'.xml';
copy($outputFile,$outputFile2);

?>