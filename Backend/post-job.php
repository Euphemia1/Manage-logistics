<?php
// post_job.php

// Database connection
$host = 'localhost'; // Your database host
$db = 'logistics'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

$item = $conn->real_escape_string($data['item']);
$pickup = $conn->real_escape_string($data['pickup']);
$dropoff = $conn->real_escape_string($data['dropoff']);
$weight = $conn->real_escape_string($data['weight']);
$state = $conn->real_escape_string($data['state']);
$price = $conn->real_escape_string($data['price']);
$startDate = $conn->real_escape_string($data['startDate']);

// Insert the job post into the database
$sql = "INSERT INTO jobs (item, pickup, dropoff, weight, state, price, start_date) VALUES ('$item', '$pickup', '$dropoff', '$weight', '$state', '$price', '$startDate')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false] + $conn->error);
}