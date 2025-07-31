<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json'); 

if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in or user ID missing.']);
    exit();
}

$cargo_owner_id = $_SESSION['user_id']; 
$sql = "SELECT
            order_id AS id,
            cargo_owner_id,
            cargo_owner_name,
            phone_number AS phone,
            cargo_type,
            weight,
            dimensions,
            origin,
            destination,
            instructions,
            pickup_date,
            status
        FROM orders
        WHERE cargo_owner_id = ?
        ORDER BY order_id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
    $conn->close();
    exit();
}

$stmt->bind_param("i", $cargo_owner_id);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute statement failed: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit();
}

$result = $stmt->get_result();
$cargos = [];
while ($row = $result->fetch_assoc()) {
    $cargos[] = $row;
}

echo json_encode(['success' => true, 'cargos' => $cargos]);

$stmt->close();
$conn->close();
?>