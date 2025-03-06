<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Post Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .header h2 {
            color: #333;
            font-size: 24px;
            font-weight: 700;
            margin: 10px 0;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-bar input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            flex: 1;
            min-width: 150px;
        }

        .search-bar button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        #jobPosts {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #228525;
            color: white;
            font-weight: 500;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-bar {
                width: 100%;
                margin-top: 10px;
            }

            .search-bar input {
                width: 100%;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            th,
            td {
                white-space: nowrap;
            }

            /* Hide less important columns on smaller screens */
            th:nth-child(4),
            td:nth-child(4),
            th:nth-child(6),
            td:nth-child(6) {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .header h2 {
                font-size: 20px;
            }

            .search-bar input {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px;
                font-size: 12px;
            }

            /* Hide additional columns on very small screens */
            th:nth-child(5),
            td:nth-child(5) {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Job Posts</h2>
            <div class="search-bar">
                <input type="text" id="pickup" placeholder="Pick up location">
                <input type="text" id="dropoff" placeholder="Drop off location">
                <button onclick="Search()">Search</button>
            </div>
        </div>
        <div id="jobPosts">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Pick up</th>
                        <th>Drop off</th>
                        <th>Weight (mt)</th>
                        <th>State</th>
                        <th>Price per tn</th>
                        <th>Job start date</th>
                    </tr>
                </thead>
                <tbody id="jobPostList">
                    <!-- Job posts will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadJobs(); // Load jobs on page load
            setInterval(loadJobs, 5000); // Reload jobs every 5 seconds
        });

        function loadJobs() {
            fetch('../Backend/get-jobs.php')
                .then(response => response.json())
                .then(jobs => {
                    const jobPostList = document.getElementById('jobPostList');
                    jobPostList.innerHTML = ''; // Clear existing jobs
                    jobs.forEach(job => {
                        showJobPost(job);
                    });
                })
                .catch(error => console.error('Error fetching jobs:', error));
        }

        function showJobPost(jobPost) {
            const jobPostList = document.getElementById('jobPostList');
            const jobPostRow = document.createElement('tr');
            jobPostRow.innerHTML = `
                <td>${jobPost.item}</td>
                <td>${jobPost.pickup}</td>
                <td>${jobPost.dropoff}</td>
                <td>${jobPost.weight}</td>
                <td>${jobPost.state}</td>
                <td>${jobPost.price}</td>
                <td>${jobPost.start_date}</td>
            `;
            jobPostList.appendChild(jobPostRow);
        }

        function clearSearch() {
            document.getElementById('pickup').value = '';
            document.getElementById('dropoff').value = '';
        }
    </script>
</body>
</html>