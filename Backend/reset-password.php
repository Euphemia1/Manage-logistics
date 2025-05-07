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
    header("Location: forgot-password.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_submit'])) {
    $token = $_POST['token'];
    $email = $_POST['email'];
    $userType = $_POST['type'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        $_SESSION['reset_message'] = "Passwords do not match!";
    } else {
        // Verify token is valid and not expired
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires > NOW()");
        $stmt->execute([$email, $token]);
        $resetRequest = $stmt->fetch();

        if (!$resetRequest) {
            $_SESSION['reset_message'] = "Invalid or expired reset link.";
            header("Location: forgot-password.php");
            exit();
        }

        // Update password in the correct table
        $table = ($userType === 'cargo_owner') ? 'cargo_owners' : 'transporters';
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();
            
            // Update password
            $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            
            // Delete the used token
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            
            $pdo->commit();
            
            // Redirect to the CORRECT login page
            $_SESSION['login_message'] = "Password updated successfully! Please log in.";
            $redirectPage = ($userType === 'cargo_owner') 
                          ? "cargo-owner-login.php" 
                          : "transporter-login.php";
            header("Location: $redirectPage");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['reset_message'] = "Error updating password: " . $e->getMessage();
        }
    }
}

// If NOT a POST request, continue to display the form below
?>