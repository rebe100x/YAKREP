<?php 

/* READ DB AND CREATE THE ONTOLOGY FILE FOR EXALEAD
 * GET THE YAKCAT YAKDICO and the ZONE
 * 
 * A BATCH ./compileOntology.sh COMPILES EVERY NIGHT THE XML to a DICO (see crontab-e)
 * */
ini_set('display_errors',1);


$zone = empty($_GET['zone'])?0:(int)$_GET['zone'];
if($zone == 0){
	echo "Zone is not set, please use ?zone=1 for Paris<br><b>BATCH FAILED</b><br>";
	exit;
}

$yakcat = empty($_GET['yakcat'])?0:$_GET['yakcat'];
if( !($yakcat == "VILLE" || $yakcat == "YAKDICO") ){
	echo "yakcat is not set, please use ?yakcat=VILLE or ?yakcat=YAKDICO<br><b>BATCH FAILED</b><br>";
	exit;
}
$templateFile ='./input/ontologyTemplate.xml';

require_once("../LIB/conf.php");

$conf = new conf();

$placeColl = $conf->mdb()->place;

$yakcatId = null;

if($yakcat == "VILLE" ){
	$yakcatId = new MongoId("507e5a9a1d22b30c44000068");
	$pkg = array('villetitle','villetext');
}
elseif($yakcat == "YAKDICO" ){
	$yakcatId = new MongoId("5056b7aafa9a95180b000000");
	$pkg = array('yakdicotitle','yakdicotext');
}
	
$query = array( "yakCat" => $yakcatId, "status"=>1, "zone"=> (int)$zone); // ID YAKCAT = YAKDICO


$places = $placeColl->find($query);

	

foreach($pkg as $p){
	$outputFile = './output/ontology/'.$p.'_zone'.$zone.'.xml';

	$row = 0;

	$ontolgyXML= '<Ontology xmlns="exa:com.exalead.mot.components.ontology">
		<Pkg path="'.$p.'" >';

		
	foreach($places as $place){
		$row++;
		var_dump($place['yakCat']);
		
		if($yakcat=="VILLE"){
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

	if($res){
		echo "<br>".$row." lines written.<br> Ontology saved here : ".$outputFile;
	}else
		echo 'error';


}


	
	
	

?>