<?php
require_once 'db.php'; // Include your database connection

// Fetch orders from the database
$result = $conn->query("SELECT * FROM orders");

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
    <title>Admin Dashboard</title>
    <script>
        function editOrder(orderId) {
            // Redirect to edit order page
            window.location.href = `edit-order.php?id=${orderId}`;
        }

        function deleteOrder(orderId) {
            if (confirm('Are you sure you want to delete this order?')) {
                // Redirect to delete order page
                window.location.href = `delete-order.php?id=${orderId}`;
            }
        }

        function addOrder() {
            // Redirect to add order page
            window.location.href = 'add-order.php';
        }
    </script>
    <style>
        .table-green {
            width: 100%;
            border-collapse: collapse;
            background-color: #e0f7e0; /* Light green background */
        }

        .table-green th, .table-green td {
            border: 1px solid #4CAF50; /* Green border */
            padding: 8px;
            text-align: left;
        }

        .table-green th {
            background-color: #4CAF50; /* Darker green for header */
            color: white;
        }
    </style>
</head>
<body>
    <h2>Manage Orders</h2>
    <button onclick="addOrder()">Add New Order</button>
    <table class="table-green">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Cargo Owner Name</th>
                <th>Cargo Type</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['cargo_owner_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['cargo_type']); ?></td>
                        <td><?php echo htmlspecialchars($order['origin']); ?></td>
                        <td><?php echo htmlspecialchars($order['destination']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                            <button onclick="editOrder(<?php echo (int) $order['order_id']; ?>)">Edit</button>
                            <button onclick="deleteOrder(<?php echo (int) $order['order_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>