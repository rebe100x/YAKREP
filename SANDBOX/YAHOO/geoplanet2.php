<?php



if(!empty($_GET['q'])){
$url = "http://where.yahooapis.com/v1/places.q('".$_GET['q']."','FR');start=0;count=5?lang=fr-FR&appid=IH4FxOHV34Hl5tAVuSCX1OjMLiwD1JnmMcBEqmCgy94rMn9y5zzFfUIpUjkYxz4-";
//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//execute post
$result = curl_exec($ch);
//close connection
curl_close($ch);
echo $result;




}else{

echo "
   <a href='http://dev.sandbox.com/YAKWALA/YAHOO/geoplanet2.php?q=paris'>dev.sandbox.com/YAKWALA/YAHOO/geoplanet2.php?q=paris</a>
";
}
?>

