<?php

//Tue, 30 Oct 2012 10:40:12 +0100
/* batch to parse parisien.fr feeds and generate a rss feed
 * */

 /*
 http://rss.leparisien.fr/leparisien/rss/paris-75.xml
 http://www.leparisien.fr/seine-et-marne-77/rss.xml
 http://www.leparisien.fr/yvelines-78/rss.xml
 http://www.leparisien.fr/essonne-91/rss.xml
 http://www.leparisien.fr/hauts-de-seine-92/rss.xml
 http://www.leparisien.fr/seine-saint-denis-93/rss.xml
 http://www.leparisien.fr/val-de-marne-94/rss.xml
 http://www.leparisien.fr/val-d-oise-95/rss.xml
 
 */
include_once "../LIB/conf.php";
ini_set('display_errors',1);


if(!empty($_GET['feed_name'])){
	header("Content-Type: application/rss+xml; charset=utf-8");
	$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
	<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:atom=\"http://www.w3.org/2005/Atom\" >
		<channel>
			<title><![CDATA[Parisien.fr feeds]]></title>
			<link><![CDATA[http://www.yakwla.fr]]></link>
			<description><![CDATA[]]></description>
			<generator>Yakwala</generator>
			<lastBuildDate>".date('D,j M Y G:i:s O')."</lastBuildDate>
			<copyright><![CDATA[]]></copyright>"; 
	$feed_name = $_GET['feed_name'];
	$rss = "";
				
	$url = "http://rss.leparisien.fr/".$feed_name;
	$ch = curl_init ($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	$rawdata=curl_exec($ch);
	curl_close ($ch);
	$infos = simplexml_load_string($rawdata);
	
	foreach ($infos->channel->item as $info) {
		
		$img = !empty($info->enclosure['url'])?$info->enclosure['url']:"";
		
	$rss .= "
		<item>
		<title><![CDATA[".($info->title)."]]></title>
		<description><![CDATA[".(!empty($img)?"<img src='".$img."' />":"").($info->description)." ]]></description>
		<link><![CDATA[".$info->link."]]></link>
		<pubDate>".$info->pubDate."</pubDate>
		<guid isPermaLink='false' ><![CDATA[".$info->guid."]]></guid>
		</item>
		";
	
	}
	
	
	$footer ="
	</channel>
</rss>";

	echo $header.$rss.$footer; 

}else{
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/leparisien/rss/paris-75.xml\">paris</a><br>";
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/seine-et-marne-77/rss.xml\">seine et marne 77</a><br>";
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/yvelines-78/rss.xml\">yveline 78</a><br>";
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/essonne-91/rss.xml\">essonne 91</a><br>";
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/hauts-de-seine-92/rss.xml\">hauts de seine 92</a><br>";
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/seine-saint-denis-93/rss.xml\">seine saint denis 93</a><br>";
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/val-de-marne-94/rss.xml\">val de marne</a><br>";
	echo "use : <a href=\"".$_SERVER['PHP_SELF']."?feed_name=/val-d-oise-95/rss.xml\">val d'oise</a><br>";
}
   
?>

