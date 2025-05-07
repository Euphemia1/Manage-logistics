<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

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

if (empty($email) || empty($type)) {
    $_SESSION['reset_message'] = "Email and user type are required.";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

// Determine which table to query based on user type
$table = ($type === 'cargo_owner') ? 'cargo_owners' : 'transporters';

// Check if email exists in the correct table
$stmt = $pdo->prepare("SELECT email FROM $table WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);
    
    // Store token
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires, user_type, created_at) VALUES (?, ?, ?, ?, NOW())");
    try {
        $stmt->execute([$email, $token, $expires, $type]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['reset_message'] = "Failed to store reset token.";
        header("Location: ../reset-password.php?type=$type");
        exit();
    }

    // Construct reset link
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
   
    
// Construct the reset link properly
// $resetLink = "https://".$_SERVER['HTTP_HOST']."../Frontend/reset-password.php?token=$token&email=".urlencode($email)."&type=".urlencode($type);
// Correct way to generate the reset link:
$resetLink = "https://localhost/Frontend/reset-password.php?token=$token&email=" . urlencode($email) . "&type=$userType";
    // PHPMailer Setup
    try {
        $phpMailer = new PHPMailer(true);
        $phpMailer->isSMTP();
        $phpMailer->Host = "smtp.zoho.com";
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = 'bervinitsolutions@zohomail.com';
        $phpMailer->Password = "!3erv!n@6!S4@Z0H0";
        $phpMailer->SMTPSecure = "tls";
        $phpMailer->Port = 587;
        $phpMailer->isHTML(true);
        $phpMailer->CharSet = "UTF-8";
        $phpMailer->setFrom('bervinitsolutions@zohomail.com', 'Nyamula Logistics');
        $phpMailer->addAddress($email);

        $phpMailer->Subject = 'Password Reset Request';
        $phpMailer->Body = "Hello,<br><br>Click the following link to reset your password:<br><br><a href='$resetLink'>Reset Password</a><br><br>This link will expire in 1 hour.";
        
        $phpMailer->send();
        $_SESSION['reset_message'] = "Password reset link has been sent.";
    } catch (Exception $e) {
        error_log("Mail error: " . $phpMailer->ErrorInfo);
        $_SESSION['reset_message'] = "Failed to send password reset email.";
    }
} else {
    $_SESSION['reset_message'] = "Email not found in our $type records.";
}

// Redirect
header("Location: ../Frontend/reset-password.php?type=$type");
exit();
