<?php


require 'constants.php';

session_start();

$baseUrl = Con::$BASE_URL;

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$name = $_SESSION['name'];
	$email = $_SESSION['email'];
} else{
	$redirect = $baseUrl;
   	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

?>

<div>
    <?php
	echo "<h1>Welcome to Ethio-Diaspora</h1>";
	print "Name: {$name} <br>";
	print "Email: {$email } <br>";
	echo "<a class='logout' href={$baseUrl}/logout.php><button>Logout</button></a>";
    ?>
</div>

