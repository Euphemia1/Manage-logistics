<?php
session_start();
// Prevent browser caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require_once 'db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit();
}

$_SESSION['last_activity'] = time();
$_SESSION['user_type'] = 'admin';


function getCount($conn, $table) {
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    return 0;
}

$cargoOwnersCount = getCount($conn, 'cargo_owners');
$transportersCount = getCount($conn, 'transporters');
$ordersCount = getCount($conn, 'jobs');

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nyamula Logistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2BC652;
            --dark-green: #219A43;
            --light-green: #E8F5E8;
            --white: #FFFFFF;
            --gray-light: #F8F9FA;
            --gray-medium: #6C757D;
            --gray-dark: #343A40;
            --border-light: #DEE2E6;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-lg: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--gray-light);
            color: var(--gray-dark);
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: var(--white);
            min-height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 2rem 0;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .sidebar-header {
            text-align: center;
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        .sidebar-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .sidebar-header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .nav-item {
            margin: 0.5rem 1.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            color: var(--white);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
            color: var(--white);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Header */
        .page-header {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--gray-dark);
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-green);
        }

        .dashboard-card.cargo-owners::before {
            background: #3498db;
        }

        .dashboard-card.transporters::before {
            background: var(--primary-green);
        }

        .dashboard-card.orders::before {
            background: #e74c3c;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .cargo-owners .card-icon {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .transporters .card-icon {
            background: var(--light-green);
            color: var(--primary-green);
        }

        .orders .card-icon {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .card-title {
            font-size: 1rem;
            color: var(--gray-medium);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .card-count {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-dark);
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .card-count.updated {
            color: var(--primary-green);
            transform: scale(1.1);
        }

        .card-subtitle {
            color: var(--gray-medium);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .card-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .card-action:hover {
            color: var(--dark-green);
            transform: translateX(5px);
        }

        /* Mobile Responsive */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: var(--primary-green);
            color: var(--white);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading .card-count {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-shield-alt"></i> Admin Panel</h3>
            <p>Nyamula Logistics</p>
        </div>
        
        <nav>
            <div class="nav-item">
                <a href="admin-dashboard.php" class="nav-link active">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="job-board.php" class="nav-link">
                    <i class="fas fa-briefcase"></i>
                    Job Board
                </a>
            </div>
            <div class="nav-item">
                <a href="manage-cargo-owners.php" class="nav-link">
                    <i class="fas fa-users"></i>
                    Cargo Owners
                </a>
            </div>
            <div class="nav-item">
                <a href="manage-transporters.php" class="nav-link">
                    <i class="fas fa-truck"></i>
                    Transporters
                </a>
            </div>
            <div class="nav-item">
                <a href="manage-orders.php" class="nav-link">
                    <i class="fas fa-box"></i>
                    Orders
                </a>
            </div>
            <div class="nav-item">
                <a href="admin-logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </nav>
    </div>

 
    <div class="main-content">
       
        <div class="page-header">
            <h1 class="header-title">
                <i class="fas fa-tachometer-alt me-2"></i>
                Admin Dashboard
            </h1>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <div style="font-weight: 600; color: var(--gray-dark);">
                        <?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?>
                    </div>
                    <div style="font-size: 0.875rem; color: var(--gray-medium);">Administrator</div>
                </div>
            </div>
        </div>

        
        <div class="dashboard-cards">
            <div class="dashboard-card cargo-owners">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="card-title">Cargo Owners</h3>
                <div id="cargo-owners-count" class="card-count"><?php echo $cargoOwnersCount; ?></div>
                <div class="card-subtitle">Total Registered Users</div>
                <a href="manage-cargo-owners.php" class="card-action">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="dashboard-card transporters">
                <div class="card-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="card-title">Transporters</h3>
                <div id="transporters-count" class="card-count"><?php echo $transportersCount; ?></div>
                <div class="card-subtitle">Active Transport Partners</div>
                <a href="manage-transporters.php" class="card-action">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="dashboard-card orders">
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3 class="card-title">Total Orders</h3>
                <div id="orders-count" class="card-count"><?php echo $ordersCount; ?></div>
                <div class="card-subtitle">All Time Job Posts</div>
                <a href="manage-orders.php" class="card-action">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        
        <div class="dashboard-card">
            <h3 class="card-title">
                <i class="fas fa-clock me-2"></i>
                Recent Activity
            </h3>
            <div class="card-subtitle">Latest system activities and updates</div>
            
            <div style="margin-top: 1.5rem;">
                <div style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-light);">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--light-green); display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <i class="fas fa-user-plus" style="color: var(--primary-green);"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <div style="font-weight: 500; color: var(--gray-dark);">New transporter registered</div>
                        <div style="font-size: 0.875rem; color: var(--gray-medium);">A new transporter joined the platform</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-medium);">2 hours ago</div>
                </div>
                
                <div style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-light);">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(52, 152, 219, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <i class="fas fa-briefcase" style="color: #3498db;"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <div style="font-weight: 500; color: var(--gray-dark);">New job posted</div>
                        <div style="font-size: 0.875rem; color: var(--gray-medium);">Cargo delivery from Lusaka to Ndola</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-medium);">5 hours ago</div>
                </div>
                
                <div style="display: flex; align-items: center; padding: 1rem 0;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(231, 76, 60, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                        <i class="fas fa-check-circle" style="color: #e74c3c;"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <div style="font-weight: 500; color: var(--gray-dark);">Order completed</div>
                        <div style="font-size: 0.875rem; color: var(--gray-medium);">Successful delivery completed</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-medium);">1 day ago</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/session-manager.js"></script>
    <script>
      
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

       
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

      
        function fetchCounts() {
            fetch('get-dashboard-counts.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    updateCountDisplay('cargo-owners-count', data.cargoOwnersCount);
                    updateCountDisplay('transporters-count', data.transportersCount);
                    updateCountDisplay('orders-count', data.ordersCount);
                })
                .catch(error => {
                    console.error('Error fetching counts:', error);
                });
        }

        function updateCountDisplay(elementId, newCount) {
            const countElement = document.getElementById(elementId);
            const currentCount = parseInt(countElement.textContent);
            
            if (currentCount !== newCount) {
                countElement.classList.add('updated');
                countElement.textContent = newCount;
                
                setTimeout(() => {
                    countElement.classList.remove('updated');
                }, 1000);
            }
        }
        setInterval(fetchCounts, 30000);   
        setTimeout(fetchCounts, 5000);
    </script>
</body>
<script>
// Log out on tab close or navigation away
window.addEventListener('beforeunload', function (e) {
    // Send logout request (async, may not always complete)
    navigator.sendBeacon('logout.php');
});
</script>
</html>
