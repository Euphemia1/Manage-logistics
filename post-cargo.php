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


// Database credentials (fine as is)
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

// Only accept POST requests (fine as is)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Get form data (fine as is)
$cargoType   = trim($_POST['cargoType'] ?? '');
$weight      = trim($_POST['weight'] ?? '');
$origin      = trim($_POST['origin'] ?? '');
$destination = trim($_POST['destination'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$status      = trim($_POST['status'] ?? '');
$startDate   = trim($_POST['start_date'] ?? '');

// Get cargo owner ID and name from session
// Ensure these variables exist for admins as well, or handle accordingly
$poster_id = $_SESSION['user_id'];
// For 'cargo_owner' column, if an admin posts, you might want to use their username
// This assumes 'user_name' is set for both admin and cargo owner logins.
$poster_name = $_SESSION['user_name'] ?? 'Unknown Poster';


// [Rest of your validation code remains the same...]
// Example: if (empty($cargoType) || empty($weight)) { ... }


// Insert into DB with poster_id and poster_name (if applicable)
try {
    $stmt = $pdo->prepare("
        INSERT INTO jobs (
            item, pickup, dropoff, weight, phone, start_date, status,
            created_at, posted_by_id, posted_by_type, cargo_owner_name_for_display // <<< Column for the name
        ) VALUES (
            :item, :pickup, :dropoff, :weight, :phone, :start_date,
            :status, NOW(), :posted_by_id, :posted_by_type, :cargo_owner_name_for_display
        )
    ");

    $stmt->execute([
        ':item'                       => $cargoType,
        ':pickup'                     => $origin,
        ':dropoff'                    => $destination,
        ':weight'                     => $weight,
        ':phone'                      => $phone,
        ':start_date'                 => $startDate,
        ':status'                     => $status,
        ':posted_by_id'               => $poster_id,     // Using the generic poster ID
        ':posted_by_type'             => $_SESSION['user_type'], // Storing the user's role/type
        ':cargo_owner_name_for_display' => $poster_name   // Name to display (was $_SESSION['user_name'])
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