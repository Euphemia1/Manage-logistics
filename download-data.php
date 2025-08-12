<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

try {

    $stmt = $conn->prepare("
        SELECT 
            id,
            item as cargo_type,
            pickup as origin,
            dropoff as destination,
            weight,
            phone,
            start_date as pickup_date,
            status,
            created_at,
            cargo_owner as owner_name
        FROM jobs 
        WHERE cargo_owner_id = ? 
        ORDER BY created_at DESC
    ");
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cargo_data = [];
    while ($row = $result->fetch_assoc()) {
        $cargo_data[] = $row;
    }
    $export_data = [
        'user_info' => [
            'user_id' => $user_id,
            'name' => $user_name,
            'export_date' => date('Y-m-d H:i:s'),
            'platform' => 'Nyamula Logistics'
        ],
        'cargo_posts' => $cargo_data,
        'summary' => [
            'total_cargo_posts' => count($cargo_data),
            'available_cargo' => array_filter($cargo_data, function($item) { return $item['status'] === 'Available'; }),
            'in_transit_cargo' => array_filter($cargo_data, function($item) { return $item['status'] === 'In Transit'; }),
            'delivered_cargo' => array_filter($cargo_data, function($item) { return $item['status'] === 'Delivered'; })
        ]
    ];

    $filename = 'nyamula_logistics_data_' . $user_name . '_' . date('Y-m-d') . '.json';
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename); 
    
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    
    echo json_encode($export_data, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Export failed: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
