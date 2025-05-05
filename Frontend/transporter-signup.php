<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nyamula Logistics - Transporter Signup</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/lucide-icons/dist/umd/lucide-icons.js" rel="stylesheet">
  <script src="https://unpkg.com/lucide-icons" defer></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center p-4">
  <div class="max-w-6xl w-full grid md:grid-cols-2 gap-8 items-center">
    <!-- Left side - Hero Section -->
    <div class="hidden md:flex flex-col space-y-6 p-8">
      <div class="flex items-center space-x-3">
        <i data-lucide="truck" class="w-10 h-10 text-blue-600"></i>
        <h1 class="text-2xl font-bold text-gray-900">Nyamula Logistics</h1>
      </div>
      
      <h2 class="text-4xl font-bold text-gray-900 leading-tight">
        Transform Your Cargo Management Experience
      </h2>
      
      <p class="text-lg text-gray-600">
        Join our network of successful Transporters and experience seamless logistics management with real-time tracking and professional support.
      </p>

      <div class="grid grid-cols-2 gap-4 mt-8">
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">Global Reach</h3>
          <p class="text-sm text-gray-600">Access to international shipping routes</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">24/7 Support</h3>
          <p class="text-sm text-gray-600">Round-the-clock customer service</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">Real-time Tracking</h3>
          <p class="text-sm text-gray-600">Monitor your cargo location live</p>
        </div>
        <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg">
          <h3 class="font-semibold text-gray-900">Secure Platform</h3>
          <p class="text-sm text-gray-600">Enhanced security protocols</p>
        </div>
      </div>
    </div>

    <!-- Right side - Sign Up Form -->
    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">
      <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Transporter Signup</h2>
        <p class="text-gray-600">Create your account to get started</p>
      </div>

      <form action="../Backend/transporter-signup.php" method="POST" class="space-y-6">
        <div class="space-y-4">
          <div>
            <label for="cargo_owner_name" class="block text-sm font-medium text-gray-700 mb-1">
              Full Name
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="user" class="h-5 w-5 text-gray-400"></i>
              </div>
              <input
                type="text"
                id="cargo_owner_name"
                name="transporter_name"
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="John Doe"
                required
              >
            </div>
          </div>

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

          <div>
            <label for="company" class="block text-sm font-medium text-gray-700 mb-1">
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
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Your Company Ltd"
                required
              >
            </div>
          </div>

          <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
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
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="+1 (555) 000-0000"
                required
              >
            </div>
          </div>
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
        >
          Create Account
        </button>
      </form>

      <p class="mt-6 text-center text-gray-600">
        Already have an account?
        <a href="transporter-login.php" class="text-blue-600 hover:text-blue-700 font-medium">
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


