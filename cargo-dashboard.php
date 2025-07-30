<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: cargo-owner-login.php"); 
    exit();
}

$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargo Owner Dashboard - Nyamula Logistics</title>
   
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
                    <a href="logout.php" class="nav-link text-danger">
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
            <!-- Cargo Form -->
            <form id="postCargoForm">
                <div class="row mb-4">
                    <div class="col-12 mb-3">
                        <label class="form-label">Pickup Point</label>
                        <input id="origin" name="origin" type="text" class="form-control" placeholder="Enter pickup point" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Drop Off</label>
                        <input id="destination" name="destination" type="text" class="form-control" placeholder="Enter drop off location" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Cargo Type</label>
                        <input id="cargoType" name="cargoType" type="text" class="form-control" placeholder="Enter cargo type" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Weight</label>
                        <input id="weight" name="weight" type="text" class="form-control" placeholder="Enter weight (e.g., 600 Tonnes)" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Start Date</label>
                       <input type="date" id="start_date" name="start_date" class="form-control" required>

                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Status</label>
                        <input id="status" name="status" type="text" class="form-control" placeholder="Enter status" required>
                    </div>
                </div>
                
                <div class="col-12 mb-3">
    <label class="form-label">Phone</label>
    <input id="phone" name="phone" type="tel" class="form-control" placeholder="Enter your phone number" required>
</div>
<div class="col-12 mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck" required>
        <label class="form-check-label" for="termsCheck">
            I agree to the terms and conditions of cargo transportation
        </label>
        <div class="invalid-feedback">
            You must agree to the terms and conditions.
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
            </form>
        </div>
    </div>
</div>




        <!-- Post Cargo Form Section -->
        <!-- <div id="postCargoSection" class="d-none">
            <div class="card dashboard-card">
                <div class="card-header bg-white">
                    <h3 class="mb-0">
                        <span class="text-success">Post New</span> Cargo
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Alert Placeholder -->
                    <!-- <div id="formAlertPlaceholder" class="mb-3"></div>
                    <!-- Multi-step form -->
                    <!-- <form id="postCargoForm"> -->
                        <!-- Step 1: Basic Info -->
                        <!-- <div id="step1" class="form-step active">
                            <h5 class="mb-4"><i class="fas fa-box me-2 text-success"></i> What are you shipping?</h5>
                            
                            <div class="row mb-4">   -->
                                <!-- <div class="col-12 mb-3">
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
                                        </label> -->
<!--                                         
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
                                </div> -->
                                
                                <!-- <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">Approximate Weight (kg)</label>
                                    <select id="weight" name="weight" class="form-select">
                                        <option value="0-50">0-50 kg</option>
                                        <option value="51-100">51-100 kg</option>
                                        <option value="101-500">101-500 kg</option>
                                        <option value="500+">500+ kg</option>
                                    </select>
                                </div> -->
                                
                                <!-- <div class="col-md-6 mb-3">
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
                        </div> -->
                        
                        <!-- Step 2: Location Info -->
                        <!-- <div id="step2" class="form-step">
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
                                </div> -->
<!--                                 
                                <div class="col-md-6 mb-3">
                                    <label for="destination" class="form-label">Delivery Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-flag-checkered"></i></span>
                                        <input id="destination" name="destination" placeholder="Start typing location..." 
                                               type="text" class="form-control location-input" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="pickupDate" class="form-label">When should it be picked up?</label> -->
                                    <!-- <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <select id="pickupDate" name="pickupDate" class="form-select">
                                            <option value="ASAP">As soon as possible</option>
                                            <option value="Today">Today</option>
                                            <option value="Tomorrow">Tomorrow</option>
                                            <option value="specific">Choose date...</option>
                                        </select>
                                    </div>
                                    <input type="date" id="specificDate" class="form-control mt-2 d-none">
                                <
                                               value="<?php echo isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : ''; ?>"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="instructions" class="form-label">Special Instructions (Optional)</label>
                                    <textarea id="instructions" name="instructions" rows="3" 
                                              class="form-control" placeholder="Fragile items, special handling, etc."></textarea>
                                </div>
                                
                                <div class="col-12"> -->
                                   <!-- Add these updates to your existing form -->

