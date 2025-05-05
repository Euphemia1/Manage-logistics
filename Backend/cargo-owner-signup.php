<?php
require 'db.php'; 

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
$stmt = $conn->prepare("INSERT INTO cargo_owners (cargo_owner_name, email, phone_number, password, company) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $cargo_owner_name, $email, $phone_number, $hashed_password, $company);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['cargo_owner_name'], $_POST['email'], $_POST['phone_number'], $_POST['password'], $_POST['company'])) {
        // Set parameters
        $cargo_owner_name = trim($_POST['cargo_owner_name']);
        $email = trim($_POST['email']);
        $phone_number = trim($_POST['phone_number']);
        $password = $_POST['password'];
        $company = trim($_POST['company']);

        // Check if cargo_owner_name is empty
        if (empty($cargo_owner_name)) {
            die("Error: cargo_owner_name cannot be empty.");
        }

        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Execute query
        if ($stmt->execute()) {
            // Prepare the notification data for Formspree
            $notificationData = [
                'name' => 'Nyamula Logistics System',
                '_replyto' => $email, // This will be the new user's email
                '_subject' => 'New Cargo Owner Registration - ' . $company,
                'message' => "A new cargo owner has registered with the following details:\n\n" .
                             "Full Name: $cargo_owner_name\n" .
                             "Email: $email\n" .
                             "Company: $company\n" .
                             "Phone Number: $phone_number\n\n" .
                             "This is an automated notification from Nyamula Logistics System.",
                '_recipient' => 'admin@nyamula.com' // This ensures it goes to your admin
            ];
            
            // Send notification via Formspree
            $ch = curl_init('https://formspree.io/f/mzzrrzww');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($notificationData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json'
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);

            // Redirect to the login page after successful signup
            header("Location: ../Frontend/cargo-owner-login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: All fields are required.";
    }
}

// Close connections
$stmt->close();
$conn->close();
?>
