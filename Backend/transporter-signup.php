<?php
require 'vendor/autoload.php'; // Include PHPMailer's autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
            // Send email notification to admin
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.hostinger.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'admin@nyamula.com'; // Your SMTP email
                $mail->Password   = 'P@55w07d@1#'; // Your SMTP email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('admin@nyamula.com', 'Nyamula Logistics');
                $mail->addAddress('admin@nyamula.com'); // Add admin email

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'New Transporter Signup';
                $mail->Body    = "A new transporter has signed up:<br>
                                  Name: $transporter_name<br>
                                  Email: $email<br>
                                  Phone Number: $phone_number<br>
                                  Company: $company";

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            // Redirect to the login page after successful signup
            header("Location: ../Frontend/transporter-login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo $_SERVER['REQUEST_METHOD'];
    }
}

// Close connections
$stmt->close();
$conn->close();
?>