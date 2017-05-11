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

	/* ################### STORING IN DATABASE #################### */
		$db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$sql = "Select email, refresh_token from user";
		$result = mysqli_query($db_connection,$sql);
		while($row = mysqli_fetch_assoc($result))
	    {
	        $email = $row['email'];
	        $refreshToken = $row['refresh_token'];
	        $client->refreshToken($refreshToken);
			$drive = new Google_Service_Drive($client);
			echo "<hr> Files of ".$email." : <hr>";
			$files = $drive->files->listFiles(array());
	  		foreach ($files['files'] as $nm) {
	  			echo $nm['name']."<br>";
	  		}

	        echo  "<hr>";
	    }
		$db_connection->close();
	/* ############################################################ */
?>