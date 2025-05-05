<?php
session_start();
require_once 'db.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Get the cargo owner's name from the session
$cargo_owner_name = $_SESSION['user_name'];

// Prepare and execute the select statement to get cargos for this owner
$stmt = $conn->prepare("SELECT * FROM orders WHERE cargo_owner_name = ? ORDER BY pickup_date DESC");
$stmt->bind_param("s", $cargo_owner_name);
$stmt->execute();

$result = $stmt->get_result();
$cargos = [];

// Fetch all cargos
while ($row = $result->fetch_assoc()) {
    $cargos[] = $row;
}

// Return the cargos as JSON
header('Content-Type: application/json');
echo json_encode($cargos);

$stmt->close();
$conn->close();
?>

