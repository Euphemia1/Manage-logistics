<?php

session_start();

require_once 'db.php'; // Adjust the path to your db.php file

// Fetch transporters
$query = "SELECT transporter_id, transporter_name, email, company FROM transporters"; // Adjust the query based on your table structure
$result = $conn->query($query);

$transporters = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transporters[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
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

    <meta charset="UTF-8">
    <title>Manage Transporters</title>
</head>
<body>
    <h2>Transporters</h2>
    <table class="table-green">
        <thead>
            <tr>
                <th>Transporter_ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transporters as $transporter): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transporter['transporter_id']); ?></td>
                    <td><?php echo htmlspecialchars($transporter['transporter_name']); ?></td>
                    <td><?php echo htmlspecialchars($transporter['email']); ?></td>
                    <td><?php echo htmlspecialchars($transporter['company']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../Frontend/admin-dashboard.php" class="button">Back to Dashboard</a>
</body>
</html>
