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
  </style>
</head>
<body class="bg-gray-100 font-roboto">
  <!-- Sidebar -->
  <div class="sidebar bg-gradient-to-b from-blue-700 to-blue-900 text-white h-screen w-64 fixed">
    <div class="p-4">
      <h2 class="text-2xl font-bold text-center mb-6">Transporter Dashboard</h2>
      <a href="#" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-home"></i> Home</a>
      <!-- <a href="#" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-box"></i> Available Loads</a> -->
      <a href="job-post.php" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-box"></i> Available Loads</a>
      <a href="settings.php" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-cogs"></i> Settings</a>
      <a href="index.php" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>

  <!-- Hamburger Menu for Mobile -->
  <div class="hamburger p-4 bg-blue-700 text-white fixed top-0 left-0 z-1000">
    <i class="fas fa-bars text-2xl cursor-pointer" onclick="toggleSidebar()"></i>
  </div>

  <!-- Main Content -->
  <div class="main-content ml-64 p-4 transition-all">
    <!-- Header -->
    <div class="header bg-white shadow-md rounded-lg p-6 mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
      <div class="user-info flex items-center">
        <img src="https://storage.googleapis.com/a1aa/image/0DsAIzPBeAztSaTGdSP8my4sUbD41IUpY4mXEmE6km2jx0DKA.jpg" alt="Profile Picture" class="w-10 h-10 rounded-full border-2 border-blue-500">
        <span class="ml-3 text-lg font-semibold text-gray-800">Transporter</span>
      </div>
    </div>

    <!-- Available Loads Section -->
    <div class="card bg-white shadow-md rounded-lg p-6 mb-6">
      <h3 class="text-xl font-bold mb-4">Available Loads</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b">Order ID</th>
              <th class="py-2 px-4 border-b">Cargo Type</th>
              <th class="py-2 px-4 border-b">Weight</th>
              <th class="py-2 px-4 border-b">Origin</th>
              <th class="py-2 px-4 border-b">Destination</th>
              <th class="py-2 px-4 border-b">Cargo Owner</th>
              <th class="py-2 px-4 border-b">Phone</th>
              <th class="py-2 px-4 border-b">Instructions</th>
              <th class="py-2 px-4 border-b">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b">
                <button class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-600 transition-colors">Proceed</button>
              </td>
            </tr>
            <tr>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b">
                <button class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-600 transition-colors">Proceed</button>
              </td>
            </tr>
            <tr>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b"></td>
              <td class="py-2 px-4 border-b">
                <button class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-600 transition-colors">Proceed</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // JavaScript for toggling sidebar on mobile
    function toggleSidebar() {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('collapsed');
    }

    // JavaScript for handling dynamic content
    // (Same as your existing JavaScript logic)
  </script>
</body>
</html>