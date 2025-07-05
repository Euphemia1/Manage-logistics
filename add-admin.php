<?php
// admin_register.php
header('Content-Type: application/json');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'roots');
define('DB_PASS', '');
define('DB_NAME', 'logistics');
define('ADMIN_SECRET_KEY', 'your-secret-key-here'); // Change this to a strong secret key

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['adminKey'])) {
            throw new Exception('All fields are required');
        }
        
        if ($data['adminKey'] !== ADMIN_SECRET_KEY) {
            throw new Exception('Invalid admin key');
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }
        
        // Connect to database
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        
        // Check if admin table exists, create if not
        // $pdo->exec("
        //     CREATE TABLE IF NOT EXISTS admin (git
        //         id INT AUTO_INCREMENT PRIMARY KEY,
        //         username VARCHAR(50) NOT NULL UNIQUE,
        //         password VARCHAR(255) NOT NULL,
        //         email VARCHAR(100) NOT NULL UNIQUE,
        //         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        //     )
        // ");
        
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
        $stmt->execute([$data['username'], $data['email']]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception('Username or email already exists');
        }
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        
        // Insert new admin
        $stmt = $pdo->prepare("
            INSERT INTO admins (username, password, email) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$data['username'], $hashedPassword, $data['email']]);
        
        // Success response
        echo json_encode([
            'success' => true,
            'message' => 'Admin registered successfully',
            'adminId' => $pdo->lastInsertId()
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>