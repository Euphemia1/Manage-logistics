<?php
session_start();
require 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT id, password FROM admins WHERE username = ?'); // Select 'id' and 'password' only
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        // Set consistent session variables for all logged-in users
        $_SESSION['logged_in'] = true;       // Universal flag for any logged-in user
        $_SESSION['user_id'] = $admin['id']; // Store their ID
        $_SESSION['user_type'] = 'admin';    // <<< IMPORTANT: Define the user type
        // Optional: $_SESSION['username'] = $username; if you want to store it

        header('Location: admin-dashboard.php'); // Redirect to admin dashboard
        exit();
    } else {
        // You might want to use $_SESSION['login_error'] here for consistent error display
        echo "Invalid credentials!"; // Or redirect with error
    }
}

// Close outside the if block, but only if they are not needed later in the script
// If this script only handles login POST, then it's fine.
if (isset($stmt)) { // Check if $stmt was prepared before trying to close
    $stmt->close();
}
if (isset($conn)) { // Check if $conn exists before trying to close
    $conn->close();
}
?>