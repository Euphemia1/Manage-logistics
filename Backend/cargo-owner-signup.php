<?php
// Database connection parameters
$servername = "localhost"; 
$username = "your_username"; // Your database username
$password = "your_password"; // Your database password
$dbname = "logistics"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO cargo_owners (cargo_owner_name, email, phone_number, password, company) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $cargo_owner_name, $email, $phone_number, $password, $company);

// Set parameters and execute
$cargo_owner_name = $_POST['cargo_owner_name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
$company = $_POST['company'];

if ($stmt->execute()) {
    // Redirect to the login page after successful signup
    header("Location: cargo-owner-login.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>