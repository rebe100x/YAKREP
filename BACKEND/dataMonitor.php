<?php 
$m = new Mongo(); // connexion
$db = $m->selectDB("yakwala");
$infoColl = $db->info;

$infoCount = $infoColl->count();
echo '<br>Total info : '.$infoCount;


$infoList =  $infoColl->group(
            array(
            	"key" => array("status"=>true, "print"=>true ),
                "cond"=> array("zone"=>1 ),
                "reduce"=> "function(obj,prev) { prev.csum += obj.id; }",
                "initial"=> "csum = 0 "
            ));
var_dump($infoList);
?>