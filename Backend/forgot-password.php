<?php
// Start session if not already started
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
        // Get the current scheme (http or https)
$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

// Get the host (domain)
$host = $_SERVER['HTTP_HOST'];

// Get the current request URI (e.g., /myapp/somepage.php)
$requestUri = $_SERVER['REQUEST_URI'];

// Extract the directory from the request URI (e.g., /myapp)
$directory = rtrim(dirname($requestUri), '/');
$directory = rtrim(dirname($directory), '/');

// Reconstruct the base URL with the scheme, host, and directory
$baseUrl = $scheme . '://' . $host . $directory;

// echo $baseUrl;
        // Send email with reset link
        $resetLink = $baseUrl."/Frontend/reset-password.php?token=$token&email=$email&type=$type";
        
        // Prepare email
        // $mail = new PHPMailer(true);
echo $resetLink;
        try {
//             // NEW CHANGES START
            $phpMailer = new PHPMailer(true);
$phpMailer->SMTPDebug = SMTP::DEBUG_SERVER;
// $phpMailer->isSMTP();
$phpMailer->isSMTP();
$phpMailer->Host = "smtp.zoho.com";
$phpMailer->SMTPAuth = true;
$phpMailer->Username   = 'bervinitsolutions@zohomail.com'; // Add your SMTP username here
$phpMailer->Password = "!3erv!n@6!S4@Z0H0";
$phpMailer->SMTPSecure = "tls"; // or PHPMailer::ENCRYPTION_STARTTLS
$phpMailer->Port = 587;
$phpMailer->isHTML(true);
$phpMailer->CharSet = "UTF-8";
$phpMailer->setFrom('bervinitsolutions@zohomail.com', 'Nyamula Logistics', 0);

$phpMailer->addAddress($email);
//Content
$phpMailer->isHTML(true);
$phpMailer->Subject = 'Password Reset Request';
$phpMailer->Body    = "Hello,<br><br>You have requested to reset your password. Click the following link to reset your password:<br><br><a href='$resetLink'>Reset Password</a><br><br>If you did not request this, please ignore this email.<br><br>This link will expire in 1 hour.";
// $phpMailer->AltBody = "Hello,\n\nYou have requested to reset your password. Click the following link to reset your password:\n\n$resetLink\n\nIf you did not request this, please ignore this email.\n\nThis link will expire in 1 hour.";

// Send email
$phpMailer->send();
$_SESSION['reset_message'] = "Password reset link has been sent to your email.";
// $phpMailer->Subject = "subject";
// $phpMailer->Body = "mail-body";
// $phpMailer->send();
            // NEW CHANGES END
            //Server settings
            // $mail->isSMTP();
            // $mail->Host       = 'smtp.zoho.com';
            // $mail->SMTPAuth   = true;
            // $mail->Username   = 'euphemiachikungulu@zohomail.com'; // Add your SMTP username here
            // $mail->Password   = '';
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Changed to SMTPS for port 465
            // $mail->Port       = 465;
            // $phpMailer->IsSMTP();
            //Recipients
            // $mail->setFrom('noreply@nyamula.com', 'Nyamula Logistics');
            //$mail->addAddress($email);

            // //Content
            // $mail->isHTML(true);
            // $mail->Subject = 'Password Reset Request';
            // $mail->Body    = "Hello,<br><br>You have requested to reset your password. Click the following link to reset your password:<br><br><a href='$resetLink'>Reset Password</a><br><br>If you did not request this, please ignore this email.<br><br>This link will expire in 1 hour.";
            // $mail->AltBody = "Hello,\n\nYou have requested to reset your password. Click the following link to reset your password:\n\n$resetLink\n\nIf you did not request this, please ignore this email.\n\nThis link will expire in 1 hour.";

            // // Send email
            // $mail->send();
            // $_SESSION['reset_message'] = "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['reset_message'] = "Failed to send password reset email. Please try again later. Error: {$phpMailer->ErrorInfo}";
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