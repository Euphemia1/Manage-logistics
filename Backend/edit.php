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
$stmt = $conn->prepare("UPDATE jobs SET item=?, pickup=?, dropoff=?, weight=?, state=?, price=?, start_date=? WHERE id=?");
$stmt->bind_param("sssssssi", $item, $pickup, $dropoff, $weight, $state, $price, $startDate, $id);

// Set parameters and execute
$item = $_POST['item'];
$pickup = $_POST['pickup'];
$dropoff = $_POST['dropoff'];
$weight = $_POST['weight'];
$state = $_POST['state'];
$price = $_POST['price'];
$startDate = $_POST['startDate'];
$id = $_POST['id'];

if ($stmt->execute()) {
    echo json_encode(["success" => "Job updated successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>