
<a href="index.php?spec=spec1">test1</a><br>
<a href="index.php?spec=spec2">test2</a><br>
<a href="index.php?spec=spec3">test3</a><br>
<a href="index.php?spec=spec4">test4</a><br>
<a href="index.php?spec=spec5">test5</a><br>
<a href="index.php?spec=spec6">test6</a><br>
<a href="index.php?spec=spec7">test7</a><br>
<a href="index.php?spec=spec8">test8</a><br>
<?php 
include('../../LIB/library.php');
$s = (!empty($_GET['spec']))?$_GET['spec']:"";
	if(!empty($s)){
	switch($s){
		case 'spec1':
	$spec = array('str' => 'Dramatique incendie dans une résidence sociale',
			  'loc' => array(48.8688319,2.376287, 0.000035, 0.000035),
			  'locName'=> "Rue Morand, 75011 Paris, France",
			  'period' => array(134645760,1351728000 ),
			  'from' => 0 ,
			  'size' => 10 );
		break;
		case 'spec2':
	$spec = array('str' => 'Paris',
				  'loc' => array(48.8688319,2.376287, 0.000035, 0.000035),
				  'period' => array(134645760,1351728000 ),
				  'locName'=> "Rue Morand, 75011 Paris, France",
				  'from' => 0 ,
				  'size' => 10 );

		break;
		case 'spec3':
	$spec = array('str' => 'rue',
				  'loc' => array(48.8688319,2.376287, 0.000035, 0.000035),
				  'period' => array(134645760,1351728000 ),
				  'locName'=> "Rue Morand, 75011 Paris, France",
				  'from' => 0 ,
				  'size' => 10 );

		break;
		case 'spec4':
	$spec = array('str' => 'photo',
				  'loc' => array(48.8688319,2.376287, 0.000035, 0.000035),
				  'period' => array(134645760,1351728000 ),
				  'locName'=> "Rue Morand, 75011 Paris, France",
				  'from' => 0 ,
				  'size' => 10 );

		break;
		case 'spec5':
	$spec = array('str' => 'Le Point éphémère conteste sa fermeture',
				  'loc' => array(48.881608,2.368623, 0.000035, 0.000035),
				  'period' => array(134645760,1351728000 ),
				  'locName'=> "Rue Morand, 75011 Paris, France",
				  'from' => 0 ,
				  'size' => 10 );

		break;
		case 'spec6':
	$spec = array('str' => 'Point éphémère',
				  'loc' => array(48.881608,2.368623, 0.000035, 0.000035),
				  'period' => array(134645760,1351728000 ),
				  'locName'=> "le point éphèmère au 206 Quai de Valmy, Paris, France",
				  'from' => 0 ,
				  'size' => 10 );

		break;
		case 'spec7':
	$spec = array('str' => 'photo',
				  'loc' => array(48.881608,2.368623, 0.000035, 0.000035),
				  'period' => array(134645760,1351728000 ),
				  'locName'=> "le point éphèmère au 206 Quai de Valmy, Paris, France",
				  'from' => 0 ,
				  'size' => 10 );

		break;
		case 'spec8':
	$spec = array('str' => 'musique',
				  'loc' => array(48.881608,2.368623, 0.000035, 0.000035),
				  'period' => array(134645760,1351728000 ),
				  'locName'=> "le point éphèmère au 206 Quai de Valmy, Paris, France",
				  'from' => 0 ,
				  'size' => 10 );

		break;
	}

	$test = getTeleportImg($spec);

	echo "<hr>PARAMs DE RECHERCHE<br>Text de recherche : <b>".$spec['str']."</b><br>Lieu de recherche : <b>".$spec['locName']."</b>";
	if(sizeof($test)==0)
		echo "<br><br>no result!";
	foreach($test as $pic){

	echo "<br><img style=\"float:left;margin10px;\" width=\"200\"  src=\"".$pic->fll."\" />";

	}
}