<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logistics";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM job";
$result = $conn->query($sql);

$jobPosts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $jobPosts[] = $row;
    }
    echo json_encode(['success' => true, 'jobPosts' => $jobPosts]);
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>