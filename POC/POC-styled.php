<!doctype html>
<html>
<head>
	<title>Social Buttons</title>
	<link rel="stylesheet" type="text/css" href="css/social.css" />
</head>
<body>

<?php

########### settings #########
$Share_URL					= 'http://www.vouchercodes.co.uk';
$Share_Text					= 'Voucher Codes - Exclusive Discount Codes and Discount Vouchers';
$Twitter_Screen_Name        = 'vouchercodesuk'; //Twitter screen name
$Facebook_Name  			= 'VoucherCodes.co.uk'; // Facebook Page ID or Name
$Google_Page_Id             = '102373209014405690244'; //Google+ Page ID
$Google_API_key             = 'AIzaSyCNamJ2yYdm-tMT5sesVoPU5LEJY5Wfj98'; //Google+ API key
##############################

$twitter_link = 'http://api.twitter.com/1/users/show.json?screen_name='.$Twitter_Screen_Name;
$facebook_like = 'http://graph.facebook.com/'.$Facebook_Name;
$google_plus_count = 'https://www.googleapis.com/plus/v1/people/'.$Google_Page_Id.'?key='.$Google_API_key.'';

$twitter_data       = twitterSucks();
$facebook_data      = get_data($facebook_like);
$google_data        = get_data($google_plus_count);

function get_data($json_url)
{
	$json_data = file_get_contents($json_url);
	return json_decode($json_data);
}

function format_count($count) 
{
	if($count >= 1000) {
		return number_format($count/1000) . "k";
	}
	else {
		return $count;
	}
}

function get_plusones($url) {
 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $curl_results = curl_exec ($curl);
    curl_close ($curl);
 
    $json = json_decode($curl_results, true);
 
    return intval( $json[0]['result']['metadata']['globalCounts']['count'] );
}

function get_likes($url) {
 
    $json_string = file_get_contents('http://graph.facebook.com/?ids=' . $url);
    $json = json_decode($json_string, true);
 
    return intval( $json[$url]['shares'] );
}
function twitterSucks() {
	// kick things off
	/*$ch = curl_init();
	 
	//set the url
	curl_setopt($ch,CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
	 
	// must be a POST
	curl_setopt($ch,CURLOPT_POST, true);
	 
	// need to post this one variable
	$data = array();
	$data['grant_type'] = "client_credentials";
	curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
	 
	// set up authorization using keys from twitter app:
	$consumerKey = '8MhOqnDkwX0uJoj1wVWvBw';
	$consumerSecret = 'GR9Kt3WmULoq9OdWlIwr9p2cQXf3U5RMbxQvAHagWI';
	curl_setopt($ch,CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
	 
	// set this so we can read the returned information
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	 
	//execute post
	$result = curl_exec($ch);
	 
	//close connection
	curl_close($ch);*/

	//open connection
	$ch = curl_init();

	//set the url, including your parameters
	curl_setopt($ch,CURLOPT_URL, 'https://api.twitter.com/1.1/users/show.json?screen_name=vouchercodesuk');

	// add in the bearer token
	$bearer = "AAAAAAAAAAAAAAAAAAAAAPzkSQAAAAAAOkiCA%2BWN%2FyqFRiabo8GG98y7ums%3Dx6JcUVzfogONd0DPilATYlxuIfpCx6WKVfxvsVkvA"; 
	curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: Bearer ' . $bearer));

	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

	//execute request
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);

	// Print out the JSON encoded response
	$json = json_decode($result, true);
	return intval( $json['followers_count'] );
}
?>

<div class="sociable clearfix">

	<div class="btn-social">
		<div class="count count-fb"><i></i><u></u><?php echo format_count($facebook_data->likes) ?></div>
		<div class="fb-like-wrap">
			<img class="btn-fb" alt="Facebook Like" src="img/fb-like-btn.png" />
			<iframe class="iframe-fb" data-path="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FVoucherCodes.co.uk&amp;send=false&amp;layout=standard&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35" src="" scrolling="no" frameborder="0" style="border:none; overflow:hidden;"></iframe> -->
		</div>
	</div>
	
	<div class="btn-social">
		<div class="count count-tw"><i></i><u></u><a target="_blank" href="http://twitter.com/search/realtime?q=<? echo $Twitter_Screen_Name ?>"><?php echo format_count(twitterSucks()); ?></a></div>
		<a class="btn-tw" target="_blank" href="https://twitter.com/intent/tweet?original_referer=<? echo urlencode($Share_URL) ?>&amp;text=<? echo urlencode($Share_Text) ?>&amp;url=<? echo urlencode($Share_URL) ?>"><b>Tweet</b></a>
	</div>
	
	<div class="btn-social">
		<div class="count count-gp"><i></i><u></u><?php echo format_count($google_data->plusOneCount) ?></div>
		<a class="btn-gp" target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($Share_URL) ?>"><b>+1</b></a>
	</div>
	
</div>
<div class="sociable clearfix">
	
	<div class="btn-social-compact">
		<a class="btn-tw" target="_blank" href="https://twitter.com/intent/tweet?original_referer=<? echo urlencode($Share_URL) ?>&amp;text=<? echo urlencode($Share_Text) ?>&amp;url=<? echo urlencode($Share_URL) ?>"><b>Tweet</b></a>
		<div class="count count-tw"><i></i><u></u><a target="_blank" href="http://twitter.com/search/realtime?q=<? echo $Twitter_Screen_Name ?>"><?php echo format_count(twitterSucks()); ?></a></div>
	</div>
	
	<div class="btn-social-compact">
		<a class="btn-gp" target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode($Share_URL) ?>"><b>+1</b></a>
		<div class="count count-gp"><i></i><u></u><? echo get_plusones('http://www.vouchercodes.co.uk/restaurant-vouchers.html'); ?></div>
	</div>
	
	<div class="btn-follow">
		<a href="https://twitter.com/intent/user?screen_name=<? echo $Twitter_Screen_Name ?>" target="_blank" class="btn-tw"><b>Follow @<? echo $Twitter_Screen_Name ?></b></a>
	</div>
</div>

<br /><br />



<script type="text/javascript" src="scripts/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="scripts/pluginify.js"></script>
<script type="text/javascript" src="scripts/sociable.js"></script>

</body>
</html>