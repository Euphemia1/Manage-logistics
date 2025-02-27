<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $type = $_POST['type'] ?? '';

    if (empty($email) || empty($type) || ($type !== 'cargo_owner' && $type !== 'transporter')) {
        $_SESSION['reset_message'] = "Invalid input.";
        header("Location: ..Frontend/forgot-password.php?type=$type");
        exit();
    }

    // Check if the email exists in the database for the given user type
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND type = ?");
    $stmt->execute([$email, $type]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $token, $expires, $type]);

        $resetLink = "http:///reset-password.php?token=$token&type=$type";
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $resetLink";
        $headers = "From: noreply@nyamula.com";

        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['reset_message'] = "A password reset link has been sent to your email.";
        } else {
            $_SESSION['reset_message'] = "Failed to send reset email. Please try again later.";
        }
    } else {
        $_SESSION['reset_message'] = "If the email exists in our system, a reset link will be sent.";
    }

    header("Location: ..Frontend/forgot-password.php?type=$type");
    exit();
}


