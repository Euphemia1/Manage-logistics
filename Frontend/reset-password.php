<?php
session_start();
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';
$type = $_GET['type'] ?? '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $token = $_POST['reset_token'];
    $email = $_POST['reset_email'];
    $userType = $_POST['user_type'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        $_SESSION['reset_message'] = "Passwords do not match!";
    } else {
        // Database connection
        $host = 'localhost';
        $dbname = 'logistics';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Verify token is valid and not expired
            $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires > NOW()");
            $stmt->execute([$email, $token]);
            $resetRequest = $stmt->fetch();

            if (!$resetRequest) {
                $_SESSION['reset_message'] = "Invalid or expired reset link.";
            } else {
                // Validate user type
                $validTypes = ['cargo_owners', 'transporters'];
                if (!in_array($userType, $validTypes)) {
                    $_SESSION['reset_message'] = "Invalid user type.";
                    header("Location: forgot-password.php");
                    exit();
                }
                
                // Use the exact table name
                $table = $userType;
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
                    $redirectPage = ($userType === 'cargo_owners') 
                                  ? "cargo-owner-login.php" 
                                  : "transporter-login.php";
                    header("Location: $redirectPage");
                    exit();
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    $_SESSION['reset_message'] = "Error updating password: " . $e->getMessage();
                }
            }
        } catch (PDOException $e) {
            $_SESSION['reset_message'] = "Database connection failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 1rem;
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
            text-align: center;
        }

        #passwordMatchMessage {
            font-size: 0.85rem;
            margin-top: 0.25rem;
            color: #c62828;
        }

        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }
            input, button {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if (isset($_SESSION['reset_message'])): ?>
            <p class="message"><?php echo $_SESSION['reset_message']; unset($_SESSION['reset_message']); ?></p>
        <?php endif; ?>

        <?php if (empty($token) || empty($email) || empty($type)): ?>
            <p class="error-message">
                Invalid password reset link. Missing required parameters.
            </p>
            <p style="text-align: center; margin-top: 1rem;">
                <a href="forgot-password.php">Return to forgot password page</a>
            </p>
        <?php else: ?>
            <form action="" method="POST" id="resetForm" onsubmit="return validatePasswords()">
                <!-- Hidden fields with correct values -->
                <input type="hidden" name="reset_token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="reset_email" value="<?= htmlspecialchars($email) ?>">
                <input type="hidden" name="user_type" value="<?= htmlspecialchars($type) ?>">
                
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8" onkeyup="checkPasswordMatch()">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8" onkeyup="checkPasswordMatch()">
                    <div id="passwordMatchMessage"></div>
                </div>
                
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>

        <script>
        function validatePasswords() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass !== confirmPass) {
                alert('Passwords do not match!');
                return false;
            }
            return true;
        }

        function checkPasswordMatch() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            const message = document.getElementById('passwordMatchMessage');
            
            if (newPass && confirmPass) {
                if (newPass !== confirmPass) {
                    message.textContent = "Passwords do not match!";
                    message.style.color = "#c62828";
                } else {
                    message.textContent = "Passwords match!";
                    message.style.color = "green";
                }
            } else {
                message.textContent = "";
            }
        }
        </script>
    </div>
</body>
</html>