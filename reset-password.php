<?php
session_start();

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';
$type = $_GET['type'] ?? '';

// Database connection parameters
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['reset_message'] = "Database connection failed: " . $e->getMessage();
    header("Location: forgot-password.php");
    exit();
}

// Function to validate user type and return safe table name
function validateUserTypeTable($type) {
    $validTypes = ['cargo_owners', 'transporters'];
    if (in_array($type, $validTypes)) {
        return $type; // Return the safe table name
    }
    return false; // Invalid type
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $token = $_POST['reset_token'] ?? '';
    $email = $_POST['reset_email'] ?? '';
    $userType = $_POST['user_type'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        $_SESSION['reset_message'] = "Passwords do not match!";
    } else if (strlen($newPassword) < 8) { // Basic password length check
        $_SESSION['reset_message'] = "New password must be at least 8 characters long.";
    } else {
        // Verify token is valid and not expired
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires > NOW()");
        $stmt->execute([$email, $token]);
        $resetRequest = $stmt->fetch();

        if (!$resetRequest) {
            $_SESSION['reset_message'] = "Invalid or expired reset link.";
            // Redirect back to forgot password page if link is invalid
            header("Location: forgot-password.php");
            exit();
        }

        // Validate user type to get a safe table name
        $table = validateUserTypeTable($userType);
        if (!$table) {
            $_SESSION['reset_message'] = "Invalid user type.";
            header("Location: forgot-password.php");
            exit();
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();
            
            // Update password in the correct table
            $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            
            // Delete the used token
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            
            $pdo->commit();
            
            // Redirect to the CORRECT login page based on user type
            $_SESSION['login_message'] = "Password updated successfully! Please log in.";
            $redirectPage = ($userType === 'cargo_owners') 
                                ? "cargo-owner-login.php" 
                                : "transporter-login.php";
            header("Location: $redirectPage");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Error updating password: " . $e->getMessage());
            $_SESSION['reset_message'] = "Error updating password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Nyamula Logistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2BC652;
            --dark-green: #219A43;
            --light-green: #E8F5E8;
            --white: #FFFFFF;
            --gray-light: #F8F9FA;
            --gray-medium: #6C757D;
            --gray-dark: #343A40;
            --border-light: #DEE2E6;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --danger: #DC3545;
            --success: #198754;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--light-green) 0%, var(--white) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .reset-password-container {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--border-light);
        }

        .reset-password-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            padding: 2rem;
            text-align: center;
        }

        .reset-password-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .reset-password-body {
            padding: 2rem;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background-color: var(--light-green);
            color: var(--dark-green);
        }

        .alert-danger {
            background-color: #FFF5F5;
            color: var(--danger);
        }

        .form-label {
            color: var(--gray-dark);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--border-light);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(43, 198, 82, 0.25);
        }

        .btn-primary {
            background: var(--primary-green);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--gray-medium);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--white);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--gray-dark);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .password-match-message {
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .password-requirements {
            background: var(--gray-light);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-green);
        }

        .password-requirements h6 {
            color: var(--gray-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 1.25rem;
            color: var(--gray-medium);
        }

        .password-requirements li {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: var(--gray-medium) !important;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        @media (max-width: 576px) {
            .reset-password-container {
                margin: 1rem;
            }
            
            .reset-password-header,
            .reset-password-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="reset-password-header">
            <h2>
                <i class="fas fa-lock"></i>
                Reset Password
            </h2>
        </div>
        
        <div class="reset-password-body">
            <?php if (isset($_SESSION['reset_message'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $_SESSION['reset_message']; unset($_SESSION['reset_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($token) || empty($email) || empty($type)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Invalid password reset link. Please request a new password reset.
                </div>
                <div class="text-center mt-3">
                    <a href="forgot_password.php" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Return to Forgot Password
                    </a>
                </div>
            <?php else: ?>
                <div class="password-requirements">
                    <h6><i class="fas fa-info-circle"></i> Password Requirements</h6>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Mix of letters, numbers, and special characters recommended</li>
                        <li>Both passwords must match</li>
                    </ul>
                </div>

                <form action="" method="POST" id="resetForm" onsubmit="return validatePasswords()">
                    <!-- Hidden fields with correct names matching backend expectations -->
                    <input type="hidden" name="reset_token" value="<?= htmlspecialchars($token) ?>">
                    <input type="hidden" name="reset_email" value="<?= htmlspecialchars($email) ?>">
                    <input type="hidden" name="user_type" value="<?= htmlspecialchars($type) ?>">
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            <i class="fas fa-key"></i>
                            New Password
                        </label>
                        <input type="password" class="form-control" id="new_password" name="new_password" 
                               required minlength="8" onkeyup="checkPasswordMatch()" 
                               placeholder="Enter your new password">
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-check-double"></i>
                            Confirm Password
                        </label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                               required minlength="8" onkeyup="checkPasswordMatch()" 
                               placeholder="Confirm your new password">
                        <div id="passwordMatchMessage" class="password-match-message"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sync-alt"></i>
                        Reset Password
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validatePasswords() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            const messageDiv = document.getElementById('passwordMatchMessage');
            
            if (newPass !== confirmPass) {
                messageDiv.textContent = 'Passwords do not match!';
                messageDiv.style.color = 'var(--danger)';
                return false;
            }
            if (newPass.length < 8) {
                messageDiv.textContent = 'Password must be at least 8 characters long.';
                messageDiv.style.color = 'var(--danger)';
                return false;
            }
            messageDiv.textContent = ''; // Clear message if valid
            return true;
        }

        function checkPasswordMatch() {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            const message = document.getElementById('passwordMatchMessage');
            
            if (newPass && confirmPass) {
                if (newPass !== confirmPass) {
                    message.textContent = "Passwords do not match!";
                    message.style.color = "var(--danger)";
                } else {
                    message.textContent = "Passwords match!";
                    message.style.color = "var(--success)";
                }
            } else {
                message.textContent = "";
            }
        }
    </script>
</body>
</html>
