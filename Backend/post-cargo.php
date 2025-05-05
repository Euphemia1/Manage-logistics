<?php
session_start();
require_once 'db.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    echo "Error: User not logged in";
    exit;
}


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
    $cargo_owner_name = $_SESSION['user_name']; // Using the session user_name
    $status = 'Available'; // Default status for newly posted cargo

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO orders (pickup_date, weight, dimensions, cargo_type, origin, destination, phone, instructions, cargo_owner_name, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $pickupDate, $weight, $dimensions, $cargoType, $origin, $destination, $phone, $instructions, $cargoOwnerName, $status);

    if ($stmt->execute()) {
        echo "Cargo posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>



