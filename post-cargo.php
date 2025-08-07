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

// Prevent duplicate submissions by checking if a similar job was posted recently
$current_time = time();
$last_submission_time = $_SESSION['last_cargo_submission'] ?? 0;
$time_diff = $current_time - $last_submission_time;
if ($time_diff < 3) {
    echo json_encode(['success' => false, 'message' => 'Please wait a moment before submitting another cargo.']);
    exit;
}

// Update last submission time
$_SESSION['last_cargo_submission'] = $current_time;

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

// Get form data - handle both form field variations
$cargoType   = trim($_POST['cargoType'] ?? $_POST['item'] ?? '');
$weight      = trim($_POST['weight'] ?? '');
$origin      = trim($_POST['origin'] ?? $_POST['pickup'] ?? '');
$destination = trim($_POST['destination'] ?? $_POST['dropoff'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$status      = trim($_POST['status'] ?? $_POST['state'] ?? 'Available');
$startDate   = trim($_POST['start_date'] ?? $_POST['startDate'] ?? '');

// Get cargo owner information from session
$cargo_owner_id = $_SESSION['user_id'];
$cargo_owner_name = $_SESSION['user_name'] ?? 'Unknown';

// Validation
$errors = [];

if (empty($cargoType)) {
    $errors[] = 'Cargo type is required.';
}

if (empty($weight)) {
    $errors[] = 'Weight is required.';
}

if (empty($origin)) {
    $errors[] = 'Origin location is required.';
}

if (empty($destination)) {
    $errors[] = 'Destination location is required.';
}

if (empty($phone)) {
    $errors[] = 'Phone number is required.';
}

if (empty($startDate)) {
    $errors[] = 'Start date is required.';
} else {
    // Validate date format
    $dateObj = DateTime::createFromFormat('Y-m-d', $startDate);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $startDate) {
        $errors[] = 'Invalid date format. Please use YYYY-MM-DD.';
    }
}

// Return validation errors if any
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Check for duplicate entries (same user, same cargo type, same route, within last 5 minutes)
try {
    $duplicateCheckStmt = $pdo->prepare("
        SELECT COUNT(*) as count FROM jobs 
        WHERE cargo_owner_id = :cargo_owner_id 
        AND item = :item 
        AND pickup = :pickup 
        AND dropoff = :dropoff 
        AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
    ");
    
    $duplicateCheckStmt->execute([
        ':cargo_owner_id' => $cargo_owner_id,
        ':item' => $cargoType,
        ':pickup' => $origin,
        ':dropoff' => $destination
    ]);
    
    $duplicateCount = $duplicateCheckStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($duplicateCount > 0) {
        echo json_encode(['success' => false, 'message' => 'A similar cargo has already been posted recently. Please wait before posting again.']);
        exit;
    }
} catch (PDOException $e) {
    // Continue if duplicate check fails, but log the error
    error_log('Duplicate check failed: ' . $e->getMessage());
}

// Insert into DB with cargo_owner_id
try {
    $stmt = $pdo->prepare("
        INSERT INTO jobs (
            item, pickup, dropoff, weight, phone, start_date, status, 
            created_at, cargo_owner_id, cargo_owner
        ) VALUES (
            :item, :pickup, :dropoff, :weight, :phone, :start_date, 
            :status, NOW(), :cargo_owner_id, :cargo_owner
        )
    ");

    $stmt->execute([
        ':item'            => $cargoType,
        ':pickup'          => $origin,
        ':dropoff'         => $destination,
        ':weight'          => $weight,
        ':phone'           => $phone,
        ':start_date'      => $startDate,
        ':status'          => $status,
        ':cargo_owner_id'  => $cargo_owner_id,
        ':cargo_owner'     => $cargo_owner_name
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