// Example validation:
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires > NOW()");
$stmt->execute([$_POST['reset_email'], $_POST['reset_token']]);
$resetRequest = $stmt->fetch();

if (!$resetRequest) {
    $_SESSION['reset_message'] = "Invalid or expired reset link.";
    header("Location: ../Frontend/forgot-password.php");
    exit();
}