<?php
session_start();
$token = $_GET['token'] ?? '';
$type = $_GET['type'] ?? '';

// if (empty($token) || empty($type)) {
//     die("Invalid password reset link.");
// }
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

        <?php
// Include the validation code shown above
// If validation passes, show this form:
?>
<!-- Add this JavaScript for client-side validation -->
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
</script>

<form method="POST" id="resetForm">
    <!-- Hidden fields MUST match backend expected names -->
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
    
    <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required minlength="8">
    </div>
    
    <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
    </div>
    
    <button type="submit" name="reset_submit">Reset Password</button>
</form>

<script>

function checkPasswordMatch() {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    const message = document.getElementById('passwordMatchMessage');
    
    if (newPass && confirmPass) {
        if (newPass !== confirmPass) {
            message.textContent = "Passwords do not match!";
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








