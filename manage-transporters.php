<?php
session_start();
require_once 'db.php'; 

$query = "SELECT transporter_id, transporter_name, email, company FROM transporters"; 
$result = $conn->query($query);

if ($result === false) {

    die("Database query failed: " . $conn->error);
}

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Transporters</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2BC652;
            --primary-light: #e8f5e8;
            --primary-dark: #1e8c3d;
            --secondary-color: #f5f5f5;
            --text-color: #333;
            --text-light: #666;
            --white: #ffffff;
            --border-radius: 10px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
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
  
    <nav style="background-color: var(--primary-color); padding: 1rem 0; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <h1 style="color: white; margin: 0; font-size: 24px; font-weight: 600;">
                    <i class="fas fa-truck" style="margin-right: 10px;"></i>Nyamula Logistics
                </h1>
            </div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <a href="admin-dashboard.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="job-board.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clipboard-list"></i>Job Board
                </a>
                <a href="manage-orders.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-box"></i>Orders
                </a>
                <a href="manage-transporters.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px; background-color: rgba(255,255,255,0.2);">
                    <i class="fas fa-truck"></i>Transporters
                </a>
                <a href="manage-cargo-owners.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-users"></i>Cargo Owners
                </a>
                <a href="admin-logout.php" style="background-color: rgba(255,255,255,0.2); color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-truck"></i> Transporters Management</h1>
            <a href="admin-dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search transporters..." id="searchInput">
            <button class="search-button"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="card">
            <div class="card-header">
                <span>Registered Transporters</span>
                <span class="count"><?php echo count($transporters); ?></span>
            </div>
            <div class="table-responsive">
                <table id="transportersTable">
                    <thead>
                        <tr>
                            <th>Transporter ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transporters as $transporter): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transporter['transporter_id']); ?></td>
                                <td><?php echo htmlspecialchars($transporter['transporter_name']); ?></td>
                                <td><?php echo htmlspecialchars($transporter['email']); ?></td>
                                <td><?php echo htmlspecialchars($transporter['company']); ?></td>
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
                    <i class="fas fa-plus"></i> Add New Transporter
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
       
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('transportersTable');
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