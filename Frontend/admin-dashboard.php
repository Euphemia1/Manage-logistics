<?php
// Include database connection
require_once '../Backend/db.php';

// Function to get count from a table
function getCount($conn, $table) {
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    return 0;
}

// Get counts from database
$cargoOwnersCount = getCount($conn, 'cargo_owners');
$transportersCount = getCount($conn, 'transporters');
$ordersCount = getCount($conn, 'orders');

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Logistics SaaS Platform</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f9;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background: linear-gradient(180deg, #2c3e50, #34495e);
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 20px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      transition: width 0.3s;
      z-index: 100;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 1.5rem;
      color: #fff;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      color: #fff;
      padding: 15px;
      text-decoration: none;
      margin: 5px 0;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background: #1abc9c;
      border-radius: 5px;
    }

    .sidebar a i {
      margin-right: 10px;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
      transition: margin-left 0.3s;
    }

    .header {
      background-color: #fff;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 10px;
      margin-bottom: 30px;
    }

    .header h1 {
      margin: 0;
      font-size: 1.8rem;
      color: #2c3e50;
    }

    .header .user-info {
      display: flex;
      align-items: center;
    }

    .header .user-info img {
      border-radius: 50%;
      margin-right: 10px;
      width: 50px;
      height: 50px;
    }

    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .dashboard-card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 25px;
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card.cargo-owners {
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      border-top: 4px solid #3498db;
    }

    .dashboard-card.transporters {
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      border-top: 4px solid #1abc9c;
    }

    .dashboard-card.orders {
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      border-top: 4px solid #e74c3c;
    }

    .dashboard-card .icon {
      font-size: 48px;
      margin-bottom: 15px;
      color: #2c3e50;
    }

    .dashboard-card.cargo-owners .icon {
      color: #3498db;
    }

    .dashboard-card.transporters .icon {
      color: #1abc9c;
    }

    .dashboard-card.orders .icon {
      color: #e74c3c;
    }

    .dashboard-card h3 {
      font-size: 1.2rem;
      color: #7f8c8d;
      margin-bottom: 10px;
    }

    .dashboard-card .count {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 10px;
      transition: all 0.3s ease;
    }

    .dashboard-card .count.updated {
      color: #1abc9c;
      transform: scale(1.1);
    }

    .dashboard-card .btn-view {
      display: inline-block;
      padding: 8px 20px;
      background-color: #f8f9fa;
      color: #2c3e50;
      border: 1px solid #e9ecef;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
      margin-top: 10px;
    }

    .dashboard-card .btn-view:hover {
      background-color: #2c3e50;
      color: #fff;
    }

    .card {
      background-color: #fff;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .card h3 {
      margin-top: 0;
      color: #2c3e50;
      font-size: 1.5rem;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }

    .card h3 i {
      margin-right: 10px;
      color: #1abc9c;
    }

    .recent-activity {
      list-style: none;
      padding: 0;
    }

    .recent-activity li {
      padding: 15px 0;
      border-bottom: 1px solid #eee;
      display: flex;
      align-items: center;
    }

    .recent-activity li:last-child {
      border-bottom: none;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #e8f4f8;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      flex-shrink: 0;
    }

    .activity-icon i {
      color: #3498db;
    }

    .activity-content {
      flex-grow: 1;
    }

    .activity-content h4 {
      margin: 0 0 5px 0;
      font-size: 1rem;
      color: #2c3e50;
    }

    .activity-content p {
      margin: 0;
      color: #7f8c8d;
      font-size: 0.9rem;
    }

    .activity-time {
      color: #95a5a6;
      font-size: 0.8rem;
      white-space: nowrap;
    }

    .last-updated {
      text-align: center;
      color: #95a5a6;
      font-size: 0.8rem;
      margin-top: 5px;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
      }

      .sidebar h2 {
        display: none;
      }

      .sidebar a {
        justify-content: center;
      }

      .sidebar a span {
        display: none;
      }

      .main-content {
        margin-left: 80px;
      }
      
      .dashboard-cards {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="#"><i class="fas fa-home"></i> <span>Home</span></a>
    <a href="job-board.php"><i class="fas fa-briefcase"></i> <span>Job Post</span></a>
    <a href="../Backend/manage-cargo-owners.php"><i class="fas fa-users"></i> <span>Manage Cargo Owners</span></a>
    <a href="../Backend/manage-transporters.php"><i class="fas fa-truck"></i> <span>Manage Transporters</span></a>
    <a href="../Backend/manage-orders.php"><i class="fas fa-box"></i> <span>Manage Orders</span></a>
    <a href="#"><i class="fas fa-cogs"></i> <span>Settings</span></a>
    <a href="../Backend/admin-logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
  </div>

  <div class="main-content">
    <div class="header">
      <h1>Dashboard</h1>
      <div class="user-info">
        <img alt="Admin profile picture" src="https://storage.googleapis.com/a1aa/image/z5OoaeeOKwoE20Gep5dZFQxdbnfSfVaxku7IhCnUSBle4U6BF.jpg"/>
        <span>Admin</span>
      </div>
    </div>

    <!-- Dashboard Summary Cards -->
    <div class="dashboard-cards">
      <div class="dashboard-card cargo-owners">
        <div class="icon">
          <i class="fas fa-users"></i>
        </div>
        <h3>Cargo Owners</h3>
        <div id="cargo-owners-count" class="count"><?php echo $cargoOwnersCount; ?></div>
        <div class="last-updated">Total Registered</div>
        <a href="../Backend/manage-cargo-owners.php" class="btn-view">View All <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="dashboard-card transporters">
        <div class="icon">
          <i class="fas fa-truck"></i>
        </div>
        <h3>Transporters</h3>
        <div id="transporters-count" class="count"><?php echo $transportersCount; ?></div>
        <div class="last-updated">Total Registered</div>
        <a href="../Backend/manage-transporters.php" class="btn-view">View All <i class="fas fa-arrow-right"></i></a>
      </div>

      <div class="dashboard-card orders">
        <div class="icon">
          <i class="fas fa-box"></i>
        </div>
        <h3>Orders</h3>
        <div id="orders-count" class="count"><?php echo $ordersCount; ?></div>
        <div class="last-updated">Total Registered</div>
        <a href="../Backend/manage-orders.php" class="btn-view">View All <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <!-- Recent Activity Card -->
    <div class="card">
      <h3><i class="fas fa-chart-line"></i> Recent Activity</h3>
      <ul class="recent-activity" id="recent-activity-list">
        <li>
          <div class="activity-icon">
            <i class="fas fa-plus"></i>
          </div>
          <div class="activity-content">
            <h4>New Order Created</h4>
            <p>Order #1234 was created by Cargo Owner ABC</p>
          </div>
          <div class="activity-time">2 hours ago</div>
        </li>
        <li>
          <div class="activity-icon">
            <i class="fas fa-user-plus"></i>
          </div>
          <div class="activity-content">
            <h4>New Transporter Registered</h4>
            <p>Transporter XYZ Logistics joined the platform</p>
          </div>
          <div class="activity-time">5 hours ago</div>
        </li>
        <li>
          <div class="activity-icon">
            <i class="fas fa-truck"></i>
          </div>
          <div class="activity-content">
            <h4>Order Status Updated</h4>
            <p>Order #1230 status changed to "In Transit"</p>
          </div>
          <div class="activity-time">Yesterday</div>
        </li>
        <li>
          <div class="activity-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="activity-content">
            <h4>Order Completed</h4>
            <p>Order #1228 was successfully delivered</p>
          </div>
          <div class="activity-time">2 days ago</div>
        </li>
      </ul>
    </div>
  </div>

  <script>
    // Function to fetch updated counts from the server
    function fetchCounts() {
      fetch('../Backend/get-dashboard-counts.php')
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

    // Function to update count display with animation if changed
    function updateCountDisplay(elementId, newCount) {
      const countElement = document.getElementById(elementId);
      const currentCount = parseInt(countElement.textContent);
      
      if (currentCount !== newCount) {
        // Add the updated class for animation
        countElement.classList.add('updated');
        
        // Update the count
        countElement.textContent = newCount;
        
        // Remove the updated class after animation completes
        setTimeout(() => {
          countElement.classList.remove('updated');
        }, 1000);
      }
    }

    // Fetch counts every 30 seconds
    setInterval(fetchCounts, 30000);
    
    // Also fetch once when the page loads (after a short delay)
    setTimeout(fetchCounts, 5000);
  </script>
</body>
</html>