<?php 
if(!empty($_GET['q'])){
$url = "http://where.yahooapis.com/geocode?locale=fr_FR&flags=CEL&q=".$_GET['q']."&appid=IH4FxOHV34Hl5tAVuSCX1OjMLiwD1JnmMcBEqmCgy94rMn9y5zzFfUIpUjkYxz4-";

//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
//execute post
$result = curl_exec($ch);
//close connection
curl_close($ch);
echo $result;
}else
    echo "no request, use : <a href='http://dev.sandbox.com/YAKWALA/YAHOO/placefinder.php?q=12+rue+de+Rivoli,+Paris,+France'>http://dev.sandbox.com/YAKWALA/YAHOO/placefinder.php</a>";
    //1600+Pennsylvania+Avenue,+Washington,+DC