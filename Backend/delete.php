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
$stmt = $conn->prepare("DELETE FROM jobs WHERE id=?");
$stmt->bind_param("i", $id);

// Set parameters and execute
$id = $_POST['id'];

if ($stmt->execute()) {
    echo json_encode(["success" => "Job deleted successfully"]);
} else {
    echo json_encode(["error" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>