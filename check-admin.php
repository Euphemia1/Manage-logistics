<?php
require 'db.php';
try {
    $query = "SELECT * FROM admins";
    $result = $conn->query($query);
    
    echo "<h2>Admin Records in Database:</h2>";
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th style='padding: 10px;'>ID</th>";
        echo "<th style='padding: 10px;'>Username</th>";
        echo "<th style='padding: 10px;'>Password Hash</th>";
        echo "<th style='padding: 10px;'>Created</th>";
        echo "</tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td style='padding: 10px;'><strong>" . htmlspecialchars($row['username']) . "</strong></td>";
            echo "<td style='padding: 10px;'>" . substr($row['password'], 0, 20) . "...</td>";
            echo "<td style='padding: 10px;'>" . (isset($row['created_at']) ? $row['created_at'] : 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>❌ No admin records found in the database!</p>";
        echo "<p>The admins table might be empty. You need to create an admin account first.</p>";
        echo "<hr>";
        echo "<h3>Create Default Admin Account:</h3>";
        echo "<form method='POST'>";
        echo "<input type='text' name='username' placeholder='Username' value='admin' required style='padding: 5px; margin: 5px;'><br>";
        echo "<input type='password' name='password' placeholder='Password' value='admin123' required style='padding: 5px; margin: 5px;'><br>";
        echo "<button type='submit' name='create_admin' style='padding: 8px 15px; background: #28a745; color: white; border: none; margin: 5px;'>Create Admin</button>";
        echo "</form>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>The admins table might not exist. Creating it now...</p>";
    $createTable = "
        CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    if ($conn->query($createTable)) {
        echo "<p style='color: green;'>✅ Admins table created successfully!</p>";
        echo "<p>Now you can create an admin account:</p>";
        
        echo "<form method='POST'>";
        echo "<input type='text' name='username' placeholder='Username' value='admin' required style='padding: 5px; margin: 5px;'><br>";
        echo "<input type='password' name='password' placeholder='Password' value='admin123' required style='padding: 5px; margin: 5px;'><br>";
        echo "<button type='submit' name='create_admin' style='padding: 8px 15px; background: #28a745; color: white; border: none; margin: 5px;'>Create Admin</button>";
        echo "</form>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create admins table: " . $conn->error . "</p>";
    }
}
if (isset($_POST['create_admin'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);
        
        if ($stmt->execute()) {
            echo "<p style='color: green;'>✅ Admin account created successfully!</p>";
            echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
            echo "<p><strong>Password:</strong> " . htmlspecialchars($password) . "</p>";
            echo "<p><a href='admin-login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a></p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to create admin: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Database Check - Nyamula Logistics</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        table { width: 100%; }
        th, td { text-align: left; }
        .back-link { margin-top: 20px; }
        .back-link a { background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Admin Database Diagnostic</h1>
    <div class="back-link">
        <a href="admin-login.php">← Back to Admin Login</a>
    </div>
</body>
</html>
