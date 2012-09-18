<?php 

/* READ DB AND CREATE THE ONTOLOGY FILE FOR EXALEAD
 * GET ONLY THE YAKCAT YAKDICO 
 * CREATE THE FILE.XML IN ./OUPUT
 * A BATCH ./compileOntology.sh COMPILES EVERY NIGHT THE XML to a DICO (see crontab-e)
 * */

$pkg = 'yakdicotitle';

ini_set('display_errors',1);
$outputFile ='./output/'.$pkg.'.xml';
$templateFile ='./input/ontologyTemplate.xml';


require_once("../LIB/conf.php");
$conf = new conf($deploy);

$m = new Mongo(); 
$db = $m->selectDB($conf->db());
$placeColl = $db->place;

$query = array( "yakCat" => new MongoId("5056b7aafa9a95180b000000"), "status"=>1); // ID YAKCAT = YAKDICO
$places = $placeColl->find($query);

$row = 0;

$ontolgyXML= '<Ontology xmlns="exa:com.exalead.mot.components.ontology">
	<Pkg path="'.$pkg.'" >';

	
foreach($places as $place){
//var_dump($place);
	$row++;
	echo '<br>place:'.$place['title'];
	$ontolgyXML.= '
		<Entry display="'.$place['title'].'"><Form level="normalized"/></Entry>';	
}
$ontolgyXML.= '
	</Pkg>
</Ontology>';

$res = file_put_contents($outputFile, $ontolgyXML);

if($res){
	echo "<br>".$row." lines written. Ontology saved here : ".$outputFile;
}else
	echo 'error';




?>