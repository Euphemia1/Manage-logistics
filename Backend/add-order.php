<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Available Load</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .button {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .edit {
            background-color: #007bff;
        }
        .delete {
            background-color: #dc3545;
        }
        .edit:hover {
            background-color: #0056b3;
        }
        .delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Add Available Load</h1>
    <form action="submit-cargo.php" method="POST">
        <label for="order_id">Order ID:</label>
        <input type="text" id="order_id" name="order_id" required>

        <label for="cargo_owner_name">Cargo Owner Name:</label>
        <input type="text" id="cargo_owner_name" name="cargo_owner_name" required>

        <label for="cargo_type">Cargo Type:</label>
        <input type="text" id="cargo_type" name="cargo_type" required>

        <label for="weight">Weight:</label>
        <input type="number" id="weight" name="weight" required>

        <label for="origin">Origin:</label>
        <input type="text" id="origin" name="origin" required>

        <label for="dimensions">Dimensions:</label>
        <input type="text" id="dimensions" name="dimensions" required>

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <input type="submit" value="Add Load">
    </form>

    <h2>Available Loads</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Cargo Owner Name</th>
                <th>Cargo Type</th>
                <th>Weight</th>
                <th>Origin</th>
                <th>Dimensions</th>
                <th>Start Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Example row, replace with dynamic data from your database -->
            <tr>
                <td><!-- Order ID --></td>
                <td><!-- Cargo Owner Name --></td>
                <td><!-- Cargo Type --></td>
                <td><!-- Weight --></td>
 <td><!-- Origin --></td>
                <td><!-- Dimensions --></td>
                <td><!-- Start Date --></td>
                <td>
                    <a href="edit.php?order_id=<!-- Order ID -->" class="button edit">Edit</a>
                    <a href="delete.php?order_id=<!-- Order ID -->" class="button delete">Delete</a>
                </td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>
</body>
</html>
