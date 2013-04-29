<?php 

//Target URL for the request
$targetUrl = "http://www.retailmenot.com";

//Get the number of likes from FB
function getLikes($url)
{
     $query = "select like_count from link_stat where url='{$url}'";
     $call = "https://api.facebook.com/method/fql.query?query=" . rawurlencode($query) . "&format=json";
     
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $call);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
     $output = curl_exec($ch);
     curl_close($ch);
     
     if( $output)
	 {
     	$outputObject = json_decode($output);
     	return $outputObject[0]->like_count;
     }
     return false;
}

//Get the number of shares from FB
function getShares($url)
{
     $query = "select share_count from link_stat where url='{$url}'";
     $call = "https://api.facebook.com/method/fql.query?query=" . rawurlencode($query) . "&format=json";
     
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $call);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
     $output = curl_exec($ch);
     curl_close($ch);
     
     if( $output)
	 {
     	$outputObject = json_decode($output);
     	return $outputObject[0]->share_count;
     }
     return false;
}
 
//Get the number of tweets from Twitter
function getTweets($url)
{
     $url = urlencode($url);
     $twitterEndpoint = "http://urls.api.twitter.com/1/urls/count.json?url=%s";
     $fileData = file_get_contents(sprintf($twitterEndpoint, $url));
     $data = json_decode($fileData, true);
     return $data['count'];
}

//Get the number of followers from Twitter
function getFollowers($screenName)
{
	$data = json_decode(file_get_contents('https://api.twitter.com/1/users/lookup.json?screen_name='.$screenName), true);
	return $data[0]['followers_count'];
}
 
//Get the number of +1s from Google
function getPlusones($url)
{
	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_HTTPHEADER => array('Content-type: application/json'),
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$url.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_URL => 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ'
	));

	$output = curl_exec($ch);
	curl_close($ch);

	if( $output)
	{
		$json = json_decode( $output,true);
		return $json[0]['result']['metadata']['globalCounts']['count'];
	}
	return false;
}

?>

<!-- /////////////////////// Demo /////////////////////// -->

<h1>Creating Custom UI for Various Features</h1>
<h3>Targets: <a target="_blank" href="http://www.retailmenot.com">http://www.retailmenot.com</a></h3>
<ul>
	<li>Facebook Like: NOT POSSIBLE WITHOUT A SPECIAL FB RELATIONSHIP</li>
	<li>Facebook Share: <a href="http://www.facebook.com/sharer/sharer.php?u=http://www.retailmenot.com" target="_blank">Share</a></li>	
	<li>Twitter Tweet: <a href="https://twitter.com/share?url=http://www.retailmenot.com" target="_blank">Tweet</a></li>
	<li>Twitter Follow: <a href="https://twitter.com/intent/user?screen_name=retailmenot">Follow @RetailMeNot</a></li>	
	<li>Gmail +1: <a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://www.retailmenot.com" target="_blank">Google+</a></li>
</ul>

<h1>Pulling Info From FB, Twitter, and Google</h1>
<h3>Results for: <a target="_blank" href="http://www.retailmenot.com">http://www.retailmenot.com</a></h3>
<ul>
	<li>Facebook Likes: <?= getLikes($targetUrl); ?></li>
	<li>Facebook Shares: <?= getShares($targetUrl); ?></li>	
	<li>Twitter Tweets: <?= getTweets($targetUrl); ?></li>
	<li>Twitter Followers: <?= getFollowers("retailmenot"); ?></li>	
	<li>Gmail +1s: <?= getPlusones($targetUrl); ?></li>
</ul> 

