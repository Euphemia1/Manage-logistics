<?php

// MySQLi connection
$db_host = 'localhost';
$db_user = 'u178619125_nyamula';
$db_pass = '@BluDiamond0100';
$db_name = 'u178619125_nyamula';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PDO connection
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Optionally log or handle PDO connection error
    // die("PDO Connection failed: " . $e->getMessage());
}
?>




