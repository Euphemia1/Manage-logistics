<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: cargo-owner-login.php"); // Redirect to login if not logged in
    exit();
}
// Update last activity time on every page load
$_SESSION['last_activity'] = time();
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
        
        /* Cargo Posting Form Styles */
        .form-step {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }
        
        .form-step.active {
            display: block;
        }
        
        .cargo-type-selector {
            margin-bottom: 10px;
        }
        
        .cargo-type-option {
            flex: 1 0 120px;
            padding: 15px 10px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .cargo-type-option:hover {
            border-color: #28a745;
            background-color: rgba(40, 167, 69, 0.05);
        }
        
        input[type="radio"]:checked + .cargo-type-option {
            border-color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
            font-weight: 500;
        }
        
        .location-input {
            background-image: none !important; /* Remove browser autocomplete background */
        }
        
        .use-current-location {
            min-width: 45px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Custom purple color for stats card */
        .text-purple {
            color: #6f42c1 !important;
        }
        .cargo-item-card:hover { /* Optional: slight hover effect for cargo cards */
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.10)!important;
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
                        <span class="text-success">Post New</span> Cargo
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Alert Placeholder -->
                    <div id="formAlertPlaceholder" class="mb-3"></div>
                    <!-- Multi-step form -->
                    <form id="postCargoForm">
                        <!-- Step 1: Basic Info -->
                        <div id="step1" class="form-step active">
                            <h5 class="mb-4"><i class="fas fa-box me-2 text-success"></i> What are you shipping?</h5>
                            
                            <div class="row mb-4">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Cargo Type</label>
                                    <div class="d-flex flex-wrap gap-2 cargo-type-selector">
                                        <input type="radio" name="cargoType" id="generalGoods" value="General Goods" class="d-none" checked>
                                        <label for="generalGoods" class="cargo-type-option">
                                            <i class="fas fa-box-open fa-2x mb-2"></i>
                                            <span>General Goods</span>
                                        </label>
                                        
                                        <input type="radio" name="cargoType" id="furniture" value="Furniture" class="d-none">
                                        <label for="furniture" class="cargo-type-option">
                                            <i class="fas fa-couch fa-2x mb-2"></i>
                                            <span>Furniture</span>
                                        </label>
                                        
                                        <input type="radio" name="cargoType" id="electronics" value="Electronics" class="d-none">
                                        <label for="electronics" class="cargo-type-option">
                                            <i class="fas fa-laptop fa-2x mb-2"></i>
                                            <span>Electronics</span>
                                        </label>
                                        
                                        <input type="radio" name="cargoType" id="otherType" value="Other" class="d-none">
                                        <label for="otherType" class="cargo-type-option">
                                            <i class="fas fa-ellipsis-h fa-2x mb-2"></i>
                                            <span>Other</span>
                                        </label>
                                    </div>
                                    
                                    <div id="customTypeContainer" class="mt-3 d-none">
                                        <input id="customCargoType" placeholder="Specify cargo type" type="text" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Approximate Weight (kg)</label>
                                    <select id="weight" name="weight" class="form-select">
                                        <option value="0-50">0-50 kg</option>
                                        <option value="51-100">51-100 kg</option>
                                        <option value="101-500">101-500 kg</option>
                                        <option value="500+">500+ kg</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="dimensions" class="form-label">Approximate Size</label>
                                    <select id="dimensions" name="dimensions" class="form-select">
                                        <option value="Small">Small (e.g., boxes)</option>
                                        <option value="Medium">Medium (e.g., furniture)</option>
                                        <option value="Large">Large (e.g., pallets)</option>
                                        <option value="Oversized">Oversized</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-success next-step" data-next="step2">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 2: Location Info -->
                        <div id="step2" class="form-step">
                            <h5 class="mb-4"><i class="fas fa-map-marker-alt me-2 text-success"></i> Where is it going?</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="origin" class="form-label">Pickup Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                        <input id="origin" name="origin" placeholder="Start typing location..." 
                                               type="text" class="form-control location-input" required>
                                        <button type="button" class="btn btn-outline-success use-current-location">
                                            <i class="fas fa-location-arrow"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="destination" class="form-label">Delivery Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-flag-checkered"></i></span>
                                        <input id="destination" name="destination" placeholder="Start typing location..." 
                                               type="text" class="form-control location-input" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="pickupDate" class="form-label">When should it be picked up?</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <select id="pickupDate" name="pickupDate" class="form-select">
                                            <option value="ASAP">As soon as possible</option>
                                            <option value="Today">Today</option>
                                            <option value="Tomorrow">Tomorrow</option>
                                            <option value="specific">Choose date...</option>
                                        </select>
                                    </div>
                                    <input type="date" id="specificDate" class="form-control mt-2 d-none">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Transport Type</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" name="transportType" id="roadTransport" value="Road" class="btn-check" autocomplete="off" checked>
                                        <label for="roadTransport" class="btn btn-outline-success">
                                            <i class="fas fa-truck me-2"></i> Road
                                        </label>
                                        
                                        <input type="radio" name="transportType" id="airTransport" value="Air" class="btn-check" autocomplete="off">
                                        <label for="airTransport" class="btn btn-outline-success">
                                            <i class="fas fa-plane me-2"></i> Air
                                        </label>
                                        
                                        <input type="radio" name="transportType" id="seaTransport" value="Sea" class="btn-check" autocomplete="off">
                                        <label for="seaTransport" class="btn btn-outline-success">
                                            <i class="fas fa-ship me-2"></i> Sea
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step1">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </button>
                                <button type="button" class="btn btn-success next-step" data-next="step3">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 3: Contact & Final Details -->
                        <div id="step3" class="form-step">
                            <h5 class="mb-4"><i class="fas fa-user me-2 text-success"></i> Final Details</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Contact Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input id="phone" name="phone" type="tel" class="form-control" 
                                               value="<?php echo isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : ''; ?>"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="instructions" class="form-label">Special Instructions (Optional)</label>
                                    <textarea id="instructions" name="instructions" rows="3" 
                                              class="form-control" placeholder="Fragile items, special handling, etc."></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                        <label class="form-check-label small" for="termsCheck">
                                            I agree to the terms and conditions of cargo transportation
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary prev-step" data-prev="step2">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i> Post Cargo
                                </button>
                            </div>
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
                    <button id="backToHomeBtn" class="btn btn-outline-success">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
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

         <!-- Settings Section -->
         <div id="settingsSection" class="d-none">
            <div class="card dashboard-card">
                <div class="card-header bg-white">
                    <h3 class="mb-0">
                        <span class="text-success">Account</span> Settings
                    </h3>
                </div>
                <div class="card-body">
                    <p>Account settings management will be available here.</p>
                    <p>This could include:</p>
                    <ul>
                        <li>Updating profile information (name, contact details)</li>
                        <li>Changing password</li>
                        <li>Notification preferences</li>
                    </ul>
                    <div id="globalAlertPlaceholder" class="mt-3"></div> <!-- For general alerts in settings or other global contexts -->
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i> This section is a placeholder for future development.
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- End Main Content -->

    <!-- Cargo Details Modal -->
    <div class="modal fade" id="cargoDetailsModal" tabindex="-1" aria-labelledby="cargoDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="cargoDetailsModalLabel">Cargo Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted">Cargo Type</small></p>
                            <h6 id="modalCargoType"></h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted">Status</small></p>
                            <div id="modalStatus"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-map-pin me-1"></i> Origin</small></p>
                            <h6 id="modalOrigin"></h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-flag-checkered me-1"></i> Destination</small></p>
                            <h6 id="modalDestination"></h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> Pickup Date</small></p>
                            <h6 id="modalPickupDate"></h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-weight-hanging me-1"></i> Weight</small></p>
                            <h6 id="modalWeight"></h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-ruler-combined me-1"></i> Dimensions</small></p>
                            <h6 id="modalDimensions"></h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1"><small class="text-muted"><i class="fas fa-phone me-1"></i> Contact Phone</small></p>
                            <h6 id="modalPhone"></h6>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-1"><small class="text-muted">Special Instructions</small></p>
                    <p id="modalInstructions" class="text-muted" style="white-space: pre-wrap;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            const settingsSection = document.getElementById('settingsSection');
            
            // Get all navigation links
            const homeLink = document.getElementById('homeLink');
            const postCargoLink = document.getElementById('postCargoLink');
            const viewPostedCargosLink = document.getElementById('viewPostedCargosLink');
            const settingsLink = document.getElementById('settingsLink');
            const postNewCargoBtn = document.getElementById('postNewCargoBtn');
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
            const formAlertPlaceholder = document.getElementById('formAlertPlaceholder');
            
            // Dashboard counters
            const cargoCount = document.getElementById('cargoCount');
            const availableCount = document.getElementById('availableCount');
            const inTransitCount = document.getElementById('inTransitCount');

            // Store all fetched cargos for client-side filtering
            let allFetchedCargos = [];
            let cargoDetailsModalInstance = null;
            if (document.getElementById('cargoDetailsModal')) {
                 cargoDetailsModalInstance = new bootstrap.Modal(document.getElementById('cargoDetailsModal'));
            }
            
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
                if(settingsSection) settingsSection.classList.add('d-none');
                
                if (section) section.classList.remove('d-none');
                
                document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
                
                if (section === homeSection) {
                    if(homeLink) homeLink.classList.add('active');
                } else if (section === postCargoSection) {
                    if(postCargoLink) postCargoLink.classList.add('active');
                    document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
                    const step1 = document.getElementById('step1');
                    if(step1) step1.classList.add('active');
                    if(postCargoForm) postCargoForm.reset();
                    
                    const customTypeContainer = document.getElementById('customTypeContainer');
                    const customCargoTypeInput = document.getElementById('customCargoType');
                    const specificDateInput = document.getElementById('specificDate');

                    if(customTypeContainer) customTypeContainer.classList.add('d-none');
                    if(customCargoTypeInput) customCargoTypeInput.value = '';
                    if(specificDateInput) {
                        specificDateInput.classList.add('d-none');
                        specificDateInput.value = '';
                    }
                    if(formAlertPlaceholder) formAlertPlaceholder.innerHTML = '';

                } else if (section === postedCargosSection) {
                    if(viewPostedCargosLink) viewPostedCargosLink.classList.add('active');
                } else if (section === settingsSection) {
                    if(settingsLink) settingsLink.classList.add('active');
                }
                
                if (window.innerWidth <= 768) {
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.querySelector('.main-content');
                    if (sidebar && mainContent) {
                       sidebar.classList.remove('active');
                       mainContent.classList.remove('active');
                    }
                }
            }

            function showAlert(message, type, placeholderId = 'formAlertPlaceholder') {
                const alertContainer = document.getElementById(placeholderId);
                if (alertContainer) {
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = `
                        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    alertContainer.innerHTML = '';
                    alertContainer.append(wrapper);
                }
            }
            
            function fetchCargos() {
                if(cargoLoader) cargoLoader.classList.remove('d-none');
                if(cargoList) cargoList.innerHTML = '<p class="text-muted text-center py-4">Loading your cargos...</p>';
                if(recentActivity) recentActivity.innerHTML = '<p class="text-center text-muted py-3">Loading recent activity...</p>';
                
                fetch('../Backend/fetch-cargo.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(apiResponse => {
                    if(cargoLoader) cargoLoader.classList.add('d-none');
                    
                    if (!apiResponse.success) {
                        console.error('Error fetching cargos:', apiResponse.message);
                        const errorMsg = apiResponse.message || 'Failed to load cargos. Please try again.';
                        if(cargoList) cargoList.innerHTML = `<p class="text-danger text-center py-4">${errorMsg}</p>`;
                        if(recentActivity) recentActivity.innerHTML = '<p class="text-center text-muted py-3">Could not load recent activity.</p>';
                        if(cargoCount) cargoCount.textContent = '0';
                        if(availableCount) availableCount.textContent = '0';
                        if(inTransitCount) inTransitCount.textContent = '0';
                        allFetchedCargos = [];
                        return;
                    }

                    allFetchedCargos = apiResponse.cargos || [];
                    
                    if (allFetchedCargos.length > 0) {
                        if(cargoCount) cargoCount.textContent = allFetchedCargos.length;
                        if(availableCount) availableCount.textContent = allFetchedCargos.filter(cargo => cargo.status === 'Available').length;
                        if(inTransitCount) inTransitCount.textContent = allFetchedCargos.filter(cargo => cargo.status === 'In Transit').length;
                        
                        updateRecentActivity(allFetchedCargos);
                        filterAndDisplayCargos();
                    } else {
                        if(cargoList) cargoList.innerHTML = `
                            <div class="text-center py-4">
                                <p class="text-muted mb-3">You haven't posted any cargos yet.</p>
                                <button id="noCargoPostBtn" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> Post Your First Cargo
                                </button>
                            </div>
                        `;
                        
                        const noCargoPostBtn = document.getElementById('noCargoPostBtn');
                        if(noCargoPostBtn) {
                            noCargoPostBtn.addEventListener('click', function() {
                                showSection(postCargoSection);
                            });
                        }
                        
                        if(cargoCount) cargoCount.textContent = '0';
                        if(availableCount) availableCount.textContent = '0';
                        if(inTransitCount) inTransitCount.textContent = '0';
                        if(recentActivity) recentActivity.innerHTML = '<p class="text-center text-muted py-3">No recent activity</p>';
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    if(cargoLoader) cargoLoader.classList.add('d-none');
                    if(cargoList) cargoList.innerHTML = '<p class="text-danger text-center py-4">Failed to load cargos. Network error or server issue.</p>';
                    if(recentActivity) recentActivity.innerHTML = '<p class="text-center text-muted py-3">Could not load recent activity.</p>';
                    if(cargoCount) cargoCount.textContent = '0';
                    if(availableCount) availableCount.textContent = '0';
                    if(inTransitCount) inTransitCount.textContent = '0';
                    allFetchedCargos = [];
                });
            }

            function filterAndDisplayCargos() {
                if (!searchCargo || !statusFilter) return;
                const searchTerm = searchCargo.value.toLowerCase();
                const statusValue = statusFilter.value;
                
                const filteredCargos = allFetchedCargos.filter(cargo => {
                    const matchesSearch = 
                        (cargo.origin && cargo.origin.toLowerCase().includes(searchTerm)) ||
                        (cargo.destination && cargo.destination.toLowerCase().includes(searchTerm)) ||
                        (cargo.cargo_type && cargo.cargo_type.toLowerCase().includes(searchTerm));
                    
                    const matchesStatus = statusValue === 'all' || cargo.status === statusValue;
                    
                    return matchesSearch && matchesStatus;
                });
                displayCargosList(filteredCargos);
            }
            
            function updateRecentActivity(cargos) {
                if (!recentActivity) return;

                const recentCargos = [...cargos].sort((a, b) => {
                    const dateA = a.created_at ? new Date(a.created_at) : (a.pickup_date ? new Date(a.pickup_date) : new Date(0));
                    const dateB = b.created_at ? new Date(b.created_at) : (b.pickup_date ? new Date(b.pickup_date) : new Date(0));
                    return dateB - dateA;
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
                    } else if (cargo.status === 'Delivered') {
                        statusBadge = '<span class="badge rounded-pill badge-delivered">Delivered</span>';
                    } else {
                         statusBadge = `<span class="badge rounded-pill bg-secondary">${cargo.status || 'Unknown'}</span>`;
                    }
                    
                    activityItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${cargo.cargo_type || 'N/A'} ${statusBadge}</h6>
                                <p class="mb-1 text-muted small">From ${cargo.origin || 'N/A'} to ${cargo.destination || 'N/A'}</p>
                                <p class="mb-0 text-muted small">Pickup: ${cargo.pickup_date || 'N/A'}</p>
                            </div>
                            <button class="btn btn-sm btn-outline-success view-details-btn" data-id="${cargo.id}">
                                Details
                            </button>
                        </div>
                    `;
                    recentActivity.appendChild(activityItem);
                });
                setupViewDetailsButtons(allFetchedCargos);
            }
            
            function displayCargosList(cargosToDisplay) {
                if (!cargoList) return;

                if (cargosToDisplay.length === 0) {
                    if (allFetchedCargos.length > 0) {
                        cargoList.innerHTML = '<p class="text-muted text-center py-4">No cargos match your current filters.</p>';
                    } else {
                         cargoList.innerHTML = `
                            <div class="text-center py-4">
                                <p class="text-muted mb-3">You haven't posted any cargos yet.</p>
                                <button id="noCargoPostBtnInDisplay" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i> Post Your First Cargo
                                </button>
                            </div>
                        `;
                        const noCargoBtnInDisplay = document.getElementById('noCargoPostBtnInDisplay');
                        if (noCargoBtnInDisplay) {
                            noCargoBtnInDisplay.addEventListener('click', () => showSection(postCargoSection));
                        }
                    }
                    return;
                }
                
                cargoList.innerHTML = '';
                
                cargosToDisplay.forEach(cargo => {
                    let statusBadgeClass = 'badge-available';
                    if (cargo.status === 'In Transit') statusBadgeClass = 'badge-transit';
                    else if (cargo.status === 'Delivered') statusBadgeClass = 'badge-delivered';
                    else statusBadgeClass = 'bg-secondary';

                    const cargoItem = document.createElement('div');
                    cargoItem.className = 'card mb-3 border-0 shadow-sm cargo-item-card';
                    cargoItem.innerHTML = `
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="mb-0 me-2 card-title">${cargo.cargo_type || 'N/A'}</h5>
                                        <span class="badge rounded-pill ${statusBadgeClass}">
                                            ${cargo.status || 'Unknown'}
                                        </span>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small"><i class="fas fa-map-pin me-1"></i> From</p>
                                            <p class="fw-bold mb-3">${cargo.origin || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small"><i class="fas fa-flag-checkered me-1"></i> To</p>
                                            <p class="fw-bold mb-3">${cargo.destination || 'N/A'}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small"><i class="fas fa-calendar-alt me-1"></i> Pickup Date</p>
                                            <p class="fw-bold mb-0">${cargo.pickup_date || 'N/A'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-muted mb-1 small"><i class="fas fa-weight-hanging me-1"></i> Weight</p>
                                            <p class="fw-bold mb-0">${cargo.weight ? cargo.weight : 'N/A'}</p> <!-- Assuming weight includes 'kg' from backend or adjust here -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex flex-column justify-content-center align-items-md-end mt-3 mt-md-0">
                                    <div class="text-md-end mb-3">
                                        <p class="text-muted mb-1 small"><i class="fas fa-ruler-combined me-1"></i> Size</p>
                                        <p class="fw-bold">${cargo.dimensions || 'N/A'}</p>
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
                setupViewDetailsButtons(allFetchedCargos);
            }
            
            function showCargoDetails(cargo) {
                if (!cargoDetailsModalInstance) return;

                document.getElementById('modalCargoType').textContent = cargo.cargo_type || 'N/A';
                
                let statusBadgeClass = 'badge-available';
                if (cargo.status === 'In Transit') statusBadgeClass = 'badge-transit';
                else if (cargo.status === 'Delivered') statusBadgeClass = 'badge-delivered';
                else statusBadgeClass = 'bg-secondary';
                document.getElementById('modalStatus').innerHTML = `<span class="badge rounded-pill ${statusBadgeClass}">${cargo.status || 'Unknown'}</span>`;
                
                document.getElementById('modalOrigin').textContent = cargo.origin || 'N/A';
                document.getElementById('modalDestination').textContent = cargo.destination || 'N/A';
                document.getElementById('modalPickupDate').textContent = cargo.pickup_date || 'N/A';
                document.getElementById('modalWeight').textContent = cargo.weight ? `${cargo.weight}` : 'N/A'; // Assuming weight includes 'kg'
                document.getElementById('modalDimensions').textContent = cargo.dimensions || 'N/A';
                document.getElementById('modalPhone').textContent = cargo.phone || 'N/A';
                document.getElementById('modalInstructions').textContent = cargo.instructions || 'No special instructions provided.';
                
                cargoDetailsModalInstance.show();
            }
            
            function setupViewDetailsButtons(cargosSource) {
                document.querySelectorAll('.view-details-btn').forEach(button => {
                    const newButton = button.cloneNode(true);
                    button.parentNode.replaceChild(newButton, button);

                    newButton.addEventListener('click', function() {
                        const cargoId = this.getAttribute('data-id');
                        const cargo = cargosSource.find(c => String(c.id) === String(cargoId));
                        if (cargo) {
                            showCargoDetails(cargo);
                        } else {
                            console.error("Cargo not found for ID:", cargoId, "in", cargosSource);
                            showAlert("Could not load details for this cargo.", "danger", "globalAlertPlaceholder");
                        }
                    });
                });
            }
            
            if(homeLink) homeLink.addEventListener('click', (e) => { e.preventDefault(); showSection(homeSection); });
            if(postCargoLink) postCargoLink.addEventListener('click', (e) => { e.preventDefault(); showSection(postCargoSection); });
            if(viewPostedCargosLink) viewPostedCargosLink.addEventListener('click', (e) => { e.preventDefault(); showSection(postedCargosSection); fetchCargos(); });
            if(settingsLink) settingsLink.addEventListener('click', (e) => { e.preventDefault(); showSection(settingsSection); });
            if(postNewCargoBtn) postNewCargoBtn.addEventListener('click', () => showSection(postCargoSection));
            if(backToHomeBtn) backToHomeBtn.addEventListener('click', () => showSection(homeSection));
            if(refreshCargosBtn) refreshCargosBtn.addEventListener('click', fetchCargos);

            if(searchCargo) searchCargo.addEventListener('input', filterAndDisplayCargos);
            if(statusFilter) statusFilter.addEventListener('change', filterAndDisplayCargos);

            const nextStepButtons = document.querySelectorAll('.next-step');
            const prevStepButtons = document.querySelectorAll('.prev-step');

            nextStepButtons.forEach(button => {
                button.addEventListener('click', () => {
                    if(formAlertPlaceholder) formAlertPlaceholder.innerHTML = '';
                    const currentStep = button.closest('.form-step');
                    const nextStepId = button.getAttribute('data-next');
                    const nextStep = document.getElementById(nextStepId);

                    let isValid = true;
                    if (currentStep && currentStep.id === 'step1') {
                        const cargoTypeSelected = document.querySelector('input[name="cargoType"]:checked');
                        const customCargoTypeInput = document.getElementById('customCargoType');
                        if (!cargoTypeSelected) {
                            showAlert('Please select a cargo type.', 'warning'); isValid = false;
                        } else if (cargoTypeSelected.value === 'Other' && (!customCargoTypeInput || !customCargoTypeInput.value.trim())) {
                             showAlert('Please specify the "Other" cargo type.', 'warning'); isValid = false;
                        }
                    } else if (currentStep && currentStep.id === 'step2') {
                        const originInput = document.getElementById('origin');
                        const destinationInput = document.getElementById('destination');
                        const pickupDateSelect = document.getElementById('pickupDate');
                        const specificDateInput = document.getElementById('specificDate');
                        if (!originInput || !originInput.value.trim()) { showAlert('Pickup location is required.', 'warning'); isValid = false; }
                        if (!destinationInput || !destinationInput.value.trim()) { showAlert('Delivery location is required.', 'warning'); isValid = false; }
                        if (pickupDateSelect && pickupDateSelect.value === 'specific' && (!specificDateInput || !specificDateInput.value)) {
                            showAlert('Please choose a specific pickup date.', 'warning'); isValid = false;
                        }
                    }

                    if (isValid && currentStep && nextStep) {
                        currentStep.classList.remove('active');
                        nextStep.classList.add('active');
                    }
                });
            });

            prevStepButtons.forEach(button => {
                button.addEventListener('click', () => {
                    if(formAlertPlaceholder) formAlertPlaceholder.innerHTML = '';
                    const currentStep = button.closest('.form-step');
                    const prevStepId = button.getAttribute('data-prev');
                    const prevStep = document.getElementById(prevStepId);
                    if (currentStep && prevStep) {
                        currentStep.classList.remove('active');
                        prevStep.classList.add('active');
                    }
                });
            });

            const cargoTypeRadios = document.querySelectorAll('input[name="cargoType"]');
            const customTypeContainer = document.getElementById('customTypeContainer');
            const customCargoTypeInput = document.getElementById('customCargoType');

            cargoTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'Other' && this.checked) {
                        if(customTypeContainer) customTypeContainer.classList.remove('d-none');
                        if(customCargoTypeInput) customCargoTypeInput.required = true;
                    } else {
                        if(customTypeContainer) customTypeContainer.classList.add('d-none');
                        if(customCargoTypeInput) {
                            customCargoTypeInput.required = false;
                            customCargoTypeInput.value = '';
                        }
                    }
                });
            });

            const pickupDateSelect = document.getElementById('pickupDate');
            const specificDateInput = document.getElementById('specificDate');

            if(pickupDateSelect && specificDateInput) {
                pickupDateSelect.addEventListener('change', function() {
                    if (this.value === 'specific') {
                        specificDateInput.classList.remove('d-none');
                        specificDateInput.required = true;
                        specificDateInput.min = new Date().toISOString().split("T")[0];
                    } else {
                        specificDateInput.classList.add('d-none');
                        specificDateInput.required = false;
                        specificDateInput.value = '';
                    }
                });
            }

            if(postCargoForm) {
                postCargoForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if(formAlertPlaceholder) formAlertPlaceholder.innerHTML = '';

                    const termsCheck = document.getElementById('termsCheck');
                    const phoneInput = document.getElementById('phone');
                    if (termsCheck && !termsCheck.checked) {
                        showAlert('You must agree to the terms and conditions.', 'warning'); return;
                    }
                    if (phoneInput && !phoneInput.value.trim()) {
                        showAlert('Contact phone number is required.', 'warning'); return;
                    }

                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Posting...';
                    submitButton.disabled = true;

                    const formData = new FormData(this);
                    if (formData.get('cargoType') === 'Other' && customCargoTypeInput) {
                        formData.set('cargoType', customCargoTypeInput.value.trim() || 'Other (Unspecified)');
                    }
                    
                    // Handle pickup date format
                    let pickupDateValue = formData.get('pickupDate');
                    if (pickupDateValue === 'specific' && specificDateInput) {
                        pickupDateValue = specificDateInput.value; // Use yyyy-mm-dd from date input
                    } else if (pickupDateValue === 'Today') {
                        pickupDateValue = new Date().toISOString().split('T')[0];
                    } else if (pickupDateValue === 'Tomorrow') {
                        const tomorrow = new Date();
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        pickupDateValue = tomorrow.toISOString().split('T')[0];
                    }
                    // For 'ASAP', backend will handle it as such or assign a date.
                    // Here, we ensure formData has the final intended value for pickupDate.
                    formData.set('pickupDate', pickupDateValue);


                    fetch('../Backend/post-cargo.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('Cargo posted successfully! Redirecting...', 'success');
                            postCargoForm.reset();
                            if(customTypeContainer) customTypeContainer.classList.add('d-none');
                            if(customCargoTypeInput) customCargoTypeInput.value = '';
                            if(specificDateInput) { specificDateInput.classList.add('d-none'); specificDateInput.value = '';}
                            
                            document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
                            const step1 = document.getElementById('step1');
                            if(step1) step1.classList.add('active');

                            fetchCargos(); 
                            setTimeout(() => {
                                showSection(postedCargosSection);
                            }, 1500);
                        } else {
                            showAlert(data.message || 'Failed to post cargo. Please try again.', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('An error occurred. Please check your connection and try again.', 'danger');
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                    });
                });
            }

            const useCurrentLocationButtons = document.querySelectorAll('.use-current-location');
            useCurrentLocationButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetInput = this.previousElementSibling;
                    if (!targetInput || targetInput.tagName !== 'INPUT') {
                        console.error("Target input for current location not found.");
                        return;
                    }
                    if (navigator.geolocation) {
                        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                        button.disabled = true;
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const lat = position.coords.latitude;
                                const lon = position.coords.longitude;
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.display_name) {
                                        targetInput.value = data.display_name;
                                    } else {
                                        targetInput.value = `Lat: ${lat.toFixed(4)}, Lon: ${lon.toFixed(4)}`;
                                        showAlert('Could not get full address, using coordinates.', 'info', 'formAlertPlaceholder');
                                    }
                                })
                                .catch(error => {
                                    console.error("Geocoding error:", error);
                                    targetInput.value = `Lat: ${lat.toFixed(4)}, Lon: ${lon.toFixed(4)}`;
                                    showAlert('Error fetching address from coordinates. Using coordinates directly.', 'warning', 'formAlertPlaceholder');
                                })
                                .finally(() => {
                                    button.innerHTML = '<i class="fas fa-location-arrow"></i>';
                                    button.disabled = false;
                                });
                            },
                            (error) => {
                                let message = 'Error getting current location: ';
                                switch (error.code) {
                                    case error.PERMISSION_DENIED: message += "Permission denied."; break;
                                    case error.POSITION_UNAVAILABLE: message += "Location unavailable."; break;
                                    case error.TIMEOUT: message += "Request timed out."; break;
                                    default: message += "Unknown error."; break;
                                }
                                showAlert(message, 'danger', 'formAlertPlaceholder');
                                button.innerHTML = '<i class="fas fa-location-arrow"></i>';
                                button.disabled = false;
                            }
                        );
                    } else {
                        showAlert('Geolocation is not supported by this browser.', 'warning', 'formAlertPlaceholder');
                    }
                });
            });
            
            showSection(homeSection); 
            fetchCargos();
        });
    </script>
</body>
</html>
