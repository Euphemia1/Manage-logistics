<?php
$host = 'localhost';
$dbname = 'logistics';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking jobs table:\n";
    $stmt = $pdo->query('SELECT cargo_owner_id, cargo_owner, COUNT(*) as count FROM jobs GROUP BY cargo_owner_id ORDER BY cargo_owner_id');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "User ID: " . $row['cargo_owner_id'] . ", Name: " . $row['cargo_owner'] . ", Jobs: " . $row['count'] . "\n";
    }
    
    echo "\nRecent jobs:\n";
    $stmt = $pdo->query('SELECT id, cargo_owner_id, cargo_owner, item, pickup, dropoff FROM jobs ORDER BY created_at DESC LIMIT 5');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . ", Owner ID: " . $row['cargo_owner_id'] . ", Owner: " . $row['cargo_owner'] . ", Item: " . $row['item'] . "\n";
    }
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}
?>
