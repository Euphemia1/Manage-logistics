<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
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

// Get email from form
$email = $_POST['email'] ?? '';

// Check if email exists in either table
$userType = null;
$table = null;

// Check in cargo owners
$stmt = $pdo->prepare("SELECT email FROM cargo_owners WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $userType = 'cargo_owner';
    $table = 'cargo_owners';
} else {
    // Check in transporters
    $stmt = $pdo->prepare("SELECT email FROM transporters WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $userType = 'transporter';
        $table = 'transporters';
    }
}

if ($userType) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);
    
    // Store token
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires, user_type, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$email, $token, $expires, $userType]);

    // Construct the reset link
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $resetLink = "$scheme://$host/Frontend/reset-password.php?token=$token&email=" . urlencode($email) . "&type=" . urlencode($userType);

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
        $phpMailer->setFrom('bervinitsolutions@zohomail.com', 'Nyamula Logistics');
        $phpMailer->addAddress($email);

        // Email content
        $phpMailer->Subject = 'Password Reset Request';
        $phpMailer->Body = "Hello,<br><br>Click the following link to reset your password:<br><br><a href='$resetLink'>Reset Password</a><br><br>This link will expire in 1 hour.";
        
        $phpMailer->send();
        $_SESSION['reset_message'] = "Password reset link has been sent.";
    } catch (Exception $e) {
        error_log("Mail error: " . $phpMailer->ErrorInfo);
        $_SESSION['reset_message'] = "Failed to send password reset email.";
    }
// } else {
//     $_SESSION['reset_message'] = "Email not found.";
}

// Redirect to the login page based on user type
if ($userType === 'cargo_owner') {
    header("Location: ../Frontend/cargo-owner-login.php");
} elseif ($userType === 'transporter') {
    header("Location: ../Frontend/transporter-login.php");
} else {
    header("Location: ../Frontend/forgot-password.php");
}
exit();