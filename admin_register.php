<?php
// admin_register.php
// You can add PHP code here for server-side processing if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .registration-container {
            background-color: white;
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
            color: #555;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .register-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .register-btn:hover {
            background-color: #3a7bc8;
        }

        .message {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Admin Registration</h2>
        <form id="adminRegisterForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required minlength="8">
            </div>
            <div class="form-group">
                <label for="adminKey">Admin Secret Key:</label>
                <input type="password" id="adminKey" name="adminKey" required>
            </div>
            <button type="submit" class="register-btn">Register Admin</button>
        </form>
        <div id="message" class="message"></div>
    </div>

    <script>
        document.getElementById('adminRegisterForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const adminKey = document.getElementById('adminKey').value;
            const messageEl = document.getElementById('message');
            
            // Clear previous messages
            messageEl.textContent = '';
            messageEl.className = 'message';
            
            // Validation
            if (password !== confirmPassword) {
                showMessage('Passwords do not match', 'error');
                return;
            }
            
            if (password.length < 8) {
                showMessage('Password must be at least 8 characters', 'error');
                return;
            }
            
            // In a real app, you would validate the admin key against your backend
            if (!adminKey) {
                showMessage('Admin key is required', 'error');
                return;
            }
            
            try {
                // Hash the password before sending (in a real app, you might do this on the backend)
                // This is a simple example - use a proper hashing library in production
                const hashedPassword = await hashPassword(password);
                
                const response = await fetch('/api/admin/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username,
                        email,
                        password: hashedPassword,
                        adminKey
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showMessage('Admin registration successful!', 'success');
                    // Redirect to admin dashboard after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/admin/dashboard';
                    }, 2000);
                } else {
                    showMessage(data.message || 'Registration failed', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('An error occurred during registration', 'error');
            }
        });

        // Simple password hashing function (use a proper library like bcrypt in production)
        async function hashPassword(password) {
            // In a real application, you would:
            // 1. Use a proper hashing algorithm (like bcrypt)
            // 2. Probably do this on the backend, not the frontend
            const encoder = new TextEncoder();
            const data = encoder.encode(password);
            const hashBuffer = await crypto.subtle.digest('SHA-256', data);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
            return hashHex;
        }

        function showMessage(message, type) {
            const messageEl = document.getElementById('message');
            messageEl.textContent = message;
            messageEl.classList.add(type);
        }
    </script>
</body>
</html>