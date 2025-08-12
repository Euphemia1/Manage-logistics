<?php
require 'db.php';

echo "<h2>Admin Login Credentials</h2>";
echo "<p>Here are the exact usernames you need to use for login:</p>";

try {
    $query = "SELECT id, username, email FROM admins ORDER BY id";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>Available Admin Accounts:</h3>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<div style='background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #28a745;'>";
            echo "<h4>Admin #" . $row['id'] . "</h4>";
            echo "<p><strong>Username to use for login:</strong> <code style='background: #e9ecef; padding: 2px 6px; border-radius: 3px;'>" . htmlspecialchars($row['username']) . "</code></p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
            echo "<p><em>Note: You need to type the username exactly as shown above, including spaces and capital letters.</em></p>";
            echo "</div>";
        }
        echo "</div>";
        
        echo "<hr>";
        echo "<h3>Test Login Form</h3>";
        echo "<p>Try logging in with one of the usernames above:</p>";
        
        echo "<form method='POST' action='debug-admin-login.php' style='background: #f8f9fa; padding: 20px; border-radius: 8px;'>";
        echo "<div style='margin-bottom: 15px;'>";
        echo "<label>Username (copy from above):</label><br>";
        echo "<input type='text' name='username' required style='width: 300px; padding: 8px; margin-top: 5px;' placeholder='e.g., Stephen Muraga'>";
        echo "</div>";
        echo "<div style='margin-bottom: 15px;'>";
        echo "<label>Password:</label><br>";
        echo "<input type='password' name='password' required style='width: 300px; padding: 8px; margin-top: 5px;'>";
        echo "</div>";
        echo "<button type='submit' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 3px;'>Test Login</button>";
        echo "</form>";
        
    } else {
        echo "<p style='color: red;'>No admin accounts found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
echo "<hr>";
echo "<h3>Create Simple Admin Account</h3>";
echo "<p>If you want a simple username like 'admin', I can create one for you:</p>";

if (isset($_POST['create_simple_admin'])) {
    $username = 'admin';
    $password = 'admin123';
    $email = 'admin@nyamula.com';
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        
        if ($stmt->execute()) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h4>✅ Simple Admin Account Created!</h4>";
            echo "<p><strong>Username:</strong> admin</p>";
            echo "<p><strong>Password:</strong> admin123</p>";
            echo "<p><strong>Email:</strong> admin@nyamula.com</p>";
            echo "<p><a href='admin-login.php' style='background: #28a745; color: white; padding: 8px 16px; text-decoration: none; border-radius: 3px;'>Go to Login</a></p>";
            echo "</div>";
        } else {
            echo "<p style='color: red;'>Failed to create admin account: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error creating admin: " . $e->getMessage() . "</p>";
    }
}
echo "<form method='POST'>";
echo "<button type='submit' name='create_simple_admin' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 3px;' onclick='return confirm(\"Create admin account with username: admin and password: admin123?\")'>Create Simple Admin Account</button>";
echo "</form>";

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Credentials - Nyamula Logistics</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            padding: 20px; 
            max-width: 800px; 
            margin: 0 auto; 
            background: #f8f9fa;
        }
        code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .back-link { 
            margin: 20px 0; 
        }
        .back-link a { 
            background: #6c757d; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 5px; 
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h1>Admin Login Credentials</h1>
    
    <div class="back-link">
        <a href="admin-login.php">← Back to Admin Login</a>
        <a href="debug-admin-login.php">Debug Login</a>
    </div>
</body>
</html>
