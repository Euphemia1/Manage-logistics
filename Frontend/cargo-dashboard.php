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
        /* Loading spinner */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #10b981;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 font-roboto">
    <!-- Sidebar -->
    <div class="sidebar bg-gradient-to-b from-green-700 to-green-900 text-white h-screen w-64 fixed">
        <div class="p-4">
            <h2 class="text-2xl font-bold text-center mb-6">Cargo Owner Dashboard</h2>
            <a href="#" id="homeLink" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-home"></i> Home</a>
            <a href="#" id="postCargoLink" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-box"></i> Post Cargo</a>
            <a href="#" id="viewPostedCargosLink" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-eye"></i> View Posted Cargos</a>
            <a href="#" id="settingsLink" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-cogs"></i> Settings</a>
            <a href="logout.php" class="block py-2 px-4 hover:bg-green-600 rounded transition-colors"><i class="fas fa-sign-out-alt"></i> Logout</a>
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

        <!-- Dashboard Home Section -->
        <div id="homeSection" class="card bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-2xl font-bold text-center mb-6">
                <span class="text-green-600">Welcome</span> <span class="text-gray-800">to Your Dashboard</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-green-50 p-6 rounded-lg shadow-sm border border-green-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">Posted Cargos</h4>
                        <i class="fas fa-box text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-green-600" id="cargoCount">0</p>
                    <p class="text-sm text-gray-600 mt-2">Total cargos you've posted</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-lg shadow-sm border border-blue-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">Available</h4>
                        <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-blue-600" id="availableCount">0</p>
                    <p class="text-sm text-gray-600 mt-2">Cargos available for transport</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg shadow-sm border border-purple-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">In Transit</h4>
                        <i class="fas fa-truck text-purple-600 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-purple-600" id="inTransitCount">0</p>
                    <p class="text-sm text-gray-600 mt-2">Cargos currently in transit</p>
                </div>
            </div>
            <div class="mt-8 flex justify-center">
                <button id="postNewCargoBtn" class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition-colors">
                    Post New Cargo
                </button>
            </div>
        </div>

        <!-- Post Cargo Form Section -->
        <div id="postCargoSection" class="card bg-white shadow-md rounded-lg p-6 mb-6 hidden">
            <h3 class="text-2xl font-bold text-center mb-6">
                <span class="text-green-600">Post Available</span> <span class="text-gray-800">Cargo</span>
            </h3>
            <form id="postCargoForm">
                <div class="form-group mb-4">
                    <label for="pickupDate" class="block text-gray-700 mb-2">Pick Up Date</label>
                    <input id="pickupDate" name="pickupDate" type="date" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="form-group mb-4">
                    <label for="weight" class="block text-gray-700 mb-2">Weight (kg)</label>
                    <div class="flex items-center">
                        <button type="button" onclick="adjustWeight(-1)" class="bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600 transition-colors">-</button>
                        <input id="weight" name="weight" placeholder="Enter weight of cargo" type="number" value="0" class="mx-2 p-2 border rounded-lg w-full" required>
                        <button type="button" onclick="adjustWeight(1)" class="bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600 transition-colors">+</button>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="dimensions" class="block text-gray-700 mb-2">Dimensions (L x W x H)</label>
                    <input id="dimensions" name="dimensions" placeholder="Enter the dimensions (e.g., 2m x 1m x 1m)" type="text" class="w-full p-2 border rounded-lg" required>
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
                <div class="flex justify-between">
                    <button type="button" id="cancelPostBtn" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">Post Cargo</button>
                </div>
            </form>
        </div>

        <!-- View Posted Cargos Section -->
        <div id="postedCargosSection" class="card bg-white shadow-md rounded-lg p-6 mb-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">
                    <span class="text-green-600">Your Posted</span> <span class="text-gray-800">Cargos</span>
                </h3>
                <button id="backToHomeBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Back to Dashboard
                </button>
            </div>
            
            <!-- Filter and Search -->
            <div class="mb-4 flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="searchCargo" placeholder="Search by origin, destination or type..." 
                           class="w-full p-2 border rounded-lg">
                </div>
                <div class="flex gap-2">
                    <select id="statusFilter" class="p-2 border rounded-lg">
                        <option value="all">All Status</option>
                        <option value="Available">Available</option>
                        <option value="In Transit">In Transit</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                    <button id="refreshCargos" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading indicator -->
            <div id="cargoLoader" class="loader hidden"></div>
            
            <!-- Cargo list container -->
            <div id="cargoList" class="mt-4">
                <p class="text-gray-600 text-center py-8">Loading your cargos...</p>
            </div>
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
            // Get all sections
            const homeSection = document.getElementById('homeSection');
            const postCargoSection = document.getElementById('postCargoSection');
            const postedCargosSection = document.getElementById('postedCargosSection');
            
            // Get all navigation links
            const homeLink = document.getElementById('homeLink');
            const postCargoLink = document.getElementById('postCargoLink');
            const viewPostedCargosLink = document.getElementById('viewPostedCargosLink');
            const postNewCargoBtn = document.getElementById('postNewCargoBtn');
            const cancelPostBtn = document.getElementById('cancelPostBtn');
            const backToHomeBtn = document.getElementById('backToHomeBtn');
            const refreshCargosBtn = document.getElementById('refreshCargos');
            
            // Get form and cargo list elements
            const postCargoForm = document.getElementById('postCargoForm');
            const cargoList = document.getElementById('cargoList');
            const cargoLoader = document.getElementById('cargoLoader');
            const searchCargo = document.getElementById('searchCargo');
            const statusFilter = document.getElementById('statusFilter');
            
            // Dashboard counters
            const cargoCount = document.getElementById('cargoCount');
            const availableCount = document.getElementById('availableCount');
            const inTransitCount = document.getElementById('inTransitCount');
            
            // Function to show a specific section and hide others
            function showSection(section) {
                homeSection.classList.add('hidden');
                postCargoSection.classList.add('hidden');
                postedCargosSection.classList.add('hidden');
                
                section.classList.remove('hidden');
                
                // Close sidebar on mobile after navigation
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            }
            
            // Function to fetch and display cargos
            function fetchCargos() {
                cargoLoader.classList.remove('hidden');
                cargoList.innerHTML = '';
                
                fetch('fetch-cargos.php')
                .then(response => response.json())
                .then(data => {
                    cargoLoader.classList.add('hidden');
                    
                    if (data.length > 0) {
                        // Update dashboard counters
                        cargoCount.textContent = data.length;
                        availableCount.textContent = data.filter(cargo => cargo.status === 'Available').length;
                        inTransitCount.textContent = data.filter(cargo => cargo.status === 'In Transit').length;
                        
                        // Display cargos
                        displayCargos(data);
                    } else {
                        cargoList.innerHTML = `
                            <div class="text-center py-8">
                                <p class="text-gray-600 mb-4">You haven't posted any cargos yet.</p>
                                <button id="noCargoPostBtn" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors">
                                    Post Your First Cargo
                                </button>
                            </div>
                        `;
                        
                        document.getElementById('noCargoPostBtn').addEventListener('click', function() {
                            showSection(postCargoSection);
                        });
                        
                        // Set dashboard counters to zero
                        cargoCount.textContent = '0';
                        availableCount.textContent = '0';
                        inTransitCount.textContent = '0';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    cargoLoader.classList.add('hidden');
                    cargoList.innerHTML = '<p class="text-red-500 text-center py-8">Failed to load cargos. Please try again.</p>';
                });
            }
            
            // Function to display cargos with filtering
            function displayCargos(cargos) {
                const searchTerm = searchCargo.value.toLowerCase();
                const statusValue = statusFilter.value;
                
                // Filter cargos based on search and status
                const filteredCargos = cargos.filter(cargo => {
                    const matchesSearch = 
                        cargo.origin.toLowerCase().includes(searchTerm) ||
                        cargo.destination.toLowerCase().includes(searchTerm) ||
                        cargo.cargo_type.toLowerCase().includes(searchTerm);
                    
                    const matchesStatus = statusValue === 'all' || cargo.status === statusValue;
                    
                    return matchesSearch && matchesStatus;
                });
                
                if (filteredCargos.length === 0) {
                    cargoList.innerHTML = '<p class="text-gray-600 text-center py-8">No cargos match your filters.</p>';
                    return;
                }
                
                cargoList.innerHTML = '';
                
                // Create cargo cards
                filteredCargos.forEach(cargo => {
                    // Determine status color
                    let statusColor = 'bg-blue-100 text-blue-800';
                    if (cargo.status === 'In Transit') {
                        statusColor = 'bg-purple-100 text-purple-800';
                    } else if (cargo.status === 'Delivered') {
                        statusColor = 'bg-green-100 text-green-800';
                    }
                    
                    const cargoItem = document.createElement('div');
                    cargoItem.className = 'bg-gray-50 p-4 mb-4 rounded-lg shadow-sm border border-gray-200';
                    cargoItem.innerHTML = `
                        <div class="flex flex-col md:flex-row justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h4 class="font-bold text-lg">${cargo.cargo_type}</h4>
                                    <span class="ml-3 px-2 py-1 rounded-full text-xs font-semibold ${statusColor}">
                                        ${cargo.status}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">From</p>
                                        <p class="font-medium">${cargo.origin}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">To</p>
                                        <p class="font-medium">${cargo.destination}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Pickup Date</p>
                                        <p class="font-medium">${cargo.pickup_date}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Weight</p>
                                        <p class="font-medium">${cargo.weight} kg</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:ml-4 flex flex-col justify-between">
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Dimensions</p>
                                    <p class="font-medium">${cargo.dimensions}</p>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button class="view-details-btn bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600 transition-colors text-sm" 
                                            data-id="${cargo.id}">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    cargoList.appendChild(cargoItem);
                });
                
                // Add event listeners to view details buttons
                document.querySelectorAll('.view-details-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const cargoId = this.getAttribute('data-id');
                        alert('View details for cargo ID: ' + cargoId);
                        // You can implement a modal or redirect to a details page
                    });
                });
            }
            
            // Navigation event listeners
            homeLink.addEventListener('click', function(e) {
                e.preventDefault();
                showSection(homeSection);
                fetchCargos(); // Update dashboard stats
            });
            
            postCargoLink.addEventListener('click', function(e) {
                e.preventDefault();
                showSection(postCargoSection);
            });
            
            viewPostedCargosLink.addEventListener('click', function(e) {
                e.preventDefault();
                showSection(postedCargosSection);
                fetchCargos();
            });
            
            postNewCargoBtn.addEventListener('click', function() {
                showSection(postCargoSection);
            });
            
            cancelPostBtn.addEventListener('click', function() {
                showSection(homeSection);
            });
            
            backToHomeBtn.addEventListener('click', function() {
                showSection(homeSection);
            });
            
            refreshCargosBtn.addEventListener('click', fetchCargos);
            
            // Search and filter event listeners
            searchCargo.addEventListener('input', function() {
                fetchCargos();
            });
            
            statusFilter.addEventListener('change', function() {
                fetchCargos();
            });
            
            // Handle Post Cargo form submission
            postCargoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(postCargoForm);
                
                // Disable submit button and show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Posting...';
                
                fetch('..Backend/post-cargo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    
                    // Show success message
                    alert(data);
                    
                    // Reset form and go to dashboard
                    postCargoForm.reset();
                    showSection(homeSection);
                    
                    // Refresh cargo data
                    fetchCargos();
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    
                    alert('An error occurred. Please try again.');
                });
            });
            
            // Adjust weight input value
            window.adjustWeight = function(change) {
                const weightInput = document.getElementById('weight');
                let weight = parseInt(weightInput.value) + change;
                if (weight < 0) weight = 0;
                weightInput.value = weight;
            };
            
            // Initialize dashboard on page load
            fetchCargos();
        });
    </script>
</body>
</html>

