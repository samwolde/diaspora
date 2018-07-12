<?php

require_once 'vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '2124336950929114', // Replace {app-id} with your app id
  'app_secret' => 'f4b1b2421704e48d425122da1b7a430a',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://google.com/', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
