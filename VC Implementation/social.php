<?php

vc::objects('V10_Social', 'V10_Social');

/**
 * Social buttons.
 *
 * 
 * @final
 * @package Classes
 */


Final Class V10_Social extends vc
{
	/**
	 * formatCount
	 * @param Int count
	 * @return Mixed
	 */
	final public function formatCount($count)
	{
		return ($count >= 1000) ? number_format($count / 1000, 1) . "k" : $count;
	}	

	/**
	 * getData
	 * @param String
	 * @return Array
	 */
	final public function getData ($network, $shareType)
	{
		$jsonUrl = '';
		$jsonData = '';
		$pageURL = 'http://www.vouchercodes.co.uk'.$_SERVER['REQUEST_URI'];
		
		if($shareType == 'global') 
		{
			switch ($network)
			{					 
				case 'facebook':
					$jsonUrl = 'http://graph.facebook.com/VoucherCodes.co.uk';
					break;
					
				default:
				case 'google':
					$jsonUrl = 'https://www.googleapis.com/plus/v1/people/102373209014405690244?key=AIzaSyCNamJ2yYdm-tMT5sesVoPU5LEJY5Wfj98';
					break;
			}
		} 
		else 
		{
			switch ($network) 
			{
				case 'facebook':
					$jsonUrl = 'http://graph.facebook.com/?ids=' . $pageURL;
					break;

				default:
				case 'google':
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $pageURL . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
					
 					$jsonData = curl_exec($curl);
 					curl_close ($curl);
					break;
			}
		}
		
		if($jsonUrl != '') {
			$jsonData = file_get_contents($jsonUrl);
		}
		$socialFeed = json_decode($jsonData, true);

		if($shareType == 'global')
		{
			switch ($network)
			{					 
				case 'facebook':
					return $this->formatCount($socialFeed['likes']);
					break;
					
				default:
				case 'google':
					return $this->formatCount($socialFeed['plusOneCount']);
					break;
			}
		}
		else
		{
			switch ($network) 
			{
				case 'facebook':
					return $this->formatCount($socialFeed[$pageURL]['shares']);
					break;
				
				default:
					return $this->formatCount($socialFeed[0]['result']['metadata']['globalCounts']['count']);
					break;
			}
		}
	}
}
?>