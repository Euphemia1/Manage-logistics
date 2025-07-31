<?php
session_start();
$type = $_GET['type'] ?? '';
$validTypes = ['cargo_owners', 'transporters'];
$isValidType = in_array($type, $validTypes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Nyamula Logistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide-icons.js" rel="stylesheet">
    <script src="https://unpkg.com/lucide-icons" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #e0ffe0 0%, #f0fff0 100%); 
            color: #333;
        }
        .container {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(0, 128, 0, 0.1);
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            color: #2e7d32; 
            margin-bottom: 2rem;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .message {
            background-color: #e8f5e9; 
            color: #2e7d32; 
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        .error-message {
            background-color: #ffebee; 
            color: #c62828;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.75rem;
            color: #555;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        input[type="email"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="email"]:focus {
            outline: none;
            border-color: #4CAF50; 
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.2); 
        }
        .btn-primary {
            background-color: #4CAF50; 
            color: white;
            padding: 0.9rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
        }
        .btn-primary:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 128, 0, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(0, 128, 0, 0.2);
        }
        .user-type-selection {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-top: 1.5rem;
        }
        .user-type-btn {
            padding: 1.25rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        .user-type-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .cargo-owner-btn {
            background-color: #28a745; 
            color: white;
        }
        .cargo-owner-btn:hover {
            background-color: #218838;
        }
        .transporter-btn {
            background-color: #6c757d; 
            color: white;
        }
        .transporter-btn:hover {
            background-color: #5a6268;
        }
        .switch-type-link {
            color: #2e7d32; 
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .switch-type-link:hover {
            color: #1b5e20; 
            text-decoration: underline;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="container">
        <div class="flex items-center justify-center mb-6">
            <i data-lucide="key-round" class="w-8 h-8 mr-3 text-green-600"></i>
            <h2>Forgot Password</h2>
        </div>
        
        <?php
        if (isset($_SESSION['reset_message'])) {
            echo '<p class="message"><i data-lucide="check-circle" class="mr-2 w-5 h-5"></i>' . $_SESSION['reset_message'] . '</p>';
            unset($_SESSION['reset_message']);
        }
        ?>

        <?php if (!$isValidType): ?>
            <p class="text-center text-gray-700 mb-4 text-lg">Please select your account type:</p>
            <div class="user-type-selection">
                <a href="?type=cargo_owners" class="user-type-btn cargo-owner-btn">
                    <i data-lucide="package" class="inline mr-2 w-5 h-5"></i>Cargo Owner
                </a>
                <a href="?type=transporters" class="user-type-btn transporter-btn">
                    <i data-lucide="truck" class="inline mr-2 w-5 h-5"></i>Transporter
                </a>
            </div>
        <?php else: ?>
            <form action="forgot-password.php" method="POST">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <div class="form-group">
                    <label for="email">
                        <i data-lucide="mail" class="inline mr-2 w-4 h-4 text-green-700"></i>Email Address:
                    </label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn-primary">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    Send Reset Link
                </button>
            </form>
            <p style="margin-top: 1.5rem; text-align: center; font-size: 0.9rem; color: #666;">
                <small>Not a <?php echo $type === 'cargo_owners' ? 'Cargo Owner' : 'Transporter'; ?>? 
                <a href="?type=<?php echo $type === 'cargo_owners' ? 'transporters' : 'cargo_owners'; ?>" class="switch-type-link">
                    Click here to switch
                </a>
                </small>
            </p>
        <?php endif; ?>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>