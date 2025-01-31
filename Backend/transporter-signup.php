<?php
$servername = "localhost"; 
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "logistics"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO transporters (transporter_id,transporter_name, email, phone_number, password, company) VALUES (?,?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $transporter_id, $transporter_name, $email,$phone_number, $hashed_password, $company);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set parameters
    $transporter_id = $_POST['transporter_id'];
    $transporter_name = $_POST['transporter_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $company = $_POST['company'];
//before inserting check if email exists
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Execute query
    if ($stmt->execute()) {
        // Redirect to the login page after successful signup
        header("Location: ../Frontend/transporter-login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else{
    echo $_SERVER['REQUEST_METHOD'];
}

// Close connections
$stmt->close();
$conn->close();
?>