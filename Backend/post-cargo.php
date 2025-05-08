<?php
session_start();
header('Content-Type: application/json'); // Essential for frontend to parse response

// db.php should establish $conn
require_once 'db.php';

// Check if user is logged in and has user_id
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in or user ID missing. Please login again.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Data from session
    $cargo_owner_id = $_SESSION['user_id'];       // Get the ID from session
    $cargo_owner_name = $_SESSION['user_name'];   // Get the name from session

    // Get the posted data from the form
    // Basic validation: check if keys exist (you can add more robust validation)
    $required_fields = ['pickupDate', 'weight', 'dimensions', 'cargoType', 'origin', 'destination', 'phone', 'instructions'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) { // Allow empty instructions, but key must exist
            // For instructions, it's okay if it's empty but not if the key is missing
            if ($field === 'instructions' && array_key_exists('instructions', $_POST)) {
                // Instructions can be empty
            } else {
                echo json_encode(['success' => false, 'message' => "Missing field: " . $field]);
                $conn->close(); // Close connection before exiting
                exit();
            }
        }
    }

    $pickupDate = trim($_POST['pickupDate']);
    $weight = trim($_POST['weight']);
    $dimensions = trim($_POST['dimensions']);
    $cargoType = trim($_POST['cargoType']);
    $origin = trim($_POST['origin']);
    $destination = trim($_POST['destination']);
    $phone_number = trim($_POST['phone']); // Match your table column name
    $instructions = trim($_POST['instructions']);
    $status = 'Available'; // Default status

    // Prepare and execute the insert statement
    // Columns: cargo_owner_id, cargo_owner_name, phone_number, cargo_type, weight, dimensions, origin, destination, instructions, pickup_date, status
    // Total 11 columns (order_id is auto-increment)
    $stmt = $conn->prepare("INSERT INTO orders (cargo_owner_id, cargo_owner_name, phone_number, cargo_type, weight, dimensions, origin, destination, instructions, pickup_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => "Prepare statement failed: " . $conn->error]);
        $conn->close();
        exit();
    }

    // Bind parameters:
    // i - integer (cargo_owner_id)
    // s - string
    // Total: 1 integer, 10 strings = "issssssssss"
    $stmt->bind_param("issssssssss",
        $cargo_owner_id,
        $cargo_owner_name,
        $phone_number,
        $cargoType,
        $weight,
        $dimensions,
        $origin,
        $destination,
        $instructions,
        $pickupDate,
        $status
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Cargo posted successfully!"]);
    } else {
        // Log the detailed error for your reference on the server
        error_log("SQL Error in post-cargo.php: " . $stmt->error . " (Errno: " . $stmt->errno . ")");
        echo json_encode(['success' => false, 'message' => "Error posting cargo. Please try again. (DB Error)"]);
        // For debugging, you might temporarily send $stmt->error:
        // echo json_encode(['success' => false, 'message' => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>