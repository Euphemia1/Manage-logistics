<?php
require 'db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted data
    $admin_username = trim($_POST['admin_username']);
    $admin_password = trim($_POST['admin_password']);

    // Prepare and execute a query to check the admin credentials
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the admin exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($admin_password, $hashed_password)) {
            // Start a session and set session variables
            session_start();
            $_SESSION['admin_username'] = $admin_username;

            // Redirect to the admin dashboard
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