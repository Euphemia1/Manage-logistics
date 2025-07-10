<?php

// 1. Sanitize inputs FIRST (critical for security)
$email = filter_var($_POST['reset_email'] ?? '', FILTER_SANITIZE_EMAIL);
$token = $_POST['reset_token'] ?? '';

// 2. Validate inputs (fail fast if invalid)
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($token)) {
    $_SESSION['reset_message'] = "Invalid request parameters.";
    header("Location: forgot-password.php");
    exit();
}

// 3. Check token validity (with prepared statement)
try {
    $stmt = $pdo->prepare("
        SELECT * FROM password_resets 
        WHERE email = ? 
        AND token = ? 
        AND expires > NOW()
        LIMIT 1  # Important for performance
    ");
    $stmt->execute([$email, $token]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        $_SESSION['reset_message'] = "Invalid, expired, or already-used reset link.";
        header("Location: ../Frontend/forgot-password.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Password reset error: " . $e->getMessage());
    $_SESSION['reset_message'] = "System error. Please try again.";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}

?>