<?php
require 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $admin_username = trim($_POST['admin_username']);
    $admin_password = trim($_POST['admin_password']);

  
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $stmt->store_result();

  
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        
        if (password_verify($admin_password, $hashed_password)) {
           
            session_start();
            $_SESSION['admin_username'] = $admin_username;

          
            header("Location: admin-dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No admin found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>