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
                <!-- Logo -->
                <img src="images/Nyamula_Logo_White__1_-output-removebg-preview.png" alt="Nyamula Logistics Logo" class="h-12 w-auto">
                
                <!-- Search Inputs -->
                <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                    <input class="p-2 rounded-md w-full md:w-48" id="pickup" placeholder="Enter pick up town / city" type="text"/>
                    <input class="p-2 rounded-md w-full md:w-48" id="dropoff" placeholder="Enter drop off town / city" type="text"/>
                </div>
                
                <!-- Weight Range and Clear Button -->
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2 px-4 border-b"><input name="item" class="p-2 w-full rounded-md border" placeholder="Enter item" type="text" required/></td>
                                <td class="py-2 px-4 border-b"><input name="pickup" class="p-2 w-full rounded-md border" id="formPickup" placeholder="Enter pick up location" type="text" required/></td>
                                <td class="py-2 px-4 border-b"><input name="dropoff" class="p-2 w-full rounded-md border" id="formDropoff" placeholder="Enter drop off location" type="text" required/></td>
                                <td class="py-2 px-4 border-b"><input name="weight" class="p-2 w-full rounded-md border" placeholder="Enter weight" type="number" min="0" max="40" required/></td>
                                <td class="py-2 px-4 border-b"><input name="state" class="p-2 w-full rounded-md border" placeholder="Enter state" type="text" required/></td>
                                <td class="py-2 px-4 border-b"><input name="price" class="p-2 w-full rounded-md border" placeholder="Enter price per tn" type="text" required/></td>
                                <td class="py-2 px-4 border-b"><input name="startDate" class="p-2 w-full rounded-md border" placeholder="Enter job start date" type="date" required/></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button class="bg-green-500 text-white p-2 rounded-md hover:bg-green-600 transition-colors w-full md:w-auto" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Posts Section -->
    <div class="container mx-auto p-4">
        <div id="jobPosts" class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-xl font-bold mb-4">Job Posts</h2>
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
                        </tr>
                    </thead>
                    <tbody id="jobPostList">
                        <!-- Job posts will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Sync search inputs with form inputs
        document.getElementById('pickup').addEventListener('input', function() {
            document.getElementById('formPickup').value = this.value;
        });

        document.getElementById('dropoff').addEventListener('input', function() {
            document.getElementById('formDropoff').value = this.value;
        });

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

            showJobPost(jobPost);
            fetch('../Backend/post-job.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(jobPost)
            })
                .then(response => response.text())
                .then(response => {
                    console.log(response);
                })
                .catch(error => console.error('Error fetching jobs:', error));
        }

        // Display job post in the table
        function showJobPost(jobPost) {
            const jobPostList = document.getElementById('jobPostList');
            const jobPostRow = document.createElement('tr');
            jobPostRow.innerHTML = `
                <td class="py-2 px-4 border-b">${jobPost.item}</td>
                <td class="py-2 px-4 border-b">${jobPost.pickup}</td>
                <td class="py-2 px-4 border-b">${jobPost.dropoff}</td>
                <td class="py-2 px-4 border-b">${jobPost.weight}</td>
                <td class="py-2 px-4 border-b">${jobPost.state}</td>
                <td class="py-2 px-4 border-b">${jobPost.price}</td>
                <td class="py-2 px-4 border-b">${jobPost.startDate}</td>
            `;
            jobPostList.appendChild(jobPostRow);
        }
    </script>
</body>
</html>