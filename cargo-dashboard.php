<?php
session_start();



if (!isset($_SESSION['user_name'])) {
    header("Location: cargo-owner-login.php");
    
    
    exit();
}





$_SESSION['last_activity'] = time();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_name':
                if (!empty($_POST['new_name'])) {
                    $_SESSION['user_name'] = htmlspecialchars($_POST['new_name']);
                    $success_message = "Name updated successfully!";
                }
                break;
            case 'update_password':

              

                if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && $_POST['new_password'] === $_POST['confirm_password']) {
                    $success_message = "Password updated successfully!";
                }
                break;
            case 'update_profile_picture':
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
                    $upload_dir = 'uploads/profile_pictures/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $new_filename = 'profile_' . $_SESSION['user_name'] . '_' . time() . '.' . $file_extension;
                    $upload_path = $upload_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                        $_SESSION['profile_picture'] = $upload_path;
                        $success_message = "Profile picture updated successfully!";
                    }
                }
                break;
        }
    }
}



$profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : "https://via.placeholder.com/40";
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Modern Color Palette */
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary-color: #10b981;
            --secondary-dark: #059669;
            --secondary-light: #34d399;
            --accent-color: #8b5cf6;
            --accent-dark: #7c3aed;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --success-color: #10b981;
            
            /* Neutral Colors */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            --gradient-secondary: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
            --gradient-accent: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
            --gradient-dark: linear-gradient(135deg, var(--gray-800), var(--gray-900));
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            overflow-x: hidden;
            color: var(--gray-700);
        }
        
        /* Sidebar styles */
        .sidebar {
            background: var(--gradient-dark);
            color: white;
            height: 100vh;
            width: 280px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-xl);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h3 {
            font-weight: 700;
            font-size: 1.25rem;
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-secondary);
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }
        
        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            left: 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            transform: translateX(4px);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Main content styles */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }
        
        /* Header styles */
        .dashboard-header {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
        }
        
        .dashboard-header h2 {
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
            font-size: 1.875rem;
        }
        
        .profile-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .profile-section:hover {
            background: var(--gray-50);
        }
        
        .profile-picture {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid var(--secondary-color);
            object-fit: cover;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .profile-picture:hover {
            transform: scale(1.05);
            border-color: var(--primary-color);
        }
        
        .profile-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1.1rem;
        }
        
        /* Card styles */
        .dashboard-card {
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--gray-200);
            background: white;
            overflow: hidden;
        }
        
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .card-header {
            background: var(--gray-50) !important;
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem 2rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        /* Stats cards */
        .stats-card {
            border-radius: 1rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        
        .stats-card.posted {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #bbf7d0;
        }
        
        .stats-card.posted::before {
            background: var(--gradient-secondary);
        }
        
        .stats-card.available {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1px solid #bfdbfe;
        }
        
        .stats-card.available::before {
            background: var(--gradient-primary);
        }
        
        .stats-card.transit {
            background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
            border: 1px solid #d8b4fe;
        }
        
        .stats-card.transit::before {
            background: var(--gradient-accent);
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin: 0.5rem 0;
        }
        
        .stats-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Button styles */
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: var(--gradient-primary);
        }
        
        .btn-success {
            background: var(--gradient-secondary);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-md);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: var(--gradient-secondary);
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        
        /* Form styles */
        .form-control {
            border-radius: 0.75rem;
            border: 2px solid var(--gray-200);
            padding: 0.75rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }
        
        /* Profile Management Styles */
        .profile-management {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
        }
        
        .profile-upload-area {
            border: 2px dashed var(--gray-300);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        
        .profile-upload-area:hover {
            border-color: var(--primary-color);
            background: var(--gray-50);
        }
        
        .profile-upload-area.dragover {
            border-color: var(--secondary-color);
            background: #ecfdf5;
        }
        
        /* Loading spinner */
        .loader {
            border: 4px solid var(--gray-200);
            border-top: 4px solid var(--secondary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 2rem auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Alert styles */
        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            color: var(--secondary-dark);
            border-left: 4px solid var(--secondary-color);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #dc2626;
            border-left: 4px solid var(--danger-color);
        }
        
        /* Mobile sidebar toggle */
        #sidebarCollapse {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
            background: var(--gradient-secondary);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem;
            box-shadow: var(--shadow-lg);
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .main-content.active {
                margin-left: 280px;
            }
            
            #sidebarCollapse {
                display: block;
            }
            
            .dashboard-header {
                padding: 1rem 1.5rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }
        
        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
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
        
        .slide-in {
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0; 
                transform: translateX(-20px); 
            }
            to { 
                opacity: 1; 
                transform: translateX(0); 
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3 class="text-center">Cargo Dashboard</h3>
        </div>
        <div class="p-3">
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
                    <a href="#" id="profileLink" class="nav-link">
                        <i class="fas fa-user"></i> Profile
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
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <h2>Dashboard</h2>
            <div class="profile-section" onclick="showSection('profileSection')">
                <img id="profilePicture" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">
                <span class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <i class="fas fa-chevron-down ms-2"></i>
            </div>
        </div>



        <!-- Success/Error Messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success fade-in" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>



        <!-- Dashboard Home Section -->
        <div id="homeSection" class="fade-in">
            <div class="card dashboard-card mb-4">
                <div class="card-body">
                    <h3 class="text-center mb-4" style="font-weight: 700; color: var(--gray-800);">
                        Welcome to Your <span style="background: var(--gradient-secondary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Dashboard</span>
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="stats-card posted">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <div class="stats-label">Posted Cargos</div>
                                        <div id="cargoCount" class="stats-number" style="color: var(--secondary-color);">0</div>
                                    </div>
                                    <i class="fas fa-box" style="font-size: 2.5rem; color: var(--secondary-color); opacity: 0.3;"></i>
                                </div>
                                <p class="text-muted small mb-0">Total cargos you've posted</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="stats-card available">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <div class="stats-label">Available</div>
                                        <div id="availableCount" class="stats-number" style="color: var(--primary-color);">0</div>
                                    </div>
                                    <i class="fas fa-check-circle" style="font-size: 2.5rem; color: var(--primary-color); opacity: 0.3;"></i>
                                </div>
                                <p class="text-muted small mb-0">Cargos available for transport</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="stats-card transit">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <div class="stats-label">In Transit</div>
                                        <div id="inTransitCount" class="stats-number" style="color: var(--accent-color);">0</div>
                                    </div>
                                    <i class="fas fa-truck" style="font-size: 2.5rem; color: var(--accent-color); opacity: 0.3;"></i>
                                </div>
                                <p class="text-muted small mb-0">Cargos currently in transit</p>
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
                <div class="card-header">
                    <h5 class="mb-0" style="font-weight: 600; color: var(--gray-800);">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div id="recentActivity" class="list-group list-group-flush">
                        <p class="text-center text-muted py-3">Loading recent activity...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Management Section -->
        <div id="profileSection" class="d-none fade-in">
            <div class="row">
                <div class="col-md-8">
                    <div class="card dashboard-card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0" style="font-weight: 600; color: var(--gray-800);">
                                <i class="fas fa-user me-2" style="color: var(--primary-color);"></i>
                                Profile Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="update_name">
                                <div class="mb-4">
                                    <label for="new_name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="new_name" name="new_name" 
                                               value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-save me-1"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <hr class="my-4">
                            
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="update_password">
                                <h5 class="mb-3" style="font-weight: 600; color: var(--gray-800);">Change Password</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-lock me-2"></i> Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card dashboard-card">
                        <div class="card-header">
                            <h4 class="mb-0" style="font-weight: 600; color: var(--gray-800);">
                                <i class="fas fa-camera me-2" style="color: var(--secondary-color);"></i>
                                Profile Picture
                            </h4>
                        </div>
                        <div class="card-body text-center">
                            <img id="currentProfilePicture" src="<?php echo htmlspecialchars($profile_picture); ?>" 
                                 alt="Profile Picture" class="profile-picture mb-3" style="width: 120px; height: 120px;">
                            
                            <form method="POST" action="" enctype="multipart/form-data" id="profilePictureForm">
                                <input type="hidden" name="action" value="update_profile_picture">
                                <div class="profile-upload-area mb-3" onclick="document.getElementById('profile_picture').click()">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                                    <p class="mb-0" style="color: var(--gray-600);">Click to upload new picture</p>
                                    <small class="text-muted">JPG, PNG or GIF (max 5MB)</small>
                                </div>
                                <input type="file" class="d-none" id="profile_picture" name="profile_picture" 
                                       accept="image/*" onchange="previewImage(this)">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-upload me-2"></i> Upload Picture
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Post Cargo Section -->
        <div id="postCargoSection" class="d-none fade-in">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h3 class="mb-0" style="font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-box me-2" style="color: var(--secondary-color);"></i>
                        Post New Cargo
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Alert Placeholder -->
                    <div id="formAlertPlaceholder" class="mb-3"></div>
                    <!-- Cargo Form -->
                    <form id="postCargoForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pickup Point</label>
                                <input id="origin" name="origin" type="text" class="form-control" placeholder="Enter pickup point" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Drop Off</label>
                                <input id="destination" name="destination" type="text" class="form-control" placeholder="Enter drop off location" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cargo Type</label>
                                <input id="cargoType" name="cargoType" type="text" class="form-control" placeholder="Enter cargo type" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Weight</label>
                                <input id="weight" name="weight" type="text" class="form-control" placeholder="Enter weight (e.g., 600 Tonnes)" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="">Select status</option>
                                    <option value="Available">Available</option>
                                    <option value="In Transit">In Transit</option>
                                    <option value="Delivered">Delivered</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input id="phone" name="phone" type="tel" class="form-control" placeholder="Enter your phone number" required>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">
                                    I agree to the terms and conditions of cargo transportation
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="showSection('homeSection')">
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

        <!-- View Posted Cargos Section -->
        <div id="viewPostedCargosSection" class="d-none fade-in">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h3 class="mb-0" style="font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-list me-2" style="color: var(--primary-color);"></i>
                        Posted Cargos
                    </h3>
                </div>
                <div class="card-body">
                    <div id="postedCargosList">
                        <p class="text-center text-muted py-5">Loading posted cargos...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div id="settingsSection" class="d-none fade-in">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h3 class="mb-0" style="font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-cog me-2" style="color: var(--accent-color);"></i>
                        Settings
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 style="font-weight: 600; color: var(--gray-800);">Notifications</h5>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">
                                    Email notifications
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="smsNotifications">
                                <label class="form-check-label" for="smsNotifications">
                                    SMS notifications
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 style="font-weight: 600; color: var(--gray-800);">Privacy</h5>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="profileVisibility" checked>
                                <label class="form-check-label" for="profileVisibility">
                                    Make profile visible to transporters
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="locationSharing">
                                <label class="form-check-label" for="locationSharing">
                                    Share location for better matching
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <button class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        
        function showSection(sectionId) {
            
            const sections = ['homeSection', 'postCargoSection', 'viewPostedCargosSection', 'profileSection', 'settingsSection'];
            sections.forEach(section => {
                document.getElementById(section).classList.add('d-none');
            });
            
            
            document.getElementById(sectionId).classList.remove('d-none');
            
    
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            
            const linkMap = {
                'homeSection': 'homeLink',
                'postCargoSection': 'postCargoLink',
                'viewPostedCargosSection': 'viewPostedCargosLink',
                'profileSection': 'profileLink',
                'settingsSection': 'settingsLink'
            };
            
            if (linkMap[sectionId]) {
                document.getElementById(linkMap[sectionId]).classList.add('active');
            }
        }
        
        
        document.getElementById('homeLink').addEventListener('click', (e) => {
            e.preventDefault();
            showSection('homeSection');
        });
        
        document.getElementById('postCargoLink').addEventListener('click', (e) => {
            e.preventDefault();
            showSection('postCargoSection');
        });
        
        document.getElementById('postNewCargoBtn').addEventListener('click', (e) => {
            e.preventDefault();
            showSection('postCargoSection');
        });
        
        document.getElementById('viewPostedCargosLink').addEventListener('click', (e) => {
            e.preventDefault();
            showSection('viewPostedCargosSection');
            loadPostedCargos();
        });
        
        document.getElementById('profileLink').addEventListener('click', (e) => {
            e.preventDefault();
            showSection('profileSection');
        });
        
        document.getElementById('settingsLink').addEventListener('click', (e) => {
            e.preventDefault();
            showSection('settingsSection');
        });
        
        
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
        
    
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentProfilePicture').src = e.target.result;
                    document.getElementById('profilePicture').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Drag and drop for profile picture
        const uploadArea = document.querySelector('.profile-upload-area');
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('profile_picture').files = files;
                previewImage(document.getElementById('profile_picture'));
            }
        });
        
        // Form submission for cargo posting
        document.getElementById('postCargoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message
            const alertPlaceholder = document.getElementById('formAlertPlaceholder');
            alertPlaceholder.innerHTML = `
                <div class="alert alert-success fade-in" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    Cargo posted successfully! You will be redirected to view your posted cargos.
                </div>
            `;
            
            // Reset form
            this.reset();
            
            // Redirect to posted cargos after 2 seconds
            setTimeout(() => {
                showSection('viewPostedCargosSection');
                loadPostedCargos();
            }, 2000);
        });
        
        // Load posted cargos (mock data for demonstration)
        function loadPostedCargos() {
            const cargosList = document.getElementById('postedCargosList');
            
            // Show loading
            cargosList.innerHTML = '<div class="loader"></div>';
            
            // Simulate API call
            setTimeout(() => {
                cargosList.innerHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0">Electronics</h5>
                                        <span class="badge bg-success">Available</span>
                                    </div>
                                    <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i>Kampala → Mombasa</p>
                                    <p class="text-muted mb-2"><i class="fas fa-weight-hanging me-2"></i>500 Tonnes</p>
                                    <p class="text-muted mb-3"><i class="fas fa-calendar me-2"></i>2024-01-15</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0">Textiles</h5>
                                        <span class="badge bg-warning">In Transit</span>
                                    </div>
                                    <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i>Nairobi → Dar es Salaam</p>
                                    <p class="text-muted mb-2"><i class="fas fa-weight-hanging me-2"></i>300 Tonnes</p>
                                    <p class="text-muted mb-3"><i class="fas fa-calendar me-2"></i>2024-01-10</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary">Track</button>
                                        <button class="btn btn-sm btn-outline-info">Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }, 1000);
        }
        
        // Load dashboard stats (mock data)
        function loadDashboardStats() {
            // Simulate loading stats
            setTimeout(() => {
                document.getElementById('cargoCount').textContent = '12';
                document.getElementById('availableCount').textContent = '8';
                document.getElementById('inTransitCount').textContent = '4';
            }, 500);
            
            // Load recent activity
            setTimeout(() => {
                document.getElementById('recentActivity').innerHTML = `
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">New cargo posted</h6>
                                <p class="mb-1 text-muted">Electronics cargo from Kampala to Mombasa</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-truck text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Cargo picked up</h6>
                                <p class="mb-1 text-muted">Textiles cargo is now in transit</p>
                                <small class="text-muted">5 hours ago</small>
                            </div>
                        </div>
                    </div>
                `;
            }, 800);
        }
        
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
        });
    </script>
</body>
</html>

