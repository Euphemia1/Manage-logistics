<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: transporter-login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Transporter Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for transitions and animations */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        .hamburger {
            display: none;
        }
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }
            .sidebar {
                width: 250px;
                position: fixed;
                z-index: 1000;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-roboto">
    <!-- Sidebar -->
    <div class="sidebar bg-gradient-to-b from-blue-700 to-blue-900 text-white h-screen w-64 fixed">
        <div class="p-4">
            <h2 class="text-2xl font-bold text-center mb-6">Transporter Dashboard</h2>
            <a href="#" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-home"></i> Home</a>
            <a href="#" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-box"></i> Available Loads</a>
            <a href="job-post.php" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-box"></i> Job Board</a>
            <a href="settings.php" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-cogs"></i> Settings</a>
            <a href="index.php" class="block py-2 px-4 hover:bg-blue-600 rounded transition-colors"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Hamburger Menu for Mobile -->
    <div class="hamburger p-4 bg-blue-700 text-white fixed top-0 left-0 z-1000">
        <i class="fas fa-bars text-2xl cursor-pointer" onclick="toggleSidebar()"></i>
    </div>

    <!-- Main Content -->
    <div class="main-content ml-64 p-4 transition-all">
        <!-- Header -->
        <div class="header bg-white shadow-md rounded-lg p-6 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
            <div class="user-info flex items-center">
                <img src="https://storage.googleapis.com/a1aa/image/0DsAIzPBeAztSaTGdSP8my4sUbD41IUpY4mXEmE6km2jx0DKA.jpg" alt="Profile Picture" class="w-10 h-10 rounded-full border-2 border-blue-500">
                <span class="ml-3 text-lg font-semibold text-gray-800"><?php echo $_SESSION['user_name']; ?></span>
            </div>
        </div>

        <!-- Profile Management Section -->
       <!-- Profile Management Section -->
<div class="card bg-white shadow-md rounded-lg p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">Profile Management</h3>
    
    <!-- Display Success/Error Messages -->
    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <p><?php echo $success; ?></p>
        </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateForm()">
        <!-- Name -->
        <div class="mb-4">
            <label class="block text-gray-700">Full Name</label>
            <input type="text" name="full_name" class="w-full p-2 border rounded-lg" value="<?php echo htmlspecialchars($driver['full_name'] ?? ''); ?>">
        </div>

        <!-- Profile Picture -->
        <div class="mb-4">
            <label class="block text-gray-700">Profile Picture</label>
            <input type="file" name="profile_picture" class="w-full p-2 border rounded-lg">
        </div>

        <!-- Contact Information -->
        <div class="mb-4">
            <label class="block text-gray-700">Phone Number</label>
            <input type="text" name="phone_number" class="w-full p-2 border rounded-lg" value="<?php echo htmlspecialchars($driver['phone_number'] ?? ''); ?>">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Email Address</label>
            <input type="email" name="email" class="w-full p-2 border rounded-lg" value="<?php echo htmlspecialchars($driver['email'] ?? ''); ?>">
        </div>

        <!-- Truck Details -->
        <div class="mb-4">
            <label class="block text-gray-700">Truck Type</label>
            <select name="truck_type" class="w-full p-2 border rounded-lg">
                <option value="Flatbed" <?php echo ($driver['truck_type'] ?? '') === 'Flatbed' ? 'selected' : ''; ?>>Flatbed</option>
                <option value="Refrigerated" <?php echo ($driver['truck_type'] ?? '') === 'Refrigerated' ? 'selected' : ''; ?>>Refrigerated</option>
                <option value="Container" <?php echo ($driver['truck_type'] ?? '') === 'Container' ? 'selected' : ''; ?>>Container</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Truck Capacity (Tons)</label>
            <input type="number" name="truck_capacity" class="w-full p-2 border rounded-lg" value="<?php echo htmlspecialchars($driver['truck_capacity'] ?? ''); ?>">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">License Plate Number</label>
            <input type="text" name="license_plate" class="w-full p-2 border rounded-lg" value="<?php echo htmlspecialchars($driver['license_plate'] ?? ''); ?>">
        </div>

        <!-- Availability Status -->
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_available" class="mr-2" <?php echo ($driver['is_available'] ?? 0) ? 'checked' : ''; ?>> Mark as Available for Loads
            </label>
        </div>

        <!-- Save Button -->
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Save Changes</button>
    </form>
</div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript for toggling sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }
        
    function validateForm() {
        const fullName = document.querySelector('input[name="full_name"]').value.trim();
        const phoneNumber = document.querySelector('input[name="phone_number"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const truckType = document.querySelector('select[name="truck_type"]').value.trim();
        const truckCapacity = document.querySelector('input[name="truck_capacity"]').value.trim();
        const licensePlate = document.querySelector('input[name="license_plate"]').value.trim();

        let errors = [];

        if (!fullName) {
            errors.push("Full name is required.");
        }
        if (!phoneNumber || !/^\+?\d{10,15}$/.test(phoneNumber)) {
            errors.push("Invalid phone number.");
        }
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push("Invalid email address.");
        }
        if (!truckType) {
            errors.push("Truck type is required.");
        }
        if (!truckCapacity || isNaN(truckCapacity)) {
            errors.push("Truck capacity must be a number.");
        }
        if (!licensePlate) {
            errors.push("License plate number is required.");
        }

        if (errors.length > 0) {
            alert(errors.join("\n"));
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }

    </script>
</body>
</html>