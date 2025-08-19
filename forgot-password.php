<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['reset_message'] = "Database connection failed: " . $e->getMessage();
    header("Location: forgot_password.php");
    exit();
}

$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$type = $_POST['type'] ?? '';


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['reset_message'] = "Please enter a valid email address.";
    header("Location: forgot_password.php?type=" . urlencode($type));
    exit();
}

if (empty($type)) {
    $_SESSION['reset_message'] = "Please select a user type.";
    header("Location: forgot_password.php");
    exit();
}

$validTypes = ['cargo_owners', 'transporters'];
if (!in_array($type, $validTypes)) {
    $_SESSION['reset_message'] = "Invalid user type selected.";
    header("Location: forgot_password.php");
    exit();
}
$table = $type; 
$stmt = $pdo->prepare("SELECT email FROM $table WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600); 
    
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires, user_type, created_at) VALUES (?, ?, ?, ?, NOW())");
    try {
        $stmt->execute([$email, $token, $expires, $type]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['reset_message'] = "Failed to store reset token.";
        header("Location: forgot_password.php?type=" . urlencode($type));
        exit();
    }


    $isLocalhost = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
    
    if ($isLocalhost) {
       
        $resetLink = "http://localhost/nyamula-logistics/reset-password.php?" . http_build_query([
            'token' => $token,
            'email' => $email,
            'type' => $type
        ]);
    } else {
      
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $resetLink = "$scheme://{$_SERVER['HTTP_HOST']} reset-password.php?" . http_build_query([
            'token' => $token,
            'email' => $email,
            'type' => $type
        ]);
    }
    $emailSent = false;
    $emailBody = "
Dear User,

You have requested to reset your password for your Nyamula Logistics account.

Please click the following link to reset your password:
$resetLink

This link will expire in 1 hour for security reasons.

If you did not request this password reset, please ignore this email.

Best regards,
Nyamula Logistics Team
";
    try {
        $phpMailer = new PHPMailer(true);
        
        $phpMailer->isSMTP();
        $phpMailer->Host = "smtp.zoho.com";
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = 'bervinitsolutions@zohomail.com';
        $phpMailer->Password = "!3erv!n@6!S4@Z0H0";
        $phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpMailer->Port = 587;
        
        $phpMailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $phpMailer->isHTML(false); 
        $phpMailer->CharSet = "UTF-8";
        $phpMailer->setFrom('bervinitsolutions@zohomail.com', 'Nyamula Logistics');
        $phpMailer->addAddress($email);
        $phpMailer->Subject = 'Password Reset Request - Nyamula Logistics';
        $phpMailer->Body = $emailBody;
        
        $phpMailer->send();
        $emailSent = true;
        
    } catch (Exception $e) {
        error_log("PHPMailer failed: " . $e->getMessage());
        
        $headers = "From: Nyamula Logistics <noreply@nyamula-logistics.com>\r\n";
        $headers .= "Reply-To: noreply@nyamula-logistics.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        if (mail($email, 'Password Reset Request - Nyamula Logistics', $emailBody, $headers)) {
            $emailSent = true;
        }
    }
    
    if ($emailSent) {
        $_SESSION['reset_message'] = "A password reset link has been sent to your email address. Please check your inbox and spam folder.";
    } else {
        error_log("Failed to send password reset email to: " . $email);
        $_SESSION['reset_message'] = "Failed to send password reset email. Please try again later or contact support.";
    }
} else {
    $_SESSION['reset_message'] = "Email not found in our records.";
}

header("Location: forgot_password.php?type=" . urlencode($type));
exit();