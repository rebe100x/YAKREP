<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 

$gQuery = "domaine de Chantilly, Île-de-France, France";
$defaultPlace['address']['country'] = "France";

$gQuery = "France";
$defaultPlace['address']['country'] = "France";


$resTMP = 	preg_match('/\b'.$gQuery.'\b/i',$defaultPlace['address']['country']);
var_dump($resTMP);

if($resTMP)
	echo "FOUND";
else
	echo "NOT FOUND";
?>
</body>
</html>
