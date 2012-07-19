<?php 

if (isset($_POST['cibulUrl'])){
	$path = parse_url($_POST['cibulUrl'], PHP_URL_PATH);

	$pathExploded = explode("/", $path);

	$url = "https://api.cibul.net/v1/events/uid/";

	$fields = array(
		'event-slug'=>urlencode($pathExploded[2]),
		'key'=>'22640375947e8efe580bbe056e4c7b60'
	);

	$fields_string = '';
	foreach ($fields as $key=>$value) {
		$fields_string .= $key.'='.$value.'&';
	}

	$fields_string = rtrim($fields_string,'&');

	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,count($fi    elds));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	$result = curl_exec($ch);
	curl_close($ch);
	echo $result;
}else{
	$value = "";
}