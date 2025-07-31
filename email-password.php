<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $type = $_POST['type'] ?? '';

    if (empty($email) || empty($type)) {
        $_SESSION['reset_message'] = "Invalid input.";
        header("Location: forgot-password.php?type=$type");
        exit();
    }
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND type = ?");
    $stmt->execute([$email, $type]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires, user_type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expires, $type]);

        $resetLink = "http://nyamula.com/Frontend/reset-password.php?token=$token&type=$type";
        $subject = "Password Reset Request";
        $message = "Click the link to reset your password: <a href='$resetLink'>$resetLink</a>";
        $headers = "From: noreply@nyamula.com\r\nContent-Type: text/html;";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['reset_message'] = "A password reset link has been sent to your email.";
        } else {
            $_SESSION['reset_message'] = "Failed to send email. Try again later.";
        }
    }

    header("Location: forgot-password.php?type=$type");
    exit();
}
