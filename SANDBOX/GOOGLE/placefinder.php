<?php 
/* CALL TO GMAP
 * enter q=htmlenncode(string) in GET param 
 * return the JSON XY
 * 
 * */

if(!empty($_GET['q'])){
$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$_GET['q']."&sensor=false";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
$result = curl_exec($ch);
curl_close($ch);
var_dump($result);
$json = json_decode($result);
echo json_encode($json->results[0]->geometry->location);
}else
    echo "no request<br>try this : http://dev.sandbox.com/YAKWALA/GOOGLE/placefinder.php?q=rue+de+Rivoli";