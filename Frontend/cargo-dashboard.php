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
    <title>Cargo Owner Dashboard - Nyamula Logistics</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Sidebar styles */
        .sidebar {
            background: linear-gradient(to bottom, #1e7e34, #155724);
            color: white;
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main content styles */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        /* Card styles */
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            border: none;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        /* Stats cards */
        .stats-card {
            border-left: 4px solid;
            padding: 15px;
        }
        
        .stats-card.posted {
            border-left-color: #28a745;
        }
        
        .stats-card.available {
            border-left-color: #007bff;
        }
        
        .stats-card.transit {
            border-left-color: #6f42c1;
        }
        
        /* Status badges */
        .badge-available {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .badge-transit {
            background-color: #e0cffc;
            color: #4b2354;
        }
        
        .badge-delivered {
            background-color: #d4edda;
            color: #155724;
        }
        
        /* Mobile sidebar toggle */
        #sidebarCollapse {
            display: none;
        }
        
        /* Loading spinner */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #28a745;
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
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
            
            #sidebarCollapse {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1100;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="p-4">
            <h3 class="text-center mb-4">Cargo Owner Dashboard</h3>
            <hr class="bg-light opacity-25">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="#" id="homeLink" class="nav-link active">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="postCargoLink" class="nav-link">
                        <i class="fas fa-box"></i> Post Cargo
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="viewPostedCargosLink" class="nav-link">
                        <i class="fas fa-list"></i> View Posted Cargos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" id="settingsLink" class="nav-link">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li class="nav-item mt-5">
                    <a href="../Backend/logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Mobile sidebar toggle button -->
    <button type="button" id="sidebarCollapse" class="btn btn-success">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Dashboard</h2>
            <div class="d-flex align-items-center">
                <img id="profilePicture" src="https://via.placeholder.com/40" alt="Profile Picture" class="rounded-circle border border-success me-2">
                <span id="cargoOwnerName" class="fw-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </div>
        </div>

        <!-- Dashboard Home Section -->
        <div id="homeSection">
            <div class="card dashboard-card mb-4">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">
                        <span class="text-success">Welcome</span> to Your Dashboard
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card stats-card posted">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">Posted Cargos</h5>
                                        <i class="fas fa-box text-success fs-4"></i>
                                    </div>
                                    <h2 id="cargoCount" class="text-success mb-0">0</h2>
                                    <p class="text-muted small">Total cargos you've posted</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card stats-card available">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">Available</h5>
                                        <i class="fas fa-check-circle text-primary fs-4"></i>
                                    </div>
                                    <h2 id="availableCount" class="text-primary mb-0">0</h2>
                                    <p class="text-muted small">Cargos available for transport</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card stats-card transit">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">In Transit</h5>
                                        <i class="fas fa-truck text-purple fs-4"></i>
                                    </div>
                                    <h2 id="inTransitCount" class="text-purple mb-0">0</h2>
                                    <p class="text-muted small">Cargos currently in transit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button id="postNewCargoBtn" class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i> Post New Cargo
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card dashboard-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div id="recentActivity" class="list-group list-group-flush">
                        <p class="text-center text-muted py-3">Loading recent activity...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Post Cargo Form Section -->
        <div id="postCargoSection" class="d-none">
            <div class="card dashboard-card">
                <div class="card-header bg-white">
                    <h3 class="mb-0">
                        <span class="text-success">Post Available</span> Cargo
                    </h3>
                </div>
                <div class="card-body">
                    <form id="postCargoForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pickupDate" class="form-label">Pick Up Date</label>
                                <input id="pickupDate" name="pickupDate" type="date" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <div class="input-group">
                                    <button type="button" onclick="adjustWeight(-1)" class="btn btn-outline-success">-</button>
                                    <input id="weight" name="weight" type="number" value="0" class="form-control text-center" required>
                                    <button type="button" onclick="adjustWeight(1)" class="btn btn-outline-success">+</button>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="dimensions" class="form-label">Dimensions (L x W x H)</label>
                                <input id="dimensions" name="dimensions" placeholder="e.g., 2m x 1m x 1m" type="text" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="cargoType" class="form-label">Type of Cargo</label>
                                <input id="cargoType" name="cargoType" placeholder="e.g., Electronics, Furniture" type="text" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="origin" class="form-label">Origin</label>
                                <input id="origin" name="origin" placeholder="Pickup location" type="text" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="destination" class="form-label">Destination</label>
                                <input id="destination" name="destination" placeholder="Delivery location" type="text" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input id="phone" name="phone" placeholder="Contact phone number" type="text" class="form-control" required>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="instructions" class="form-label">Special Instructions</label>
                                <textarea id="instructions" name="instructions" placeholder="Any special handling instructions" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" id="cancelPostBtn" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i> Post Cargo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Posted Cargos Section -->
        <div id="postedCargosSection" class="d-none">
            <div class="card dashboard-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <span class="text-success">Your Posted</span> Cargos
                    </h3>
                    <button id="backToHomeBtn" class="btn btn-success">
                        <i class="fas fa-home me-2"></i> Back to Dashboard
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filter and Search -->
                    <div class="row mb-4">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <input type="text" id="searchCargo" placeholder="Search by origin, destination or type..." 
                                   class="form-control">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <select id="statusFilter" class="form-select">
                                <option value="all">All Status</option>
                                <option value="Available">Available</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                            <button id="refreshCargos" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Loading indicator -->
                    <div id="cargoLoader" class="loader d-none"></div>
                    
                    <!-- Cargo list container -->
                    <div id="cargoList" class="mt-4">
                        <p class="text-muted text-center py-4">Loading your cargos...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
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
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            
            // Get form and cargo list elements
            const postCargoForm = document.getElementById('postCargoForm');
            const cargoList = document.getElementById('cargoList');
            const cargoLoader = document.getElementById('cargoLoader');
            const searchCargo = document.getElementById('searchCargo');
            const statusFilter = document.getElementById('statusFilter');
            const recentActivity = document.getElementById('recentActivity');
            
            // Dashboard counters
            const cargoCount = document.getElementById('cargoCount');
            const availableCount = document.getElementById('availableCount');
            const inTransitCount = document.getElementById('inTransitCount');
            
            // Toggle sidebar on mobile
            sidebarCollapse.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('active');
            });
            
            // Function to show a specific section and hide others
            function showSection(section) {
                homeSection.classList.add('d-none');
                postCargoSection.classList.add('d-none');
                postedCargosSection.classList.add('d-none');
                
                section.classList.remove('d-none');
                
                // Update active nav link
                homeLink.classList.remove('active');
                postCargoLink.classList.remove('active');
                viewPostedCargosLink.classList.remove('active');
                
                if (section === homeSection) {
                    homeLink.classList.add('active');
                } else if (section === postCargoSection) {
                    postCargoLink.classList.add('active');
                } else if (section === postedCargosSection) {
                    viewPostedCargosLink.classList.add('active');
                }
                
                // Close sidebar on mobile after navigation
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.querySelector('.main-content');
                    
                    sidebar.classList.remove('active');
                    mainContent.classList.remove('active');
                }
            }
            
            // Function to fetch and display cargos
            function fetchCargos() {
                cargoLoader.classList.remove('d-none');
                cargoList.innerHTML = '';
                
                fetch('../Backend/fetch-cargos.php')
                .then(response => response.json())
                .then(data => {
                    cargoLoader.classList.add('d-none');
                    
                    if (data.length > 0) {
                        // Update dashboard counters
                        cargoCount.textContent = data.length;
                        availableCount.textContent = data.filter(cargo => cargo.status === 'Available').length;
                        inTransitCount.textContent = data.filter(cargo => cargo.status === 'In Transit').length;
                        
                        // Update recent activity
                        updateRecentActivity(data);
                        
                        // Display cargos
                        displayCargos(data);
                    } else {
                        cargoList.innerHTML = `
                            <div class="text-center py-4">
                                <p class="text-muted mb-3">You haven't posted any cargos yet.</p>
                                <button id="noCargoPostBtn" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> Post Your First Cargo
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
                        
                        // Clear recent activity
                        recentActivity.innerHTML = '<p class="text-center text-muted py-3">No recent activity</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    cargoLoader.classList.add('d-none');
                    cargoList.innerHTML = '<p class="text-danger text-center py-4">Failed to load cargos. Please try again.</p>';
                });
            }
            
            // Function to update recent activity
            function updateRecentActivity(cargos) {
                // Sort cargos by most recent
                const recentCargos = [...cargos].sort((a, b) => {
                    return new Date(b.created_at || b.pickup_date) - new Date(a.created_at || a.pickup_date);
                }).slice(0, 3);
                
                if (recentCargos.length === 0) {
                    recentActivity.innerHTML = '<p class="text-center text-muted py-3">No recent activity</p>';
                    return;
                }
                
                recentActivity.innerHTML = '';
                
                recentCargos.forEach(cargo => {
                    const activityItem = document.createElement('div');
                    activityItem.className = 'list-group-item border-0 border-bottom py-3';
                    
                    let statusBadge = '';
                    if (cargo.status === 'Available') {
                        statusBadge = '<span class="badge rounded-pill badge-available">Available</span>';
                    } else if (cargo.status === 'In Transit') {
                        statusBadge = '<span class="badge rounded-pill badge-transit">In Transit</span>';
                    } else {
                        statusBadge = '<span class="badge rounded-pill badge-delivered">Delivered</span>';
                    }
                    
                    activityItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${cargo.cargo_type} ${statusBadge}</h6>
                                <p class="mb-1 text-muted small">From ${cargo.origin} to ${cargo.destination}</p>
                                <p class="mb-0 text-muted small">Pickup: ${cargo.pickup_date}</p>
                            </div>
                            <button class="btn btn-sm btn-outline-success view-details-btn" data-id="${cargo.id}">
                                Details
                            </button>
                        </div>
                    `;
                    
                    recentActivity.appendChild(activityItem);
                });
                
                // Add event listeners to view details buttons
                setupViewDetailsButtons(cargos);
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
                    cargoList.innerHTML = '<p class="text-muted text-center py-4">No cargos match your filters.</p>';
                    return;
                }
                
                cargoList.innerHTML = '';
                
                // Create cargo cards
                filteredCargos.forEach(cargo => {
                    // Determine status badge class
                    let statusBadgeClass = 'badge-available';
                    if (cargo.status === 'In Transit') {
                        statusBadgeClass = 'badge-transit';
                    } else if (cargo.status === 'Delivered') {
                        statusBadgeClass = 'badge-delivered';
                    }
                    
                    const cargoItem = document.createElement('div');
                    cargoItem.className = 'card mb-3 border-0 shadow-sm';
                    cargoItem.innerHTML = `
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="mb-0 me-2">${cargo.cargo_type}</h5>
                                        <span class="badge rounded-pill ${statusBadgeClass}">
                                            ${cargo.status}
                                        </span>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small">From</p>
                                            <p class="fw-bold mb-3">${cargo.origin}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small">To</p>
                                            <p class="fw-bold mb-3">${cargo.destination}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small">Pickup Date</p>
                                            <p class="fw-bold mb-0">${cargo.pickup_date}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small">Weight</p>
                                            <p class="fw-bold mb-0">${cargo.weight} kg</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-md-end mb-3">
                                        <p class="text-muted mb-1 small">Dimensions</p>
                                        <p class="fw-bold">${cargo.dimensions}</p>
                                    </div>
                                    <div class="text-md-end">
                                        <button class="btn btn-success view-details-btn" data-id="${cargo.id}">
                                            <i class="fas fa-eye me-2"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    cargoList.appendChild(cargoItem);
                });
                
                // Add event listeners to view details buttons
                setupViewDetailsButtons(cargos);
            }
            
            // Function to show cargo details in modal
            function showCargoDetails(cargo) {
                // Get modal elements
                const modalCargoType = document.getElementById('modalCargoType');
                const modalStatus = document.getElementById('modalStatus');
                const modalOrigin = document.getElementById('modalOrigin');
                const modalDestination = document.getElementById('modalDestination');
                const modalPickupDate = document.getElementById('modalPickupDate');
                const modalWeight = document.getElementById('modalWeight');
                const modalDimensions = document.getElementById('modalDimensions');
                const modalPhone = document.getElementById('modalPhone');
                const modalInstructions = document.getElementById('modalInstructions');
                
                // Set modal content
                modalCargoType.textContent = cargo.cargo_type;
                
                // Set status with appropriate badge
                let statusBadgeClass = 'badge-available';
                if (cargo.status === 'In Transit') {
                    statusBadgeClass = 'badge-transit';
                } else if (cargo.status === 'Delivered') {
                    statusBadgeClass = 'badge-delivered';
                }
                modalStatus.innerHTML = `<span class="badge rounded-pill ${statusBadgeClass}">${cargo.status}</span>`;
                
                modalOrigin.textContent = cargo.origin;
                modalDestination.textContent = cargo.destination;
                modalPickupDate.textContent = cargo.pickup_date;
                modalWeight.textContent = `${cargo.weight} kg`;
                modalDimensions.textContent = cargo.dimensions;
                modalPhone.textContent = cargo.phone;
                modalInstructions.textContent = cargo.instructions || 'No special instructions provided';
                
                // Show the modal
                const cargoDetailsModal = new bootstrap.Modal(document.getElementById('cargoDetailsModal'));
                cargoDetailsModal.show();
            }
            
            // Function to setup view details buttons
            function setupViewDetailsButtons(cargos) {
                document.querySelectorAll('.view-details-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const cargoId = this.getAttribute('data-id');
                        const cargo = cargos.find(c => c.id == cargoId);
                        
                        if (cargo) {
                            showCargoDetails(cargo);
                        }
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
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Posting...';
                
                // Send form data to backend
                fetch('../Backend/post-cargo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    
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
                    submitBtn.innerHTML = originalText;
                    
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