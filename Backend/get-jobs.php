<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('localhost', 
'root',
 '',
 'logistics');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM jobs");
$jobs = [];


while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

echo json_encode($jobs);

$conn->close();
?>