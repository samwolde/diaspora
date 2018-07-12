<?php

require_once 'vendor/autoload.php';
require 'connection.php';

session_start();

/* 
 * INITIALIZATION
 *
 * Create a google client object
 * set the id,secret and redirect uri
 * set the scope variables if required
 * create google plus object
 */
$client = new Google_Client();
$client->setClientId(Con::$CLIENT_ID);
$client->setClientSecret(Con::$CLIENT_SECRET);
$client->setRedirectUri(Con::$REDIRECT_URI);
$client->setScopes('email');

$plus = new Google_Service_Plus($client);

// access without ssl certificate
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);


/* 
 * A. AUTHORIZATION AND ACCESS TOKEN
 *
 * If the request is a return url from the google server then
 *  1. authenticate code
 *  2. get the access token and store in session
 */
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
}

/* 
 * C. RETRIVE DATA
 * 
 * If access token if available in session 
 * load it to the client object and access the required profile data
 */
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $me = $plus->people->get('me');

  // check if user exists in database
  $sql1 = "SELECT * FROM GoogleUsers WHERE googleId=".$me['id'];
  $usr = DB::querySelect($sql1);

  // insert if user is new
  if(sizeof($usr)<1){
	  DB::insert('GoogleUsers',['googleId'=>$me['id'],'name'=>$me['displayName'],'email'=>$me['emails'][0]['value']]);
  }
  
  $_SESSION['name'] = $me['displayName'];
  $_SESSION['email'] = $me['emails'][0]['value'];
  
  $redirect = Con::$BASE_URL."/home.php";
} else {
  $redirect = Con::$BASE_URL;
}

// redirect to the desired location
header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));

?>


