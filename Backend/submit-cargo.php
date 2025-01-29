<?php
// submit_cargo.php

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

// Get form data (ensure these match the names from your JavaScript)
// $cargo_owner_name = $_POST['cargo_owner_name'];  // Updated to match JS
$pickupDate = $_POST['pickupDate'];
$weight = $_POST['weight'];
$dimensions = $_POST['dimensions']; // Adjust the field name in the frontend if necessary
$cargoType = $_POST['cargoType'];
$origin = $_POST['origin'];
$destination = $_POST['destination'];
$phone = $_POST['phone'];
$instructions = $_POST['instructions'];

// Optionally, generate or retrieve order_id and cargo_owner_id from your database or session
$order_id = uniqid();  // For example, generate a unique order ID (you might have another way)
$cargo_owner_id = 1;  // Example: set cargo_owner_id from session or database

// Insert data into the database
$sql = "INSERT INTO orders ( pickup_date, weight, dimensions, cargo_type, origin, destination, phone, instructions)
        VALUES ( :pickupDate, :weight, :dimensions, :cargoType, :origin, :destination, :phone, :instructions)";

$stmt = $conn->prepare($sql);

// Bind the form data to the SQL query
// $stmt->bindParam(':order_id', $order_id);
// $stmt->bindParam(':cargo_owner_id', $cargo_owner_id);
// $stmt->bindParam(':cargo_owner_name', $cargo_owner_name);
$stmt->bindParam(':pickupDate', $pickupDate);
$stmt->bindParam(':weight', $weight);
$stmt->bindParam(':dimensions', $dimensions);
$stmt->bindParam(':cargoType', $cargoType);
$stmt->bindParam(':origin', $origin);
$stmt->bindParam(':destination', $destination);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':instructions', $instructions);

// Execute the query and return a JSON response
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Cargo posted successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to post cargo.']);
}
?>
