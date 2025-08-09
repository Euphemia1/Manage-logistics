<?php
// Database connection configuration
$db_host = 'localhost';
$db_user = 'u178619125_nyamula';
$db_pass = '@BluDiamond0100';
$db_name = 'u178619125_nyamula'; 

// Create the database connection
$conn = new mysqli($db_host, 
$db_user,
 $db_pass, 
 $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>




