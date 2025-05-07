<?php
session_start();
// Destroy the session
session_unset();
session_destroy();
echo json_encode(value: [
    'status' => 'logged_out'
]);

// Redirect to login page
header("Location: ../Frontend/index.php");
exit();
?>
