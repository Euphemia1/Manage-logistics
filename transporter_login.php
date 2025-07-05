<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }
        .login-container {
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .login-container:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-container bg-white rounded-xl shadow-md overflow-hidden w-full max-w-md">
        <div class="bg-blue-600 py-4 px-6">
            <div class="flex items-center justify-center space-x-2">
                <i data-lucide="truck" class="w-6 h-6 text-white"></i>
                <h2 class="text-xl font-bold text-white">Transporter Login</h2>
            </div>
        </div>
        
        <div class="p-6">
            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="mb-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-lg flex items-start">
                    <i data-lucide="alert-circle" class="flex-shrink-0 mr-2"></i>
                    <span><?php echo htmlspecialchars($_SESSION['login_error']); ?></span>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form id="loginForm" action="transporter-login.php" method="POST" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="mail" class="inline mr-2 w-4 h-4"></i>Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="input-field w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="you@company.com"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i data-lucide="lock" class="inline mr-2 w-4 h-4"></i>Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-field w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember-me"
                            name="remember-me"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="forgot-password.php?type=transporter" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center justify-center">
                    <i data-lucide="log-in" class="mr-2"></i>
                    Login
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Need an account? <a href="transporter-signup.php" class="font-medium text-blue-600 hover:text-blue-500">Sign up here</a>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
