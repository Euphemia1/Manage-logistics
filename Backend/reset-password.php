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

// At the top of reset-password.php, add:
$email = $_GET['email'] ?? '';
$userType = $_GET['type'] ?? '';
// Get token, email, and type from URL
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';
$userType = $_GET['type'] ?? '';

// Validate parameters
if (empty($token) || empty($email) || empty($userType)) {
    $_SESSION['reset_message'] = "Invalid reset link";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

// Verify token exists and isn't expired
$stmt = $pdo->prepare("SELECT * FROM password_resets 
                      WHERE email = ? AND token = ? AND expires > NOW()");
$stmt->execute([$email, $token]);
$resetRequest = $stmt->fetch();

if (!$resetRequest) {
    $_SESSION['reset_message'] = "Invalid or expired reset link";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

// If form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate passwords
    if (empty($newPassword) || $newPassword !== $confirmPassword) {
        $_SESSION['reset_message'] = "Passwords don't match or are empty";
    } else {
        // Determine which table to update
        $table = ($userType === 'cargo_owner') ? 'cargo_owners' : 'transporters';
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        try {
            $pdo->beginTransaction();
            
            // Update password in the appropriate table
            $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            
            // Delete the used token
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            
            $pdo->commit();
            
            $_SESSION['reset_message'] = "Password updated successfully!";
            $redirect = ($userType === 'cargo_owner') 
                      ? "../Frontend/cargo-owner-login.php" 
                      : "../Frontend/transporter-login.php";
            header("Location: $redirect");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['reset_message'] = "Error updating password";
        }
    }
}

// Display the reset form if GET request or if POST validation failed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .message { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Reset Your Password</h1>
    
    <?php if (isset($_SESSION['reset_message'])): ?>
        <div class="message"><?= htmlspecialchars($_SESSION['reset_message']) ?></div>
        <?php unset($_SESSION['reset_message']); ?>
    <?php endif; ?>
    
    <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <input type="hidden" name="type" value="<?= htmlspecialchars($userType) ?>">
        
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
