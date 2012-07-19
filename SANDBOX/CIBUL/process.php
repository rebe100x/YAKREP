<?php 

if (isset($_POST['cibulUrl'])){
	$path = parse_url($_POST['cibulUrl'], PHP_URL_PATH);
	$pathExploded = explode("/", $path);

	$url = "https://api.cibul.net/v1/events/uid/";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url . urlencode($pathExploded[2]) . '?key=22640375947e8efe580bbe056e4c7b60');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$json = trim(curl_exec($ch));
	curl_close($ch);

	$result = json_decode($json);

	$uid = $result->data->uid;

	$chuid = curl_init();

	$url = 'https://api.cibul.net/v1/events/';

	curl_setopt($chuid, CURLOPT_URL, $url . $uid . '?key=22640375947e8efe580bbe056e4c7b60');

	curl_setopt($chuid, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($chuid, CURLOPT_SSL_VERIFYPEER, false);

	$json = trim(curl_exec($chuid));
	curl_close($chuid);

	$result = json_decode($json);
	var_dump($result);
	
}else{
	$value = "";
}