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

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Prepare and bind
        $stmt = $conn->prepare("DELETE FROM jobs WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Job deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "ID not provided"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>