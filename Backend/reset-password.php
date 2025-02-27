<?php
session_start();
require_once 'db_connection.php'; // Ensure this file properly initializes $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $type = $_POST['type'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($type) || empty($password) || empty($confirm_password)) {
        $_SESSION['reset_message'] = "All fields are required.";
        header("Location: ../Frontend/reset-password.php?token=$token&type=$type");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['reset_message'] = "Passwords do not match.";
        header("Location: ../Frontend/reset-password.php?token=$token&type=$type");
        exit();
    }

    // Verify token and get associated email
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND user_type = ? AND expires > NOW()");
    $stmt->execute([$token, $type]);
    $reset = $stmt->fetch();

    if ($reset) {
        $email = $reset['email'];

        // Hash the new password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update user's password
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ? AND type = ?");
        $stmt->execute([$hashed_password, $email, $type]);

        // Delete used token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);

        $_SESSION['reset_message'] = "Your password has been successfully reset. You can now log in.";

        // Redirect to the appropriate login page based on user type
        $login_page = ($type === 'cargo_owner') ? 'cargo-owner-login.php' : 'transporter-login.php';
        header("Location: ../Frontend/$login_page");
        exit();
    } else {
        $_SESSION['reset_message'] = "Invalid or expired reset link. Please try again.";
        header("Location: ../Frontend/forgot-password.php?type=$type");
        exit();
    }
}
