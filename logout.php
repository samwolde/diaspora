<?php
session_start();

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
   session_unset();
   $redirect = "http://localhost/diaspora/";
   header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

?>


