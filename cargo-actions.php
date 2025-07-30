<?php
session_start();
header('Content-Type: application/json');

$config = [
    'host' => 'localhost',
    'dbname' => 'logistics', 
    'username' => 'root',
    'password' => ''
];

try {
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
    $db = new PDO($dsn, $config['username'], $config['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        if ($action === 'details') {
            getCargoDetails($db);
        }
        break;
        
    case 'PUT':
        if ($action === 'update') {
            updateCargo($db);
        }
        break;
        
    case 'DELETE':
        if ($action === 'delete') {
            deleteCargo($db);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

function getCargoDetails($db) {
    $id = $_GET['id'] ?? 0;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Cargo ID required']);
        return;
    }
    
    try {
        $stmt = $db->prepare("
            SELECT 
                id,
                item as cargo_type,
                pickup as pickup_location,
                dropoff as delivery_location,
                weight,
                dimensions,
                transport_type,
                phone,
                instructions,
                start_date as pickup_date,
                price,
                state as status,
                created_at as posted_date
            FROM jobs 
            WHERE id = :id
        ");
        
        $stmt->execute([':id' => $id]);
        $cargo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cargo) {
            echo json_encode(['success' => true, 'data' => $cargo]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cargo not found']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function updateCargo($db) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Cargo ID required']);
        return;
    }
    
    
    try {
        $stmt = $db->prepare("
            UPDATE jobs SET 
                item = :cargo_type,
                pickup = :pickup_location,
                dropoff = :delivery_location,
                weight = :weight,
                dimensions = :dimensions,
                transport_type = :transport_type,
                phone = :phone,
                instructions = :instructions,
                start_date = :pickup_date,
                price = :price,
                state = :status
            WHERE id = :id
        ");
        
        $result = $stmt->execute([
            ':id' => $id,
            ':cargo_type' => $input['cargo_type'] ?? '',
            ':pickup_location' => $input['pickup_location'] ?? '',
            ':delivery_location' => $input['delivery_location'] ?? '',
            ':weight' => $input['weight'] ?? '',
            ':dimensions' => $input['dimensions'] ?? '',
            ':transport_type' => $input['transport_type'] ?? '',
            ':phone' => $input['phone'] ?? '',
            ':instructions' => $input['instructions'] ?? '',
            ':pickup_date' => $input['pickup_date'] ?? '',
            ':price' => $input['price'] ?? '',
            ':status' => $input['status'] ?? 'pending'
        ]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Cargo updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cargo']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

function deleteCargo($db) {
    $id = $_GET['id'] ?? 0;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Cargo ID required']);
        return;
    }
    
    try {
        $stmt = $db->prepare("DELETE FROM jobs WHERE id = :id");
        $result = $stmt->execute([':id' => $id]);
        
        if ($result && $stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Cargo deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cargo not found or already deleted']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
