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
echo "Here";

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO cargo_owners (cargo_owner_name, email, phone_number, password, company) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $cargo_owner_name, $email, $phone_number, $hashed_password, $company);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set parameters
    $cargo_owner_name = $_POST['cargo_owner_name'];
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
        header("Location: ../Frontend/cargo-owner-login.php");
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