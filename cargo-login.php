<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php'; // Assuming $conn is mysqli

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT cargo_owner_id, password, email, cargo_owner_name FROM cargo_owners WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;        // Universal flag
            $_SESSION['user_id'] = $user['cargo_owner_id']; // Store their ID
            $_SESSION['user_type'] = 'cargo_owner'; // <<< ADD THIS LINE
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['cargo_owner_name']; // Already there, good!

            $_SESSION['last_activity'] = time();
            $_SESSION['expire_after'] = 300;

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
if (isset($stmt)) $stmt->close();
if (isset($conn)) $conn->close();
?>

