<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logistics";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch job details if ID is provided
$job = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission for updating the job
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item'];
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $weight = $_POST['weight'];
    $state = $_POST['state'];
    $price = $_POST['price'];
    $startDate = $_POST['startDate'];
    $id = $_POST['id'];

    // Update job in the database
    $stmt = $conn->prepare("UPDATE jobs SET item=?, pickup=?, dropoff=?, weight=?, state=?, price=?, start_date=? WHERE id=?");
    $stmt->bind_param("sssssssi", $item, $pickup, $dropoff, $weight, $state, $price, $startDate, $id);

    if ($stmt->execute()) {
        // Redirect back to the job board after successful update
        header("Location: ../Frontend/job-board.php");
        exit();
    } else {
        echo json_encode(["error" => "Error: " . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
</head>
<body>
    <h1>Edit Job</h1>
    <?php if ($job): ?>
        <form method="POST" action="edit.php?id=<?php echo $job['id']; ?>">
            <input type="hidden" name="id" value="<?php echo $job['id']; ?>">
            <label>Item:</label>
            <input type="text" name="item" value="<?php echo $job['item']; ?>" required><br>
            <label>Pick up:</label>
            <input type="text" name="pickup" value="<?php echo $job['pickup']; ?>" required><br>
            <label>Drop off:</label>
            <input type="text" name="dropoff" value="<?php echo $job['dropoff']; ?>" required><br>
            <label>Weight (mt):</label>
            <input type="number" name="weight" value="<?php echo $job['weight']; ?>" required><br>
            <label>State:</label>
            <input type="text" name="state" value="<?php echo $job['state']; ?>" required><br>
            <label>Price per tn:</label>
            <input type="number" name="price" value="<?php echo $job['price']; ?>" required><br>
            <label>Job start date:</label>
            <input type="date" name="startDate" value="<?php echo $job['start_date']; ?>" required><br>
            <button type="submit">Update Job</button>
        </form>
    <?php else: ?>
        <p>Job not found.</p>
    <?php endif; ?>
</body>
</html>