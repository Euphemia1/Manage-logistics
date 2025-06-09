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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data using the correct field names from your HTML form
    $cargoType = trim($_POST['cargoType'] ?? '');
    $weight = trim($_POST['weight'] ?? '');
    $origin = trim($_POST['origin'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $status = trim($_POST['status'] ?? '');

    // Handle custom cargo type
    if ($cargoType === 'Other' && !empty($_POST['customCargoType'])) {
        $cargoType = trim($_POST['customCargoType']);
    }

    // Determine pickup date
    $pickupDate = trim($_POST['pickupDate'] ?? '');
    if ($pickupDate === 'specific' && !empty($_POST['specificDate'])) {
        $pickupDate = trim($_POST['specificDate']);
    }
    $startDate = !empty($pickupDate) ? $pickupDate : date('Y-m-d');

    // Validation - check required fields
    $requiredFields = [
        'cargoType' => $cargoType,
        'origin' => $origin,
        'destination' => $destination,
        'pickupDate' => $pickupDate,
        'phone' => $phone
    ];

    $missingFields = [];
    foreach ($requiredFields as $fieldName => $fieldValue) {
        if (empty($fieldValue)) {
            $missingFields[] = $fieldName;
        }
    }

    // Check terms checkbox
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

    try {
        // Insert job into database
        $stmt = $pdo->prepare("
            INSERT INTO jobs (
                item, pickup, dropoff, weight, phone, start_date, status, created_at
            ) VALUES (
                :item, :pickup, :dropoff, :weight, :phone, :start_date, :status, NOW()
            )
        ");

        $stmt->execute([
            ':item' => $cargoType,
            ':pickup' => $origin,
            ':dropoff' => $destination,
            ':weight' => $weight,
            ':phone' => $phone,
            ':start_date' => $startDate,
            ':status' => $status
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Cargo posted successfully!',
            'job_id' => $pdo->lastInsertId()
        ]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
exit;
?>
