<?php
// Start session if not already started
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Database connection setup
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['reset_message'] = "Database connection failed: " . $e->getMessage();
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

// Get email and user type from form
$email = $_POST['email'] ?? '';
$type = $_POST['type'] ?? '';

if (!empty($email) && !empty($type)) {
    // Determine which table to query based on user type
    $table = ($type === 'cargo_owner') ? 'cargo_owners' : 'transporters';
    
    // Check if the email exists in the appropriate table
    $stmt = $pdo->prepare("SELECT email FROM $table WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Generate a token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // Token expires in 1 hour
        $created_at = date('Y-m-d H:i:s');
        
        // Store the token in a password_reset table
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires, user_type, created_at) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$email, $token, $expires, $type, $created_at]);
        } catch (PDOException $e) {
            $_SESSION['reset_message'] = "Failed to store reset token: " . $e->getMessage();
            header("Location: ../Frontend/forgot-password.php?type=$type");
            exit();
        }
        
        // Send email with reset link
        $resetLink = "https://nyamula.com/reset-password.php?token=$token&email=$email&type=$type";
        
        // Prepare email
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = ''; // Add your SMTP username here
            $mail->Password   = 'P@55w07d@1#';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Changed to SMTPS for port 465
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('noreply@Admin@nyamula.com', 'Nyamula Logistics');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hello,<br><br>You have requested to reset your password. Click the following link to reset your password:<br><br><a href='$resetLink'>Reset Password</a><br><br>If you did not request this, please ignore this email.<br><br>This link will expire in 1 hour.";
            $mail->AltBody = "Hello,\n\nYou have requested to reset your password. Click the following link to reset your password:\n\n$resetLink\n\nIf you did not request this, please ignore this email.\n\nThis link will expire in 1 hour.";

            // Send email
            $mail->send();
            $_SESSION['reset_message'] = "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['reset_message'] = "Failed to send password reset email. Please try again later. Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['reset_message'] = "Email not found.";
    }
} else {
    $_SESSION['reset_message'] = "Please provide an email address and user type.";
}

// Redirect back to the forgot password page
header("Location: ../Frontend/forgot-password.php" . (!empty($type) ? "?type=$type" : ""));
exit();
?>