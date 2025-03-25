<?php
session_start();

// Database connection setup with proper error handling
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    $_SESSION['reset_message'] = "Database connection error. Please try again later.";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

// Get form data
$token = $_POST['token'] ?? '';
$email = $_POST['email'] ?? '';
$userType = $_POST['type'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validate input
if (empty($token) || empty($email) || empty($userType) || 
    empty($newPassword) || $newPassword !== $confirmPassword) {
    $_SESSION['reset_message'] = "Invalid request or passwords don't match";
    header("Location: ../Frontend/reset-password.php?token=" . urlencode($token) . 
          "&email=" . urlencode($email) . "&type=" . urlencode($userType));
    exit();
}

// Verify token is still valid
try {
    $stmt = $pdo->prepare("SELECT * FROM password_resets 
                          WHERE email = ? AND token = ? AND expires > NOW()");
    $stmt->execute([$email, $token]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        $_SESSION['reset_message'] = "Invalid or expired reset link";
        header("Location: ../Frontend/forgot-password.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Token verification failed: " . $e->getMessage());
    $_SESSION['reset_message'] = "Error verifying reset token";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

// Update password
$table = ($userType === 'cargo_owner') ? 'cargo_owners' : 'transporters';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    $pdo->beginTransaction();
    
    // Update password in the appropriate table
    $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE email = ?");
    $stmt->execute([$hashedPassword, $email]);
    
    // Delete used token
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->execute([$email]);
    
    $pdo->commit();
    
    $_SESSION['reset_message'] = "Password updated successfully!";
    $redirectPage = ($userType === 'cargo_owner') 
                  ? '../Frontend/cargo-owner-login.php' 
                  : '../Frontend/transporter-login.php';
    header("Location: $redirectPage");
    exit();
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Password update failed: " . $e->getMessage());
    $_SESSION['reset_message'] = "Error updating password. Please try again.";
    header("Location: ../Frontend/reset-password.php?token=" . urlencode($token) . 
          "&email=" . urlencode($email) . "&type=" . urlencode($userType));
    exit();
}