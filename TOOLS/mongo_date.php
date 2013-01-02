<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Yakwala Test</title>
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />
</head>

<body>

<?php

	$dateTime = DateTime::createFromFormat('Y-m-d H:i:s','2012-12-01 12:30:00');
	$mongoDate = new MongoDate(date_timestamp_get($dateTime));
	var_dump($mongoDate);
	echo '<br>';
	var_dump($mongoDate->__toString());
	echo '<br>';
	var_dump($mongoDate->sec);
	echo '<br>';
	echo date('d/m/Y',$mongoDate->sec);

?>
</body>
</html>