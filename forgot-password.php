<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// git

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
    header("Location: forgot-password.php");
    exit();
}

// Get email and user type from form
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$type = $_POST['type'] ?? '';

// Validate email and type
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['reset_message'] = "Please enter a valid email address.";
    header("Location: forgot-password.php?type=" . urlencode($type));
    exit();
}

if (empty($type)) {
    $_SESSION['reset_message'] = "Please select a user type.";
    header("Location: forgot-password.php");
    exit();
}

// Validate user type
$validTypes = ['cargo_owners', 'transporters'];
if (!in_array($type, $validTypes)) {
    $_SESSION['reset_message'] = "Invalid user type selected.";
    header("Location: forgot-password.php");
    exit();
}

// Determine which table to query based on user type
$table = $type; // Since we're now using the exact table names

// Check if email exists in the correct table
$stmt = $pdo->prepare("SELECT email FROM $table WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiration
    
    // Store token
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires, user_type, created_at) VALUES (?, ?, ?, ?, NOW())");
    try {
        $stmt->execute([$email, $token, $expires, $type]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['reset_message'] = "Failed to store reset token.";
        header("Location: forgot-password.php?type=" . urlencode($type));
        exit();
    }

    // Construct reset link - PROPERLY FORMATED
    $isLocalhost = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
    
    if ($isLocalhost) {
        // For local development
        $resetLink = "http://localhost/nyamula-logistics/Frontend/reset-password.php?" . http_build_query([
            'token' => $token,
            'email' => $email,
            'type' => $type
        ]);
    } else {
        // For production
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $resetLink = "$scheme://{$_SERVER['HTTP_HOST']}/Frontend/reset-password.php?" . http_build_query([
            'token' => $token,
            'email' => $email,
            'type' => $type
        ]);
    }

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
        $phpMailer->Body = "
            <html>
            <body>
                <p>Hello,</p>
                <p>Click the following link to reset your password:</p>
                <p><a href='$resetLink' style='color: #0066cc;'>Reset Password</a></p>
                <p><strong>This link expires in 1 hour.</strong></p>
                <p>If you didn't request this, please ignore this email.</p>
                <hr>
                <p style='color: #666; font-size: 0.8em;'>
                    For security reasons, please don't share this link with anyone.
                </p>
            </body>
            </html>
        ";
        
        $phpMailer->send();
        $_SESSION['reset_message'] = "Password reset link has been sent to your email.";
    } catch (Exception $e) {
        error_log("Mail error: " . $phpMailer->ErrorInfo);
        $_SESSION['reset_message'] = "Failed to send password reset email. Please try again later.";
    }
} else {
    $_SESSION['reset_message'] = "Email not found in our records.";
}

// Always include the type in the redirect
header("Location: forgot-password.php?type=" . urlencode($type));
exit();