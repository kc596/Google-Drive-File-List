<?php
	if(1<0){
		if (!isset($_SESSION))
		{
			session_start();
		}
		session_destroy();
		die();
	}
	
	require_once '/vendor/autoload.php';
	require_once 'core/init.php';
	if(!isset($_SESSION)) { session_start(); }
	$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));

	$client = new Google_Client;													// Build the client object
	$client->setHttpClient($guzzleClient);											// Fixing the ssl error (Curl)
	$client->setApplicationName(" Read-only offline access to a user's Drive");		// set the application name

	$client->setAuthConfig('client_secret/client_secret.json');
	$client->setAccessType("offline");												// offline access
	$client->setIncludeGrantedScopes(true);											// incremental auth
	$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
	$client->addScope("email");

	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] .'/tercept/oauth2callback.php';
	$client->setRedirectUri($redirect_uri);

	$access_token = "ya29.GltHBEddHp-_0Hd6KDigPxgNc0Z1cQNmCZbm98QiiR1aWWa595Bke6Y-FLjtUYjFDTYaVQ8iTl5HLndBnB16oBRkQRQs-SYroVOg_OZmPJeXUDPJ7Qlz3tX5CNPf";
	//$client->setAccessToken($access_token);
	$refreshToken = "1/NPJAQ7QuBlujBR4O7JchAmfiydXV29fb3FGEy_o3gFewJAZM7KAb0hXc_F9NW9lf";
	$client->refreshToken($refreshToken);

	/*print_r($client->getAccessToken());
	echo "<hr><hr><hr>";*/
		$drive = new Google_Service_Drive($client);
		$files = $drive->files->listFiles(array());
  		foreach ($files['files'] as $nm) {
  			echo $nm['name']."<br>";
  		}
?>