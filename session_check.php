<?php
session_start();
header('Content-Type: application/json');

$response = ['expired' => false, 'redirect_url' => ''];

// Set session timeout to 2 minutes (120 seconds)
$session_timeout = 120;

// Check if user is logged in and determine user type
$user_type = '';
$redirect_url = '';

if (isset($_SESSION['admin_id'])) {
    $user_type = 'admin';
    $redirect_url = 'admin-login.php';
} elseif (isset($_SESSION['user_name']) && isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'cargo_owner') {
        $user_type = 'cargo_owner';
        $redirect_url = 'cargo-owner-login.php';
    } elseif ($_SESSION['user_type'] === 'transporter') {
        $user_type = 'transporter';
        $redirect_url = 'transporter-login.php';
    }
} elseif (isset($_SESSION['user_name'])) {
    // For backward compatibility, check session variables to determine type
    if (isset($_SESSION['cargo_owner_id'])) {
        $user_type = 'cargo_owner';
        $redirect_url = 'cargo-owner-login.php';
    } else {
        $user_type = 'transporter';
        $redirect_url = 'transporter-login.php';
    }
}

// If no valid session found, mark as expired
if (empty($user_type)) {
    $response['expired'] = true;
    $response['redirect_url'] = 'index.php';
    session_unset();
    session_destroy();
} else {
    // Check if session has timed out
    if (!isset($_SESSION['last_activity']) || 
        (time() - $_SESSION['last_activity'] > $session_timeout)) {
        $response['expired'] = true;
        $response['redirect_url'] = $redirect_url;
        session_unset();
        session_destroy();
    } else {
        // Update last activity time
        $_SESSION['last_activity'] = time();
    }
}

echo json_encode($response);
?>

