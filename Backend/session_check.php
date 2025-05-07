<?php
session_start();
header('Content-Type: application/json');

$response = ['expired' => false];

if (!isset($_SESSION['last_activity']) || 
    (time() - $_SESSION['last_activity'] > $_SESSION['expire_after'])) {
    $response['expired'] = true;
    session_unset();
    session_destroy();
}

echo json_encode($response);
?>
