<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: transporter-login.php");
    exit();
}

// Database connection
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

// Fetch transporter's current details
$user_name = $_SESSION['user_name'];
$stmt = $pdo->prepare("SELECT * FROM transporters WHERE transporter_name = :user_name");
$stmt->execute(['user_name' => $user_name]);
$driver = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $errors = [];

    $transporter_name = trim($_POST['transporter_name']);
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $company = trim($_POST['company']);
    $truck_number = trim($_POST['truck_number']);

    // Server-side validation
    if (empty($transporter_name)) {
        $errors[] = "Transporter name is required.";
    }
    if (empty($phone_number) || !preg_match('/^\+?\d{10,15}$/', $phone_number)) {
        $errors[] = "Invalid phone number.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (empty($company)) {
        $errors[] = "Company name is required.";
    }

    // If no errors, update the database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE transporters 
                SET transporter_name = :transporter_name, 
                    phone_number = :phone_number, 
                    email = :email, 
                    company = :company, 
                    truck_number = :truck_number
                WHERE transporter_name = :original_name
            ");
            $stmt->execute([
                'transporter_name' => $transporter_name,
                'phone_number' => $phone_number,
                'email' => $email,
                'company' => $company,
                'truck_number' => $truck_number,
                'original_name' => $user_name
            ]);

            // Success message
            $success = "Profile updated successfully!";
            
            // Update session if name changed
            if ($transporter_name !== $user_name) {
                $_SESSION['user_name'] = $transporter_name;
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Nyamula Logistics</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2BC652;
            --primary-green-dark: #229944;
            --primary-green-light: #e8f5e8;
            --secondary-green: #1e7e34;
            --accent-green: #28a745;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --border-gray: #e9ecef;
            --text-dark: #333333;
            --text-muted: #6c757d;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            color: var(--white);
            min-height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 2rem 0;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .sidebar-header {
            text-align: center;
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        .sidebar-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .sidebar-header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .nav-item {
            margin: 0.5rem 1.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            color: var(--white);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
            color: var(--white);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Header */
        .page-header {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .header-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Settings Card */
        .settings-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-gray);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-control:focus {
            border-color: var(--primary-green);
            outline: none;
            box-shadow: 0 0 0 3px rgba(43, 198, 82, 0.1);
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--primary-green);
            border: none;
            color: var(--white);
            padding: 0.875rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .btn-primary-custom:hover {
            background: var(--primary-green-dark);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-secondary-custom {
            background: transparent;
            border: 2px solid var(--border-gray);
            color: var(--text-muted);
            padding: 0.875rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-secondary-custom:hover {
            border-color: var(--primary-green);
            color: var(--primary-green);
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: var(--primary-green-light);
            color: var(--primary-green-dark);
            border: 1px solid var(--primary-green);
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Mobile Responsive */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary-green);
            color: var(--white);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .settings-card {
                padding: 1.5rem;
            }
        }

        /* Form Row */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-truck"></i> Transporter</h3>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        </div>
        
        <nav>
            <div class="nav-item">
                <a href="transporter-dashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="job-board.php" class="nav-link">
                    <i class="fas fa-truck"></i>
                    Available Loads
                </a>
            </div>
            <div class="nav-item">
                <a href="settings.php" class="nav-link active">
                    <i class="fas fa-cogs"></i>
                    Settings
                </a>
            </div>
            <div class="nav-item">
                <a href="index.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="header-title">Settings</h1>
            <p class="header-subtitle">Manage your profile and account preferences</p>
        </div>

        <!-- Display Success/Error Messages -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Settings Form -->
        <div class="settings-card">
            <h2 class="card-title">
                <i class="fas fa-user-cog"></i>
                Profile Information
            </h2>

            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="transporter_name" class="form-label">
                            <i class="fas fa-user"></i> Transporter Name
                        </label>
                        <input 
                            type="text" 
                            id="transporter_name" 
                            name="transporter_name" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($driver['transporter_name'] ?? ''); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($driver['email'] ?? ''); ?>" 
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number" class="form-label">
                            <i class="fas fa-phone"></i> Phone Number
                        </label>
                        <input 
                            type="tel" 
                            id="phone_number" 
                            name="phone_number" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($driver['phone_number'] ?? ''); ?>" 
                            placeholder="+260762273483"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="company" class="form-label">
                            <i class="fas fa-building"></i> Company Name
                        </label>
                        <input 
                            type="text" 
                            id="company" 
                            name="company" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($driver['company'] ?? ''); ?>" 
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="truck_number" class="form-label">
                        <i class="fas fa-truck"></i> Truck Number/License Plate
                    </label>
                    <input 
                        type="text" 
                        id="truck_number" 
                        name="truck_number" 
                        class="form-control" 
                        value="<?php echo htmlspecialchars($driver['truck_number'] ?? ''); ?>" 
                        placeholder="Enter truck license plate number"
                    >
                </div>

                <div class="form-group" style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--border-gray);">
                    <button type="submit" class="btn-primary-custom">
                        <i class="fas fa-save"></i>
                        Update Profile
                    </button>
                    
                    <a href="transporter-dashboard.php" class="btn-secondary-custom" style="margin-left: 1rem;">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>

        <!-- Additional Settings -->
        <div class="settings-card">
            <h2 class="card-title">
                <i class="fas fa-shield-alt"></i>
                Account Security
            </h2>
            
            <div class="form-group">
                <p style="color: var(--text-muted); margin-bottom: 1rem;">
                    <i class="fas fa-info-circle"></i>
                    To change your password, please contact the administrator or use the password reset feature.
                </p>
                <a href="forgot-password.php" class="btn-secondary-custom">
                    <i class="fas fa-key"></i>
                    Reset Password
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('phone_number');
            const phonePattern = /^\+?\d{10,15}$/;
            
            if (!phonePattern.test(phoneInput.value)) {
                e.preventDefault();
                alert('Please enter a valid phone number (10-15 digits, optional + prefix)');
                phoneInput.focus();
                return false;
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>