<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: cargo-owner-login.php"); // Redirect to login if not logged in
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargo Owner Dashboard - Logistics SaaS Platform</title>
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
    <div class="sidebar bg-gradient-to-b from-green-700 to-green-900 text-white h-screen w-64 fixed">
        <div class="p-4">
            <h2 class="text-2xl font-bold text-center mb-6">Cargo Owner Dashboard</h2>
            <a href="#" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-home"></i> Home</a>
            <a href="#" id="postCargoLink" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-box"></i> Post Cargo</a>
            <a href="#" id="viewPostedCargosLink" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-eye"></i> View Posted Cargos</a>
            <a href="#" id="settingsLink" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-cogs"></i> Settings</a>
            <a href="index.php" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Hamburger Menu for Mobile -->
    <div class="hamburger p-4 bg-green-700 text-white fixed top-0 left-0 z-1000">
        <i class="fas fa-bars text-2xl cursor-pointer" onclick="toggleSidebar()"></i>
    </div>

    <!-- Main Content -->
    <div class="main-content ml-64 p-4 transition-all">
        <!-- Header -->
        <div class="header bg-white shadow-md rounded-lg p-6 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <div class="user-info flex items-center">
                <img id="profilePicture" src="https://via.placeholder.com/50" alt="Profile Picture" class="w-10 h-10 rounded-full border-2 border-green-500">
                <span id="cargoOwnerName" class="ml-3 text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </div>
        </div>

        <!-- Post Cargo Form Section -->
        <div id="postCargoSection" class="card bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-2xl font-bold text-center mb-6">
                <span class="text-green-600">Post Available</span> <span class="text-gray-800">Cargo</span>
            </h3>
            <form id="postCargoForm">
                <div class="form-group mb-4">
                    <label for="pickupDate" class="block text-gray-700 mb-2">Pick Up Date</label>
                    <input id="pickupDate" name="pickupDate" type="date" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="form-group mb-4">
                    <label for="weight" class="block text-gray-700 mb-2">Weight</label>
                    <div class="flex items-center">
                        <button type="button" onclick="adjustWeight(-1)" class="bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600 transition-colors">-</button>
                        <input id="weight" name="weight" placeholder="Enter weight of cargo" type="number" value="0" class="mx-2 p-2 border rounded-lg w-full" required>
                        <button type="button" onclick="adjustWeight(1)" class="bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600 transition-colors">+</button>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="dimensions" class="block text-gray-700 mb-2">Dimensions</label>
                    <input id="dimensions" name="dimensions" placeholder="Enter the dimensions" type="text" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="form-group mb-4">
                    <label for="cargoType" class="block text-gray-700 mb-2">Type of Cargo</label>
                    <input id="cargoType" name="cargoType" placeholder="Enter type of cargo" type="text" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="form-group mb-4">
                    <label for="origin" class="block text-gray-700 mb-2">Origin</label>
                    <input id="origin" name="origin" placeholder="Enter origin location" type="text" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="form-group mb-4">
                    <label for="destination" class="block text-gray-700 mb-2">Destination</label>
                    <input id="destination" name="destination" placeholder="Enter destination location" type="text" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="form-group mb-4">
                    <label for="phone" class="block text-gray-700 mb-2">Phone Number</label>
                    <input id="phone" name="phone" placeholder="Enter contact phone number" type="text" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="form-group mb-4">
                    <label for="instructions" class="block text-gray-700 mb-2">Instructions</label>
                    <textarea id="instructions" name="instructions" placeholder="Enter any special instructions" rows="4" class="w-full p-2 border rounded-lg"></textarea>
                </div>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors w-full">Post Cargo</button>
            </form>
        </div>

        <!-- View Posted Cargos Section -->
        <div id="postedCargos" class="card bg-white shadow-md rounded-lg p-6 mb-6 hidden">
            <h3 class="text-xl font-bold mb-4">Your Posted Cargos</h3>
            <div id="cargoList"></div>
        </div>
    </div>

    <script>
        // JavaScript for toggling sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // JavaScript for handling form submissions and dynamic content
        document.addEventListener('DOMContentLoaded', function () {
            const postCargoForm = document.getElementById('postCargoForm');
            const viewPostedCargosLink = document.getElementById('viewPostedCargosLink');
            const postCargoSection = document.getElementById('postCargoSection');
            const postedCargosSection = document.getElementById('postedCargos');
            const cargoList = document.getElementById('cargoList');

            // Handle Post Cargo form submission
            postCargoForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(postCargoForm);

                fetch('post-cargo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Show success or error message
                    postCargoForm.reset(); // Clear the form
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });

            // Handle View Posted Cargos link click
            viewPostedCargosLink.addEventListener('click', function (e) {
                e.preventDefault();
                postCargoSection.classList.add('hidden');
                postedCargosSection.classList.remove('hidden');

                // Fetch posted cargos from the server
                fetch('fetch-cargos.php')
                .then(response => response.json())
                .then(data => {
                    cargoList.innerHTML = ''; // Clear previous content
                    if (data.length > 0) {
                        data.forEach(cargo => {
                            const cargoItem = document.createElement('div');
                            cargoItem.className = 'bg-gray-50 p-4 mb-4 rounded-lg shadow-sm';
                            cargoItem.innerHTML = `
                                <p><strong>Pickup Date:</strong> ${cargo.pickup_date}</p>
                                <p><strong>Weight:</strong> ${cargo.weight} kg</p>
                                <p><strong>Dimensions:</strong> ${cargo.dimensions}</p>
                                <p><strong>Type:</strong> ${cargo.cargo_type}</p>
                                <p><strong>Origin:</strong> ${cargo.origin}</p>
                                <p><strong>Destination:</strong> ${cargo.destination}</p>
                                <p><strong>Status:</strong> ${cargo.status}</p>
                            `;
                            cargoList.appendChild(cargoItem);
                        });
                    } else {
                        cargoList.innerHTML = '<p class="text-gray-600">No cargos posted yet.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    cargoList.innerHTML = '<p class="text-red-500">Failed to load cargos. Please try again.</p>';
                });
            });
        });

        // Adjust weight input value
        function adjustWeight(change) {
            const weightInput = document.getElementById('weight');
            let weight = parseInt(weightInput.value) + change;
            if (weight < 0) weight = 0;
            weightInput.value = weight;
        }
    </script>
</body>
</html>