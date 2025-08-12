<?php

require_once 'db.php'; 

header('Content-Type: application/json');
function getCount($conn, $table) {
    $query = "SELECT COUNT(*) as count FROM $table";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    return 0;
}

$cargoOwnersCount = getCount($conn, 'cargo_owners');
$transportersCount = getCount($conn, 'transporters');
$ordersCount = getCount($conn, 'jobs'); 
$response = [
    'cargoOwnersCount' => $cargoOwnersCount,
    'transportersCount' => $transportersCount,
    'ordersCount' => $ordersCount,
    'timestamp' => date('Y-m-d H:i:s')
];
$conn->close();
echo json_encode($response);
?>