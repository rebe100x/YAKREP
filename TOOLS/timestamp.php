<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Yakwala Batch</title>
	<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />
</head>

<body>

<?php
echo mktime(0,0,0,0,0,2038);
echo "<br>".mktime();

$str1 = 'été à la mer';
 
echo mb_convert_case($str1, MB_CASE_TITLE, 'utf-8');
?>
</body>
</html>