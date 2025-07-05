<?php
// Include database connection
require_once 'db.php'; // Ensure this path is correct relative to get-dashboard-counts.php

// Set header to return JSON
header('Content-Type: application/json');

// Function to get count from a table
function getCount($conn, $table) {
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    return 0;
}

// Get counts from database
$cargoOwnersCount = getCount($conn, 'cargo_owners');
$transportersCount = getCount($conn, 'transporters');
$ordersCount = getCount($conn, 'jobs'); // <<<--- THIS IS THE CRUCIAL CHANGE

// Create response array
$response = [
    'cargoOwnersCount' => $cargoOwnersCount,
    'transportersCount' => $transportersCount,
    'ordersCount' => $ordersCount,
    'timestamp' => date('Y-m-d H:i:s')
];

// Close connection
$conn->close();

// Return JSON response
echo json_encode($response);
?>