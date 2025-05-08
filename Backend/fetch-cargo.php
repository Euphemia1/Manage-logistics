<?php
session_start(); // Call session_start() only once at the beginning

// db.php should establish $conn
require_once 'db.php';

header('Content-Type: application/json'); // Set content type for JSON response

// Check if user is logged in and has user_id (which should be cargo_owner_id)
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in or user ID missing.']);
    exit();
}

$cargo_owner_id = $_SESSION['user_id']; // Use the ID for querying

// Prepare and execute the select statement to get cargos for this owner
// Explicitly list columns and alias order_id to 'id' and phone_number to 'phone' for frontend compatibility
// Order by order_id DESC to get newest first (assuming order_id is auto-incrementing)
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

// Bind the cargo_owner_id (integer)
$stmt->bind_param("i", $cargo_owner_id);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute statement failed: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit();
}

$result = $stmt->get_result();
$cargos = [];

// Fetch all cargos
while ($row = $result->fetch_assoc()) {
    $cargos[] = $row;
}

// Return the cargos as JSON within a structured response
echo json_encode(['success' => true, 'cargos' => $cargos]);

$stmt->close();
$conn->close();
?>