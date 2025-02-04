<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'logistics');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    echo "New job posted successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>