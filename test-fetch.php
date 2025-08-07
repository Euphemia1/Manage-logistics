<?php
// Test fetch-cargo.php functionality
session_start();

// Simulate a logged-in user (replace with actual user ID from your database)
$_SESSION['user_id'] = 3; // Change this to a real user ID
$_SESSION['user_name'] = 'Euphemia Chikungulu';

echo "Testing fetch-cargo.php...\n";
echo "Session user_id: " . ($_SESSION['user_id'] ?? 'Not set') . "\n";
echo "Session user_name: " . ($_SESSION['user_name'] ?? 'Not set') . "\n\n";

// Include the fetch-cargo.php file
ob_start();
include 'fetch-cargo.php';
$output = ob_get_clean();

echo "Output from fetch-cargo.php:\n";
echo $output;
?>
