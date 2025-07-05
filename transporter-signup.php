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
            // Prepare the notification data for Formspree
            $notificationData = [
                'name' => 'Nyamula Logistics System',
                '_replyto' => $email, // This will be the new transporter's email
                '_subject' => 'New Transporter Registration - ' . $company,
                'message' => "A new transporter has registered with the following details:\n\n" .
                             "Full Name: $transporter_name\n" .
                             "Email: $email\n" .
                             "Company: $company\n" .
                             "Phone Number: $phone_number\n\n" .
                             "This is an automated notification from Nyamula Logistics System.",
                '_recipient' => 'admin@nyamula.com' // Direct to admin email
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


 // Set congratulatory message in session
 $_SESSION['signup_success'] = [
    'name' => $transporter_name,
    'email' => $email,
    'type' => 'transporter'
];

            // Redirect to the login page after successful signup
            header("Location: transporter-login.php");
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

