<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: transporter-login.php"); // Redirect to login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transporter Dashboard - Logistics SaaS Platform</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#f0fdf4',
              100: '#dcfce7',
              200: '#bbf7d0',
              300: '#86efac',
              400: '#4ade80',
              500: '#22c55e',
              600: '#16a34a',
              700: '#15803d',
              800: '#166534',
              900: '#14532d',
            }
          }
        }
      }
    }
  </script>
  <style>
    /* Custom styles for transitions and animations */
    .sidebar {
      transition: transform 0.3s ease-in-out;
    }
    .sidebar.collapsed {
      transform: translateX(-100%);
    }
    .hamburger {
      display: none;
    }
    @media (max-width: 768px) {
      .hamburger {
        display: block;
      }
      .sidebar {
        width: 250px;
        position: fixed;
        z-index: 1000;
      }
      .main-content {
        margin-left: 0;
      }
    }
    /* Custom scrollbar for tables */
    .table-container::-webkit-scrollbar {
      height: 8px;
    }
    .table-container::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }
    .table-container::-webkit-scrollbar-thumb {
      background: #22c55e;
      border-radius: 4px;
    }
    .table-container::-webkit-scrollbar-thumb:hover {
      background: #16a34a;
    }
  </style>
</head>
<body class="bg-gray-50 font-roboto">
  <!-- Sidebar -->
  <div class="sidebar bg-gradient-to-b from-primary-600 to-primary-800 text-white h-screen w-64 fixed">
    <div class="p-4">
      <h2 class="text-2xl font-bold text-center mb-6">Transporter Dashboard</h2>
      <a href="#" class="block py-2 px-4 hover:bg-primary-700 rounded transition-colors mb-1"><i class="fas fa-home mr-2"></i> Home</a>
      <a href="job-post.php" class="block py-2 px-4 hover:bg-primary-700 rounded transition-colors mb-1"><i class="fas fa-truck mr-2"></i> Available Loads</a>
      <a href="my-trips.php" class="block py-2 px-4 hover:bg-primary-700 rounded transition-colors mb-1"><i class="fas fa-route mr-2"></i> My Trips</a>
      <a href="settings.php" class="block py-2 px-4 hover:bg-primary-700 rounded transition-colors mb-1"><i class="fas fa-cogs mr-2"></i> Settings</a>
      <a href="index.php" class="block py-2 px-4 hover:bg-primary-700 rounded transition-colors"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
    </div>
  </div>

  <!-- Hamburger Menu for Mobile -->
  <div class="hamburger p-4 bg-primary-600 text-white fixed top-0 left-0 z-50">
    <i class="fas fa-bars text-2xl cursor-pointer" onclick="toggleSidebar()"></i>
  </div>

  <!-- Main Content -->
  <div class="main-content ml-64 p-4 transition-all">
    <!-- Header -->
    <div class="header bg-white shadow-sm rounded-lg p-4 mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Welcome, Transporter</h1>
      <div class="user-info flex items-center">
        <span class="mr-3 text-sm font-medium text-gray-700 hidden md:inline-block">Available for delivery</span>
        <label for="availability-toggle" class="inline-flex items-center cursor-pointer">
          <span class="relative">
            <input type="checkbox" id="availability-toggle" class="sr-only peer" checked>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
          </span>
        </label>
        <img src="https://storage.googleapis.com/a1aa/image/0DsAIzPBeAztSaTGdSP8my4sUbD41IUpY4mXEmE6km2jx0DKA.jpg" alt="Profile Picture" class="ml-4 w-10 h-10 rounded-full border-2 border-primary-500">
      </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white shadow-sm rounded-lg p-4">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-primary-100 text-primary-600">
            <i class="fas fa-truck text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Available Loads</p>
            <p class="text-2xl font-semibold text-gray-800">12</p>
          </div>
        </div>
      </div>
      <div class="bg-white shadow-sm rounded-lg p-4">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-primary-100 text-primary-600">
            <i class="fas fa-route text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Active Trips</p>
            <p class="text-2xl font-semibold text-gray-800">3</p>
          </div>
        </div>
      </div>
      <div class="bg-white shadow-sm rounded-lg p-4">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-primary-100 text-primary-600">
            <i class="fas fa-money-bill-wave text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Earnings (This Month)</p>
            <p class="text-2xl font-semibold text-gray-800">$2,450</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Available Loads Section -->
    <div class="card bg-white shadow-sm rounded-lg p-4 mb-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-800">Available Loads Near You</h3>
        <div class="flex items-center">
          <select class="mr-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2">
            <option></option>
            <option></option>
            <option></option>
            <option></option>
            <option></option>
          </select>
          <button class="bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition-colors">
            <i class="fas fa-sync-alt mr-1"></i> Refresh
          </button>
        </div>
      </div>
      <div class="overflow-x-auto table-container">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-50">
            <tr>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo Type</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origin</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <button class="bg-primary-500 text-white py-1 px-3 rounded-lg hover:bg-primary-600 transition-colors">Accept</button>
                <button class="ml-2 text-gray-500 hover:text-gray-700">
                  <i class="fas fa-info-circle"></i>
                </button>
              </td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <button class="bg-primary-500 text-white py-1 px-3 rounded-lg hover:bg-primary-600 transition-colors">Accept</button>
                <button class="ml-2 text-gray-500 hover:text-gray-700">
                  <i class="fas fa-info-circle"></i>
                </button>
              </td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <button class="bg-primary-500 text-white py-1 px-3 rounded-lg hover:bg-primary-600 transition-colors">Accept</button>
                <button class="ml-2 text-gray-500 hover:text-gray-700">
                  <i class="fas fa-info-circle"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Current Location Section -->
    <div class="card bg-white shadow-sm rounded-lg p-4 mb-6">
      <h3 class="text-xl font-bold text-gray-800 mb-4">Your Current Location</h3>
      <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center">
        <div class="text-center">
          <i class="fas fa-map-marker-alt text-primary-500 text-4xl mb-2"></i>
          <p class="text-gray-700">Lusaka, Zambia</p>
          <button class="mt-4 bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition-colors">
            <i class="fas fa-location-arrow mr-1"></i> Update Location
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // JavaScript for toggling sidebar on mobile
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('collapsed');
    }

    // JavaScript for handling availability toggle
    document.getElementById('availability-toggle').addEventListener('change', function() {
      // Here you would update the driver's availability status in the database
      console.log('Availability changed to:', this.checked);
    });

    // JavaScript for handling load acceptance
    document.querySelectorAll('button').forEach(button => {
      if (button.textContent.trim() === 'Accept') {
        button.addEventListener('click', function() {
          const row = this.closest('tr');
          const orderId = row.querySelector('td:first-child').textContent;
          
          // Here you would send an AJAX request to accept the load
          console.log('Accepting load:', orderId);
          
          // Visual feedback
          this.textContent = 'Accepted';
          this.classList.remove('bg-primary-500', 'hover:bg-primary-600');
          this.classList.add('bg-gray-500', 'cursor-not-allowed');
          this.disabled = true;
          
          // You could also remove the row or update it
          setTimeout(() => {
            row.style.opacity = '0.5';
          }, 1000);
        });
      }
    });
  </script>
</body>
</html>