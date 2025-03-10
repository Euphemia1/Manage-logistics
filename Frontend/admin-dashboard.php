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
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th, .table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .table th {
      background-color: #f4f4f9;
      color: #2c3e50;
    }

    .table tr:hover {
      background-color: #f1f1f1;
    }

    .btn {
      padding: 10px 20px;
      background: linear-gradient(135deg, #1abc9c, #16a085);
      color: #fff;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .btn:hover {
      background: linear-gradient(135deg, #16a085, #1abc9c);
    }

    .btn-danger {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

    .btn-danger:hover {
      background: linear-gradient(135deg, #c0392b, #e74c3c);
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

    <div class="card">
      <h3>Manage Cargo Owners</h3>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr id="cargo-owner-1">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <button class="btn" onclick="editCargoOwner(1)">Edit</button>
              <button class="btn btn-danger" onclick="deleteCargoOwner(1)">Delete</button>
            </td>
          </tr>
          <!-- Add more rows as needed -->
        </tbody>
      </table>
    </div>

    <div class="card">
      <h3>Manage Transporters</h3>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr id="transporter-1">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <button class="btn" onclick="editCargoOwner(1)">Edit</button>
              <button class="btn btn-danger" onclick="deleteCargoOwner(1)">Delete</button>
            </td>
          </tr>
          <!-- Add more rows as needed -->
        </tbody>
      </table>
    </div>

    <div class="card">
      <h3>Manage Orders</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Cargo Owner</th>
            <th>Transporter</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr id="order-1">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <button class="btn" onclick="viewCargoOwner(1)">View</button>
              <button class="btn" onclick="updateCargoOwner(1)">Update</button>
            </td>
          </tr>
          <!-- Add more rows as needed -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    fetchCargoOwners();
    fetchTransporters();
  });

  function fetchCargoOwners() {
    fetch('../Backend/manage-cargo-owners.php')
      .then(response => response.json())
      .then(data => {
        const tbody = document.querySelector('table:nth-of-type(1) tbody');
        tbody.innerHTML = ''; // Clear existing rows
        data.forEach(owner => {
          const row = document.createElement('tr');
          row.id = `cargo-owner-${owner.id}`;
          row.innerHTML = `
            <td>${owner.id}</td>
            <td>${owner.name}</td>
            <td>${owner.email}</td>
            <td>${owner.phone}</td>
            <td>
              <button class="btn" onclick="editCargoOwner(${owner.id})">Edit</button>
              <button class="btn btn-danger" onclick="deleteCargoOwner(${owner.id})">Delete</button>
            </td>
          `;
          tbody.appendChild(row);
        });
      })
      .catch(error => console.error('Error fetching cargo owners:', error));
  }

  function fetchTransporters() {
    fetch('../Backend/manage-transporters.php')
      .then(response => response.json())
      .then(data => {
        const tbody = document.querySelector('table:nth-of-type(2) tbody');
        tbody.innerHTML = ''; // Clear existing rows
        data.forEach(transporter => {
          const row = document.createElement('tr');
          row.id = `transporter-${transporter.id}`;
          row.innerHTML = `
            <td>${transporter.id}</td>
            <td>${transporter.name}</td>
            <td>${transporter.email}</td>
            <td>${transporter.phone}</td>
            <td>
              <button class="btn" onclick="editTransporter(${transporter.id})">Edit</button>
              <button class="btn btn-danger" onclick="deleteTransporter(${transporter.id})">Delete</button>
            </td>
          `;
          tbody.appendChild(row);
        });
      })
      .catch(error => console.error('Error fetching transporters:', error));
  }

  function editCargoOwner(id) {
    alert(`Edit Cargo Owner with ID: ${id}`);
    // Add actual edit functionality here
  }

  function deleteCargoOwner(id) {
    if (confirm(`Are you sure you want to delete Cargo Owner with ID: ${id}?`)) {
      alert(`Deleted Cargo Owner with ID: ${id}`);
      // Add actual delete functionality here
    }
  }

  function editTransporter(id) {
    alert(`Edit Transporter with ID: ${id}`);
    // Add actual edit functionality here
  }

  function deleteTransporter(id) {
    if (confirm(`Are you sure you want to delete Transporter with ID: ${id}?`)) {
      alert(`Deleted Transporter with ID: ${id}`);
      // Add actual delete functionality here
    }
  }
</script>
</body>
</html>