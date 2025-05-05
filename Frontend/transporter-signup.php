<?php 
session_start(); // This must be the very first line

// Check for signup success message
if (isset($_SESSION['signup_success'])) {
    $signup_name = htmlspecialchars($_SESSION['signup_success']['name']);
    $signup_email = htmlspecialchars($_SESSION['signup_success']['email']);
    $user_type = $_SESSION['signup_success']['type'];
    
    unset($_SESSION['signup_success']);
    
    $success_message = <<<HTML
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 animate-fade-in">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-3">Welcome to Nyamula Logistics!</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Congratulations, $signup_name! Your transporter account has been successfully created.
                    </p>
                    <p class="text-sm text-gray-500 mt-2">
                        We've sent a confirmation to <span class="font-medium">$signup_email</span>.
                    </p>
                </div>
                <div class="mt-4">
                    <button onclick="this.parentNode.parentNode.parentNode.parentNode.remove()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-blue-900 bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Continue to Login
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Remove the modal after 5 seconds automatically
        setTimeout(() => {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) modal.remove();
        }, 5000);
    </script>
HTML;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nyamula Logistics - Transporter Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/lucide-icons/dist/umd/lucide-icons.js" rel="stylesheet">
  <script src="https://unpkg.com/lucide-icons" defer></script>
  <style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center p-4">
  <?php if (isset($success_message)) echo $success_message; ?>
  
  <div class="max-w-6xl w-full grid md:grid-cols-2 gap-8 items-center">
    <!-- Left side - Hero Section -->
    <div class="hidden md:flex flex-col space-y-6 p-8">
      <div class="flex items-center space-x-3">
        <i data-lucide="truck" class="w-10 h-10 text-blue-600"></i>
        <h1 class="text-2xl font-bold text-gray-900">Nyamula Logistics</h1>
      </div>
      
      <h2 class="text-4xl font-bold text-gray-900 leading-tight">
        Grow Your Transport Business
      </h2>
      
      <p class="text-lg text-gray-600">
        Join our network of trusted transporters and get access to more cargo opportunities with real-time tracking and professional support.
      </p>

      <div class="grid grid-cols-2 gap-4 mt-8">
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">More Loads</h3>
          <p class="text-sm text-gray-600">Access to premium cargo requests</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">24/7 Support</h3>
          <p class="text-sm text-gray-600">Round-the-clock customer service</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">Real-time Tracking</h3>
          <p class="text-sm text-gray-600">Monitor your shipments live</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">Secure Payments</h3>
          <p class="text-sm text-gray-600">Guaranteed timely payments</p>
        </div>
      </div>
    </div>

    <!-- Right side - Login Form -->
    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">
      <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Transporter Login</h2>
        <p class="text-gray-600">Sign in to access your account</p>
      </div>

      <form action="../Backend/transporter-login.php" method="POST" class="space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Email Address
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
            </div>
            <input
              type="email"
              id="email"
              name="email"
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="you@company.com"
              required
            >
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i data-lucide="lock" class="h-5 w-5 text-gray-400"></i>
            </div>
            <input
              type="password"
              id="password"
              name="password"
              class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="••••••••"
              required
            >
          </div>
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
            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
              Forgot password?
            </a>
          </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
          Sign in
        </button>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Don't have an account?
        <a href="transporter-signup.php" class="text-blue-600 hover:text-blue-700 font-medium">
          Sign up
        </a>
      </p>
    </div>
  </div>
  
  <script>
    // Initialize Lucide icons
    lucide.createIcons();
  </script>
</body>
</html>