<?php
// fetch_cargos.php
session_start();
// Database connection
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch cargos from the database
$sql = "SELECT * FROM orders WHERE cargo_owner_name = :cargo_owner_name ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':cargo_owner_name', $_SESSION['user_name']); // Fetch cargos for the logged-in user
$stmt->execute();
$cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($cargos);
?>