<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: transporter-login.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch driver's current details
$user_name = $_SESSION['user_name'];
$stmt = $pdo->prepare("SELECT * FROM drivers WHERE user_name = :user_name");
$stmt->execute(['user_name' => $user_name]);
$driver = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $errors = [];

    $full_name = trim($_POST['full_name']);
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $truck_type = trim($_POST['truck_type']);
    $truck_capacity = trim($_POST['truck_capacity']);
    $license_plate = trim($_POST['license_plate']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    // Server-side validation
    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    }
    if (empty($phone_number) || !preg_match('/^\+?\d{10,15}$/', $phone_number)) {
        $errors[] = "Invalid phone number.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (empty($truck_type)) {
        $errors[] = "Truck type is required.";
    }
    if (empty($truck_capacity) || !is_numeric($truck_capacity)) {
        $errors[] = "Truck capacity must be a number.";
    }
    if (empty($license_plate)) {
        $errors[] = "License plate number is required.";
    }

    // If no errors, update the database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE drivers 
                SET full_name = :full_name, 
                    phone_number = :phone_number, 
                    email = :email, 
                    truck_type = :truck_type, 
                    truck_capacity = :truck_capacity, 
                    license_plate = :license_plate, 
                    is_available = :is_available 
                WHERE user_name = :user_name
            ");
            $stmt->execute([
                'full_name' => $full_name,
                'phone_number' => $phone_number,
                'email' => $email,
                'truck_type' => $truck_type,
                'truck_capacity' => $truck_capacity,
                'license_plate' => $license_plate,
                'is_available' => $is_available,
                'user_name' => $user_name
            ]);

            // Success message
            $success = "Profile updated successfully!";
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>