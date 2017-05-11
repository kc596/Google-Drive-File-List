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

	if(isset($_GET['code'])){
		$client->authenticate($_GET['code']);
		$access_token = $client->getAccessToken();
		$refresh_token = $access_token['refresh_token'];
		print_r($access_token);		

		echo "<br><br><hr><br><br>";
		$q = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$access_token['access_token'];
		echo $q;
		$json = file_get_contents($q);
		$userInfoArray = json_decode($json,true);
		$googleEmail = $userInfoArray['email'];
		
		/* ################### STORING IN DATABASE #################### */
		$db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
		$sql = "INSERT INTO user values ('"
				.$googleEmail."',
				'".$access_token['access_token']."',
				'".$refresh_token."')";
		echo $sql."<hr>";
		$db_connection->query($sql);
		$db_connection->close();
		/* ############################################################ */
		
		$_SESSION['email'] = $googleEmail;
		$_SESSION['access_token'] = $access_token;
		header('Location: index.php');
	} else {
		echo "Error!";
		die();
	}
?>