<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    echo "<h2>Login Debug Information</h2>";
    echo "<p><strong>Entered Username:</strong> '" . htmlspecialchars($username) . "'</p>";
    echo "<p><strong>Entered Password:</strong> '" . htmlspecialchars($password) . "'</p>";
    $stmt = $conn->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    
    if ($admin) {
        echo "<p style='color: green;'>✅ User found in database!</p>";
        echo "<p><strong>Database Username:</strong> '" . htmlspecialchars($admin['username']) . "'</p>";
        echo "<p><strong>Database Password Hash:</strong> " . substr($admin['password'], 0, 30) . "...</p>";
        if (password_verify($password, $admin['password'])) {
            echo "<p style='color: green;'>✅ Password verification successful!</p>";
            echo "<p>Login should work. Redirecting to dashboard...</p>";
            
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'admin-dashboard.php';
                }, 3000);
            </script>";
        } else {
            echo "<p style='color: red;'>❌ Password verification failed!</p>";
            echo "<p>The password you entered doesn't match the stored hash.</p>";
            $testHash = password_hash($password, PASSWORD_DEFAULT);
            echo "<p><strong>Hash for entered password would be:</strong> " . substr($testHash, 0, 30) . "...</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ No user found with username: '" . htmlspecialchars($username) . "'</p>";

        $allUsers = $conn->query("SELECT username FROM admins");
        if ($allUsers && $allUsers->num_rows > 0) {
            echo "<p><strong>Available usernames in database:</strong></p>";
            echo "<ul>";
            while ($user = $allUsers->fetch_assoc()) {
                echo "<li>'" . htmlspecialchars($user['username']) . "'</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No admin users found in the database at all!</p>";
        }
    }
    
    $stmt->close();
    echo "<hr>";
    echo "<p><a href='admin-login.php'>← Try Again</a> | <a href='check-admin.php'>Check Admin Database</a></p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login Debug - Nyamula Logistics</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Admin Login Debug</h1>
    
    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
        <p>This page helps debug admin login issues. Use the form below to test login:</p>
        
        <form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <div style="margin-bottom: 15px;">
                <label>Username:</label><br>
                <input type="text" name="username" required style="width: 200px; padding: 8px; margin-top: 5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Password:</label><br>
                <input type="password" name="password" required style="width: 200px; padding: 8px; margin-top: 5px;">
            </div>
            <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 3px;">Debug Login</button>
        </form>
        
        <p><a href="check-admin.php">Check Admin Database</a> | <a href="admin-login.php">Go to Normal Login</a></p>
    <?php endif; ?>
</body>
</html>
