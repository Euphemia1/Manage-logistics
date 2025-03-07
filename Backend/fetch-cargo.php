<?php
session_start();
require_once 'db.php'; // Include your database connection

// Fetch cargos from the database
$sql = "SELECT * FROM orders WHERE cargo_owner_name = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['cargo_owner_name']); // Fetch cargos for the logged-in user
$stmt->execute();
$result = $stmt->get_result();
$cargos = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($cargos);
$stmt->close();
$conn->close();
?>
