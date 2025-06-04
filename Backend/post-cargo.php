<?php
// post-cargo.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

// 🔧 Update with your actual database credentials
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// 📨 Only accept POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $item       = trim($_POST['cargoType'] ?? 'Unknown');
    $pickup     = trim($_POST['pickup'] ?? '');
    $dropoff    = trim($_POST['dropoff'] ?? '');
    $weight     = trim($_POST['weight'] ?? '');
    $state      = trim($_POST['state'] ?? '');
    $price      = trim($_POST['price'] ?? '');
    $start_date = trim($_POST['pickupDate'] ?? '');

    // Basic validation
    if (!$item || !$pickup || !$dropoff || !$start_date) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO jobs (item, pickup, dropoff, weight, state, price, start_date)
                               VALUES (:item, :pickup, :dropoff, :weight, :state, :price, :start_date)");

        $stmt->execute([
            ':item'       => $item,
            ':pickup'     => $pickup,
            ':dropoff'    => $dropoff,
            ':weight'     => $weight,
            ':state'      => $state,
            ':price'      => $price,
            ':start_date' => $start_date,
        ]);

        echo json_encode(['success' => true, 'message' => 'Cargo saved to database.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
exit;
