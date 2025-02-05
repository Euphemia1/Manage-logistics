<?php
session_start();
require_once 'db.php'; // Include your database connection


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $pickupDate = $_POST['pickupDate'];
    $weight = $_POST['weight'];
    $dimensions = $_POST['dimensions'];
    $cargoType = $_POST['cargoType'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $phone = $_POST['phone'];
    $instructions = $_POST['instructions'];
    $cargoOwnerName = $_SESSION['user_name']; // Assuming the cargo owner's name is stored in the session

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO orders (pickup_date, weight, dimensions, cargo_type, origin, destination, phone, instructions, cargo_owner_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $pickupDate, $weight, $dimensions, $cargoType, $origin, $destination, $phone, $instructions, $cargoOwnerName);

    if ($stmt->execute()) {
        echo "Cargo posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>