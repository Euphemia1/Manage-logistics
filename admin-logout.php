<?php
session_start(); 

$_SESSION = [];

session_destroy();
header("index.php");
exit();
?>