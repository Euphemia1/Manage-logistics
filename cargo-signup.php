<?php
session_start(); // This must be the very first line

// Check for signup success message
if (isset($_SESSION['signup_success'])) {
    $signup_name = htmlspecialchars($_SESSION['signup_success']['name']);
    $signup_email = htmlspecialchars($_SESSION['signup_success']['email']);
    $user_type = $_SESSION['signup_success']['type'];
    
    unset($_SESSION['signup_success']);
    
    // The modal HTML was removed as per the instruction to only return the corrected PHP file content.
    // If a modal is desired, its HTML and JavaScript logic would need to be added here.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nyamula Logistics - Cargo Owner Signup</title>
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
    .signup-card {
        transition: all 0.3s ease;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 128, 0, 0.1); /* Subtle green border */
    }
    .signup-card:hover {
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
    .feature-card {
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
  <div class="max-w-6xl w-full grid md:grid-cols-2 gap-10 items-center">
    <div class="hidden md:flex flex-col space-y-7 p-8 animate-fade-in-left">
      <div class="flex items-center space-x-4">
        <i data-lucide="truck" class="w-12 h-12 text-green-600"></i>
        <h1 class="text-3xl font-bold text-gray-900">Nyamula Logistics</h1>
      </div>
      
      <h2 class="text-5xl font-bold text-gray-900 leading-tight">
        Seamless Logistics for Cargo Owners
      </h2>
      
      <p class="text-lg text-gray-700">
        Join our network and experience efficient cargo management with real-time tracking, dedicated support, and a secure platform.
      </p>

      <div class="grid grid-cols-2 gap-5 mt-10">
        <div class="feature-card p-5 rounded-lg shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-1">Global Reach</h3>
          <p class="text-sm text-gray-600">Access to international shipping routes</p>
        </div>
        <div class="feature-card p-5 rounded-lg shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-1">24/7 Support</h3>
          <p class="text-sm text-gray-600">Round-the-clock customer service</p>
        </div>
        <div class="feature-card p-5 rounded-lg shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-1">Real-time Tracking</h3>
          <p class="text-sm text-gray-600">Monitor your cargo location live</p>
        </div>
        <div class="feature-card p-5 rounded-lg shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-1">Secure Platform</h3>
          <p class="text-sm text-gray-600">Enhanced security protocols</p>
        </div>
      </div>
    </div>

    <div class="signup-card bg-white rounded-2xl p-8 md:p-10 animate-fade-in-right">
      <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Cargo Owner Signup</h2>
        <p class="text-gray-600">Create your account to get started</p>
      </div>

      <form action="./cargo-owner-signup.php" method="POST" class="space-y-6">
        <div class="space-y-5">
          <div>
            <label for="cargo_owner_name" class="block text-sm font-medium text-gray-700 mb-1.5">
              Full Name
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
              </div>
              <input
                type="text"
                id="cargo_owner_name"
                name="cargo_owner_name"
                class="input-field block w-full pl-10 pr-3 py-2.5 border rounded-lg"
                placeholder="John Doe"
                required
              >
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
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
                class="input-field block w-full pl-10 pr-3 py-2.5 border rounded-lg"
                placeholder="you@company.com"
                required
              >
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
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
                class="input-field block w-full pl-10 pr-3 py-2.5 border rounded-lg"
                placeholder="••••••••"
                required
              >
            </div>
          </div>

          <div>
            <label for="company" class="block text-sm font-medium text-gray-700 mb-1.5">
              Company Name
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="building-2" class="h-5 w-5 text-gray-400"></i>
              </div>
              <input
                type="text"
                id="company"
                name="company"
                class="input-field block w-full pl-10 pr-3 py-2.5 border rounded-lg"
                placeholder="Your Company Ltd"
                required
              >
            </div>
          </div>

          <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1.5">
              Phone Number
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="phone" class="h-5 w-5 text-gray-400"></i>
              </div>
              <input
                type="tel"
                id="phone_number"
                name="phone_number"
                class="input-field block w-full pl-10 pr-3 py-2.5 border rounded-lg"
                placeholder="+1 (555) 000-0000"
                required
              >
            </div>
          </div>
        </div>

        <button type="submit" class="w-full btn-primary text-white py-3.5 px-4 rounded-lg font-semibold shadow-md hover:shadow-lg">
          Create Account
        </button>
      </form>

      <p class="mt-8 text-center text-gray-600">
        Already have an account?
        <a href="cargo-owner-login.php" class="text-link font-medium hover:underline">
          Sign in
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