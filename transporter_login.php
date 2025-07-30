<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// session_start(); // This line is redundant if already called above
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM transporters WHERE email = ?";
    $stmt = $conn->prepare($sql);
    // Check if prepare() was successful
    if ($stmt === false) {
        $_SESSION['login_error'] = "Database query preparation failed: " . $conn->error;
        header("Location: transporter-login.php");
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['transporter_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['transporter_name'];
            // Redirect to transporter dashboard upon successful login
            header("Location: transporter-dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
            header("Location: transporter-login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: transporter-login.php");
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transporter Login - Nyamula Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide-icons.js" rel="stylesheet">
    <script src="https://unpkg.com/lucide-icons" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #e0ffe0 0%, #f0fff0 100%); /* Light green gradient */
            color: #333;
        }
        .login-container {
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 128, 0, 0.1); /* Subtle green border */
        }
        .login-container:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        .input-field {
            border-color: #d1d5db; /* Default border color */
            transition: all 0.2s ease-in-out;
        }
        .input-field:focus {
            outline: none;
            border-color: #4CAF50; /* Green border on focus */
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.2); /* Green glow on focus */
        }
        .btn-primary {
            background-color: #4CAF50; /* Green button */
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #45a049; /* Darker green on hover */
            transform: translateY(-2px);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .text-link {
            color: #2e7d32; /* Darker green for links */
            transition: color 0.3s ease;
        }
        .text-link:hover {
            color: #1b5e20; /* Even darker green on hover */
            text-decoration: underline;
        }
        .alert-error {
            background-color: #ffebee; /* Light red */
            border-color: #ef9a9a; /* Red border */
            color: #c62828; /* Dark red text */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-container bg-white rounded-xl overflow-hidden w-full max-w-md">
        <div class="bg-green-600 py-5 px-6 text-center">
            <div class="flex items-center justify-center space-x-3">
                <i data-lucide="truck" class="w-7 h-7 text-white animate-pulse-slow"></i>
                <h2 class="text-2xl font-bold text-white tracking-wide">Transporter Login</h2>
            </div>
        </div>
        
        <div class="p-8">
            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="mb-5 p-4 alert-error rounded-lg flex items-start animate-fade-in">
                    <i data-lucide="alert-circle" class="flex-shrink-0 mr-3 mt-0.5 w-5 h-5"></i>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($_SESSION['login_error']); ?></span>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form id="loginForm" action="transporter-login.php" method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        <i data-lucide="mail" class="inline mr-2 w-4 h-4 text-green-700"></i>Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="input-field w-full px-4 py-2.5 border rounded-lg"
                        placeholder="you@company.com"
                        required
                        autocomplete="email"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        <i data-lucide="lock" class="inline mr-2 w-4 h-4 text-green-700"></i>Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-field w-full px-4 py-2.5 border rounded-lg"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember-me"
                            name="remember-me"
                            type="checkbox"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700 select-none">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="forgot-password.php?type=transporters" class="font-medium text-link hover:underline">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <button type="submit" class="w-full btn-primary text-white py-3.5 px-4 rounded-lg font-semibold flex items-center justify-center shadow-md hover:shadow-lg">
                    <i data-lucide="log-in" class="mr-2 w-5 h-5"></i>
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-600">
                Need an account? 
                <a href="transporter_signup.php" class="font-medium text-link hover:underline">Sign up here</a>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Simple animation for the truck icon
        const truckIcon = document.querySelector('.animate-pulse-slow');
        if (truckIcon) {
            setInterval(() => {
                truckIcon.classList.toggle('scale-105');
            }, 1500); // Toggle scale every 1.5 seconds
        }

        // Force logout when user leaves the page (client-side)
        window.addEventListener('beforeunload', (event) => {
            sessionStorage.clear();
        });

        // Check session expiry periodically
        setInterval(() => {
            fetch('session-check.php') // Assuming you have a session-check.php for transporters too
                .then(response => response.json())
                .then(data => {
                    if (data.expired) {
                        window.location.href = 'transporter-login.php?session_expired=1';
                    }
                })
                .catch(error => console.error('Error checking session:', error));
        }, 60000); // Check every 1 minute
    </script>
</body>
</html>