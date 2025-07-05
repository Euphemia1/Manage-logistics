<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM transporters WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['transporter_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['transporter_name'];
            header("Location: ../Frontend/transporter-dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }

    if (isset($error)) {
        $_SESSION['login_error'] = $error;
        header("Location: transporter-login.php");
        exit();
    }
}
?>
