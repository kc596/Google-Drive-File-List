<?php
	require_once '/vendor/autoload.php';
	
	$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));

	$client = new Google_Client;													// Build the client object
	$client->setHttpClient($guzzleClient);											// Fixing the ssl error (Curl)
	$client->setApplicationName("Client_Library_Examples");							// set the application name
	$client->setDeveloperKey("AIzaSyDCrd-HUEcNmrkXLt1bdtUuidKf21zHu7A");			// API key for public data access
	$service = new Google_Service_Books($client);									// build the service object
	
	/**
	 * Calling an API :
	 * Each API provides resources and methods, usually in a chain. 
	 * These can be accessed from the service object in the form $service->resource->method(args)
	 */
	$optParams = array('filter' => 'free-ebooks');									//
	$results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);	//

	foreach ($results as $item) {													// Handling the result
	  echo $item['volumeInfo']['title'], "<br /> \n";
	}
?>