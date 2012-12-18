<?php



if(!empty($_GET['woeid'])){
$url = "http://where.yahooapis.com/v1/place/".$_GET['woeid']."?format=json&appid=IH4FxOHV34Hl5tAVuSCX1OjMLiwD1JnmMcBEqmCgy94rMn9y5zzFfUIpUjkYxz4-";
//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//execute post
$result = curl_exec($ch);
//close connection
curl_close($ch);
echo "<br>PLACE:<br>".$result;

$url = "http://where.yahooapis.com/v1/place/".$_GET['woeid']."/neighbors?appid=IH4FxOHV34Hl5tAVuSCX1OjMLiwD1JnmMcBEqmCgy94rMn9y5zzFfUIpUjkYxz4-";
//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//execute post
$result = curl_exec($ch);
//close connection
curl_close($ch);
echo "<br>NEIGHBORS:<br>".$result;




}else{

echo "
    PARIS : <a href='http://dev.sandbox.com/YAKWALA/YAHOO/geoplanet.php?woeid=12597155'>http://dev.sandbox.com/YAKWALA/YAHOO/geoplanet.php?woeid=12597155</a><br />
    PARC DES PRINCES : <a href='http://dev.sandbox.com/YAKWALA/YAHOO/geoplanet.php?woeid=22144818'>http://dev.sandbox.com/YAKWALA/YAHOO/geoplanet.php?woeid=22144818</a><br />
";
}
?>

