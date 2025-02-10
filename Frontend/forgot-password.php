<?php
session_start();
$type = $_GET['type'] ?? '';
if ($type !== 'cargo_owner' && $type !== 'transporter') {
    die('Invalid user type');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
 
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
        <form action="../Backend/forgot-password.php" method="POST">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
