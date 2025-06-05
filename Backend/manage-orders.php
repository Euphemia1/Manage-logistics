<?php
require_once 'db.php'; // Include your database connection

// Fetch orders from the database
$result = $conn->query("SELECT * FROM jobs");

if ($result) {
    $orders = $result->fetch_all(MYSQLI_ASSOC); // Fetch all orders as an associative array
} else {
    $orders = []; // Initialize as an empty array if the query fails
    echo "Error fetching orders: " . $conn->error; // Optional: Display error message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-light: #e0f7e0;
            --primary-dark: #388E3C;
            --secondary-color: #f5f5f5;
            --text-color: #333;
            --text-light: #666;
            --white: #ffffff;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background-color: var(--white);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: 600;
        }
        
        .search-container {
            display: flex;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            font-size: 16px;
            outline: none;
            transition: var(--transition);
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
        }
        
        .search-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .search-button:hover {
            background-color: var(--primary-dark);
        }
        
        .card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header .count {
            background-color: var(--white);
            color: var(--primary-color);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 16px 20px;
            text-align: left;
            border: 1px solid var(--primary-color);
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }
        
        tr:nth-child(even) {
            background-color: var(--primary-light);
        }
        
        tr:nth-child(odd) {
            background-color: var(--white);
        }
        
        tr:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }
        
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .status.pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #FF9800;
        }
        
        .status.in-transit {
            background-color: rgba(33, 150, 243, 0.1);
            color: #2196F3;
        }
        
        .status.delivered {
            background-color: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }
        
        .status.cancelled {
            background-color: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            text-align: center;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #f5f5f5;
            color: var(--text-color);
        }
        
        .btn-secondary:hover {
            background-color: #e0e0e0;
        }
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .icon-button {
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            font-size: 18px;
            transition: var(--transition);
            padding: 5px;
        }
        
        .icon-button:hover {
            color: var(--primary-color);
        }
        
        .icon-button.edit:hover {
            color: #2196F3;
        }
        
        .icon-button.delete:hover {
            color: #F44336;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-top: 30px;
        }
        
        .pagination {
            display: flex;
            list-style: none;
            gap: 5px;
        }
        
        .pagination li a {
            display: inline-block;
            padding: 8px 12px;
            background-color: var(--white);
            border: 1px solid #ddd;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 4px;
            transition: var(--transition);
        }
        
        .pagination li.active a,
        .pagination li a:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 16px;
            background-color: var(--white);
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .filter-btn:hover, .filter-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            th, td {
                padding: 12px 10px;
            }
            
            .actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .footer {
                flex-direction: column;
                gap: 15px;
            }
            
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: auto;
        }
        
        .modal-content {
            background-color: var(--white);
            margin: 10% auto;
            padding: 20px;
            border-radius: var(--border-radius);
            max-width: 500px;
            box-shadow: var(--box-shadow);
            position: relative;
        }
        
        .close-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
            color: var(--text-light);
        }
        
        .close-modal:hover {
            color: var(--text-color);
        }
        
        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shipping-fast"></i> Orders Management</h1>
            <a href="../Frontend/admin-dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search orders..." id="searchInput">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="filters">
            <button class="filter-btn active" data-filter="all">All Orders</button>
            <button class="filter-btn" data-filter="pending">Pending</button>
            <button class="filter-btn" data-filter="in-transit">In Transit</button>
            <button class="filter-btn" data-filter="delivered">Delivered</button>
            <button class="filter-btn" data-filter="cancelled">Cancelled</button>
        </div>
        
        <div class="card">
            <div class="card-header">
                <span>Order List</span>
                <span class="count"><?php echo count($orders); ?></span>
            </div>
            <div class="table-responsive">
                <table id="ordersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Cargo Owner</th>
                            <th>Cargo Type</th>
                            <th>Origin</th>
                            <th>Dropoff</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <?php 
                                    // Determine status class
                                    $statusClass = strtolower(str_replace(' ', '-', $order['status']));
                                ?>
                                <tr data-status="<?php echo $statusClass; ?>">
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['cargo_owner_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['pickup']); ?></td>
                                    <td><?php echo htmlspecialchars($order['dropoff']); ?></td>
                                    <td>
                                        <span class="status <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                    <td class="actions">
                                        <button class="icon-button view" title="View Details" onclick="viewOrder(<?php echo (int) $order['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="icon-button edit" title="Edit Order" onclick="editOrder(<?php echo (int) $order['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="icon-button delete" title="Delete Order" onclick="confirmDelete(<?php echo (int) $order['id']; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        


        <div class="footer">
            <div>
                <button class="btn" onclick="addOrder()">
                    <i class="fas fa-plus"></i> Add New Order
                </button>
            </div>
            <ul class="pagination">
                <li><a href="#"><i class="fas fa-chevron-left"></i></a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#"><i class="fas fa-chevron-right"></i></a></li>
            </ul>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('deleteModal')">&times;</span>
            <div class="modal-header">
                <h3>Confirm Deletion</h3>
            </div>
            <p>Are you sure you want to delete this order? This action cannot be undone.</p>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                <button class="btn" id="confirmDeleteBtn" style="background-color: #F44336;">Delete</button>
            </div>
        </div>
    </div>

    <script>
        // Function to handle order editing
        function editOrder(orderId) {
            window.location.href = `edit-order.php?id=${orderId}`;
        }
        
        // Function to handle order viewing
        function viewOrder(orderId) {
            window.location.href = `view-order.php?id=${orderId}`;
        }
        
        // Function to show delete confirmation modal
        function confirmDelete(orderId) {
            const modal = document.getElementById('deleteModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            
            modal.style.display = 'block';
            
            // Set up the confirm button to actually delete when clicked
            confirmBtn.onclick = function() {
                deleteOrder(orderId);
            };
        }
        
        // Function to close any modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Function to handle order deletion
        function deleteOrder(orderId) {
            // Close the modal
            closeModal('deleteModal');
            
            // Redirect to delete order page
            window.location.href = `delete-order.php?id=${orderId}`;
        }
        
        // Function to add a new order
        function addOrder() {
            window.location.href = 'add-order.php';
        }
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('ordersTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].innerText.toLowerCase();
                    if (cellText.indexOf(searchValue) > -1) {
                        found = true;
                        break;
                    }
                }
                
                rows[i].style.display = found ? '' : 'none';
            }
        });
        
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                const rows = document.querySelectorAll('#ordersTable tbody tr');
                
                rows.forEach(row => {
                    if (filter === 'all') {
                        row.style.display = '';
                    } else {
                        const status = row.getAttribute('data-status');
                        row.style.display = (status === filter) ? '' : 'none';
                    }
                });
            });
        });
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target === modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        };
    </script>
</body>
</html>