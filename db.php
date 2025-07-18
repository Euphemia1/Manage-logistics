

<?php
// Database connection configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Use this exact password from the screenshot
$db_name = 'logistics';

// Create the database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


