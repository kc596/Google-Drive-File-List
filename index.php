<?php
	require_once '/vendor/autoload.php';
	
	$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));

	$client = new Google_Client;													//
	$client->setHttpClient($guzzleClient);											//
	$client->setApplicationName("Client_Library_Examples");							//
	$client->setDeveloperKey("AIzaSyDCrd-HUEcNmrkXLt1bdtUuidKf21zHu7A");			//
	$service = new Google_Service_Books($client);									//build the service object
	$optParams = array('filter' => 'free-ebooks');									//
	$results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);	//

	foreach ($results as $item) {
	  echo $item['volumeInfo']['title'], "<br /> \n";
	}
?>