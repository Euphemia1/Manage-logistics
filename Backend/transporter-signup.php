<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "logistics";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO transporters (transporter_name, email, phone_number, password, company) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $transporter_name, $email, $phone_number, $hashed_password, $company);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['transporter_name'], $_POST['email'], $_POST['phone_number'], $_POST['password'], $_POST['company'])) {
        // Set parameters
        $transporter_name = trim($_POST['transporter_name']);
        $email = trim($_POST['email']);
        $phone_number = trim($_POST['phone_number']);
        $password = $_POST['password'];
        $company = trim($_POST['company']);

        // Check if transporter_name is empty
        if (empty($transporter_name)) {
            die("Error: transporter_name cannot be empty.");
        }

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Execute query
        if ($stmt->execute()) {
            // Redirect to the login page after successful signup
            header("Location: ../Frontend/transporter-login.php");
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
    }
    ?>