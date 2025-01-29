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

$item = $_POST['item'];
$pickup = $_POST['pickup'];
$dropoff = $_POST['dropoff'];
$weight = $_POST['weight'];
$state = $_POST['state'];
$price = $_POST['price'];
$start_date = $_POST['start_date'];

$sql = "INSERT INTO job_posts (item, pickup, dropoff, weight, state, price, start_date)
VALUES ('$item', '$pickup', '$dropoff', '$weight', '$state', '$price', '$start_date')";

if ($conn->query($sql) === TRUE) {
    $jobPost = [
        'item' => $item,
        'pickup' => $pickup,
        'dropoff' => $dropoff,
        'weight' => $weight,
        'state' => $state,
        'price' => $price,
        'start_date' => $start_date
    ];
    echo json_encode(['success' => true, 'jobPost' => $jobPost]);
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>