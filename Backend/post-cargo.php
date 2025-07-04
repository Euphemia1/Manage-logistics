<?php
// post-cargo.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

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

// Handle custom cargo type
if ($cargoType === 'Other' && !empty($_POST['customCargoType'])) {
    $cargoType = trim($_POST['customCargoType']);
}

// Validate date format (expecting YYYY-MM-DD)
if (!empty($startDate)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $startDate);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $startDate) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format for pickup date.']);
        exit;
    }
} else {
    // If no date provided, default to today
    $startDate = date('Y-m-d');
}

// Validation of required fields
$requiredFields = [
    'cargoType'   => $cargoType,
    'origin'      => $origin,
    'destination' => $destination,
    'phone'       => $phone,
    'start_date'  => $startDate
];

$missingFields = [];
foreach ($requiredFields as $field => $value) {
    if (empty($value)) {
        $missingFields[] = $field;
    }
}

if (!isset($_POST['termsCheck'])) {
    $missingFields[] = 'terms agreement';
}

if (!empty($missingFields)) {
    echo json_encode([
        'success' => false,
        'message' => 'Required fields are missing: ' . implode(', ', $missingFields)
    ]);
    exit;
}

// Insert into DB
try {
    $stmt = $pdo->prepare("
        INSERT INTO jobs (
            item, pickup, dropoff, weight, phone, start_date, status, created_at
        ) VALUES (
            :item, :pickup, :dropoff, :weight, :phone, :start_date, :status, NOW()
        )
    ");

    $stmt->execute([
        ':item'       => $cargoType,
        ':pickup'     => $origin,
        ':dropoff'    => $destination,
        ':weight'     => $weight,
        ':phone'      => $phone,
        ':start_date' => $startDate,
        ':status'     => $status
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
?>

