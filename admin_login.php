<?php
session_start();
require 'db.php'; // Include your database connection (assuming $conn is mysqli)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use PDO for consistency if db.php provides it, otherwise ensure $conn is mysqli
    // Assuming $conn is a mysqli connection from db.php:
    $stmt = $conn->prepare('SELECT id, password FROM admins WHERE username = ?');
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['logged_in'] = true;       // Universal flag
        $_SESSION['user_id'] = $admin['id']; // Store their ID
        $_SESSION['user_type'] = 'admin';    // <<< ADD THIS LINE
        $_SESSION['user_name'] = $admin['username']; // Useful for 'cargo_owner' field if admin posts

        header('Location: admin-dashboard.php'); // Redirect to admin dashboard
        exit();
    } else {
        // Handle invalid credentials
        // echo "Invalid credentials!"; // For production, redirect with error message
        $_SESSION['login_error'] = "Invalid username or password.";
        header('Location: admin-login.php'); // Assuming you have an admin-login.php form
        exit();
    }
}

if (isset($stmt)) $stmt->close();
if (isset($conn)) $conn->close();
?>