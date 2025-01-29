<?php

session_start();
require_once '../Backend/db.php'; // Adjust the path to your db.php file

// Fetch cargo_owners
$query = "SELECT cargo_owner_id, cargo_owner_name, email,company FROM cargo_owners"; // Adjust the query based on your table structure
$result = $conn->query($query);

if ($result === false) {
    // Handle the error, e.g., log it or display a message
    die("Database query failed: " . $conn->error);
}

$cargo_owners = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cargo_owners[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
        body {
            font-family: Arial, sans-serif; /* Modern font */
            background-color: #f4f4f4; /* Light background for contrast */
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px; /* Rounded corners */
            overflow: hidden; /* Ensures rounded corners are visible */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            background-color: white; /* Table background */
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: green; /* Header background color */
            color: white; /* Header text color */
            font-weight: bold; /* Bold header text */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light grey for even rows */
        }
        tr:nth-child(odd) {
            background-color: #ffffff; /* White for odd rows */
        }
        tr:hover {
            background-color: #e0f7e0; /* Light green on hover */
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: green; /* Button background color */
            color: white; /* Button text color */
            text-align: center;
            text-decoration: none; /* Remove underline */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            transition: background-color 0.3s; /* Smooth transition */
        }
        .button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>

    <meta charset="UTF-8">
    <title>Manage Cargo Owners</title>

</head>
<body>
    <h2>Cargo Owners</h2>
    <table>
        <thead>
            <tr class="green">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cargo_owners as $cargo_owner): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cargo_owner['cargo_owner_id']); ?></td>
                    <td><?php echo htmlspecialchars($cargo_owner['cargo_owner_name']); ?></td>
                    <td><?php echo htmlspecialchars($cargo_owner['email']); ?></td>
                    <td><?php echo htmlspecialchars(string: $cargo_owner['company'] );?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../Frontend/admin-dashboard.html" class="button">Back to Dashboard</a>
</body>
</html>
