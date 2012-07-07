<?php




$url = 'http://wherein.yahooapis.com/v1/document';
$fields = array(
            'documentURL'=>urlencode("http://rss.leparisien.fr/leparisien/rss/paris-75.xml"),
            'documentType'=>urlencode("text/rss"),
            'outputType'=>urlencode("rss"),
            'appid'=>'IH4FxOHV34Hl5tAVuSCX1OjMLiwD1JnmMcBEqmCgy94rMn9y5zzFfUIpUjkYxz4-'
        );
/*
        $fields = array(
            'documentContent'=>urlencode("Plusieurs policiers du XVIIIe arrondissement ont été pris à partie, samedi soir, alors qu’ils venaient d’évacuer près de 150 personnes qui voulaient assister à l’anniversaire d’un adolescent...."),
            'documentType'=>urlencode("text/plain"),
            'appid'=>'IH4FxOHV34Hl5tAVuSCX1OjMLiwD1JnmMcBEqmCgy94rMn9y5zzFfUIpUjkYxz4-'
        );
*/
//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

echo $result;




?>