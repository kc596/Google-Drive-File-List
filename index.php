<?php
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


	if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
		$client->setAccessToken($_SESSION['access_token']);
		echo "Hello ".$_SESSION['email']."<hr> Access Token exists <hr> Your files : <br>";
		
		$drive = new Google_Service_Drive($client);
		$files = $drive->files->listFiles(array());
  		foreach ($files['files'] as $nm) {
  			echo $nm['name']."<br>";
  		}
	} else {
		// Generate a URL to request access from Google's OAuth 2.0 server :
		$auth_url = $client->createAuthUrl();
		// Redirect the user to $auth_url:
		header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
	}
?>