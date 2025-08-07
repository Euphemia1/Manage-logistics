<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration - Nyamula Logistics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2BC652;
            --primary-dark: #1e8c3d;
            --primary-light: #e8f5e8;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --text-light: #666;
            --border-color: #e0e0e0;
            --white: #ffffff;
            --border-radius: 10px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        .register-header {
            background: var(--primary-color);
            color: white;
            text-align: center;
            padding: 40px 30px;
            position: relative;
        }

        .register-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            opacity: 0.1;
        }

        .logo {
            font-size: 48px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .register-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .register-header p {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .register-form {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
            font-size: 14px;
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 15px 45px 15px 15px;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(43, 198, 82, 0.2);
        }

        .form-group .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 18px;
        }

        .password-toggle {
            cursor: pointer;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(43, 198, 82, 0.3);
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
            color: var(--text-light);
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            background: var(--white);
            padding: 0 20px;
            position: relative;
        }

        .login-link {
            text-align: center;
            padding: 20px 0;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .login-link a:hover {
            color: var(--primary-dark);
        }

        .alert {
            padding: 12px 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .alert.success {
            background: rgba(43, 198, 82, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(43, 198, 82, 0.3);
        }

        .alert.error {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .password-requirements {
            font-size: 12px;
            color: var(--text-light);
            margin-top: 5px;
            line-height: 1.4;
        }

        .requirement {
            margin: 2px 0;
            transition: var(--transition);
        }

        .requirement.met {
            color: var(--primary-color);
        }

        .requirement.met::before {
            content: '✓ ';
            font-weight: bold;
        }

        .requirement:not(.met)::before {
            content: '• ';
        }

        @media (max-width: 576px) {
            .register-container {
                margin: 10px;
            }
            
            .register-header {
                padding: 30px 20px;
            }
            
            .register-form {
                padding: 30px 20px;
            }
            
            .register-header h1 {
                font-size: 24px;
            }
            
            .logo {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="logo">
                <i class="fas fa-truck"></i>
            </div>
            <h1>Admin Registration</h1>
            <p>Create your admin account</p>
        </div>

        <div class="register-form">
            <div id="alert" class="alert"></div>

            <form id="registerForm" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye password-toggle input-icon" onclick="togglePassword('password')"></i>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" id="length">At least 8 characters</div>
                        <div class="requirement" id="uppercase">One uppercase letter</div>
                        <div class="requirement" id="lowercase">One lowercase letter</div>
                        <div class="requirement" id="number">One number</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <i class="fas fa-eye password-toggle input-icon" onclick="togglePassword('confirmPassword')"></i>
                    </div>
                </div>

                <button type="submit" class="btn" id="submitBtn">
                    <i class="fas fa-user-plus"></i>
                    Create Admin Account
                </button>
            </form>

            <div class="divider">
                <span>or</span>
            </div>

            <div class="login-link">
                Already have an account? <a href="admin-login.php">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            
            // Check length
            const lengthReq = document.getElementById('length');
            if (password.length >= 8) {
                lengthReq.classList.add('met');
            } else {
                lengthReq.classList.remove('met');
            }
            
            // Check uppercase
            const uppercaseReq = document.getElementById('uppercase');
            if (/[A-Z]/.test(password)) {
                uppercaseReq.classList.add('met');
            } else {
                uppercaseReq.classList.remove('met');
            }
            
            // Check lowercase
            const lowercaseReq = document.getElementById('lowercase');
            if (/[a-z]/.test(password)) {
                lowercaseReq.classList.add('met');
            } else {
                lowercaseReq.classList.remove('met');
            }
            
            // Check number
            const numberReq = document.getElementById('number');
            if (/[0-9]/.test(password)) {
                numberReq.classList.add('met');
            } else {
                numberReq.classList.remove('met');
            }
        });

        // Form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const alert = document.getElementById('alert');
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            
            // Hide previous alerts
            alert.style.display = 'none';
            
            fetch('add-admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.className = 'alert success';
                    alert.textContent = data.message;
                    alert.style.display = 'block';
                    
                    // Reset form
                    this.reset();
                    
                    // Redirect after success
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                } else {
                    alert.className = 'alert error';
                    alert.textContent = data.message;
                    alert.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert.className = 'alert error';
                alert.textContent = 'An error occurred. Please try again.';
                alert.style.display = 'block';
            })
            .finally(() => {
                // Restore button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Admin Account';
            });
        });

        // Real-time validation
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#e0e0e0';
            }
        });

        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#e0e0e0';
            }
        });
    </script>
</body>
</html>