<!-- Make sure the terms checkbox has a name attribute -->
<!-- <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck" required>
    <label class="form-check-label small" for="termsCheck">
        I agree to the terms and conditions of cargo transportation
    </label>
    <div class="invalid-feedback">
        You must agree to the terms and conditions
    </div>
</div> -->
<!-- 
        <!-- Add name attribute to specific date input -->
        <!-- <input type="date" id="specificDate" name="specificDate" class="form-control mt-2 d-none">

        <!-- Make sure custom cargo type has name attribute -->
        <!-- <div id="customTypeContainer" class="mt-3 d-none">
             <input id="customCargoType" name="customCargoType" placeholder="Specify cargo type" type="text" class="form-control">
                </div>  --> 

<!--                             
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
        </div> -->


        <<!-- Enhanced Posted Cargos Section -->
<div id="postedCargosSection" class="dashboard-section d-none">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h2 class="mb-1">
                                    <i class="fas fa-boxes text-primary me-2"></i>
                                    Your Posted Cargos
                                </h2>
                                <p class="text-muted mb-0">
                                    Manage and track your cargo shipments
                                    <span class="badge bg-primary ms-2">
                                        <span id="totalCargoCount">0</span> Total
                                    </span>
                                </p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-outline-secondary" id="refreshCargosBtn">
                                    <i class="fas fa-sync-alt me-1"></i>Refresh
                                </button>
                                <button class="btn btn-success" data-target-section="postCargoSection">
                                    <i class="fas fa-plus me-1"></i>Post New Cargo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="cargoSearchInput" 
                                           placeholder="Search by cargo type, pickup, or destination...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                    <button class="btn btn-outline-primary btn-sm cargo-filter-btn active" data-filter="all">
                                        All
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm cargo-filter-btn" data-filter="pending">
                                        Pending
                                    </button>
                                    <button class="btn btn-outline-info btn-sm cargo-filter-btn" data-filter="in-transit">
                                        In Transit
                                    </button>
                                    <button class="btn btn-outline-success btn-sm cargo-filter-btn" data-filter="delivered">
                                        Delivered
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer" class="mb-3"></div>

        <!-- Loading Spinner -->
        <div id="cargoLoadingSpinner" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading your cargos...</p>
        </div>

        <!-- Cargo List Container -->
        <div id="cargoListContainer" class="row">
            <!-- Cargo cards will be dynamically loaded here -->
        </div>

        <!-- Pagination Container -->
        <div id="paginationContainer" class="mt-4">
            <!-- Pagination will be dynamically loaded here -->
        </div>
    </div>
</div>


