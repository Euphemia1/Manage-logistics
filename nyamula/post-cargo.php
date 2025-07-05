<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to post cargo.']);
    exit;
}

// Database credentials
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Get form data
$cargoType   = trim($_POST['cargoType'] ?? '');
$weight      = trim($_POST['weight'] ?? '');
$origin      = trim($_POST['origin'] ?? '');
$destination = trim($_POST['destination'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$status      = trim($_POST['status'] ?? '');
$startDate   = trim($_POST['start_date'] ?? '');

// Get cargo owner ID from session
$cargo_owner_id = $_SESSION['user_id'];

// [Rest of your validation code remains the same...]

// Insert into DB with cargo_owner_id
try {
    $stmt = $pdo->prepare("
        INSERT INTO jobs (
            item, pickup, dropoff, weight, phone, start_date, status, 
            created_at, cargo_owner_id
        ) VALUES (
            :item, :pickup, :dropoff, :weight, :phone, :start_date, 
            :status, NOW(), :cargo_owner_id
        )
    ");

    $stmt->execute([
        ':item'           => $cargoType,
        ':pickup'         => $origin,
        ':dropoff'        => $destination,
        ':weight'        => $weight,
        ':phone'         => $phone,
        ':start_date'    => $startDate,
        ':status'        => $status,
        ':cargo_owner_id' => $cargo_owner_id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Cargo posted successfully!',
        'job_id'  => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
