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

	require_once("../LIB/conf.php");

/*
 * Global batch variables
 */


	$sitemap = "";
	$sitemapUrl = "http://cibul.net/sitemap.xml";
	$localSitemap = "./CIBUL/sitemap.xml";
	$sitemap = file_get_contents("http://cibul.net/sitemap.xml");
	file_put_contents($localSitemap, $sitemap);
	

?>
</body>
</html>