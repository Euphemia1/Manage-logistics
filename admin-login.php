<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Nyamula Logistics</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background-color: #fff;
      padding: 40px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      border-radius: 10px;
      width: 400px;
      text-align: center;
    }
    .login-container h2 {
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .btn {
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      width: 100%;
      border-radius: 5px;
      font-size: 1em;
    }
    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>
    <form onsubmit="window.location.href='admin-dashboard.php'; return false;">
      <div class="form-group">
        <label for="username">Username</label>
        <input id="username" name="username" placeholder="Enter your username" type="text"/>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input id="password" name="password" placeholder="Enter your password" type="password"/>
      </div>
      <button class="btn" type="submit">Login</button>
    </form>
  </div>
</body>
</html>
