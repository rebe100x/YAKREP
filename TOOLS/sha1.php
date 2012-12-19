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
$pass = 'yakwala2012kjbjd23aplkfs';
echo "<br>1: ".$pass;
$pass2 = sha1($pass);
echo "<br>2: ".$pass2;
$hash = sha1('la lune est belle');
echo "<br>hash: ".$hash;
$pass3 = sha1($pass.$hash);
echo "<br>3: ".$pass3;

?>
</body>
</html>