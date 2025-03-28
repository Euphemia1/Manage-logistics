<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Cargo Owner Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #333;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: #0056b3;
        }
        .login-container .fa {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Cargo Owner Login</h2>
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
            unset($_SESSION['login_error']);
        }
        ?>


        <form id="loginForm" action="../Backend/cargo-login.php" method="POST">

            <div class="form-group">
                <label for="email"><i class="fa fa-envelope"></i>Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fa fa-lock"></i>Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" id="loginBtn" class="login-btn">Login</button>
        </form>
        <p class="mt-3 text-center">
            <a href="forgot-password.php?type=cargo_owner" class="text-blue-500 hover:underline">Forgot Password?</a>
        </p>
    </div>
    <div class="text-center mt-3">
        <!-- <p class="mt-3 text-center">
            <a href="forgot-password.php?type=cargo_owner" class="text-blue-500 hover:underline">Forgot Password?</a>
        </p> -->
    </div>
</body>
</html>

