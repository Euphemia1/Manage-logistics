<html>
 <head>
  <title>
   Cargo Owner Signup - Nyamula Logistics
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com">
  </script>
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
        .signup-container {
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 10px;
            width: 500px;
            text-align: center;
        }
        .signup-container h2 {
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
        .login-link {
            margin-top: 20px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
  </style>
 </head>
 <body>

  <div class="signup-container">
   <h2>
    Cargo Owner Signup </h2>
    <form action="../Backend/cargo-owner-signup.php"  method="POST">
        <div class="form-group">
         <label for="cargo_owner_name">
          Full Name
         </label>
         <input id="cargo_owner_name" name="cargo_owner_name" placeholder="Enter your full name" type="text"/>
        </div>
        <div class="form-group">
         <label for="email">
          Email
         </label>
         <input id="email" name="email" placeholder="Enter your email" type="email"/>
        </div>
        <div class="form-group">
         <label for="password">Password</label>
         <input id="password" name="password" placeholder="Enter your password" type="password"/>
        </div>
        <div class="form-group">
         <label for="company">  Company Name </label>
         <input id="company" name="company" placeholder="Enter your company name" type="text"/>
        </div>
        <div class="form-group">
         <label for="phone_number">Phone Number</label>
         <input id="phone_number" name="phone_number" placeholder="Enter your phone number" type="text"/>
        </div>
        <button class="btn" type="submit"> Signup</button>
       </form>
   <div class="login-link">
    Already have an account?
    <a href="cargo-owner-login.html">Login here</a>
   </div>
  </div>
  <script>
    function redirectToLogin(event) {
        event.preventDefault(); // Prevent the form from submitting
        window.location.href = "cargo-owner-login.html"; // Redirect to the login page
    }
</script>
 </body>
</html>
