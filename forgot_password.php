<?php
session_start();
$type = $_GET['type'] ?? '';
$validTypes = ['cargo_owners', 'transporters'];
$isValidType = in_array($type, $validTypes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Nyamula Logistics</title>
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

        .forgot-password-container {
            background: var(--white);
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border-light);
        }

        .forgot-password-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: var(--white);
            padding: 2rem;
            text-align: center;
        }

        .forgot-password-header h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .forgot-password-body {
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

        .user-type-selection {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .user-type-btn {
            padding: 1.25rem;
            border: none;
            border-radius: 0.75rem;
            text-decoration: none;
            color: var(--white);
            font-weight: 500;
            font-size: 1.1rem;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .cargo-owner-btn {
            background: var(--primary-green);
        }

        .cargo-owner-btn:hover {
            background: var(--dark-green);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .transporter-btn {
            background: var(--gray-medium);
        }

        .transporter-btn:hover {
            background: var(--gray-dark);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .switch-link {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
        }

        .switch-link:hover {
            color: var(--dark-green);
            text-decoration: underline;
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

        .description-text {
            color: var(--gray-medium);
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 576px) {
            .forgot-password-container {
                margin: 1rem;
            }
            
            .forgot-password-header,
            .forgot-password-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <div class="forgot-password-header">
            <h2>
                <i class="fas fa-key"></i>
                Forgot Password
            </h2>
        </div>
        
        <div class="forgot-password-body">
            <?php
            if (isset($_SESSION['reset_message'])) {
                echo '<div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        ' . $_SESSION['reset_message'] . '
                      </div>';
                unset($_SESSION['reset_message']);
            }
            ?>

            <?php if (!$isValidType): ?>
                <p class="description-text text-center">Please select your account type to reset your password:</p>
                <div class="user-type-selection">
                    <a href="?type=cargo_owners" class="user-type-btn cargo-owner-btn">
                        <i class="fas fa-box"></i>
                        Cargo Owner
                    </a>
                    <a href="?type=transporters" class="user-type-btn transporter-btn">
                        <i class="fas fa-truck"></i>
                        Transporter
                    </a>
                </div>
            <?php else: ?>
                <form action="forgot-password.php" method="POST">
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane"></i>
                        Send Reset Link
                    </button>
                </form>
                <p class="text-center mt-3 text-muted">
                    <small>Not a <?php echo $type === 'cargo_owners' ? 'Cargo Owner' : 'Transporter'; ?>? 
                    <a href="?type=<?php echo $type === 'cargo_owners' ? 'transporters' : 'cargo_owners'; ?>" class="switch-link">
                        Click here to switch
                    </a>
                    </small>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
