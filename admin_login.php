<?php
session_start();
require 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: admin-dashboard.php'); // Redirect to admin dashboard
        exit();
    } else {
        echo "Invalid credentials!";
    }
}

$stmt->close();
$conn->close();
?>
