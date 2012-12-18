<?php

//Tue, 30 Oct 2012 10:40:12 +0100
/* batch to parse csv files and generate a rss feed
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);


if(!empty($_GET['file_name'])){
	header("Content-Type: application/rss+xml; charset=utf-8");
	$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
	<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:atom=\"http://www.w3.org/2005/Atom\" >
		<channel>
			<title><![CDATA[csv feeds]]></title>
			<link><![CDATA[http://www.yakwla.fr]]></link>
			<description><![CDATA[]]></description>
			<generator>Yakwala</generator>
			<lastBuildDate>".date('D,j M Y G:i:s O')."</lastBuildDate>
			<copyright><![CDATA[]]></copyright>"; 
	$file_name = $_GET['file_name'];
	$rss = "";
	$row = 0;
	$filenameInput = "./input/".$file_name.".csv";
	if (($handle = fopen($filenameInput, "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {     
			if($row > 0){
				foreach ($data as $key => &$value) {
					$value = utf8_encode(trim($value));
				}
				$rss .= "
					<item>
					<title><![CDATA[".($data[0])."]]></title>
					<description><![CDATA[".$data[3]." ".$data[4]." ".$data[5]." ".$data[6]." ".$data[7]." ".$data[8]." ".$data[10]." ".$data[11]." ".$data[15]." ".$data[16]." ".$data[17]."]]></description>
					<link><![CDATA[".$data[17]."]]></link>
					<pubDate>".date('d m Y')."</pubDate>
					<guid isPermaLink='false' ><![CDATA[".$data[15]."]]></guid>
					</item>
					";
				}
				$row++;	
			}
			
		}
		
	fclose($handle); 	
	
}else
	echo "use : ?file_name=locationdevelo";

$footer ="
	</channel>
</rss>";

echo $header.$rss.$footer;    
?>


