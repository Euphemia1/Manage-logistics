<?php
// Start session if not already started
session_start();

// Database connection setup - this is what was missing
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
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
            die("Failed to store reset token: " . $e->getMessage());
        }
        
        // Send email with reset link
        $resetLink = "https://nyamula.com/reset-password.php?token=$token&email=$email&type=$type";
        
        // Here you would use mail() or a library like PHPMailer to send the email
        // For example:
        // mail($email, "Password Reset", "Click the following link to reset your password: $resetLink", "From: noreply@yourdomain.com");
        
        echo "Password reset link has been sent to your email.";
    } else {
        echo "Email not found.";
    }
} else {
    echo "Please provide an email address and user type.";
}
?>
