<?php

//Tue, 30 Oct 2012 10:40:12 +0100
/* batch to parse twitter feeds and generate a rss feed
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);


if(!empty($_GET['screen_name'])){
	header("Content-Type: application/rss+xml; charset=utf-8");
	$header = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>
	<rss version=\"2.0\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" xmlns:atom=\"http://www.w3.org/2005/Atom\" >
		<channel>
			<title><![CDATA[Twitter feeds]]></title>
			<link><![CDATA[http://www.yakwla.fr]]></link>
			<description><![CDATA[]]></description>
			<generator>Yakwala</generator>
			<lastBuildDate>".date('D,j M Y G:i:s O')."</lastBuildDate>
			<copyright><![CDATA[]]></copyright>"; 
	$screen_name = $_GET['screen_name'];
	$rss = "";
				
	$url = "https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=false&trim_user=false&count=20&exclude_replies=true&contributor_details=false&screen_name=".$screen_name;

		//https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=false&trim_user=false&count=20&exclude_replies=true&contributor_details=false&screen_name=".$screen_name	//?screen_name=Century21_PP
	
	$ch = curl_init ($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	$rawdata=curl_exec($ch);
	curl_close ($ch);
	$tweets = json_decode($rawdata);
	//print_r($tweets);
	foreach($tweets as $tweet){
	//var_dump($tweet);exit;
	
	$img = !empty($tweet->entities->media)?$tweet->entities->media[0]->media_url:"";
	//	echo $tweet->user->profile_background_image_url." == http://a0.twimg.com/images/themes/theme1/bg.png";
	if($tweet->user->profile_background_image_url == "http://a0.twimg.com/images/themes/theme1/bg.png")	
		$img = "http://upload.wikimedia.org/wikipedia/fr/thumb/c/c8/Twitter_Bird.svg/220px-Twitter_Bird.svg.png";
	else
		$img = $tweet->user->profile_background_image_url;
	
	$pubDate = date( 'D,j M Y G:i:s O', strtotime($tweet->created_at) );
	$rss .= "
		<item>
		<title><![CDATA[".($tweet->text)."]]></title>
		<description><![CDATA[]]></description>
		<link><![CDATA[https://twitter.com/rebe100x/status/".$tweet->id_str."]]></link>
		<pubDate>".$pubDate."</pubDate>
		<guid isPermaLink='false' ><![CDATA[".$tweet->id_str."]]></guid>
		<enclosure url='".$img."' type='image/jpeg'/>
		</item>
		";
	}
	

}else
	echo "use : ?screen_name=Century21_PP";

$footer ="
	</channel>
</rss>";

echo $header.$rss.$footer;    
?>


