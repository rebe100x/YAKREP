<?php 

/* READ yakNE AND CREATE THE ONTOLOGY FILE FOR EXALEAD
 * A BATCH ./compileOntology.sh COMPILES EVERY NIGHT THE XML to one DICO (see crontab-e)
 * */
ini_set('display_errors',1);



$templateFile ='./input/ontologyTemplate.xml';
$outputFile = './output/ontologies/yakne.xml';

require_once("../LIB/conf.php");
$conf = new conf();
$yakNEColl = $conf->mdb()->yakNE;

$yakNE = $yakNEColl->find(array('status'=>1));
$ontolgyXML= '<Ontology xmlns="exa:com.exalead.mot.components.ontology">
	<Pkg path="yakNE" >';
$row = 0;
foreach($yakNE as $ne) {
	
	
	$row++;
	
	//echo '<br>NE:'.$ne['title'];
	foreach($ne['match'] as $match){
		if(empty($match['level']))
			$level = "normalized";
		else
			$level = $match['level'];
		//echo '<br>-->match:'.$match['title'];
		$ontolgyXML.= '
			<Entry display="'.indexForOntology($match['title']).'"><Form level="'.$level.'"/></Entry>';	
	}
	

	
}	
$ontolgyXML.= '
	</Pkg>
</Ontology>';
	$res = file_put_contents($outputFile, $ontolgyXML);
	chmod($outputFile,0777);
		
	if($res){
		echo "<br>".$row." lines written.<br> Ontology saved here : ".$outputFile;
	}else
		echo 'error';





	
	
	

?>