<?php
// post-cargo.php - TEMPORARY DEBUGGING VERSION

// Make sure no characters, spaces, or BOM are before this line.
error_reporting(E_ALL);
ini_set('display_errors', 1); // Show errors directly for now (REMOVE FOR PRODUCTION)

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'post-cargo.php was reached. Method: ' . $_SERVER['REQUEST_METHOD']];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response['message'] = 'post-cargo.php reached via POST. Data received: ' . print_r($_POST, true) . '. Session: ' . print_r($_SESSION, true);
    // You can add a very simple success true here for testing if POST is working
    // $response['success'] = true;
} else {
    $response['message'] = 'post-cargo.php reached, but NOT via POST. Method: ' . $_SERVER['REQUEST_METHOD'];
}

echo json_encode($response);
exit(); // Important to stop further execution if anything else was in the file

?>