<?php

$db_host = 'localhost';
$db_user = 'u178619125_nyamula';
$db_pass = '@BluDiamond0100';
$db_name = 'u178619125_nyamula'; 

$conn = new mysqli($db_host, 
$db_user,
 $db_pass, 
 $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>




