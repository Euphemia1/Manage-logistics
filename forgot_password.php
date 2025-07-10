<?php
session_start();
$type = $_GET['type'] ?? '';

// Check if type is empty or invalid
$validTypes = ['cargo_owners', 'transporters'];
$isValidType = in_array($type, $validTypes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .message {
            background-color: #e8f5e9;
            color:rgb(91, 77, 214);
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input[type="email"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .user-type-selection {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
        }
        .user-type-btn {
            padding: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        .cargo-owner-btn {
            background-color: #2196F3;
            color: white;
        }
        .cargo-owner-btn:hover {
            background-color: #0b7dda;
        }
        .transporter-btn {
            background-color: #FF9800;
            color: white;
        }
        .transporter-btn:hover {
            background-color: #e68a00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <?php
        if (isset($_SESSION['reset_message'])) {
            echo '<p class="message">' . $_SESSION['reset_message'] . '</p>';
            unset($_SESSION['reset_message']);
        }
        ?>

        <?php if (!$isValidType): ?>
            <!-- Show user type selection if type is empty or invalid -->
            <p>Please select your account type:</p>
            <div class="user-type-selection">
                <a href="?type=cargo_owners" class="user-type-btn cargo-owner-btn">Cargo Owner</a>
                <a href="?type=transporters" class="user-type-btn transporter-btn">Transporter</a>
            </div>
        <?php else: ?>
            <!-- Show email form if type is valid -->
            <form action="forgot-password.php" method="POST">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
            <p style="margin-top: 1rem; text-align: center;">
                <small>Not a <?php echo $type === 'cargo_owners' ? 'Cargo Owner' : 'Transporter'; ?>? 
                <a href="?type=<?php echo $type === 'cargo_owners' ? 'transporters' : 'cargo_owners'; ?>">
                    Click here
                </a>
                </small>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>