<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup and Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
        .form-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
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
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-btn {
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
        .form-btn:hover {
            background-color: #0056b3;
        }
        .form-container .fa {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>User Signup and Login</h2>
        <form action="user_handler.php" method="POST">
            <div class="form-group">
                <label for="email"><i class="fa fa-envelope"></i>Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fa fa-lock"></i>Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="user_type"><i class="fa fa-user"></i>User Type</label>
                <select id="user_type" name="user_type" required>
                    <option value="transporter">Transporter</option>
                    <option value="cargo_owner">Cargo Owner</option>
                </select>
            </div>
            <button type="submit" name="action" value="signup" class="form-btn">Sign Up</button>
            <button type="submit" name="action" value="login" class="form-btn mt-2">Login</button>
        </form>
    </div>
</body>
</html>