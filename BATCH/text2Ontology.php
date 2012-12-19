<?php 
/* READ A TEXT FILE
 * RETURN AN XML ONTOLGY FILE FOR EXALEAD
 * -- USED FOR THE QUARTIER DATA ONTOLOGY --
 * 
 * */
ini_set('display_errors',1);
$inputFile ='./input/quartier.txt';
$outputFile ='./output/quartier.xml';
$templateFile ='./input/ontologyTemplate.xml';

$ontolgyXML = simplexml_load_file($templateFile);



$f = fopen ($inputFile, "r");
$ln= 0;

while ($line= fgetss ($f)) {
    ++$ln;
    
	
		
    if ($line===FALSE) print ("FALSE\n");
    else{
		$line = str_replace("\r\n", "", $line);
    	$line = utf8_encode($line);
		
    	$entry = $ontolgyXML->Pkg[0]->addChild('Entry');
		$entry->addAttribute('display', $line);
		$form = $entry->addChild('Form');
		$form->addAttribute('level','exact');
		
    } 
    
    
    
}

$res  = $ontolgyXML->asXML($outputFile);

if($res == 1)
	echo "Ontology saved here : ".$outputFile;
else
	'error';
fclose ($f);


?>