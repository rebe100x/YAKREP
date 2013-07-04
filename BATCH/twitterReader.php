<?php

//Tue, 30 Oct 2012 10:40:12 +0100
/* batch to parse twitter feeds and generate a rss feed
 * */

include_once "../LIB/conf.php";
ini_set('display_errors',1);
$res = '';

if(!empty($_GET['screen_name'])){
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
		'oauth_access_token' => "6651492-1iMT5wqfJ0QcDHdAiVJKAUS0AVdSivMeqC9X0CXzlY",
		'oauth_access_token_secret' => "Pk82ZR4LRrdTbTfIiGS97qdFM6NxTplGhM07k3YzM",
		'consumer_key' => "0f7XLsWByRDCABFPeEQ",
		'consumer_secret' => "9HsVyk4V2TWK1StkSSVBghMnvtXHo09Dgvi9RUywks"
	);

	/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
	$url = 'https://api.twitter.com/1.1/blocks/create.json';
	
	/** Perform a GET request and echo the response **/
	/** Note: Set the GET field BEFORE calling buildOauth(); **/
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?screen_name='.$_GET['screen_name'];
	$requestMethod = 'GET';
	$twitter = new TwitterAPIExchange($settings);
	echo $twitter->setGetfield($getfield)
				 ->buildOauth($url, $requestMethod)
				 ->performRequest();
}else
	echo "use : ?screen_name=Paris";


echo $res;    
?>


