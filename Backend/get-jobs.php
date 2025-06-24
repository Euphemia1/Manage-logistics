<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// DB config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logistics";

// Connect
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection error."]);
    exit();
}

// Query
$sql = "SELECT * FROM jobs";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to retrieve jobs."]);
    $conn->close();
    exit();
}

// Fetch
$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

// Respond
echo json_encode($jobs);

// Close
$conn->close();
?>
