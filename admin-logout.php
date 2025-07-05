<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the index page
header("Location: ../Frontend/index.php");
exit();
?>