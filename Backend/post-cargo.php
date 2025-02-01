<?php
// post-cargo.php

// Database connection
$servername = "localhost";
$username = "roots";
$password = "";
$dbname = "logistics";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize input data
  $pickupDate = htmlspecialchars($_POST['pickupDate']);
  $weight = intval($_POST['weight']);
  $dimensions = htmlspecialchars($_POST['dimensions']);
  $cargoType = htmlspecialchars($_POST['cargoType']);
  $origin = htmlspecialchars($_POST['origin']);
  $destination = htmlspecialchars($_POST['destination']);
  $phone = htmlspecialchars($_POST['phone']);
  $instructions = htmlspecialchars($_POST['instructions']);

  // Insert data into the database
  $sql = "INSERT INTO orders (pickupDate, weight, dimensions, cargo_type, origin, destination, phone, instructions, status)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

  // Prepare and bind
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sissssss", $pickupDate, $weight, $dimensions, $cargoType, $origin, $destination, $phone, $instructions);

  // Execute the statement
  if ($stmt->execute()) {
    echo "Cargo posted successfully!";
  } else {
    echo "Error: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request method.";
}
?>