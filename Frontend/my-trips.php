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
  <title>My Trips - Transporter Dashboard</title>
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
      <a href="transporter-dashboard.php" class="block py-2 px-4 hover:bg-primary-700 rounded transition-colors mb-1"><i class="fas fa-home mr-2"></i> Home</a>
      <a href="job-post.php" class="block py-2 px-4 hover:bg-primary-700 rounded transition-colors mb-1"><i class="fas fa-truck mr-2"></i> Available Loads</a>
      <a href="my-trips.php" class="block py-2 px-4 bg-primary-700 rounded transition-colors mb-1"><i class="fas fa-route mr-2"></i> My Trips</a>
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
      <h1 class="text-2xl font-bold text-gray-800">My Trips</h1>
      <div class="user-info flex items-center">
        <img src="https://storage.googleapis.com/a1aa/image/0DsAIzPBeAztSaTGdSP8my4sUbD41IUpY4mXEmE6km2jx0DKA.jpg" alt="Profile Picture" class="w-10 h-10 rounded-full border-2 border-primary-500">
        <span class="ml-3 text-lg font-semibold text-gray-800 hidden md:inline-block">Transporter</span>
      </div>
    </div>

    <!-- Trip Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white shadow-sm rounded-lg p-4">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-primary-100 text-primary-600">
            <i class="fas fa-route text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Trips</p>
            <p class="text-2xl font-semibold text-gray-800"></p>
          </div>
        </div>
      </div>
      <div class="bg-white shadow-sm rounded-lg p-4">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-primary-100 text-primary-600">
            <i class="fas fa-road text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Distance</p>
            <p class="text-2xl font-semibold text-gray-800"></p>
          </div>
        </div>
      </div>
      <div class="bg-white shadow-sm rounded-lg p-4">
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-primary-100 text-primary-600">
            <i class="fas fa-money-bill-wave text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Average Earnings</p>
            <p class="text-2xl font-semibold text-gray-800"></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Trip History -->
    <div class="card bg-white shadow-sm rounded-lg p-4 mb-6">
      <h3 class="text-xl font-bold text-gray-800 mb-4">Trip History</h3>
      <div class="overflow-x-auto table-container">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-50">
            <tr>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip ID</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cargo Type</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origin</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="py-3 px-4 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
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
              <td class="py-3 px-4 text-sm">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                  In Transit
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Completed
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-gray-900">$580</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Completed
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-gray-900">$850</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Completed
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                  Cancelled
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900">i</td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Completed
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-gray-900">$120</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm text-gray-900"></td>
              <td class="py-3 px-4 text-sm">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Completed
                </span>
              </td>
              <td class="py-3 px-4 text-sm text-gray-900">$320</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-4 flex justify-between items-center">
        <div class="text-sm text-gray-700">
          Showing <span class="font-medium">1</span> to <span class="font-medium">7</span> of <span class="font-medium">48</span> trips
        </div>
        <div class="flex space-x-2">
          <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50" disabled>
            Previous
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-white bg-primary-500 hover:bg-primary-600">
            1
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            2
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            3
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Next
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
  </script>
</body>
</html>