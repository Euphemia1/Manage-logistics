
<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $type = $_POST['type'];

    $table = ($type == 'cargo_owner') ? 'cargo_owners' : 'transporters';
    $id_column = ($type == 'cargo_owner') ? 'cargo_owner_id' : 'transporter_id';

    $stmt = $conn->prepare("SELECT $id_column FROM $table WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user[$id_column];
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $user_id, $token, $expires, $type);
        $stmt->execute();

        // Send email with reset link
        $reset_link = "http://yourdomain.com/Frontend/reset-password.php?token=$token";
        $to = $email;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $reset_link";
        $headers = "From: noreply@yourdomain.com";

        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['reset_message'] = "Password reset link sent to your email.";
        } else {
            $_SESSION['reset_message'] = "Failed to send password reset email.";
        }
    } else {
        $_SESSION['reset_message'] = "No account found with that email address.";
    }

    header("Location: ../Frontend/forgot-password.php?type=$type");
    exit();
}