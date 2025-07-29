<?php
// Database connection configuration
$db_host = 'localhost';
$db_user = 'u962968097_nyamuladb';
$db_pass = 'p@55w07d@1#';
$db_name = 'u962968097_nyamula'; 

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



