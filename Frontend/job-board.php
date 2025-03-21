<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logistics";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Count total jobs
$countQuery = "SELECT COUNT(*) as total FROM jobs";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalJobs = $countRow['total'];

// Fetch jobs
$query = "SELECT * FROM jobs ORDER BY start_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Job Post Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --primary-dark: #388E3C;
            --primary-light: #e0f7e0;
            --secondary-color: #2196F3;
            --accent-color: #FF9800;
            --text-color: #333;
            --text-light: #666;
            --text-lighter: #888;
            --border-color: #e0e0e0;
            --background-color: #f8f9fa;
            --card-background: #fff;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --danger-color: #F44336;
            --info-color: #2196F3;
            --border-radius: 10px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .header h2 {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h2 i {
            font-size: 24px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .search-bar input {
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            flex: 1;
            min-width: 200px;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
        }

        .search-bar input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }

        .search-bar input::placeholder {
            color: var(--text-lighter);
        }

        .search-bar button {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Poppins', sans-serif;
        }

        .search-bar button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .search-bar button.clear {
            background-color: #f1f1f1;
            color: var(--text-color);
        }

        .search-bar button.clear:hover {
            background-color: #e0e0e0;
        }

        .table-container {
            background-color: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header .job-count {
            background-color: white;
            color: var(--primary-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .table-responsive {
            overflow-x: auto;
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background-color: #f9f9f9;
        }

        th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--text-color);
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
            position: sticky;
            top: 0;
            background-color: #f9f9f9;
            z-index: 10;
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tbody tr {
            transition: var(--transition);
        }

        tbody tr:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            min-width: 100px;
        }

        .status-badge.available {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .status-badge.pending {
            background-color: rgba(255, 152, 0, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(255, 152, 0, 0.2);
        }

        .status-badge.completed {
            background-color: rgba(33, 150, 243, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(33, 150, 243, 0.2);
        }

        .price-cell {
            font-weight: 600;
            color: var(--primary-color);
        }

        .location-cell {
            display: flex;
            flex-direction: column;
        }

        .location-label {
            font-size: 12px;
            color: var(--text-lighter);
            margin-bottom: 4px;
        }

        .location-value {
            font-weight: 500;
        }

        .cargo-owner-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .owner-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 14px;
        }

        .owner-info {
            display: flex;
            flex-direction: column;
        }

        .owner-name {
            font-weight: 500;
        }

        .owner-phone {
            font-size: 12px;
            color: var(--text-lighter);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #1976D2;
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background-color: #f1f1f1;
            transform: translateY(-2px);
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--primary-light);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spinner 1s ease-in-out infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 48px;
            color: var(--text-lighter);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--text-lighter);
            max-width: 400px;
            margin: 0 auto;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background-color: white;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: var(--transition);
        }

        .pagination-btn:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-color);
        }

        .pagination-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Form styling */
        .hidden {
            display: none;
        }

        .bg-white {
            background-color: white;
        }

        .rounded-lg {
            border-radius: 10px;
        }

        .shadow-md {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .p-4 {
            padding: 1rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .text-gray-800 {
            color: #2d3748;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .text-primary-600 {
            color: var(--primary-color);
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .gap-4 {
            gap: 1rem;
        }

        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .block {
            display: block;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-gray-700 {
            color: #4a5568;
        }

        .w-full {
            width: 100%;
        }

        .border {
            border-width: 1px;
        }

        .border-gray-300 {
            border-color: #e2e8f0;
        }

        .rounded-md {
            border-radius: 0.375rem;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .inset-y-0 {
            top: 0;
            bottom: 0;
        }

        .left-0 {
            left: 0;
        }

        .pl-3 {
            padding-left: 0.75rem;
        }

        .pointer-events-none {
            pointer-events: none;
        }

        .text-gray-500 {
            color: #a0aec0;
        }

        .pl-8 {
            padding-left: 2rem;
        }

        .justify-end {
            justify-content: flex-end;
        }

        .space-x-2 > * + * {
            margin-left: 0.5rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .bg-primary-600 {
            background-color: var(--primary-color);
        }

        .hover\:bg-primary-700:hover {
            background-color: var(--primary-dark);
        }

        /* Responsive Styles */
        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            
            .md\:col-span-2 {
                grid-column: span 2 / span 2;
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
            
            .lg\:col-span-4 {
                grid-column: span 4 / span 4;
            }
        }

        @media (max-width: 992px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-bar {
                width: 100%;
                margin-top: 15px;
            }
        }

        @media (max-width: 768px) {
            th, td {
                padding: 12px 15px;
            }

            .status-badge {
                min-width: 80px;
                padding: 4px 8px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                padding: 6px 12px;
                font-size: 12px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .header h2 {
                font-size: 22px;
            }

            .table-header {
                padding: 15px;
            }

            .table-header h3 {
                font-size: 16px;
            }

            th, td {
                padding: 10px;
                font-size: 13px;
            }

            .owner-avatar {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-shipping-fast"></i> Available Jobs</h2>
            <div class="search-bar">
                <input type="text" id="pickup" placeholder="Pick up location">
                <input type="text" id="dropoff" placeholder="Drop off location">
                <button onclick="searchJobs()"><i class="fas fa-search"></i> Search</button>
                <button class="clear" onclick="clearSearch()"><i class="fas fa-times"></i> Clear</button>
                <button class="btn btn-primary" onclick="toggleJobForm()"><i class="fas fa-plus"></i> Add New Job</button>
            </div>
        </div>

        <!-- Add Job Form (Hidden by default) -->
        <div id="jobFormPanel" class="bg-white rounded-lg shadow-md p-4 mb-6 hidden">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-primary-600"></i> Add New Job
            </h2>
            <form id="jobForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label for="formItem" class="block text-sm font-medium text-gray-700">Item</label>
                    <input id="formItem" name="item" type="text" placeholder="Enter item" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="formPickup" class="block text-sm font-medium text-gray-700">Pick up</label>
                    <input id="formPickup" name="pickup" type="text" placeholder="Enter pick up location" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="formDropoff" class="block text-sm font-medium text-gray-700">Drop off</label>
                    <input id="formDropoff" name="dropoff" type="text" placeholder="Enter drop off location" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="formWeight" class="block text-sm font-medium text-gray-700">Weight (mt)</label>
                    <input id="formWeight" name="weight" type="number" min="0" step="0.1" placeholder="Enter weight" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="formState" class="block text-sm font-medium text-gray-700">State</label>
                    <select id="formState" name="state" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                        <option value="Available">Available</option>
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="formPrice" class="block text-sm font-medium text-gray-700">Price per tn</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input id="formPrice" name="price" type="number" min="0" step="0.01" placeholder="Enter price" required
                            class="pl-8 w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="formStartDate" class="block text-sm font-medium text-gray-700">Job start date</label>
                    <input id="formStartDate" name="startDate" type="date" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <!-- Added Cargo Owner Name and Phone fields -->
                <div class="space-y-2">
                    <label for="formOwnerName" class="block text-sm font-medium text-gray-700">Cargo Owner Name</label>
                    <input id="formOwnerName" name="ownerName" type="text" placeholder="Enter cargo owner name" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="formOwnerPhone" class="block text-sm font-medium text-gray-700">Cargo Owner Phone</label>
                    <input id="formOwnerPhone" name="ownerPhone" type="text" placeholder="Enter cargo owner phone" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2 md:col-span-2 lg:col-span-4 flex justify-end">
                    <div class="flex space-x-2">
                        <button type="button" onclick="toggleJobForm()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                            <i class="fas fa-save mr-2"></i> Save Job
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3><i class="fas fa-list"></i> Job Listings</h3>
                <div class="job-count"></div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Pick up</th>
                            <th>Drop off</th>
                            <th>Weight (mt)</th>
                            <th>Status</th>
                            <th>Price per tn</th>
                            <th>Job start date</th>
                            <th>Cargo Owner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="jobsTableBody">
                        <!-- Sample job 1 -->
                        <tr>
                            <td></td>
                            <td>
                                <div class="location-cell">
                                    <span class="location-label">From</span>
                                    <span class="location-value"></span>
                                </div>
                            </td>
                            <td>
                                <div class="location-cell">
                                    <span class="location-label">To</span>
                                    <span class="location-value"></span>
                                </div>
                            </td>
                            <td></td>
                            <td><span class="status-badge available"></span></td>
                            <td class="price-cell"></td>
                            <td></td>
                            <td>
                                <div class="cargo-owner-cell">
                                    <div class="owner-avatar"></div>
                                    <div class="owner-info">
                                        <span class="owner-name"></span>
                                        <span class="owner-phone"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="#" class="btn btn-primary">View Details</a>
                                    <a href="#" class="btn btn-secondary">Contact</a>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Sample job 2 -->
                        <tr>
                            <td></td>
                            <td>
                                <div class="location-cell">
                                    <span class="location-label">From</span>
                                    <span class="location-value"></span>
                                </div>
                            </td>
                            <td>
                                <div class="location-cell">
                                    <span class="location-label">To</span>
                                    <span class="location-value"></span>
                                </div>
                            </td>
                            <td>1.2</td>
                            <td><span class="status-badge pending"></span></td>
                            <td class="price-cell"></td>
                            <td></td>
                            <td>
                                <div class="cargo-owner-cell">
                                    <div class="owner-avatar"></div>
                                    <div class="owner-info">
                                        <span class="owner-name"></span>
                                        <span class="owner-phone"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="#" class="btn btn-primary">View Details</a>
                                    <a href="#" class="btn btn-secondary">Contact</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination">
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <button class="pagination-btn"><i class="fas fa-ellipsis-h"></i></button>
            <button class="pagination-btn">10</button>
        </div>
    </div>

    <script>
        // Toggle job form visibility
        function toggleJobForm() {
            const jobFormPanel = document.getElementById('jobFormPanel');
            if (jobFormPanel.classList.contains('hidden')) {
                jobFormPanel.classList.remove('hidden');
            } else {
                jobFormPanel.classList.add('hidden');
            }
        }

        // Search jobs functionality
        function searchJobs() {
            const pickupValue = document.getElementById('pickup').value.toLowerCase();
            const dropoffValue = document.getElementById('dropoff').value.toLowerCase();
            
            // Get all rows in the table
            const rows = document.querySelectorAll('#jobsTableBody tr');
            
            // Loop through rows and hide/show based on search criteria
            rows.forEach(row => {
                const pickup = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const dropoff = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                const matchPickup = pickup.includes(pickupValue);
                const matchDropoff = dropoff.includes(dropoffValue);
                
                if ((pickupValue === '' || matchPickup) && (dropoffValue === '' || matchDropoff)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Check if any rows are visible
            const visibleRows = document.querySelectorAll('#jobsTableBody tr:not([style*="display: none"])');
            if (visibleRows.length === 0) {
                // No matching jobs found, show empty state
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'emptyRow';
                emptyRow.innerHTML = `
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>No Jobs Found</h3>
                            <p>There are no jobs matching your search criteria. Try adjusting your filters or add a new job.</p>
                        </div>
                    </td>
                `;
                
                // Remove any existing empty row first
                const existingEmptyRow = document.getElementById('emptyRow');
                if (existingEmptyRow) {
                    existingEmptyRow.remove();
                }
                
                document.getElementById('jobsTableBody').appendChild(emptyRow);
            } else {
                // Remove empty row if it exists
                const existingEmptyRow = document.getElementById('emptyRow');
                if (existingEmptyRow) {
                    existingEmptyRow.remove();
                }
            }
        }

        // Clear search fields
        function clearSearch() {
            document.getElementById('pickup').value = '';
            document.getElementById('dropoff').value = '';
            
            // Show all rows
            const rows = document.querySelectorAll('#jobsTableBody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
            
            // Remove empty row if it exists
            const existingEmptyRow = document.getElementById('emptyRow');
            if (existingEmptyRow) {
                existingEmptyRow.remove();
            }
        }

        // Initialize date picker with today's date
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            const startDateInput = document.getElementById('formStartDate');
            if (startDateInput) {
                startDateInput.value = formattedDate;
            }
            
            // Add form submission handler
            const jobForm = document.getElementById('jobForm');
            if (jobForm) {
                jobForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form values
                    const item = document.getElementById('formItem').value;
                    const pickup = document.getElementById('formPickup').value;
                    const dropoff = document.getElementById('formDropoff').value;
                    const weight = document.getElementById('formWeight').value;
                    const state = document.getElementById('formState').value;
                    const price = document.getElementById('formPrice').value;
                    const startDate = document.getElementById('formStartDate').value;
                    const ownerName = document.getElementById('formOwnerName').value;
                    const ownerPhone = document.getElementById('formOwnerPhone').value;
                    
                    // Create new row
                    const newRow = document.createElement('tr');
                    
                    // Get owner initials
                    const initials = ownerName.split(' ').map(name => name.charAt(0).toUpperCase()).join('');
                    
                    // Format date
                    const date = new Date(startDate);
                    const formattedDate = `${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getDate().toString().padStart(2, '0')}/${date.getFullYear()}`;
                    
                    // Set row HTML
                    newRow.innerHTML = `
                        <td>${item}</td>
                        <td>
                            <div class="location-cell">
                                <span class="location-label">From</span>
                                <span class="location-value">${pickup}</span>
                            </div>
                        </td>
                        <td>
                            <div class="location-cell">
                                <span class="location-label">To</span>
                                <span class="location-value">${dropoff}</span>
                            </div>
                        </td>
                        <td>${weight}</td>
                        <td><span class="status-badge ${state.toLowerCase()}">${state}</span></td>
                        <td class="price-cell">$${price}</td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="cargo-owner-cell">
                                <div class="owner-avatar">${initials}</div>
                                <div class="owner-info">
                                    <span class="owner-name">${ownerName}</span>
                                    <span class="owner-phone">${ownerPhone}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="#" class="btn btn-primary">View Details</a>
                                <a href="#" class="btn btn-secondary">Contact</a>
                            </div>
                        </td>
                    `;
                    
                    // Add the new row to the table
                    document.getElementById('jobsTableBody').appendChild(newRow);
                    
                    // Update job count
                    const jobCountElement = document.querySelector('.job-count');
                    const currentCount = parseInt(jobCountElement.textContent);
                    jobCountElement.textContent = `${currentCount + 1} Jobs`;
                    
                    // Reset form and hide it
                    jobForm.reset();
                    document.getElementById('formStartDate').value = formattedDate;
                    toggleJobForm();
                });
            }
        });
    </script>
</body>
</html>