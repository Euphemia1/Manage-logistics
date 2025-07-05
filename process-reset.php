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
// Get and trim all input values to remove whitespace
$token = trim($_POST['token'] ?? '');
$email = trim($_POST['email'] ?? '');
$userType = trim($_POST['type'] ?? '');
$newPassword = trim($_POST['new_password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

// Enhanced validation
if (empty($token) || empty($email) || empty($userType)) {
    $_SESSION['reset_message'] = "Invalid reset request. Please try the reset link again.";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

if (empty($newPassword) || empty($confirmPassword)) {
    $_SESSION['reset_message'] = "Both password fields are required.";
    header("Location: ../Frontend/reset-password.php?token=" . urlencode($token) . 
          "&email=" . urlencode($email) . "&type=" . urlencode($userType));
    exit();
}

if ($newPassword !== $confirmPassword) {
    $_SESSION['reset_message'] = "The passwords you entered do not match. Please try again.";
    header("Location: ../Frontend/reset-password.php?token=" . urlencode($token) . 
          "&email=" . urlencode($email) . "&type=" . urlencode($userType));
    exit();
}

if (strlen($newPassword) < 8) {
    $_SESSION['reset_message'] = "Password must be at least 8 characters long.";
    header("Location: ../Frontend/reset-password.php?token=" . urlencode($token) . 
          "&email=" . urlencode($email) . "&type=" . urlencode($userType));
    exit();
}
// } catch (PDOException $e) {
//     error_log("Token verification failed: " . $e->getMessage());
//     $_SESSION['reset_message'] = "Error verifying reset token";
//     header("Location: ../Frontend/forgot-password.php");
//     exit();
// }

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
