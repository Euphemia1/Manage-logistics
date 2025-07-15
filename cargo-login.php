<?php
session_start(); // <<< Ensure this is the first line
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT cargo_owner_id, password, email, cargo_owner_name FROM cargo_owners WHERE email = ?"; // Select necessary columns
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set consistent session variables for all logged-in users
            $_SESSION['logged_in'] = true;        // Universal flag for any logged-in user
            $_SESSION['user_id'] = $user['cargo_owner_id']; // Store their ID
            $_SESSION['user_type'] = 'cargo_owner'; // <<< IMPORTANT: Define the user type
            $_SESSION['user_email'] = $user['email']; // Still useful for cargo owner specific pages
            $_SESSION['user_name'] = $user['cargo_owner_name']; // Still useful for cargo owner specific pages

            // Your session timeout logic (good addition!)
            $_SESSION['last_activity'] = time(); // Track activity time
            $_SESSION['expire_after'] = 300; // 5 minutes (adjust as needed)

            header("Location: cargo-dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }

    if (isset($error)) {
        $_SESSION['login_error'] = $error;
        header("Location: cargo-owner-login.php");
        exit();
    }
}
// Close outside the if block, but only if they are not needed later in the script
if (isset($stmt)) {
    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
}
?>