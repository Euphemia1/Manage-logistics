<?php
session_start();
$login_error = '';
if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Nyamula Logistics</title>
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
        .admin-login-container {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border-light);
        }
        .admin-login-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            padding: 2rem;
            text-align: center;
        }
        .admin-login-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .admin-login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .admin-login-body {
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
        .alert-danger {
            background-color: #FFF5F5;
            color: #C53030;
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
        .admin-badge {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2rem;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.5rem;
            display: inline-block;
        }
        @media (max-width: 576px) {
            .admin-login-container {
                margin: 1rem;
            }
            .admin-login-header,
            .admin-login-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-header">
            <h2>
                <i class="fas fa-shield-alt"></i>
                Admin Login
            </h2>
            <p>Nyamula Logistics Management Portal</p>
            <span class="admin-badge">Administrative Access</span>
        </div>
        
        <div class="admin-login-body">
            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <form action="admin_login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-user"></i>
                        Username
                    </label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter admin username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter admin password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to Dashboard
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Authorized personnel only
                </small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
