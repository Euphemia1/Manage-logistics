<?php
session_start();

// Determine user type before destroying session
$redirect_url = 'index.php';

if (isset($_SESSION['admin_id'])) {
    $redirect_url = 'admin-login.php';
} elseif (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'cargo_owner') {
        $redirect_url = 'cargo-owner-login.php';
    } elseif ($_SESSION['user_type'] === 'transporter') {
        $redirect_url = 'transporter-login.php';
    }
} elseif (isset($_SESSION['user_name'])) {
    // For backward compatibility
    if (isset($_SESSION['cargo_owner_id'])) {
        $redirect_url = 'cargo-owner-login.php';
    } else {
        $redirect_url = 'transporter-login.php';
    }
}

// Destroy the session
session_unset();
session_destroy();

// Handle AJAX requests
if (isset($_SERVER['HTTP_CONTENT_TYPE']) && $_SERVER['HTTP_CONTENT_TYPE'] === 'application/json') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'logged_out',
        'redirect_url' => $redirect_url
    ]);
    exit();
}

// Regular redirect for non-AJAX requests
header("Location: " . $redirect_url);
exit();
?>
