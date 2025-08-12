<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: cargo-owner-login.php"); 
    exit();
}

$_SESSION['last_activity'] = time();
$_SESSION['user_type'] = 'cargo_owner';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargo Owner Dashboard - Nyamula Logistics</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
   
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #28a745;
            --dark-green: #1e7e34;
            --light-green: #d4edda;
            --very-light-green: #f8fff9;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --text-dark: #2d3748;
            --text-muted: #6c757d;
            --border-color: #e2e8f0;
            --shadow: 0 2px 10px rgba(40, 167, 69, 0.1);
            --shadow-hover: 0 4px 20px rgba(40, 167, 69, 0.15);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--very-light-green) 0%, var(--white) 100%);
            color: var(--text-dark);
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: var(--white);
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 15px rgba(40, 167, 69, 0.2);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: var(--white);
            transform: translateX(5px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .sidebar h3 {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        .dashboard-card, .card {
            border-radius: 15px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            background: var(--white);
        }
        
        .dashboard-card:hover, .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            background: linear-gradient(135deg, var(--very-light-green) 0%, var(--light-green) 100%);
            border-bottom: 1px solid var(--border-color);
            border-radius: 15px 15px 0 0 !important;
        }

        .stats-card {
            border-left: 4px solid var(--primary-green);
            padding: 20px;
            background: var(--white);
            border-radius: 15px;
        }
        
        .stats-card .card-title {
            color: var(--text-dark);
            font-weight: 500;
        }

        .stats-card h2 {
            color: var(--primary-green);
            font-weight: 700;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-success, .btn-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border: none;
            color: var(--white);
        }

        .btn-success:hover, .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-outline-success, .btn-outline-primary {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            background: transparent;
        }

        .btn-outline-success:hover, .btn-outline-primary:hover {
            background: var(--primary-green);
            color: var(--white);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border: 2px solid var(--border-color);
            color: var(--text-muted);
        }

        .btn-outline-secondary:hover {
            background: var(--light-gray);
            border-color: var(--primary-green);
            color: var(--primary-green);
        }

        .btn-outline-info {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
        }

        .btn-outline-info:hover {
            background: var(--primary-green);
            color: var(--white);
        }

        .btn-outline-warning {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
        }

        .btn-outline-warning:hover {
            background: var(--primary-green);
            color: var(--white);
        }

        .btn-outline-danger {
            border: 2px solid #dc3545;
            color: #dc3545;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            color: var(--white);
        }

        .btn-warning {
            background: var(--primary-green);
            color: var(--white);
            border: none;
        }

        .btn-warning:hover {
            background: var(--dark-green);
        }

        .btn-info {
            background: var(--primary-green);
            color: var(--white);
            border: none;
        }

        .btn-info:hover {
            background: var(--dark-green);
        }

        .badge {
            border-radius: 8px;
            font-weight: 500;
            padding: 6px 12px;
        }

        .badge-available, .bg-success {
            background: var(--primary-green) !important;
            color: var(--white) !important;
        }
        
        .badge-transit, .bg-info {
            background: var(--primary-green) !important;
            color: var(--white) !important;
        }
        
        .badge-delivered {
            background: var(--dark-green) !important;
            color: var(--white) !important;
        }

        .bg-warning {
            background: var(--primary-green) !important;
            color: var(--white) !important;
        }

        .bg-primary {
            background: var(--primary-green) !important;
            color: var(--white) !important;
        }

        .bg-danger {
            background: #dc3545 !important;
            color: var(--white) !important;
        }

        .bg-secondary {
            background: var(--text-muted) !important;
            color: var(--white) !important;
        }

        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            background: var(--white);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
            background: var(--white);
        }

        .form-label {
            color: var(--text-dark);
            font-weight: 500;
            margin-bottom: 8px;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: var(--light-green);
            color: var(--dark-green);
            border-left: 4px solid var(--primary-green);
        }

        .alert-info {
            background: var(--very-light-green);
            color: var(--dark-green);
            border-left: 4px solid var(--primary-green);
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid var(--primary-green);
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .text-success, .text-primary {
            color: var(--primary-green) !important;
        }

        .text-purple {
            color: var(--primary-green) !important;
        }

        .text-info {
            color: var(--primary-green) !important;
        }

        .text-warning {
            color: var(--primary-green) !important;
        }
        .fa, .fas {
            color: inherit;
        }

        .text-success .fa, .text-success .fas {
            color: var(--primary-green) !important;
        }

        .text-primary .fa, .text-primary .fas {
            color: var(--primary-green) !important;
        }

        .text-purple .fa, .text-purple .fas {
            color: var(--primary-green) !important;
        }

        .spinner-border {
            color: var(--primary-green);
        }

        .spinner-border-sm {
            color: var(--white);
        }
    
        .rounded-circle {
            border: 3px solid var(--primary-green) !important;
        }

        .cargo-item-card {
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .cargo-item-card:hover { 
            box-shadow: var(--shadow-hover);
            transform: translateY(-3px);
        }

        .cargo-item-card .card-header {
            background: linear-gradient(135deg, var(--very-light-green) 0%, var(--light-green) 100%);
        }

        .cargo-item-card .card-footer {
            background: var(--very-light-green);
            border-top: 1px solid var(--border-color);
        }

        .cargo-filter-btn.active {
            background: var(--primary-green) !important;
            color: var(--white) !important;
            border-color: var(--primary-green) !important;
        }

        #sidebarCollapse {
            display: none;
            background: var(--primary-green);
            border: none;
            color: var(--white);
            border-radius: 10px;
        }

        #sidebarCollapse:hover {
            background: var(--dark-green);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border-bottom: none;
            border-radius: 15px 15px 0 0;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            background: var(--very-light-green);
            border-radius: 0 0 15px 15px;
        }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
 
        .page-link {
            color: var(--primary-green);
            border-color: var(--border-color);
        }

        .page-link:hover {
            color: var(--white);
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .page-item.active .page-link {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: var(--white);
        }

        .page-item.disabled .page-link {
            color: var(--text-muted);
            background-color: var(--light-gray);
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--text-dark);
            font-weight: 600;
        }
        .welcome-gradient {
            background: linear-gradient(135deg, var(--very-light-green) 0%, var(--white) 100%);
            border-left: 5px solid var(--primary-green);
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
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
        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        .dashboard-section {
            animation: fadeIn 0.5s ease-out;
        }

        .download-btn {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: var(--white);
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .download-btn:hover {
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: var(--white);
            text-decoration: none;
        }
    </style>
</head>
<body>
  
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
                    <a href="logout.php" class="nav-link" style="color: rgba(255, 255, 255, 0.9); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 10px;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

 
    <button type="button" id="sidebarCollapse" class="btn btn-success">
        <i class="fas fa-bars"></i>
    </button>

   
    <div class="main-content">
      
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Dashboard</h2>
            <div class="d-flex align-items-center">
                <img id="profilePicture" src="https://via.placeholder.com/40/28a745/ffffff?text=<?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>" alt="Profile Picture" class="rounded-circle me-2">
                <span id="cargoOwnerName" class="fw-bold text-success"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </div>
        </div>

      
        <div id="homeSection">
            <div class="card dashboard-card mb-4 welcome-gradient">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="display-6 mb-3">
                            <i class="fas fa-truck text-success me-3"></i>
                            Welcome to <span class="text-success">Nyamula Logistics</span>
                        </h2>
                        <p class="lead text-muted">Your trusted partner in cargo transportation across Africa</p>
                    </div>
                    
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
        
            <div id="formAlertPlaceholder" class="mb-3"></div>
          
            <form id="postCargoForm" action="post-cargo.php" method="POST">
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
                        <select id="status" name="status" class="form-control" required>
                            <option value="">Select Status</option>
                            <option value="Available">Available</option>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Loading in Progress">Loading in Progress</option>
                        </select>
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
                                <button type="button" class="btn btn-outline-secondary" onclick="showSection(homeSection)">
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
      
<div id="postedCargosSection" class="dashboard-section d-none">
    <div class="container-fluid">
      
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
        <div id="alertContainer" class="mb-3"></div>

        <div id="cargoLoadingSpinner" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading your cargos...</p>
        </div>

        <div id="cargoListContainer" class="row">
         
        </div>
        <div id="paginationContainer" class="mt-4">
           
        </div>
    </div>
</div>

         <div id="settingsSection" class="d-none">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h2 class="mb-1">
                                    <i class="fas fa-cog text-success me-2"></i>
                                    Account Settings
                                </h2>
                                <p class="text-muted mb-0">Manage your account preferences and security settings</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="settingsAlertPlaceholder" class="mb-3"></div>

                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Profile Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="profileUpdateForm">
                                    <div class="text-center mb-4">
                                        <div class="position-relative d-inline-block">
                                            <img id="currentProfilePic" 
                                                 src="https://via.placeholder.com/120/28a745/ffffff?text=<?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>" 
                                                 alt="Profile Picture" 
                                                 class="rounded-circle border border-3 border-success"
                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                            <button type="button" class="btn btn-success btn-sm position-absolute bottom-0 end-0 rounded-circle" 
                                                    id="changeProfilePicBtn" style="width: 35px; height: 35px;">
                                                <i class="fas fa-camera"></i>
                                            </button>
                                        </div>
                                        <input type="file" id="profilePictureInput" class="d-none" accept="image/*">
                                    </div>

                                    <div class="mb-3">
                                        <label for="profileName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="profileName" 
                                               value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="profileEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="profileEmail" 
                                               placeholder="your.email@example.com">
                                        <div class="form-text">Used for notifications and password recovery</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="profilePhone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="profilePhone" 
                                               placeholder="+260 XXX XXX XXX">
                                    </div>

                                    <div class="mb-3">
                                        <label for="profileAddress" class="form-label">Address</label>
                                        <textarea class="form-control" id="profileAddress" rows="2" 
                                                  placeholder="Your business address"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">
                                    <i class="fas fa-shield-alt text-warning me-2"></i>
                                    Security Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="passwordChangeForm">
                                    <h6 class="fw-bold mb-3">Change Password</h6>
                                    
                                    <div class="mb-3">
                                        <label for="currentPassword" class="form-label">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="currentPassword" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('currentPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="newPassword" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">Password should be at least 8 characters long</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirmPassword" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </button>
                                </form>

                                <hr class="my-4">

                                <h6 class="fw-bold mb-3">Account Security</h6>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong>Two-Factor Authentication</strong>
                                        <div class="text-muted small">Add an extra layer of security</div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                                        <label class="form-check-label" for="twoFactorAuth"></label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong>Email Notifications</strong>
                                        <div class="text-muted small">Receive updates about your cargo</div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                        <label class="form-check-label" for="emailNotifications"></label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>SMS Notifications</strong>
                                        <div class="text-muted small">Get SMS alerts for urgent updates</div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="smsNotifications">
                                        <label class="form-check-label" for="smsNotifications"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">
                                    <i class="fas fa-sliders-h text-info me-2"></i>
                                    Preferences
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="defaultLanguage" class="form-label">Language</label>
                                    <select class="form-select" id="defaultLanguage">
                                        <option value="en" selected>English</option>
                                        <option value="ny">Chichewa</option>
                                        <option value="sw">Swahili</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select" id="timezone">
                                        <option value="Africa/Lusaka" selected>Africa/Lusaka (CAT)</option>
                                        <option value="Africa/Nairobi">Africa/Nairobi (EAT)</option>
                                        <option value="Africa/Harare">Africa/Harare (CAT)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="dateFormat" class="form-label">Date Format</label>
                                    <select class="form-select" id="dateFormat">
                                        <option value="DD/MM/YYYY" selected>DD/MM/YYYY</option>
                                        <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                                        <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                    </select>
                                </div>

                                <button type="button" class="btn btn-info" onclick="savePreferences()">
                                    <i class="fas fa-save me-2"></i>Save Preferences
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Danger Zone
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="fw-bold text-success">Download Account Data</h6>
                                    <p class="text-muted mb-2">Download a copy of all your cargo data and account information in JSON format.</p>
                                    <a href="download-data.php" class="download-btn" target="_blank">
                                        <i class="fas fa-download"></i>Download My Data
                                    </a>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <h6 class="fw-bold text-danger">Deactivate Account</h6>
                                    <p class="text-muted mb-2">Temporarily disable your account. You can reactivate it anytime.</p>
                                    <button type="button" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-pause me-2"></i>Deactivate Account
                                    </button>
                                </div>

                                <hr>

                                <div>
                                    <h6 class="fw-bold text-danger">Delete Account</h6>
                                    <p class="text-muted mb-2">Permanently delete your account and all data. This action cannot be undone.</p>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmAccountDeletion()">
                                        <i class="fas fa-trash me-2"></i>Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/session-manager.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
         
            const homeSection = document.getElementById('homeSection');
            const postCargoSection = document.getElementById('postCargoSection');
            const postedCargosSection = document.getElementById('postedCargosSection');
            const settingsSection = document.getElementById('settingsSection');
            
          
            const homeLink = document.getElementById('homeLink');
            const postCargoLink = document.getElementById('postCargoLink');
            const viewPostedCargosLink = document.getElementById('viewPostedCargosLink');
            const settingsLink = document.getElementById('settingsLink');
            const postNewCargoBtn = document.getElementById('postNewCargoBtn');
            const backToHomeBtn = document.getElementById('backToHomeBtn');
            const refreshCargosBtn = document.getElementById('refreshCargos');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            
            const postCargoForm = document.getElementById('postCargoForm');
            const cargoList = document.getElementById('cargoList');
            const cargoLoader = document.getElementById('cargoLoader');
            const searchCargo = document.getElementById('searchCargo');
            const statusFilter = document.getElementById('statusFilter');
            const recentActivity = document.getElementById('recentActivity');
            const formAlertPlaceholder = document.getElementById('formAlertPlaceholder');
            
            const cargoCount = document.getElementById('cargoCount');
            const availableCount = document.getElementById('availableCount');
            const inTransitCount = document.getElementById('inTransitCount');

            let allFetchedCargos = [];
            let cargoDetailsModalInstance = null;
            if (document.getElementById('cargoDetailsModal')) {
                 cargoDetailsModalInstance = new bootstrap.Modal(document.getElementById('cargoDetailsModal'));
            }
            
            sidebarCollapse.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('active');
            });
            function loadUserCargos() {
                const cargoListContainer = document.getElementById('cargoListContainer');
                const cargoLoadingSpinner = document.getElementById('cargoLoadingSpinner');
                const totalCargoCount = document.getElementById('totalCargoCount');
                
                if (cargoLoadingSpinner) cargoLoadingSpinner.classList.remove('d-none');
                if (cargoListContainer) cargoListContainer.innerHTML = '';
                
                fetch('fetch-cargo.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(apiResponse => {
                    if (cargoLoadingSpinner) cargoLoadingSpinner.classList.add('d-none');
                    
                    if (!apiResponse.success) {
                        console.error('Error fetching cargos:', apiResponse.message);
                        const errorMsg = apiResponse.message || 'Failed to load cargos. Please try again.';
                        if (cargoListContainer) {
                            cargoListContainer.innerHTML = `
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h4 class="text-muted">Error Loading Cargos</h4>
                                        <p class="text-muted">${errorMsg}</p>
                                        <button class="btn btn-primary" onclick="loadUserCargos()">
                                            <i class="fas fa-refresh me-2"></i>Try Again
                                        </button>
                                    </div>
                                </div>
                            `;
                        }
                        if (totalCargoCount) totalCargoCount.textContent = '0';
                        return;
                    }

                    const cargos = apiResponse.cargos || [];
                    
                    if (cargos.length > 0) {
                        if (totalCargoCount) totalCargoCount.textContent = cargos.length;
                        displayUserCargos(cargos);
                    } else {
                        if (cargoListContainer) {
                            cargoListContainer.innerHTML = `
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                                        <h4 class="text-muted mb-3">No Cargos Found</h4>
                                        <p class="text-muted mb-4">You haven't posted any cargo yet.</p>
                                        <button class="btn btn-success btn-lg" onclick="showSection(postCargoSection)">
                                            <i class="fas fa-plus me-2"></i>Post New Cargo
                                        </button>
                                    </div>
                                </div>
                            `;
                        }
                        if (totalCargoCount) totalCargoCount.textContent = '0';
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    if (cargoLoadingSpinner) cargoLoadingSpinner.classList.add('d-none');
                    if (cargoListContainer) {
                        cargoListContainer.innerHTML = `
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                    <h4 class="text-muted">Network Error</h4>
                                    <p class="text-muted">Failed to load cargos. Please check your connection and try again.</p>
                                    <button class="btn btn-primary" onclick="loadUserCargos()">
                                        <i class="fas fa-refresh me-2"></i>Try Again
                                    </button>
                                </div>
                            </div>
                        `;
                    }
                    if (totalCargoCount) totalCargoCount.textContent = '0';
                });
            }
            function displayUserCargos(cargos) {
                const cargoListContainer = document.getElementById('cargoListContainer');
                if (!cargoListContainer) return;
                
                let html = '';
                cargos.forEach(cargo => {
                    const statusClass = getStatusBadgeClass(cargo.status);
                    const formattedDate = formatDate(cargo.pickup_date);
                    const createdDate = formatDate(cargo.created_at);
                    
                    html += `
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card cargo-item-card h-100 shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-primary">${escapeHtml(cargo.cargo_type)}</h6>
                                    <span class="badge ${statusClass}">${escapeHtml(cargo.status || 'Unknown')}</span>
                                </div>
                                <div class="card-body">
                                    <div class="route-info mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            <small class="text-muted">From:</small>
                                        </div>
                                        <div class="fw-semibold mb-2">${escapeHtml(cargo.origin)}</div>
                                        
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-flag-checkered text-danger me-2"></i>
                                            <small class="text-muted">To:</small>
                                        </div>
                                        <div class="fw-semibold">${escapeHtml(cargo.destination)}</div>
                                    </div>
                                    
                                    <div class="cargo-details">
                                        <div class="row text-sm">
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Weight:</small>
                                                <div>${escapeHtml(cargo.weight || 'N/A')}</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Phone:</small>
                                                <div>${escapeHtml(cargo.phone || 'N/A')}</div>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <small class="text-muted">Pickup Date:</small>
                                                <div>${formattedDate}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Posted: ${createdDate}</small>
                                        <button class="btn btn-outline-info btn-sm view-cargo-details-btn" data-cargo='${JSON.stringify(cargo)}'>
                                            <i class="fas fa-eye me-1"></i>Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                cargoListContainer.innerHTML = html;
                document.querySelectorAll('.view-cargo-details-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const cargoData = JSON.parse(this.getAttribute('data-cargo'));
                        showUserCargoDetails(cargoData);
                    });
                });
            }
            function getStatusBadgeClass(status) {
                const statusClasses = {
                    'Available': 'badge-available bg-success text-white',
                    'Pending': 'bg-warning text-dark',
                    'In Transit': 'badge-transit bg-info text-white',
                    'Loading in Progress': 'bg-primary text-white',
                    'Delivered': 'badge-delivered bg-success text-white',
                    'Cancelled': 'bg-danger text-white',
                    'ongoing load': 'bg-info text-white'
                };
                return statusClasses[status] || 'bg-secondary text-white';
            }
            
            function formatDate(dateString) {
                if (!dateString) return 'Not specified';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }
            
            function escapeHtml(text) {
                if (!text) return 'N/A';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
            
            function showUserCargoDetails(cargo) {
                const modalHTML = `
                    <div class="modal fade" id="userCargoDetailsModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">Cargo Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Cargo Type:</label>
                                            <p>${escapeHtml(cargo.cargo_type)}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Status:</label>
                                            <p><span class="badge ${getStatusBadgeClass(cargo.status)}">${escapeHtml(cargo.status || 'Unknown')}</span></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Origin:</label>
                                            <p>${escapeHtml(cargo.origin)}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Destination:</label>
                                            <p>${escapeHtml(cargo.destination)}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Weight:</label>
                                            <p>${escapeHtml(cargo.weight || 'Not specified')}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Contact Phone:</label>
                                            <p>${escapeHtml(cargo.phone || 'Not provided')}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Pickup Date:</label>
                                            <p>${formatDate(cargo.pickup_date)}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Posted Date:</label>
                                            <p>${formatDate(cargo.created_at)}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="fw-bold">Special Instructions:</label>
                                            <p>${escapeHtml(cargo.instructions || 'None')}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                const existingModal = document.getElementById('userCargoDetailsModal');
                if (existingModal) {
                    existingModal.remove();
                }

                document.body.insertAdjacentHTML('beforeend', modalHTML);

                const modal = new bootstrap.Modal(document.getElementById('userCargoDetailsModal'));
                modal.show();
            }
            
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
                document.getElementById('modalWeight').textContent = cargo.weight ? `${cargo.weight}` : 'N/A'; 
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
            if(viewPostedCargosLink) viewPostedCargosLink.addEventListener('click', (e) => { 
                e.preventDefault(); 
                showSection(postedCargosSection); 
                loadUserCargos(); 
            });
            if(settingsLink) settingsLink.addEventListener('click', (e) => { e.preventDefault(); showSection(settingsSection); });
            if(postNewCargoBtn) postNewCargoBtn.addEventListener('click', () => showSection(postCargoSection));
            if(backToHomeBtn) backToHomeBtn.addEventListener('click', () => showSection(homeSection));
            if(refreshCargosBtn) refreshCargosBtn.addEventListener('click', loadUserCargos);

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
                const newForm = postCargoForm.cloneNode(true);
                postCargoForm.parentNode.replaceChild(newForm, postCargoForm);
                const form = document.getElementById('postCargoForm');
                
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (this.dataset.submitting === 'true') {
                        console.log('Form already submitting, ignoring duplicate submission');
                        return;
                    }
                    
                    this.dataset.submitting = 'true';
                    
                    if(formAlertPlaceholder) formAlertPlaceholder.innerHTML = '';

                    const termsCheck = document.getElementById('termsCheck');
                    const phoneInput = document.getElementById('phone');
                    if (termsCheck && !termsCheck.checked) {
                        showAlert('You must agree to the terms and conditions.', 'warning');
                        this.dataset.submitting = 'false';
                        return;
                    }
                    if (phoneInput && !phoneInput.value.trim()) {
                        showAlert('Contact phone number is required.', 'warning');
                        this.dataset.submitting = 'false';
                        return;
                    }

                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Posting...';
                    submitButton.disabled = true;

                    const formData = new FormData(this);

                    console.log('Form data being sent:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key + ': ' + value);
                    }
                    
                    fetch('post-cargo.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.text();
                    })
                    .then(text => {
                        console.log('Raw response:', text);
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                showAlert('Cargo posted successfully! Redirecting...', 'success');
                                form.reset();
                                
                                fetchCargos(); 
                                setTimeout(() => {
                                    showSection(postedCargosSection);
                                }, 1500);
                            } else {
                                showAlert(data.message || 'Failed to post cargo. Please try again.', 'danger');
                            }
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            showAlert('Server returned invalid response: ' + text, 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('An error occurred. Please check your connection and try again.', 'danger');
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                        this.dataset.submitting = 'false';
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
        document.getElementById('changeProfilePicBtn').addEventListener('click', function() {
            document.getElementById('profilePictureInput').click();
        });

        document.getElementById('profilePictureInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) { 
                    showSettingsAlert('File size should be less than 5MB', 'warning');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentProfilePic').src = e.target.result;
                    showSettingsAlert('Profile picture updated! Click "Update Profile" to save changes.', 'info');
                };
                reader.readAsDataURL(file);
            }
        });
        document.getElementById('profileUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('name', document.getElementById('profileName').value);
            formData.append('email', document.getElementById('profileEmail').value);
            formData.append('phone', document.getElementById('profilePhone').value);
            formData.append('address', document.getElementById('profileAddress').value);
            
            const profilePic = document.getElementById('profilePictureInput').files[0];
            if (profilePic) {
                formData.append('profile_picture', profilePic);
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Updating...';
            submitBtn.disabled = true;
            setTimeout(() => {
                showSettingsAlert('Profile updated successfully!', 'success');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
        document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword.length < 8) {
                showSettingsAlert('New password must be at least 8 characters long', 'warning');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showSettingsAlert('New passwords do not match', 'warning');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Changing...';
            submitBtn.disabled = true;
            setTimeout(() => {
                showSettingsAlert('Password changed successfully!', 'success');
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });

        function showSettingsAlert(message, type) {
            const alertContainer = document.getElementById('settingsAlertPlaceholder');
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
                setTimeout(() => {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            }
        }
        window.togglePassword = function(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        };

        // Save preferences
        window.savePreferences = function() {
            const language = document.getElementById('defaultLanguage').value;
            const timezone = document.getElementById('timezone').value;
            const dateFormat = document.getElementById('dateFormat').value;
            
            // Here you would save preferences to backend/localStorage
            // For now, we'll simulate success
            showSettingsAlert('Preferences saved successfully!', 'success');
        };

        // Confirm account deletion
        window.confirmAccountDeletion = function() {
            const confirmed = confirm('Are you absolutely sure you want to delete your account? This action cannot be undone and will permanently delete all your data including cargo posts, messages, and profile information.');
            
            if (confirmed) {
                const doubleConfirm = prompt('Type "DELETE MY ACCOUNT" to confirm account deletion:');
                if (doubleConfirm === 'DELETE MY ACCOUNT') {
                    showSettingsAlert('Account deletion request submitted. You will receive an email with further instructions.', 'warning');
                } else {
                    showSettingsAlert('Account deletion cancelled.', 'info');
                }
            }
        };

        // Consolidated cargo manager class (keeping only one instance)
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
    const existingModal = document.getElementById("cargoDetailsModal")
    if (existingModal) {
      existingModal.remove()
    }
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

    this.showAlert("Edit functionality coming soon!", "info")
  }
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
    setTimeout(() => {
      const alert = alertContainer.querySelector(".alert")
      if (alert) {
        const bsAlert = new bootstrap.Alert(alert)
        bsAlert.close()
      }
    }, 5000)
  }
}

document.addEventListener("DOMContentLoaded", () => {
  window.cargoManager = new CargoManager()
})
    </script>
</body>
</html>

