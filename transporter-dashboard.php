<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: transporter-login.php"); // Redirect to login if not logged in
    exit();
}

// Include database connection
require_once 'db.php';

// Get user information
$transporter_name = $_SESSION['user_name'];
$transporter_id = $_SESSION['user_id'] ?? 1; // Default to 1 if not set

// Fetch available loads count
$available_loads_count = 0;
try {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM jobs WHERE status = 'available'");
    $stmt->execute();
    $result = $stmt->get_result();
    $available_loads_count = $result->fetch_assoc()['count'];
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching available loads count: " . $e->getMessage());
}

// Fetch recent loads for notifications
$recent_loads = [];
try {
    $stmt = $conn->prepare("SELECT id, item, pickup, dropoff, cargo_owner, created_at FROM jobs WHERE status = 'available' ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recent_loads[] = $row;
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching recent loads: " . $e->getMessage());
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transporter Dashboard - Nyamula Logistics</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-green: #2BC652;
      --primary-green-dark: #229944;
      --primary-green-light: #e8f5e8;
      --secondary-green: #1e7e34;
      --accent-green: #28a745;
      --white: #ffffff;
      --light-gray: #f8f9fa;
      --border-gray: #e9ecef;
      --text-dark: #333333;
      --text-muted: #6c757d;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
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
      background-color: var(--light-gray);
      color: var(--text-dark);
      line-height: 1.6;
    }

    /* Sidebar Styles */
    .sidebar {
      background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
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
    .dashboard-header {
      background: var(--white);
      border-radius: var(--border-radius);
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: var(--shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .header-title {
      font-size: 1.75rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .header-subtitle {
      color: var(--text-muted);
      font-size: 1rem;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    /* Notification Bell */
    .notification-container {
      position: relative;
    }

    .notification-bell {
      background: var(--primary-green-light);
      color: var(--primary-green);
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      cursor: pointer;
      transition: var(--transition);
      position: relative;
    }

    .notification-bell:hover {
      background: var(--primary-green);
      color: var(--white);
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #dc3545;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: 600;
    }

    /* Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
      max-width: 900px;
    }

    .stat-card {
      background: var(--white);
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--shadow);
      transition: var(--transition);
      border-left: 4px solid var(--primary-green);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: var(--primary-green-light);
      color: var(--primary-green);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .stat-label {
      color: var(--text-muted);
      font-weight: 500;
    }

    /* Notifications Panel */
    .notifications-panel {
      background: var(--white);
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
    }

    .panel-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .notification-item {
      padding: 1.25rem;
      border: 1px solid var(--border-gray);
      border-radius: var(--border-radius);
      margin-bottom: 1rem;
      transition: var(--transition);
      position: relative;
    }

    .notification-item:hover {
      border-color: var(--primary-green);
      background: var(--primary-green-light);
    }

    .notification-item.new {
      border-left: 4px solid var(--primary-green);
      background: var(--primary-green-light);
    }

    .notification-header {
      display: flex;
      justify-content: between;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .notification-title {
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 0.25rem;
    }

    .notification-meta {
      font-size: 0.85rem;
      color: var(--text-muted);
    }

    .notification-content {
      font-size: 0.95rem;
      color: var(--text-dark);
    }

    /* Buttons */
    .btn-primary-custom {
      background: var(--primary-green);
      border: none;
      color: var(--white);
      padding: 0.75rem 1.5rem;
      border-radius: var(--border-radius);
      font-weight: 500;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary-custom:hover {
      background: var(--primary-green-dark);
      color: var(--white);
      transform: translateY(-2px);
    }

    .btn-outline-custom {
      border: 2px solid var(--primary-green);
      color: var(--primary-green);
      background: transparent;
      padding: 0.75rem 1.5rem;
      border-radius: var(--border-radius);
      font-weight: 500;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-outline-custom:hover {
      background: var(--primary-green);
      color: var(--white);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
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
      
      .dashboard-header {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 1rem;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Mobile Toggle */
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
    }

    /* Animation for new notifications */
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .notification-new {
      animation: pulse 2s infinite;
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
      <h3><i class="fas fa-truck"></i> Transporter</h3>
      <p>Welcome, <?php echo htmlspecialchars($transporter_name); ?></p>
    </div>
    
    <nav>
      <div class="nav-item">
        <a href="#" class="nav-link active">
          <i class="fas fa-home"></i>
          Dashboard
        </a>
      </div>
      <div class="nav-item">
        <a href="job-board.php" class="nav-link">
          <i class="fas fa-truck"></i>
          Available Loads
        </a>
      </div>
      <div class="nav-item">
        <a href="settings.php" class="nav-link">
          <i class="fas fa-cogs"></i>
          Settings
        </a>
      </div>
      <div class="nav-item">
        <a href="index.php" class="nav-link">
          <i class="fas fa-sign-out-alt"></i>
          Logout
        </a>
      </div>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
      <div>
        <h1 class="header-title">Dashboard Overview</h1>
        <p class="header-subtitle">Welcome back, <?php echo htmlspecialchars($transporter_name); ?>! Here's what's happening today.</p>
      </div>
      <div class="header-actions">
        <!-- Notification Bell -->
        <div class="notification-container">
          <button class="notification-bell" onclick="toggleNotifications()">
            <i class="fas fa-bell"></i>
            <?php if (count($recent_loads) > 0): ?>
            <span class="notification-badge"><?php echo count($recent_loads); ?></span>
            <?php endif; ?>
          </button>
        </div>
        
        <!-- Status Toggle -->
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="statusToggle" checked>
          <label class="form-check-label" for="statusToggle">Available for Jobs</label>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-truck"></i>
        </div>
        <div class="stat-value"><?php echo $available_loads_count; ?></div>
        <div class="stat-label">Available Loads</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-route"></i>
        </div>
        <div class="stat-value"><?php echo $available_loads_count; ?></div>
        <div class="stat-label">Active Trips</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value"><?php echo count($recent_loads); ?></div>
        <div class="stat-label">Recent Notifications</div>
      </div>
    </div>

    <!-- Notifications Panel -->
    <div class="notifications-panel" id="notificationsPanel" style="display: none;">
      <h2 class="panel-title">
        <i class="fas fa-bell"></i>
        Recent Load Notifications
      </h2>
      
      <?php if (empty($recent_loads)): ?>
        <div class="notification-item">
          <div class="notification-title">No new loads available</div>
          <div class="notification-content">Check back later for new cargo opportunities.</div>
        </div>
      <?php else: ?>
        <?php foreach ($recent_loads as $index => $load): ?>
          <div class="notification-item <?php echo $index < 2 ? 'new' : ''; ?>">
            <div class="notification-header">
              <div class="notification-title">New Load Available: <?php echo htmlspecialchars($load['item']); ?></div>
              <small class="notification-meta"><?php echo date('M j, Y g:i A', strtotime($load['created_at'])); ?></small>
            </div>
            <div class="notification-content">
              <strong>Route:</strong> <?php echo htmlspecialchars($load['pickup']); ?> → <?php echo htmlspecialchars($load['dropoff']); ?><br>
              <strong>Posted by:</strong> <?php echo htmlspecialchars($load['cargo_owner']); ?>
            </div>
            <div class="mt-2">
              <a href="job-board.php" class="btn-primary-custom">
                <i class="fas fa-eye"></i>
                View Details
              </a>
              <button class="btn-outline-custom" onclick="markAsRead(<?php echo $load['id']; ?>)">
                <i class="fas fa-check"></i>
                Mark as Read
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      
      <div class="mt-3 text-center">
        <a href="job-board.php" class="btn-primary-custom">
          <i class="fas fa-truck"></i>
          View All Available Loads
        </a>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="notifications-panel">
      <h2 class="panel-title">
        <i class="fas fa-bolt"></i>
        Quick Actions
      </h2>
      
      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="notification-item text-center">
            <div class="stat-icon mx-auto mb-3">
              <i class="fas fa-search"></i>
            </div>
            <div class="notification-title">Find Loads</div>
            <div class="notification-content mb-3">Browse available cargo loads in your area</div>
            <a href="job-board.php" class="btn-primary-custom">
              <i class="fas fa-search"></i>
              Browse Loads
            </a>
          </div>
        </div>
        
        <div class="col-md-4 mb-3">
          <div class="notification-item text-center">
            <div class="stat-icon mx-auto mb-3">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="notification-title">Update Location</div>
            <div class="notification-content mb-3">Keep your location updated for better load matching</div>
            <button class="btn-outline-custom" onclick="updateLocation()">
              <i class="fas fa-location-arrow"></i>
              Update Location
            </button>
          </div>
        </div>
        
        <div class="col-md-4 mb-3">
          <div class="notification-item text-center">
            <div class="stat-icon mx-auto mb-3">
              <i class="fas fa-user-cog"></i>
            </div>
            <div class="notification-title">Profile Settings</div>
            <div class="notification-content mb-3">Manage your profile and preferences</div>
            <a href="settings.php" class="btn-outline-custom">
              <i class="fas fa-cog"></i>
              Settings
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Toggle sidebar for mobile
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('active');
    }

    // Toggle notifications panel
    function toggleNotifications() {
      const panel = document.getElementById('notificationsPanel');
      panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }

    // Mark notification as read
    function markAsRead(loadId) {
      // Here you would typically make an AJAX call to mark the notification as read
      console.log('Marking load ' + loadId + ' as read');
      
      // Visual feedback
      event.target.closest('.notification-item').classList.remove('new');
      event.target.closest('.notification-item').style.opacity = '0.7';
      
      // Update notification count
      const badge = document.querySelector('.notification-badge');
      if (badge) {
        let count = parseInt(badge.textContent) - 1;
        if (count <= 0) {
          badge.remove();
        } else {
          badge.textContent = count;
        }
      }
    }

    // Update location function
    function updateLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          
          // Here you would send the coordinates to your server
          console.log('Location updated:', lat, lng);
          
          // Show success message
          alert('Location updated successfully!');
        }, function(error) {
          console.error('Error getting location:', error);
          alert('Unable to get your location. Please check your browser settings.');
        });
      } else {
        alert('Geolocation is not supported by this browser.');
      }
    }

    // Status toggle functionality
    document.getElementById('statusToggle').addEventListener('change', function() {
      const isAvailable = this.checked;
      console.log('Availability status:', isAvailable ? 'Available' : 'Unavailable');
      
      // Here you would send an AJAX request to update the status in the database
      // For now, just show visual feedback
      const statusText = isAvailable ? 'You are now available for jobs' : 'You are now unavailable for jobs';
      
      // You could show a toast notification here
      console.log(statusText);
    });

    // Auto-refresh notifications every 30 seconds
    setInterval(function() {
      // In a real application, you would make an AJAX call to check for new notifications
      console.log('Checking for new notifications...');
    }, 30000);

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
      const sidebar = document.getElementById('sidebar');
      const mobileToggle = document.querySelector('.mobile-toggle');
      
      if (window.innerWidth <= 768 && 
          !sidebar.contains(event.target) && 
          !mobileToggle.contains(event.target)) {
        sidebar.classList.remove('active');
      }
    });

    // Hide notifications panel when clicking outside
    document.addEventListener('click', function(event) {
      const panel = document.getElementById('notificationsPanel');
      const bell = document.querySelector('.notification-bell');
      
      if (!panel.contains(event.target) && !bell.contains(event.target)) {
        panel.style.display = 'none';
      }
    });
  </script>
</body>
</html>