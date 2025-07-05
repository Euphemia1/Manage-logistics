<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logistics";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO jobs (item, pickup, dropoff, weight, state, price, start_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $item, $pickup, $dropoff, $weight, $state, $price, $startDate);

// Set parameters and execute
$item = $_POST['item'];
$pickup = $_POST['pickup'];
$dropoff = $_POST['dropoff'];
$weight = $_POST['weight'];
$state = $_POST['state'];
$price = $_POST['price'];
$startDate = $_POST['startDate'];

if ($stmt->execute()) {
    echo json_encode(["success" => "New job posted successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>