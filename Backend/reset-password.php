<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['reset_message'] = "Database connection failed";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

// Get token, email, and type from URL
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';
$userType = $_GET['type'] ?? '';

// If form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? $token;
    $email = $_POST['email'] ?? $email;
    $userType = $_POST['type'] ?? $userType;
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate passwords
    if (empty($newPassword) || $newPassword !== $confirmPassword) {
        $_SESSION['reset_message'] = "Passwords don't match or are empty";
    } else {
        // Verify token first
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires > NOW()");
        $stmt->execute([$email, $token]);
        $resetRequest = $stmt->fetch();

        if (!$resetRequest) {
            $_SESSION['reset_message'] = "Invalid or expired reset link";
            header("Location: ../Frontend/forgot-password.php");
            exit();
        }

        // Determine which table to update
        $table = ($userType === 'cargo_owner') ? 'cargo_owners' : 'transporters';
        
        try {
            $pdo->beginTransaction();
            
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            
            // Delete token
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            
            $pdo->commit();
            
            // Success - redirect to login
            $_SESSION['login_message'] = "Password updated successfully! Please login.";
            $redirect = ($userType === 'cargo_owner') 
                      ? "../Frontend/cargo-owner-login.php" 
                      : "../Frontend/transporter-login.php";
            header("Location: $redirect");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['reset_message'] = "Error updating password: " . $e->getMessage();
        }
    }
}

// If GET request or POST validation failed, show the form
?>

<!DOCTYPE html>
<!-- Rest of your HTML form remains the same -->