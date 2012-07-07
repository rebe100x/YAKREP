<?php
ini_set('display_errors',1);
$filenameInput = "./input/centresante2011_3.csv";
$filenameOutput = "./output/centresante2011_3.csv";
$row = 0;
$centressante = array('');
$fieldsProcessed = array('');
$i=0;
$j=0;
if (($handle = fopen($filenameInput, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        //echo "<p> $num fields in line $row: <br /></p>\n";
        
        //for ($c=0; $c < $num; $c++) {
            //echo $data[$c] . "<br />\n";
		//	echo $row;
        //    $centressante[$row] .= $data[$c].";";
        //}
		
        
		if($row == 0){
				$csvHead =  $data;
			}else{
			$centressante[$row] = $data;
			}
		
			
		$i++;
		$row++;
    }
    fclose($handle);
}


$fp = fopen($filenameOutput, 'wt');

// TRAITMENT
$join_id = 0;


//SAUVEGARDE
$i=0;
$j=0;
$csvHead2 = '';

for($k=0;$k<sizeof($csvHead);$k++){
		$csvHead[7] = "nom";
		$csvHead[8] = "rue";
		$csvHead[9] = "zip";
		$csvHead[10] = "ville";
		$csvHead2 .= "\"".$csvHead[$k]."\";";
}

		
$csvHead2 .= "\"join_id\";";
$csvHead2 .= "\"starttime\";";
$csvHead2 .= "\"endtime\";";
$csvHead2 .= "\"stardate\";";
$csvHead2 .= "\"enddate\";";
$csvHead2 .= "\"Liste des Jours\";";
$csvHead2 .= "\"Liste horaires\";";
$csvHead2 .= "\"Praticien\";";
$csvHead2 .= utf8_encode("\"Sympt�mes\";");

//$csvHead2 = utf8_encode($csvHead2);

fwrite($fp, $csvHead2."\n");

foreach ($centressante as $fields) {

	if($join_id == 0)
		$join_id++;
	else{
		if( $lastfields[1] != $fields[1] || $lastfields[7] != $fields[7] || $lastfields[4] != $fields[4] )
			$join_id++;
	}
	
	
	for($j=0;$j<sizeof($fields);$j++){
		
		if(empty($fields[$j]))
			$fields[$j] = "null";
		
		
		$fieldsProcessed[$i] .= "\"".stripslashes($fields[$j])."\";";
		
		
	}
	
	//join_id
	$fieldsProcessed[$i] .= "\"".$join_id."\";";
	//starttime
	$tmp1='';$tmp2='';$starttime=0;$endtime=0;
	$tmp1 = explode(' ',$fields[5]);
	//echo 'ddd'.$fields[5].'<hr>';
	if($tmp1[1] == 'PM' && $tmp1[0] != '12:00:00' )
		$starttime = 12*60;
	$tmp2 = explode(':',$tmp1[0]);
	$starttime += floor($tmp2[0]*60+$tmp2[1]);		
	$fieldsProcessed[$i] .= "\"".$starttime."\";";
	//endtime	
	$tmp1='';$tmp2='';
	$tmp1 = explode(' ',$fields[6]);
	if($tmp1[1] == 'PM' && $tmp1[0] != '12:00:00' )
		$endtime = 12*60;
	$tmp2 = explode(':',$tmp1[0]);
	$endtime += floor($tmp2[0]*60+$tmp2[1]);
	$fieldsProcessed[$i] .= "\"".$endtime."\";";
	
	$fieldsProcessed[$i] .= "\"01/01/2012\";";
	$fieldsProcessed[$i] .= "\"01/01/2013\";";
	
	$inc = 0;
	$lastField = array();
	$listeJours = "";
	$listeHoraire = "";
	foreach($centressante as $fields2){
		
		if($inc > 0){
			//echo $fields2[1]."==".$fields[1]."---".$fields2[7]."==".$fields[7].'<hr>';
			if($fields2[1]==$fields[1] && $fields2[7]==$fields[7]){
				//echo "/".$fields2[4]."/<hr>". $listeJours;
				if(preg_match("/".$fields2[4]."/", $listeJours) == 0){
					$listeJours .= " ".$fields2[4];
					//echo $inc."tete<hr>".$listeJours;
				}
			}
			
			if($fields2[1]==$fields[1] && $fields2[7]==$fields[7] && $fields2[4] == $fields[4]){	
				if(preg_match("/".$fields2[5]."/", $listeHoraire) == 0){			
					$listeHoraire .= $fields2[5]." - ".$fields2[6] . " et " ;
				}
			}
			
		}
		
		$inc++;
	}	
	
	$fieldsProcessed[$i] .= "\"".$listeJours."\";";
	
	$listeHoraire = substr($listeHoraire,0,strlen($listeHoraire)-4);
	$fieldsProcessed[$i] .= "\"".$listeHoraire."\";";
	
	$praticien = '';
	$symptome = '';
	
	switch(utf8_decode($fields[1])){
		case 'M�decine g�n�rale':
			$praticien = 'g�n�raliste toubib m�decin medecin';
		break;
		case 'M�decine interne':
			$praticien = 'interniste';
		break;
		case 'Acupuncture':
			$praticien = '';
		break;
		case 'Alcoologie':
			$praticien = '';
		break;
		case 'Allergologie':
			$praticien = '';
		break;
		case 'Andrologie':
			$praticien = '';
		break;
		case 'Angiologie-phl�bologie':
			$praticien = '';
		break;
		case 'Biologie':
			$praticien = "Labo analyse,laboratoire d'analyse";
		break;
		case 'Cardiologie':
			$praticien = 'cardiologue';
		break;
		case 'Chirurgie Buccale':
			$praticien = 'chirurgien-dentiste dentiste stomatologue stomato stomatologiste';
		break;
		case 'Chirurgie dentaire':
			$praticien = 'chirurgien-dentiste dentiste';
			$symptome = 'mal aux dents, rage de dent';
		break;
		case 'Chirurgie esth�tique':
			$praticien = 'chirurgien';
		break;
		case 'Chirurgie orthop�dique':
			$praticien = 'chirurgien orthop�diste';
		break;
		case 'Chirurgie plastique ':
			$praticien = 'chirurgien';
		break;
		case 'Chirurgie vasculaire':
			$praticien = 'chirurgien';
		break;
		case 'Chirurgie visc�rale':
			$praticien = 'chirurgien';
		break;
		case 'Consultation Sage-femme':
			$praticien = '';
		break;
		case 'Dentaire':
			$praticien = 'dentiste';
			$symptome = 'mal aux dents, rage de dent';
		break;
		case 'Dermatologie':
			$praticien = 'dermatologue dermeto';
		break;
		case 'Di�t�tique':
			$praticien = 'Di�t�ticien';
		break;
		case 'Echographie':
			$praticien = '';
		break;
		case 'Endocrinologie':
			$praticien = 'Endocrino Endocrinologue';
		break;
		case 'Gastro-ent�rologie':
			$praticien = 'Gastro-ent�rologue';
		break;
		case 'G�riatrie':
			$praticien = 'G�riatre';
		break;
		case 'Gyn�cologie':
			$praticien = 'Gyn�cologue gyn�co';
		break;
		case 'H�matologie':
			$praticien = 'H�matologue H�mato';
		break;		
		case 'Hom�opathie':
			$praticien = 'Hom�opathe';
		break;		
		case 'Imagerie':
			$praticien = 'radiologue';
		break;
		case 'Implantologie':
			$praticien = 'chirurgien dentiste';
		break;
		case 'Ionophor�se':
			$praticien = '';
		break;
		case 'Kin�sith�rapie':
			$praticien = 'kin�';
		break;
		case 'Mammographie':
			$praticien = 'radiologue';
		break;
		case 'M�decine agr��e':
			$praticien = 'M�decin agr��';
		break;
		case 'N�phrologie':
			$praticien = 'N�phrologue';
		break;
		case 'Neurologie':
			$praticien = 'Neurologue';
		break;
		case 'Ophtalmologie':
			$praticien = 'Ophtalmologue Ophtalmo';
		break;
		case 'Orthodontie':
			$praticien = 'Orthodontiste';
		break;
		case 'Orthophonie':
			$praticien = 'Orthophoniste';
		break;
		case 'Orthoptie':
			$praticien = 'Ophtalmologue Ophtalmo';
		break;
		case 'Ost�opathie':
			$praticien = 'Ost�opathe Ost�o';
		break;		
		case 'Oto-rhino-laryngologie':
			$praticien = 'Oto-rhino-laryngologue oto-rhino otorino';
		break;	
		case 'Panoramique dentaire':
			$praticien = 'dentiste';
		break;	
		case 'Parodontologie':
			$praticien = 'Parodontiste';
		break;	
		case 'P�diatrie':
			$praticien = 'P�diatre';
		break;	
		case 'P�dicurie':
			$praticien = 'P�dicure';
		break;
		case 'P�dicurie / Podologie':
			$praticien = 'P�dicure Podologue';
		break;
		case 'P�dodontie':
			$praticien = 'P�dodontiste dentiste';
		break;
		case 'P�dopsychiatrie':
			$praticien = 'psychiatre psy';
		break;
		case 'Phl�bologie':
			$praticien = 'Phl�bologue';
		break;
		case 'Pneumologie':
			$praticien = 'Pneumologue';
		break;
		case 'Podologie':
			$praticien = 'Podologue';
		break;
		case 'Psychiatrie':
			$praticien = 'psychiatre psy';
		break;
		case 'Psychologie':
			$praticien = 'Psychologue psy';
		break;
		case 'Rhumatologie':
			$praticien = 'Rhumatologue rhumato';
		break;
		case 'Soins infirmiers':
			$praticien = 'infirmier infirmi�re';
			$symptome = 'piqure piq�re';
		break;
		case 'Stomatologie':
			$praticien = 'Stomatologue Stomato';
		break;
		case 'Tabacologie':
			$praticien = 'Tabacologue';
		break;
		case 'Urologie':
			$praticien = 'Urologue Urologiste';
		break;
		case 'Vaccinations internationales':
			$praticien = 'Vaccination';
		break;
		}
	$fieldsProcessed[$i] .= "\"".utf8_encode($praticien)."\";";
	
	$fieldsProcessed[$i] .= "\"".utf8_encode($symptome)."\";";
	
	$fieldsProcessed[$i] .= "\n";
	
    fwrite($fp, $fieldsProcessed[$i]);
	$i++;	
	
	$lastfields = $fields;
	
}

fclose($fp);














