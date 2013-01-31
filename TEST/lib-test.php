<!doctype html><html><head><meta charset="utf-8" /><title>YAKWALA BATCH</title></head><body>
<?php 



require_once("../LIB/conf.php");
$conf = new conf();

$m = new Mongo(); 
$db = $m->selectDB($conf->db());

$infoColl = $db->info;
$placeColl = $db->place;
$yakcatColl = $db->yakcat;
$batchlogColl = $db->batchlog;
$statColl = $db->stat;
$feedColl = $db->feed;
$logCallToGMap = 0;
$logLocationInDB = 0;
$logDataInserted = 0;
$logDataUpdated = 0;
$logDataAlreadyInDB = 0;

$arr = '(14e)';
$theArr = rewriteArrondissement($arr);
echo $theArr;

$str =  " 2013-03-19T09:30:00.0Z#2013-03-19T17:00:00.0Z 2013-03-20T09:30:00.0Z#2013-03-20T17:00:00.0Z ";
$arr = explode(' ',$str);
var_dump($arr);
?>
</body>
</html>
