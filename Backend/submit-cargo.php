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

// Get form data
$order_id = $_POST['order_id'];
$cargo_owner_name = $_POST['cargo_owner_name'];
$pickupDate = $_POST['start_date'];
$weight = $_POST['weight'];
$dimensions = $_POST['dimensions'];
$cargoType = $_POST['cargo_type'];
$origin = $_POST['origin'];

// Optionally, generate or retrieve cargo_owner_id from your database or session
$cargo_owner_id = 1;  // Example: set cargo_owner_id from session or database

// Insert data into the database
$sql = "INSERT INTO orders (order_id, cargo_owner_name, pickup_date, weight, dimensions, cargo_type, origin, cargo_owner_id)
        VALUES (:order_id, :cargo_owner_name, :pickupDate, :weight, :dimensions, :cargoType, :origin, :cargo_owner_id)";

$stmt = $conn->prepare($sql);

// Bind the form data to the SQL query
$stmt->bindParam(':order_id', $order_id);
$stmt->bindParam(':cargo_owner_name', $cargo_owner_name);
$stmt->bindParam(':pickupDate', $pickupDate);
$stmt->bindParam(':weight', $weight);
$stmt->bindParam(':dimensions', $dimensions);
$stmt->bindParam(':cargoType', $cargoType);
$stmt->bindParam(':origin', $origin);
$stmt->bindParam(':cargo_owner_id', $cargo_owner_id);

// Execute the query and return a JSON response
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Cargo posted successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to post cargo.']);
}
?>
</create_file>