<!-- Navigation Buttons (Update your existing navigation) -->
<div class="dashboard-nav mb-4">
    <button class="btn btn-outline-success me-2" data-target-section="postCargoSection">
        <i class="fas fa-plus me-2"></i>Post New Cargo
    </button>
    <button class="btn btn-outline-primary me-2 active" data-target-section="postedCargosSection">
        <i class="fas fa-list me-2"></i>View Posted Cargos
    </button>
    <!-- <button class="btn btn-outline-info me-2" data-target-section="dashboardOverview">
        <i class="fas fa-chart-bar me-2"></i>Dashboard
    </button> -->
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
                

                fetch('fetch-cargo.php')
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


                    fetch('post-cargo.php', {
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

        document.addEventListener("DOMContentLoaded", () => {
  const postCargoForm = document.getElementById("postCargoForm")
  const formAlertPlaceholder = document.getElementById("formAlertPlaceholder")

  // Handle form submission
  if (postCargoForm) {
    postCargoForm.addEventListener("submit", (e) => {
      e.preventDefault()

      // Show loading state
      const submitBtn = postCargoForm.querySelector('button[type="submit"]')
      const originalText = submitBtn.innerHTML
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Posting...'
      submitBtn.disabled = true

      // Create FormData object
      const formData = new FormData(postCargoForm)

      // Debug: Log form data
      console.log("Form data being sent:")
      for (const [key, value] of formData.entries()) {
        console.log(key, value)
      }

      // Submit form
      fetch("post-cargo.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showAlert("Cargo posted successfully!", "success")
            postCargoForm.reset()
            // Reset to first step
            document.querySelectorAll(".form-step").forEach((step) => {
              step.classList.remove("active")
            })
            document.getElementById("step1").classList.add("active")
          } else {
            showAlert(data.message || "Error posting cargo. Please try again.", "danger")
          }
        })
        .catch((error) => {
          console.error("Error:", error)
          showAlert("Network error. Please check your connection and try again.", "danger")
        })
        .finally(() => {
          // Reset button
          submitBtn.innerHTML = originalText
          submitBtn.disabled = false
        })
    })
  }

  // Handle step navigation
  document.querySelectorAll(".next-step").forEach((button) => {
    button.addEventListener("click", function () {
      const currentStep = this.closest(".form-step")
      const nextStepId = this.dataset.next
      const nextStep = document.getElementById(nextStepId)

      // Basic validation for current step
      const requiredInputs = currentStep.querySelectorAll("[required]")
      let isValid = true

      requiredInputs.forEach((input) => {
        if (!input.value.trim()) {
          input.classList.add("is-invalid")
          isValid = false
        } else {
          input.classList.remove("is-invalid")
        }
      })

      if (isValid) {
        currentStep.classList.remove("active")
        nextStep.classList.add("active")
      } else {
        showAlert("Please fill in all required fields.", "warning")
      }
    })
  })

  // Handle previous step
  document.querySelectorAll(".prev-step").forEach((button) => {
    button.addEventListener("click", function () {
      const currentStep = this.closest(".form-step")
      const prevStepId = this.dataset.prev
      const prevStep = document.getElementById(prevStepId)

      currentStep.classList.remove("active")
      prevStep.classList.add("active")
    })
  })

  // Handle "Other" cargo type
  document.querySelectorAll('input[name="cargoType"]').forEach((radio) => {
    radio.addEventListener("change", function () {
      const customContainer = document.getElementById("customTypeContainer")
      if (this.value === "Other") {
        customContainer.classList.remove("d-none")
        document.getElementById("customCargoType").setAttribute("required", "")
      } else {
        customContainer.classList.add("d-none")
        document.getElementById("customCargoType").removeAttribute("required")
      }
    })
  })

  // Handle specific date selection
  const pickupDateSelect = document.getElementById("pickupDate")
  if (pickupDateSelect) {
    pickupDateSelect.addEventListener("change", function () {
      const specificDateInput = document.getElementById("specificDate")
      if (this.value === "specific") {
        specificDateInput.classList.remove("d-none")
        specificDateInput.setAttribute("required", "")
      } else {
        specificDateInput.classList.add("d-none")
        specificDateInput.removeAttribute("required")
      }
    })
  }

  // Helper function to show alerts
  function showAlert(message, type) {
    if (formAlertPlaceholder) {
      formAlertPlaceholder.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `
    }
  }
})

document.addEventListener("DOMContentLoaded", () => {
  const postCargoForm = document.getElementById("postCargoForm")
  const formAlertPlaceholder = document.getElementById("formAlertPlaceholder")

  // Handle form submission
  postCargoForm.addEventListener("submit", (e) => {
    e.preventDefault()

    // Validate all required fields regardless of which step they're in
    const requiredFields = postCargoForm.querySelectorAll("[required]")
    let isValid = true
    let firstInvalidField = null

    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        isValid = false
        field.classList.add("is-invalid")
        if (!firstInvalidField) firstInvalidField = field
      } else {
        field.classList.remove("is-invalid")
      }
    })

    // Check terms checkbox
    const termsCheck = document.getElementById("termsCheck")
    if (!termsCheck.checked) {
      isValid = false
      termsCheck.classList.add("is-invalid")
      if (!firstInvalidField) firstInvalidField = termsCheck
    } else {
      termsCheck.classList.remove("is-invalid")
    }

    if (!isValid) {
      // Show which step contains errors
      const invalidStep = firstInvalidField.closest(".form-step")
      const stepId = invalidStep.id

      // Switch to the step with errors
      document.querySelectorAll(".form-step").forEach((step) => {
        step.classList.remove("active")
      })
      invalidStep.classList.add("active")

      // Show error message
      showAlert("Required fields are missing. Please check highlighted fields.", "danger")
      return
    }

    // If validation passes, submit the form
    const formData = new FormData(postCargoForm)

    // Add custom cargo type if "Other" is selected
    if (formData.get("cargoType") === "Other" && document.getElementById("customCargoType").value) {
      formData.set("cargoType", document.getElementById("customCargoType").value)
    }

    // Add specific date if selected
    if (formData.get("pickupDate") === "specific" && document.getElementById("specificDate").value) {
      formData.set("pickupDate", document.getElementById("specificDate").value)
    }

    // Submit form data via fetch
    fetch("post-cargo.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showAlert("Cargo posted successfully!", "success")
          postCargoForm.reset()
          // Reset to first step
          document.querySelectorAll(".form-step").forEach((step) => {
            step.classList.remove("active")
          })
          document.getElementById("step1").classList.add("active")
        } else {
          showAlert(data.message || "Error posting cargo. Please try again.", "danger")
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        showAlert("An unexpected error occurred. Please try again.", "danger")
      })
  })

  // Handle next step buttons
  document.querySelectorAll(".next-step").forEach((button) => {
    button.addEventListener("click", function () {
      const currentStep = this.closest(".form-step")
      const nextStepId = this.dataset.next
      const nextStep = document.getElementById(nextStepId)

      // Validate current step fields before proceeding
      const requiredFields = currentStep.querySelectorAll("[required]")
      let isValid = true

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false
          field.classList.add("is-invalid")
        } else {
          field.classList.remove("is-invalid")
        }
      })

      if (!isValid) {
        showAlert("Please fill in all required fields before proceeding.", "danger")
        return
      }

      // Proceed to next step
      currentStep.classList.remove("active")
      nextStep.classList.add("active")
    })
  })

  // Handle previous step buttons
  document.querySelectorAll(".prev-step").forEach((button) => {
    button.addEventListener("click", function () {
      const currentStep = this.closest(".form-step")
      const prevStepId = this.dataset.prev
      const prevStep = document.getElementById(prevStepId)

      currentStep.classList.remove("active")
      prevStep.classList.add("active")
    })
  })

  // Handle "Other" cargo type selection
  document.getElementById("otherType").addEventListener("change", function () {
    const customTypeContainer = document.getElementById("customTypeContainer")
    if (this.checked) {
      customTypeContainer.classList.remove("d-none")
    } else {
      customTypeContainer.classList.add("d-none")
    }
  })

  // Handle specific date selection
  document.getElementById("pickupDate").addEventListener("change", function () {
    const specificDateInput = document.getElementById("specificDate")
    if (this.value === "specific") {
      specificDateInput.classList.remove("d-none")
      specificDateInput.setAttribute("required", "")
    } else {
      specificDateInput.classList.add("d-none")
      specificDateInput.removeAttribute("required")
    }
  })

  // Handle current location buttons
  document.querySelectorAll(".use-current-location").forEach((button) => {
    button.addEventListener("click", function () {
      const inputField = this.closest(".input-group").querySelector("input")

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            // For a real implementation, you would use a geocoding service
            // to convert coordinates to an address
            inputField.value = `${position.coords.latitude}, ${position.coords.longitude}`
            inputField.classList.remove("is-invalid")
          },
          (error) => {
            console.error("Geolocation error:", error)
            showAlert("Could not get your location. Please enter it manually.", "warning")
          },
        )
      } else {
        showAlert("Geolocation is not supported by your browser. Please enter location manually.", "warning")
      }
    })
  })

  // Helper function to show alerts
  function showAlert(message, type) {
    formAlertPlaceholder.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `
  }
})

class CargoManager {
  constructor() {
    this.currentPage = 1
    this.itemsPerPage = 6
    this.searchTerm = ""
    this.isLoading = false
    this.init()
  }

  init() {
    this.bindEvents()
    this.setupNavigation()
  }

  bindEvents() {
   
    const searchInput = document.getElementById("cargoSearchInput")
    if (searchInput) {
      searchInput.addEventListener(
        "input",
        this.debounce((e) => this.handleSearch(e.target.value), 300),
      )
    }

   

    const refreshBtn = document.getElementById("refreshCargosBtn")
    if (refreshBtn) {
      refreshBtn.addEventListener("click", () => this.refreshCargoList())
    }

   


    document.querySelectorAll(".cargo-filter-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => this.handleFilter(e.target.dataset.filter))
    })
  }

  setupNavigation() {
    const navButtons = document.querySelectorAll("[data-target-section]")
    navButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault()
        const targetSection = button.getAttribute("data-target-section")
        this.showSection(targetSection)

        if (targetSection === "postedCargosSection") {
          this.loadCargoList()
        }
      })
    })
  }

  showSection(sectionId) {


    document.querySelectorAll(".dashboard-section").forEach((section) => {
      section.classList.add("d-none")
    })

   

    const targetSection = document.getElementById(sectionId)
    if (targetSection) {
      targetSection.classList.remove("d-none")
    }

  

    document.querySelectorAll("[data-target-section]").forEach((btn) => {
      btn.classList.remove("active")
    })
    document.querySelector(`[data-target-section="${sectionId}"]`)?.classList.add("active")
  }

  async loadCargoList(page = 1) {
    if (this.isLoading) return

    this.isLoading = true
    this.currentPage = page
    this.showLoadingState()

    try {
      const params = new URLSearchParams({
        page: this.currentPage,
        limit: this.itemsPerPage,
        search: this.searchTerm,
      })

      const response = await fetch(`api/cargo-list.php?${params}`)
      const result = await response.json()

      if (result.success) {
        this.renderCargoList(result.data.cargos)
        this.renderPagination(result.data.pagination)
        this.updateCargoStats(result.data.pagination.total_records)
      } else {
        this.showErrorState(result.message || "Failed to load cargos")
      }
    } catch (error) {
      console.error("Error loading cargos:", error)
      this.showErrorState("Network error occurred")
    } finally {
      this.isLoading = false
      this.hideLoadingState()
    }
  }

  renderCargoList(cargos) {
    const container = document.getElementById("cargoListContainer")
    if (!container) return

    if (cargos.length === 0) {
      this.showEmptyState()
      return
    }

    container.innerHTML = cargos.map((cargo) => this.createCargoCard(cargo)).join("")
  }

  createCargoCard(cargo) {
    const statusClass = this.getStatusClass(cargo.status)
    const formattedDate = this.formatDate(cargo.pickup_date)
    const postedDate = this.formatDate(cargo.posted_date)

    return `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card cargo-item-card h-100 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary">${this.escapeHtml(cargo.cargo_type)}</h6>
                        <span class="badge ${statusClass}">${this.escapeHtml(cargo.status || "pending")}</span>
                    </div>
                    <div class="card-body">
                        <div class="route-info mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-success me-2"></i>
                                <small class="text-muted">From:</small>
                            </div>
                            <div class="fw-semibold mb-2">${this.escapeHtml(cargo.pickup_location)}</div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-flag-checkered text-danger me-2"></i>
                                <small class="text-muted">To:</small>
                            </div>
                            <div class="fw-semibold">${this.escapeHtml(cargo.delivery_location)}</div>
                        </div>
                        
                        <div class="cargo-details">
                            <div class="row text-sm">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Weight:</small>
                                    <div>${this.escapeHtml(cargo.weight || "N/A")}</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Size:</small>
                                    <div>${this.escapeHtml(cargo.dimensions || "N/A")}</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Transport:</small>
                                    <div>${this.escapeHtml(cargo.transport_type || "Road")}</div>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Pickup:</small>
                                    <div>${formattedDate}</div>
                                </div>
                            </div>
                        </div>
                        
                        ${
                          cargo.instructions
                            ? `
                            <div class="mt-2">
                                <small class="text-muted">Instructions:</small>
                                <p class="small text-truncate">${this.escapeHtml(cargo.instructions)}</p>
                            </div>
                        `
                            : ""
                        }
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Posted: ${postedDate}</small>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" onclick="cargoManager.viewDetails(${cargo.id})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" onclick="cargoManager.editCargo(${cargo.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="cargoManager.deleteCargo(${cargo.id})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `
  }

  renderPagination(pagination) {
    const container = document.getElementById("paginationContainer")
    if (!container || pagination.total_pages <= 1) {
      container.innerHTML = ""
      return
    }

    let paginationHTML = `<nav aria-label="Cargo pagination"><ul class="pagination justify-content-center">`


    paginationHTML += `
            <li class="page-item ${!pagination.has_prev ? "disabled" : ""}">
                <button class="page-link" onclick="cargoManager.loadCargoList(${pagination.current_page - 1})" 
                        ${!pagination.has_prev ? "disabled" : ""}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            </li>
        `



    const startPage = Math.max(1, pagination.current_page - 2)
    const endPage = Math.min(pagination.total_pages, pagination.current_page + 2)

    for (let i = startPage; i <= endPage; i++) {
      paginationHTML += `
                <li class="page-item ${i === pagination.current_page ? "active" : ""}">
                    <button class="page-link" onclick="cargoManager.loadCargoList(${i})">${i}</button>
                </li>
            `
    }

   

    paginationHTML += `
            <li class="page-item ${!pagination.has_next ? "disabled" : ""}">
                <button class="page-link" onclick="cargoManager.loadCargoList(${pagination.current_page + 1})" 
                        ${!pagination.has_next ? "disabled" : ""}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </li>
        `

    paginationHTML += `</ul></nav>`
    container.innerHTML = paginationHTML
  }

  showLoadingState() {
    const container = document.getElementById("cargoListContainer")
    const spinner = document.getElementById("cargoLoadingSpinner")

    if (container) container.innerHTML = ""
    if (spinner) spinner.classList.remove("d-none")
  }

  hideLoadingState() {
    const spinner = document.getElementById("cargoLoadingSpinner")
    if (spinner) spinner.classList.add("d-none")
  }

  showEmptyState() {
    const container = document.getElementById("cargoListContainer")
    if (!container) return

    container.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">No Cargos Found</h4>
                    <p class="text-muted mb-4">
                        ${this.searchTerm ? "No cargos match your search criteria." : "You haven't posted any cargo yet."}
                    </p>
                    <button class="btn btn-success btn-lg" data-target-section="postCargoSection">
                        <i class="fas fa-plus me-2"></i>Post New Cargo
                    </button>
                </div>
            </div>
        `
  }

  showErrorState(message) {
    const container = document.getElementById("cargoListContainer")
    if (!container) return

    container.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>
                    <h4 class="text-muted mb-3">Error Loading Cargos</h4>
                    <p class="text-muted mb-4">${this.escapeHtml(message)}</p>
                    <button class="btn btn-primary" onclick="cargoManager.refreshCargoList()">
                        <i class="fas fa-refresh me-2"></i>Try Again
                    </button>
                </div>
            </div>
        `
  }

  updateCargoStats(totalCount) {
    const statsElement = document.getElementById("totalCargoCount")
    if (statsElement) {
      statsElement.textContent = totalCount
    }
  }

  handleSearch(searchTerm) {
    this.searchTerm = searchTerm
    this.currentPage = 1
    this.loadCargoList()
  }

  refreshCargoList() {
    this.currentPage = 1
    this.loadCargoList()
  }

  async viewDetails(cargoId) {
    try {
      const response = await fetch(`api/cargo-actions.php?action=details&id=${cargoId}`)
      const result = await response.json()

      if (result.success) {
        this.showCargoDetailsModal(result.data)
      } else {
        this.showAlert("Error loading cargo details: " + result.message, "danger")
      }
    } catch (error) {
      console.error("Error:", error)
      this.showAlert("Network error occurred", "danger")
    }
  }

  showCargoDetailsModal(cargo) {
    
    const modalHTML = `
            <div class="modal fade" id="cargoDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cargo Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Cargo Type:</label>
                                    <p>${this.escapeHtml(cargo.cargo_type)}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Status:</label>
                                    <p><span class="badge ${this.getStatusClass(cargo.status)}">${this.escapeHtml(cargo.status || "pending")}</span></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Pickup Location:</label>
                                    <p>${this.escapeHtml(cargo.pickup_location)}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Delivery Location:</label>
                                    <p>${this.escapeHtml(cargo.delivery_location)}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Weight:</label>
                                    <p>${this.escapeHtml(cargo.weight || "Not specified")}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Dimensions:</label>
                                    <p>${this.escapeHtml(cargo.dimensions || "Not specified")}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Transport Type:</label>
                                    <p>${this.escapeHtml(cargo.transport_type || "Road")}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Contact Phone:</label>
                                    <p>${this.escapeHtml(cargo.phone || "Not provided")}</p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="fw-bold">Special Instructions:</label>
                                    <p>${this.escapeHtml(cargo.instructions || "None")}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-warning" onclick="cargoManager.editCargo(${cargo.id})">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        `

    // Remove existing modal if any
    const existingModal = document.getElementById("cargoDetailsModal")
    if (existingModal) {
      existingModal.remove()
    }

    // Add modal to DOM and show
    document.body.insertAdjacentHTML("beforeend", modalHTML)
    const modal = new bootstrap.Modal(document.getElementById("cargoDetailsModal"))
    modal.show()
  }

  async deleteCargo(cargoId) {
    if (!confirm("Are you sure you want to delete this cargo? This action cannot be undone.")) {
      return
    }

    try {
      const response = await fetch(`api/cargo-actions.php?action=delete&id=${cargoId}`, {
        method: "DELETE",
      })
      const result = await response.json()

      if (result.success) {
        this.showAlert("Cargo deleted successfully", "success")
        this.loadCargoList(this.currentPage)
      } else {
        this.showAlert("Error deleting cargo: " + result.message, "danger")
      }
    } catch (error) {
      console.error("Error:", error)
      this.showAlert("Network error occurred", "danger")
    }
  }

  editCargo(cargoId) {
    // Implement edit functionality
    this.showAlert("Edit functionality coming soon!", "info")
  }

  // Utility methods
  getStatusClass(status) {
    const statusClasses = {
      pending: "bg-warning text-dark",
      "in-transit": "bg-info text-white",
      delivered: "bg-success text-white",
      cancelled: "bg-danger text-white",
    }
    return statusClasses[status?.toLowerCase()] || "bg-secondary text-white"
  }

  formatDate(dateString) {
    if (!dateString) return "Not specified"
    const date = new Date(dateString)
    return date.toLocaleDateString("en-US", {
      year: "numeric",
      month: "short",
      day: "numeric",
    })
  }

  escapeHtml(text) {
    const div = document.createElement("div")
    div.textContent = text
    return div.innerHTML
  }

  debounce(func, wait) {
    let timeout
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout)
        func(...args)
      }
      clearTimeout(timeout)
      timeout = setTimeout(later, wait)
    }
  }

  showAlert(message, type) {
    const alertContainer = document.getElementById("alertContainer")
    if (!alertContainer) return

    const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${this.escapeHtml(message)}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `

    alertContainer.innerHTML = alertHTML

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
      const alert = alertContainer.querySelector(".alert")
      if (alert) {
        const bsAlert = new bootstrap.Alert(alert)
        bsAlert.close()
      }
    }, 5000)
  }
}

// Initialize the cargo manager when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  window.cargoManager = new CargoManager()
})


    </script>
</body>
</html>

