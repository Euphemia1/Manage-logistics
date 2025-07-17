<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

// --- AUTHENTICATION CHECK (REVISED) ---
// 1. Check if ANY user is logged in AND their type is set
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_type'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to post cargo.']);
    exit;
}

// 2. Check if the logged-in user's type is authorized to post cargo
$allowed_user_types_to_post = ['admin', 'cargo_owner'];

if (!in_array($_SESSION['user_type'], $allowed_user_types_to_post)) {
    echo json_encode(['success' => false, 'message' => 'Your account type does not have permission to post cargo.']);
    exit;
}

// If execution reaches here, the user is logged in as an 'admin' or 'cargo_owner'.

// --- DEBUGGING (Optional, good to keep during development) ---
error_log("--- post-cargo.php hit (Authenticated) ---");
error_log("Session ID: " . session_id());
error_log("User ID: " . $_SESSION['user_id'] . ", User Type: " . $_SESSION['user_type']);
// --- END DEBUGGING ---


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

// Get poster ID and name from session
// $_SESSION['user_id'] will contain the ID from either admin or cargo_owner table
$poster_id = $_SESSION['user_id'];
// $_SESSION['user_name'] should be set in both login scripts (admin username or cargo owner name)
$poster_name = $_SESSION['user_name'] ?? 'Unknown Poster';
// $_SESSION['user_type'] will contain 'admin' or 'cargo_owner'
$poster_type = $_SESSION['user_type'];


// --- Basic Validation Example (you should expand this) ---
if (empty($cargoType) || empty($weight) || empty($origin) || empty($destination) || empty($phone) || empty($status) || empty($startDate)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
// Example: Validate weight is numeric
if (!is_numeric($weight) || $weight <= 0) {
    echo json_encode(['success' => false, 'message' => 'Weight must be a positive number.']);
    exit;
}
// Example: Validate start_date format
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $startDate)) {
    echo json_encode(['success' => false, 'message' => 'Start date must be in YYYY-MM-DD format.']);
    exit;
}


// Insert into DB with poster_id, poster_name, and poster_type
try {
    $stmt = $pdo->prepare("
        INSERT INTO jobs (
            item, pickup, dropoff, weight, phone, start_date, status,
            created_at, cargo_owner_id, cargo_owner, poster_type
        ) VALUES (
            :item, :pickup, :dropoff, :weight, :phone, :start_date,
            :status, NOW(), :cargo_owner_id, :cargo_owner_name, :poster_type
        )
    ");

    $stmt->execute([
        ':item'           => $cargoType,
        ':pickup'         => $origin,
        ':dropoff'        => $destination,
        ':weight'         => $weight,
        ':phone'          => $phone,
        ':start_date'     => $startDate,
        ':status'         => $status,
        ':cargo_owner_id' => $poster_id,    // This column will now store either admin_id or cargo_owner_id
        ':cargo_owner_name' => $poster_name, // This column will now store either admin_username or cargo_owner_name
        ':poster_type'    => $poster_type   // <<< NEW: Stores 'admin' or 'cargo_owner'
    ]);


    echo json_encode([
        'success' => true,
        'message' => 'Cargo posted successfully!',
        'job_id'  => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    // Log the actual error for debugging
    error_log("PDO Error in post-cargo.php: " . $e->getMessage() . " - SQL: " . ($stmt->queryString ?? 'N/A')); // Added query string for better debugging
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}

?>
