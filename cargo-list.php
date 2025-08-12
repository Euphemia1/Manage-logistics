<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
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
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $whereClause = '';
        $params = [];
        
        if (!empty($search)) {
            $whereClause = "WHERE item LIKE :search OR pickup LIKE :search OR dropoff LIKE :search";
            $params[':search'] = "%{$search}%";
        }

        $countQuery = "SELECT COUNT(*) as total FROM jobs {$whereClause}";
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $totalRecords = $countStmt->fetch()['total'];

        $query = "
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
            {$whereClause}
            ORDER BY created_at DESC 
            LIMIT :limit OFFSET :offset
        ";
        
        $stmt = $db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $cargos = $stmt->fetchAll();
    
        $response = [
            'success' => true,
            'data' => [
                'cargos' => $cargos,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => ceil($totalRecords / $limit),
                    'total_records' => $totalRecords,
                    'per_page' => $limit,
                    'has_next' => $page < ceil($totalRecords / $limit),
                    'has_prev' => $page > 1
                ]
            ],
            'message' => 'Cargos retrieved successfully'
        ];
        echo json_encode($response);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database query failed',
            'details' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
