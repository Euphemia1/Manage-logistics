<?php
session_start();
require_once 'db.php'; // Adjust the path to your db.php file

// Fetch cargo_owners
$query = "SELECT cargo_owner_id, cargo_owner_name, email, company FROM cargo_owners"; // Adjust the query based on your table structure
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cargo Owners</title>
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
        
        .status.active {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--primary-color);
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-users"></i> Cargo Owners Management</h1>
            <a href="admin-dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search cargo owners..." id="searchInput">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="card">
            <div class="card-header">
                <span>Registered Cargo Owners</span>
                <span class="count"><?php echo count($cargo_owners); ?></span>
            </div>
            <div class="table-responsive">
                <table id="cargoOwnersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cargo_owners as $cargo_owner): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cargo_owner['cargo_owner_id']); ?></td>
                                <td><?php echo htmlspecialchars($cargo_owner['cargo_owner_name']); ?></td>
                                <td><?php echo htmlspecialchars($cargo_owner['email']); ?></td>
                                <td><?php echo htmlspecialchars($cargo_owner['company']); ?></td>
                                <td class="actions">
                                    <button class="icon-button" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="icon-button" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="icon-button" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="footer">
            <div>
                <a href="#" class="btn">
                    <i class="fas fa-plus"></i> Add New Cargo Owner
                </a>
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

    <script>
        // Simple search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('cargoOwnersTable');
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
    </script>
</body>
</html>