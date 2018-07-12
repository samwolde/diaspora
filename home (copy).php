<?php
// /*  GOOGLE LOGIN BASIC - Tutorial
//  *  file            - index.php
//  *  Developer       - Krishna Teja G S
//  *  Website         - http://packetcode.com/apps/google-login/
//  *  Date            - 28th Aug 2015
//  *  license         - GNU General Public License version 2 or later
// */

// // REQUIREMENTS - PHP v5.3 or later
// // Note: The PHP client library requires that PHP has curl extensions configured. 

// /*
//  * DEFINITIONS
//  *
//  * load the autoload file
//  * define the constants client id,secret and redirect url
//  * start the session
//  */
// require_once 'vendor/autoload.php';
// require 'connection.php';

// const CLIENT_ID = '700978285044-3quri81p15vem4bt605rncvl6jptr4b1.apps.googleusercontent.com';
// const CLIENT_SECRET = 'eADeeRVlrxL-3k5PcNr0jXLh';
// const REDIRECT_URI = 'http://localhost/diaspora/home.php';

// $conn = DB::getInstance();

session_start();

// /* 
//  * INITIALIZATION
//  *
//  * Create a google client object
//  * set the id,secret and redirect uri
//  * set the scope variables if required
//  * create google plus object
//  */
// $client = new Google_Client();
// $client->setClientId(CLIENT_ID);
// $client->setClientSecret(CLIENT_SECRET);
// $client->setRedirectUri(REDIRECT_URI);
// $client->setScopes('email');

// $plus = new Google_Service_Plus($client);
// echo "here";

// $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
// $client->setHttpClient($guzzleClient);

// /*
//  * PROCESS
//  *
//  * A. Pre-check for logout
//  * B. Authentication and Access token
//  * C. Retrive Data
//  */

// /* 
//  * A. PRE-CHECK FOR LOGOUT
//  * 
//  * Unset the session variable in order to logout if already logged in    
//  */
// if (isset($_REQUEST['logout'])) {
//    session_unset();
// }

// /* 
//  * B. AUTHORIZATION AND ACCESS TOKEN
//  *
//  * If the request is a return url from the google server then
//  *  1. authenticate code
//  *  2. get the access token and store in session
//  *  3. redirect to same url to eleminate the url varaibles sent by google
//  */
// if (isset($_GET['code'])) {
// //   echo "HEREEE";
// //   try{
// // 	  $client->authenticate($_GET['code']);
// // 	  echo "HEREEE";
// //   }catch(Exception $e){
// // 	  echo "Not working";
// // 	  echo $e->getMessage();
// //   }
//   $client->authenticate($_GET['code']);
//   $_SESSION['access_token'] = $client->getAccessToken();
//   $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
// //   echo $redirect;
//   header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
// }

// /* 
//  * C. RETRIVE DATA
//  * 
//  * If access token if available in session 
//  * load it to the client object and access the required profile data
//  */
// if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//   $client->setAccessToken($_SESSION['access_token']);
//   $me = $plus->people->get('me');

//   $sql1 = "SELECT * FROM GoogleUsers WHERE googleId=".$me['id'];
//   $usr = DB::querySelect($sql1);

//   if(sizeof($usr)<1){
// 	  DB::insert('GoogleUsers',['googleId'=>$me['id'],'name'=>$me['displayName'],'email'=>$me['emails'][0]['value']]);
//   }
//   // Get User data
//   $id = $me['id'];
//   $name =  $me['displayName'];
//   $email =  $me['emails'][0]['value'];
//   $profile_image_url = $me['image']['url'];
//   $cover_image_url = $me['cover']['coverPhoto']['url'];
//   $profile_url = $me['url'];
  
//   $_SESSION['name'] = $me['displayName'];
//   $_SESSION['email'] = $me['emails'][0]['value'];
// } else {
// 	echo "there";
//   // get the login url   
//   $authUrl = $client->createAuthUrl();
//   $_SESSION['authUrl'] = $authUrl;
// }


// 
require 'constants.php';
$baseUrl = Con::$BASE_URL;
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	var_dump($_SESSION);
	$name = $_SESSION['name'];
	$email = $_SESSION['email'];
} else{
	$redirect = Con::$BASE_URL;
   	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}


?>

<!-- HTML CODE with Embeded PHP-->
<div>
    <?php
    /*
     * If login url is there then display login button
     * else print the retieved data
    */
	echo "<h1>Welcome to Ethio-Diaspora</h1>";
	print "Name: {$name} <br>";
	print "Email: {$email } <br>";
	echo "<a class='logout' href={Con::$BASE_URL}><button>Logout</button></a>";
    ?>
</div>

