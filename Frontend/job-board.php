<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Job Post Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        /* Custom scrollbar for tables */
        .table-container {
            overflow-x: auto;
        }
        .table-container::-webkit-scrollbar {
            height: 8px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #4ade80;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-green-100 font-roboto">
    <div class="container mx-auto p-4">
        <!-- Search Section -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-green-700 text-white p-4 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <img src="images/Nyamula_Logo_White__1_-output-removebg-preview.png" alt="Nyamula Logistics Logo" class="h-12 w-auto">
                <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <input class="p-2 rounded-md w-full md:w-48" id="pickup" placeholder="Enter pick up town / city" type="text"/>
                    <input class="p-2 rounded-md w-full md:w-48" id="dropoff" placeholder="Enter drop off town / city" type="text"/>
                </div>
                <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <label for="weightRange" class="mr-2">Min: 0 ton Max: 40 ton</label>
                    <input class="w-32" id="weightRange" max="40" min="0" type="range"/>
                    <button class="bg-green-500 text-white p-2 rounded-md hover:bg-green-600 transition-colors w-full md:w-auto" onclick="clearSearch()">Clear search</button>
                </div>
            </div>

            <!-- Job Post Form -->
            <form class="p-4" method="POST" action="../Backend/post-job.php" onsubmit="submitForm(event)">
                <div class="table-container">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Item</th>
                                <th class="py-2 px-4 border-b">Pick up</th>
                                <th class="py-2 px-4 border-b">Drop off</th>
                                <th class="py-2 px-4 border-b">Weight (mt)</th>
                                <th class="py-2 px-4 border-b">State</th>
                                <th class="py-2 px-4 border-b">Price per tn</th>
                                <th class="py-2 px-4 border-b">Job start date</th>
                                <th class="py-2 px-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobPostList">
                            <!-- Job posts will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button class="bg-green-500 text-white p-2 rounded-md hover:bg-green-600 transition-colors w-full md:w-auto" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load jobs from the database on page load
        document.addEventListener('DOMContentLoaded', loadJobs);

        // Clear search inputs
        function clearSearch() {
            document.getElementById('pickup').value = '';
            document.getElementById('dropoff').value = '';
            document.getElementById('weightRange').value = 0;
        }

        // Submit form and add job post dynamically
        function submitForm(event) {
            event.preventDefault();
            const item = document.querySelector('input[placeholder="Enter item"]').value;
            const pickup = document.getElementById('formPickup').value;
            const dropoff = document.getElementById('formDropoff').value;
            const weight = document.querySelector('input[placeholder="Enter weight"]').value;
            const state = document.querySelector('input[placeholder="Enter state"]').value;
            const price = document.querySelector('input[placeholder="Enter price per tn"]').value;
            const startDate = document.querySelector('input[placeholder="Enter job start date"]').value;

            const jobPost = {
                item,
                pickup,
                dropoff,
                weight,
                state,
                price,
                startDate
            };

            fetch('../Backend/post-job.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(jobPost)
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        alert(response.success);
                        loadJobs(); // Reload jobs to reflect the new addition
                        document.querySelector('form').reset(); // Clear form fields
                    } else {
                        alert(response.error);
                    }
                })
                .catch(error => console.error('Error fetching jobs:', error));
        }

        // Load jobs from the database
        function loadJobs() {
            fetch('../Backend/get-jobs.php')
                .then(response => response.json())
                .then(jobs => {
                    const jobPostList = document.getElementById('jobPostList');
                    jobPostList.innerHTML = ''; // Clear existing jobs
                    jobs.forEach(job => showJobPost(job));
                })
                .catch(error => console.error('Error fetching jobs:', error));
        }

        // Display job post in the table
        function showJobPost(jobPost) {
            const jobPostList = document.getElementById('jobPostList');
            const jobPostRow = document.createElement('tr');
            jobPostRow.setAttribute('data-id', jobPost.id); // Set data-id for easy access
            jobPostRow.innerHTML = `
                <td class="py-2 px-4 border-b">${jobPost.item}</td>
                <td class="py-2 px-4 border-b">${jobPost.pickup}</td>
                <td class="py-2 px-4 border-b">${jobPost.dropoff}</td>
                <td class="py-2 px-4 border-b">${jobPost.weight}</td>
                <td class="py-2 px-4 border-b">${jobPost.state}</td>
                <td class="py-2 px-4 border-b">${jobPost.price}</td>
                <td class="py-2 px-4 border-b">${jobPost.start_date}</td>
                <td class="py-2 px-4 border-b">
                    <a href="../Backend/edit.php?id=${jobPost.id}" class="bg-blue-500 text-white p-1 rounded-md hover:bg-blue-600">Edit</a>
                    <button class="bg-red-500 text-white p-1 rounded-md hover:bg-red-600" onclick="deleteJob(${jobPost.id})">Delete</button>
                </td>
            `;
            jobPostList.appendChild(jobPostRow);
        }

        // Delete job
        function deleteJob(id) {
            if (confirm('Are you sure you want to delete this job?')) {
                fetch('../Backend/delete-job.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ id })
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            alert(response.success);
                            loadJobs(); // Reload jobs to reflect the deletion
                        } else {
                            alert(response.error);
                        }
                    })
                    .catch(error => console.error('Error deleting job:', error));
            }
        }
    </script>
</body>
</html>