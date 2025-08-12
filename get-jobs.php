<?php
require_once 'db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = array();

try {
  
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $pickup = isset($_GET['pickup']) ? $_GET['pickup'] : '';
    $dropoff = isset($_GET['dropoff']) ? $_GET['dropoff'] : '';
    $poster_type = isset($_GET['poster_type']) ? $_GET['poster_type'] : '';
    $cargo_owner = isset($_GET['cargo_owner']) ? $_GET['cargo_owner'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    $where_conditions = array();
    $params = array();
    $types = '';
    
    if (!empty($status)) {
        $where_conditions[] = "status = ?";
        $params[] = $status;
        $types .= 's';
    }
    
    if (!empty($pickup)) {
        $where_conditions[] = "pickup LIKE ?";
        $params[] = '%' . $pickup . '%';
        $types .= 's';
    }
    
    if (!empty($dropoff)) {
        $where_conditions[] = "dropoff LIKE ?";
        $params[] = '%' . $dropoff . '%';
        $types .= 's';
    }
    if (!empty($cargo_owner)) {
        $where_conditions[] = "cargo_owner LIKE ?";
        $params[] = '%' . $cargo_owner . '%';
        $types .= 's';
    }
    
    if (!empty($search)) {
        $where_conditions[] = "(item LIKE ? OR pickup LIKE ? OR dropoff LIKE ? OR cargo_owner LIKE ?)";
        $search_term = '%' . $search . '%';
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
        $types .= 'ssss';
    }
    
    $where_clause = '';
    if (!empty($where_conditions)) {
        $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
    }
    
    $count_sql = "SELECT COUNT(*) as total FROM jobs $where_clause";
    
    if (!empty($params)) {
        $count_stmt = $conn->prepare($count_sql);
        if ($count_stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $count_stmt->bind_param($types, ...$params);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total_records = $count_result->fetch_assoc()['total'];
        $count_stmt->close();
    } else {
        $count_result = $conn->query($count_sql);
        if ($count_result === false) {
            throw new Exception("Count query failed: " . $conn->error);
        }
        $total_records = $count_result->fetch_assoc()['total'];
    }
    
    $sql = "SELECT * FROM jobs $where_clause ORDER BY created_at DESC LIMIT ? OFFSET ?";
    
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }
    if ($result->num_rows > 0) {
        $jobs = array();
        
        while($row = $result->fetch_assoc()) {
           
            $job = array(
                'id' => (int)$row['id'],
                'item' => htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8'),
                'pickup' => htmlspecialchars($row['pickup'], ENT_QUOTES, 'UTF-8'),
                'dropoff' => htmlspecialchars($row['dropoff'], ENT_QUOTES, 'UTF-8'),
                'weight' => htmlspecialchars($row['weight'], ENT_QUOTES, 'UTF-8'),
                'status' => htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'),
                'start_date' => $row['start_date'],
                'phone' => htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'),
                'created_at' => $row['created_at'],
                'cargo_owner' => htmlspecialchars($row['cargo_owner'], ENT_QUOTES, 'UTF-8'),
                'cargo_owner_id' => (int)$row['cargo_owner_id']
            );
            
            $jobs[] = $job;
        }
        
        $response['success'] = true;
        $response['message'] = 'Jobs retrieved successfully';
        $response['count'] = count($jobs);
        $response['total_records'] = (int)$total_records;
        $response['limit'] = $limit;
        $response['offset'] = $offset;
        $response['has_more'] = ($offset + $limit) < $total_records;
        $response['data'] = $jobs;
        
    } else {
        $response['success'] = true;
        $response['message'] = 'No jobs found';
        $response['count'] = 0;
        $response['total_records'] = (int)$total_records;
        $response['limit'] = $limit;
        $response['offset'] = $offset;
        $response['has_more'] = false;
        $response['data'] = array();
    }

    $stmt->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
    $response['count'] = 0;
    $response['total_records'] = 0;
    $response['data'] = array();
    
    error_log("get-jobs.php Error: " . $e->getMessage());
    
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit();
?>
