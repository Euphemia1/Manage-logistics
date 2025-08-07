<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Please enter both username and password.";
        header('Location: admin-login.php');
        exit();
    }

    $stmt = $conn->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $stmt->close();
        $conn->close();
        header('Location: admin-dashboard.php'); 
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid username or password.";
        $stmt->close();
        $conn->close();
        header('Location: admin-login.php');
        exit();
    }
}

$conn->close();
?>
