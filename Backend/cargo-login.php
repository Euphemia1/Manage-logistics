<?php
session_start();
require_once 'db.php'; // Include your database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query to check credentials
    $stmt = $conn->prepare("SELECT * FROM cargo_owners WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password (assuming you are using password hashing)
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_name'] = $user['name']; // Assuming you have a 'name' field
            $_SESSION['user_email'] = $user['email']; // Store email if needed
            header("Location: ../Frontend/cargo-dashboard.php"); // Redirect to the cargo dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
}
$conn->close();
?>