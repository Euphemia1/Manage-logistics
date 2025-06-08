
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        /* Responsive Styles */
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
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3><i class="fas fa-list"></i> Job Listings</h3>
                <span class="job-count" id="jobCount"></span>
            </div>
            <div class="table-responsive">
                <div class="loading" id="loading">
                    <div class="loading-spinner"></div>
                </div>
                <table id="jobTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Pick up</th>
                            <th>Drop off</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Phone number</th>
                           
                            <!-- <th>Cargo Owner</th>
                            <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody id="jobPostList">
                        <!-- Job posts will be dynamically inserted here -->
                    </tbody>
                </table>
                <div class="empty-state" id="emptyState" style="display: none;">
                    <i class="fas fa-truck-loading"></i>
                    <h3>No Jobs Available</h3>
                    <p>There are currently no jobs matching your criteria. Please check back later or try a different search.</p>
                </div>
            </div>
        </div>

        <div class="pagination">
            <button class="pagination-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <button class="pagination-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        loadJobs(); // Load jobs on page load
        setInterval(loadJobs, 30000); // Reload jobs every 30 seconds
    });

    function loadJobs() {
        const loading = document.getElementById('loading');
        const jobTable = document.getElementById('jobTable');
        const emptyState = document.getElementById('emptyState');
        
        loading.style.display = 'flex';
        jobTable.style.display = 'none';
        emptyState.style.display = 'none';
        
        fetch('../Backend/get-jobs.php')
            .then(response => response.json())
            .then(jobs => {
                const jobPostList = document.getElementById('jobPostList');
                const jobCount = document.getElementById('jobCount');
                
                // Update job count
                jobCount.textContent = `${jobs.length} job${jobs.length !== 1 ? 's' : ''}`;
                
                // Clear existing jobs
                jobPostList.innerHTML = '';
                
                if (jobs.length === 0) {
                    jobTable.style.display = 'none';
                    emptyState.style.display = 'flex';
                } else {
                    jobTable.style.display = 'table';
                    emptyState.style.display = 'none';
                    
                    jobs.forEach(job => {
                        showJobPost(job);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching jobs:', error);
                jobTable.style.display = 'none';
                emptyState.style.display = 'flex';
            })
            .finally(() => {
                loading.style.display = 'none';
            });
    }

    function showJobPost(job) {
        const jobPostList = document.getElementById('jobPostList');
        const row = document.createElement('tr');
        
        // Determine status class
        let statusClass = 'available';
        if (job.state && job.state.toLowerCase() === 'pending') {
            statusClass = 'pending';
        } else if (job.state && job.state.toLowerCase() === 'completed') {
            statusClass = 'completed';
        }
        
        row.innerHTML = `
            <td>${job.item || 'N/A'}</td>
            <td>
                <div class="location-cell">
                    <span class="location-label">Pick up</span>
                    <span class="location-value">${job.pickup || 'N/A'}</span>
                </div>
            </td>
            <td>
                <div class="location-cell">
                    <span class="location-label">Drop off</span>
                    <span class="location-value">${job.dropoff || 'N/A'}</span>
                </div>
            </td>
            <td>${job.weight ? job.weight + ' mt' : 'N/A'}</td>
            <td>${job.cargo_owner_phone || 'N/A'}</td>   <!-- Phone Number -->
            <td>${job.start_date || 'N/A'}</td>          <!-- Start Date -->
            <td><span class="status-badge ${statusClass}">${job.state || 'Available'}</span></td>
            <td>
                <div class="action-buttons">
                    ${job.cargo_owner_phone ? 
                        `<a href="tel:${job.cargo_owner_phone}" class="btn btn-secondary"><i class="fas fa-phone"></i> Call</a>` : 
                        // `<button class="btn btn-outline" disabled><i class="fas fa-phone"></i> No Phone</button>`
                    }
                  
                </div>
            </td>
        `;
        
        jobPostList.appendChild(row);
    }

    function searchJobs() {
        const pickup = document.getElementById('pickup').value.toLowerCase();
        const dropoff = document.getElementById('dropoff').value.toLowerCase();
        const rows = document.querySelectorAll('#jobPostList tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const pickupCell = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const dropoffCell = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            const matchesPickup = pickup === '' || pickupCell.includes(pickup);
            const matchesDropoff = dropoff === '' || dropoffCell.includes(dropoff);
            
            if (matchesPickup && matchesDropoff) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update job count
        document.getElementById('jobCount').textContent = `${visibleCount} job${visibleCount !== 1 ? 's' : ''}`;
        
        // Show empty state if no results
        const jobTable = document.getElementById('jobTable');
        const emptyState = document.getElementById('emptyState');
        
        if (visibleCount === 0) {
            jobTable.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            jobTable.style.display = 'table';
            emptyState.style.display = 'none';
        }
    }

    function clearSearch() {
        document.getElementById('pickup').value = '';
        document.getElementById('dropoff').value = '';
        loadJobs();
    }

    function viewJobDetails(jobId) {
        // Implement job details view
        alert(`Viewing details for job ID: ${jobId}`);
        // You could redirect to a details page or show a modal
    }
</script>

</body>
</html>