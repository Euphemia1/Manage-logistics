<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Job Post Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
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
        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        /* Fade in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        /* Shimmer loading effect */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-primary-700 text-white shadow-md">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="../images/logo.png" alt="Nyamula Logistics Logo" class="h-10 w-auto">
                    <h1 class="text-xl font-bold hidden sm:block">Job Board Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="toggleSearchBtn" class="bg-primary-600 hover:bg-primary-800 px-3 py-1.5 rounded-md transition-colors flex items-center">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                    <button id="toggleFormBtn" class="bg-primary-600 hover:bg-primary-800 px-3 py-1.5 rounded-md transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i> New Job
                    </button>
                </div>
            </div>
        </header>

        <div class="container mx-auto px-4 py-6 flex-grow">
            <!-- Search Panel (Hidden by default) -->
            <div id="searchPanel" class="bg-white rounded-lg shadow-md p-4 mb-6 hidden fade-in">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-search mr-2 text-primary-600"></i> Search Jobs
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label for="pickup" class="block text-sm font-medium text-gray-700">Pickup Location</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <input id="pickup" type="text" placeholder="Enter pick up town / city" 
                                class="pl-10 w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="dropoff" class="block text-sm font-medium text-gray-700">Dropoff Location</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker text-gray-400"></i>
                            </div>
                            <input id="dropoff" type="text" placeholder="Enter drop off town / city" 
                                class="pl-10 w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="weightRange" class="block text-sm font-medium text-gray-700">Weight Range (tons)</label>
                        <div class="flex items-center space-x-2">
                            <span id="weightValue" class="text-sm font-medium w-8 text-center">0</span>
                            <input id="weightRange" type="range" min="0" max="40" value="0" 
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600"
                                oninput="document.getElementById('weightValue').textContent = this.value"/>
                            <span class="text-sm font-medium">40</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-4 space-x-2">
                    <button onclick="clearSearch()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Clear
                    </button>
                    <button onclick="searchJobs()" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                        Search
                    </button>
                </div>
            </div>

            <!-- Add Job Form (Hidden by default) -->
            <div id="jobFormPanel" class="bg-white rounded-lg shadow-md p-4 mb-6 hidden fade-in">
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
                            <option value="">Select state</option>
                            <option value="Available">Available</option>
                            <option value="In Progress">In Progress</option>
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

            <!-- Jobs List Section -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-primary-700 text-white p-4 flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Job Listings</h2>
                    <div class="flex items-center space-x-2">
                        <select id="sortOptions" class="text-gray-800 text-sm p-1.5 rounded-md border-0 focus:ring-2 focus:ring-primary-500">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="weight_high">Weight (High to Low)</option>
                            <option value="weight_low">Weight (Low to High)</option>
                            <option value="price_high">Price (High to Low)</option>
                            <option value="price_low">Price (Low to High)</option>
                        </select>
                        <button id="refreshBtn" onclick="loadJobs()" class="bg-primary-600 hover:bg-primary-800 p-1.5 rounded-md transition-colors">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="p-8 hidden">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
                        <p class="text-gray-500">Loading jobs...</p>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="p-12 hidden">
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-briefcase text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800">No Jobs Found</h3>
                        <p class="text-gray-500 max-w-md">There are no jobs matching your criteria. Try adjusting your search filters or add a new job.</p>
                        <button onclick="toggleJobForm()" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add New Job
                        </button>
                    </div>
                </div>

                <!-- Job Table -->
                <div class="table-container">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                                <th class="py-3 px-4 text-left font-semibold">Item</th>
                                <th class="py-3 px-4 text-left font-semibold">Pick up</th>
                                <th class="py-3 px-4 text-left font-semibold">Drop off</th>
                                <th class="py-3 px-4 text-left font-semibold">Weight (mt)</th>
                                <th class="py-3 px-4 text-left font-semibold">State</th>
                                <th class="py-3 px-4 text-left font-semibold">Price per tn</th>
                                <th class="py-3 px-4 text-left font-semibold">Job start date</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobPostList">
                            <!-- Job posts will be dynamically added here -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="pagination" class="p-4 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Showing <span id="startRange">0</span>-<span id="endRange">0</span> of <span id="totalJobs">0</span> jobs
                    </div>
                    <div class="flex space-x-1">
                        <button id="prevPage" class="px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div id="pageNumbers" class="flex space-x-1">
                            <!-- Page numbers will be added here -->
                        </div>
                        <button id="nextPage" class="px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-4">
            <div class="container mx-auto px-4 text-center text-gray-600 text-sm">
                &copy; 2023 Nyamula Logistics. All rights reserved.
            </div>
        </footer>
    </div>

    <!-- Edit Job Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4 overflow-hidden">
            <div class="bg-primary-700 text-white p-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Edit Job</h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="editJobId" name="id">
                
                <div class="space-y-2">
                    <label for="editItem" class="block text-sm font-medium text-gray-700">Item</label>
                    <input id="editItem" name="item" type="text" placeholder="Enter item" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="editPickup" class="block text-sm font-medium text-gray-700">Pick up</label>
                    <input id="editPickup" name="pickup" type="text" placeholder="Enter pick up location" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="editDropoff" class="block text-sm font-medium text-gray-700">Drop off</label>
                    <input id="editDropoff" name="dropoff" type="text" placeholder="Enter drop off location" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="editWeight" class="block text-sm font-medium text-gray-700">Weight (mt)</label>
                    <input id="editWeight" name="weight" type="number" min="0" step="0.1" placeholder="Enter weight" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="space-y-2">
                    <label for="editState" class="block text-sm font-medium text-gray-700">State</label>
                    <select id="editState" name="state" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                        <option value="Available">Available</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="editPrice" class="block text-sm font-medium text-gray-700">Price per tn</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input id="editPrice" name="price" type="number" min="0" step="0.01" placeholder="Enter price" required
                            class="pl-8 w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="editStartDate" class="block text-sm font-medium text-gray-700">Job start date</label>
                    <input id="editStartDate" name="startDate" type="date" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"/>
                </div>
                
                <div class="md:col-span-2 flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Update Job
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Global variables
        let allJobs = [];
        let filteredJobs = [];
        let currentPage = 1;
        const jobsPerPage = 10;
        
        // DOM elements
        const searchPanel = document.getElementById('searchPanel');
        const jobFormPanel = document.getElementById('jobFormPanel');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const emptyState = document.getElementById('emptyState');
        const jobPostList = document.getElementById('jobPostList');
        const pagination = document.getElementById('pagination');
        const editModal = document.getElementById('editModal');
        
        // Toggle search panel
        document.getElementById('toggleSearchBtn').addEventListener('click', function() {
            searchPanel.classList.toggle('hidden');
            if (!searchPanel.classList.contains('hidden')) {
                jobFormPanel.classList.add('hidden');
            }
        });
        
        // Toggle job form panel
        document.getElementById('toggleFormBtn').addEventListener('click', toggleJobForm);
        
        function toggleJobForm() {
            jobFormPanel.classList.toggle('hidden');
            if (!jobFormPanel.classList.contains('hidden')) {
                searchPanel.classList.add('hidden');
                // Set default date to today
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('formStartDate').value = today;
            }
        }
        
        // Clear search inputs
        function clearSearch() {
            document.getElementById('pickup').value = '';
            document.getElementById('dropoff').value = '';
            document.getElementById('weightRange').value = 0;
            document.getElementById('weightValue').textContent = '0';
            searchJobs();
        }
        
        // Search jobs
        function searchJobs() {
            const pickup = document.getElementById('pickup').value.toLowerCase();
            const dropoff = document.getElementById('dropoff').value.toLowerCase();
            const weight = parseInt(document.getElementById('weightRange').value);
            
            filteredJobs = allJobs.filter(job => {
                const matchPickup = !pickup || job.pickup.toLowerCase().includes(pickup);
                const matchDropoff = !dropoff || job.dropoff.toLowerCase().includes(dropoff);
                const matchWeight = !weight || parseFloat(job.weight) >= weight;
                
                return matchPickup && matchDropoff && matchWeight;
            });
            
            currentPage = 1;
            displayJobs();
            updatePagination();
        }
        
        // Sort jobs
        document.getElementById('sortOptions').addEventListener('change', function() {
            const sortOption = this.value;
            
            switch(sortOption) {
                case 'newest':
                    filteredJobs.sort((a, b) => new Date(b.start_date) - new Date(a.start_date));
                    break;
                case 'oldest':
                    filteredJobs.sort((a, b) => new Date(a.start_date) - new Date(b.start_date));
                    break;
                case 'weight_high':
                    filteredJobs.sort((a, b) => parseFloat(b.weight) - parseFloat(a.weight));
                    break;
                case 'weight_low':
                    filteredJobs.sort((a, b) => parseFloat(a.weight) - parseFloat(b.weight));
                    break;
                case 'price_high':
                    filteredJobs.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
                    break;
                case 'price_low':
                    filteredJobs.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
                    break;
            }
            
            displayJobs();
        });
        
        // Submit form and add job post
        document.getElementById('jobForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            const jobData = {
                item: formData.get('item'),
                pickup: formData.get('pickup'),
                dropoff: formData.get('dropoff'),
                weight: formData.get('weight'),
                state: formData.get('state'),
                price: formData.get('price'),
                startDate: formData.get('startDate')
            };
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            
            fetch('../Backend/post-job.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(jobData)
            })
            .then(response => response.json())
            .then(response => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (response.success) {
                    // Show success notification
                    showNotification(response.success, 'success');
                    
                    // Reset form and hide panel
                    this.reset();
                    jobFormPanel.classList.add('hidden');
                    
                    // Reload jobs
                    loadJobs();
                } else {
                    showNotification(response.error || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                console.error('Error posting job:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                showNotification('Failed to post job. Please try again.', 'error');
            });
        });
        
        // Edit form submission
        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            const jobData = {
                id: formData.get('id'),
                item: formData.get('item'),
                pickup: formData.get('pickup'),
                dropoff: formData.get('dropoff'),
                weight: formData.get('weight'),
                state: formData.get('state'),
                price: formData.get('price'),
                startDate: formData.get('startDate')
            };
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
            
            fetch('../Backend/edit-job.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(jobData)
            })
            .then(response => response.json())
            .then(response => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (response.success) {
                    // Show success notification
                    showNotification(response.success, 'success');
                    
                    // Close modal and reload jobs  'success');
                    
                    // Close modal and reload jobs
                    closeEditModal();
                    loadJobs();
                } else {
                    showNotification(response.error || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating job:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                showNotification('Failed to update job. Please try again.', 'error');
            });
        });
        
        // Load jobs from the database
        function loadJobs() {
            // Show loading indicator
            jobPostList.innerHTML = '';
            loadingIndicator.classList.remove('hidden');
            emptyState.classList.add('hidden');
            
            fetch('../Backend/get-jobs.php')
                .then(response => response.json())
                .then(jobs => {
                    // Hide loading indicator
                    loadingIndicator.classList.add('hidden');
                    
                    // Store all jobs
                    allJobs = jobs;
                    filteredJobs = [...jobs];
                    
                    // Apply current sort
                    const sortOption = document.getElementById('sortOptions').value;
                    if (sortOption === 'newest') {
                        filteredJobs.sort((a, b) => new Date(b.start_date) - new Date(a.start_date));
                    }
                    
                    // Display jobs and update pagination
                    displayJobs();
                    updatePagination();
                })
                .catch(error => {
                    console.error('Error fetching jobs:', error);
                    loadingIndicator.classList.add('hidden');
                    showNotification('Failed to load jobs. Please try again.', 'error');
                });
        }
        
        // Display jobs with pagination
        function displayJobs() {
            jobPostList.innerHTML = '';
            
            if (filteredJobs.length === 0) {
                emptyState.classList.remove('hidden');
                pagination.classList.add('hidden');
                return;
            }
            
            emptyState.classList.add('hidden');
            pagination.classList.remove('hidden');
            
            // Calculate pagination
            const startIndex = (currentPage - 1) * jobsPerPage;
            const endIndex = Math.min(startIndex + jobsPerPage, filteredJobs.length);
            const currentJobs = filteredJobs.slice(startIndex, endIndex);
            
            // Update pagination text
            document.getElementById('startRange').textContent = startIndex + 1;
            document.getElementById('endRange').textContent = endIndex;
            document.getElementById('totalJobs').textContent = filteredJobs.length;
            
            // Display current page of jobs
            currentJobs.forEach((job, index) => {
                const jobRow = document.createElement('tr');
                jobRow.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                jobRow.setAttribute('data-id', job.id);
                
                // Determine state badge color
                let stateColor = 'bg-blue-100 text-blue-800';
                if (job.state === 'In Progress') {
                    stateColor = 'bg-yellow-100 text-yellow-800';
                } else if (job.state === 'Completed') {
                    stateColor = 'bg-green-100 text-green-800';
                }
                
                jobRow.innerHTML = `
                    <td class="py-3 px-4 border-b border-gray-200">${job.item}</td>
                    <td class="py-3 px-4 border-b border-gray-200">${job.pickup}</td>
                    <td class="py-3 px-4 border-b border-gray-200">${job.dropoff}</td>
                    <td class="py-3 px-4 border-b border-gray-200">${job.weight}</td>
                    <td class="py-3 px-4 border-b border-gray-200">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold ${stateColor}">
                            ${job.state}
                        </span>
                    </td>
                    <td class="py-3 px-4 border-b border-gray-200">$${parseFloat(job.price).toFixed(2)}</td>
                    <td class="py-3 px-4 border-b border-gray-200">${formatDate(job.start_date)}</td>
                    <td class="py-3 px-4 border-b border-gray-200 text-center">
                        <button onclick="openEditModal(${job.id})" class="bg-blue-500 text-white p-1.5 rounded-md hover:bg-blue-600 transition-colors mr-1">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteJob(${job.id})" class="bg-red-500 text-white p-1.5 rounded-md hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                jobPostList.appendChild(jobRow);
            });
        }
        
        // Update pagination controls
        function updatePagination() {
            const totalPages = Math.ceil(filteredJobs.length / jobsPerPage);
            const pageNumbers = document.getElementById('pageNumbers');
            pageNumbers.innerHTML = '';
            
            // Previous button
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    displayJobs();
                    updatePagination();
                }
            });
            
            // Next button
            document.getElementById('nextPage').disabled = currentPage === totalPages;
            document.getElementById('nextPage').addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayJobs();
                    updatePagination();
                }
            });
            
            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            // First page
            if (startPage > 1) {
                const firstPageBtn = document.createElement('button');
                firstPageBtn.className = 'px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50';
                firstPageBtn.textContent = '1';
                firstPageBtn.addEventListener('click', () => {
                    currentPage = 1;
                    displayJobs();
                    updatePagination();
                });
                pageNumbers.appendChild(firstPageBtn);
                
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-3 py-1 text-gray-600';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }
            }
            
            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = i === currentPage
                    ? 'px-3 py-1 rounded-md bg-primary-600 text-white'
                    : 'px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50';
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => {
                    currentPage = i;
                    displayJobs();
                    updatePagination();
                });
                pageNumbers.appendChild(pageBtn);
            }
            
            // Last page
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-3 py-1 text-gray-600';
                    ellipsis.textContent = '...';
                    pageNumbers.appendChild(ellipsis);
                }
                
                const lastPageBtn = document.createElement('button');
                lastPageBtn.className = 'px-3 py-1 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50';
                lastPageBtn.textContent = totalPages;
                lastPageBtn.addEventListener('click', () => {
                    currentPage = totalPages;
                    displayJobs();
                    updatePagination();
                });
                pageNumbers.appendChild(lastPageBtn);
            }
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
                        showNotification(response.success, 'success');
                        loadJobs();
                    } else {
                        showNotification(response.error || 'An error occurred', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting job:', error);
                    showNotification('Failed to delete job. Please try again.', 'error');
                });
            }
        }
        
        // Open edit modal
        function openEditModal(id) {
            const job = allJobs.find(job => job.id == id);
            if (!job) return;
            
            document.getElementById('editJobId').value = job.id;
            document.getElementById('editItem').value = job.item;
            document.getElementById('editPickup').value = job.pickup;
            document.getElementById('editDropoff').value = job.dropoff;
            document.getElementById('editWeight').value = job.weight;
            document.getElementById('editState').value = job.state;
            document.getElementById('editPrice').value = job.price;
            document.getElementById('editStartDate').value = formatDateForInput(job.start_date);
            
            editModal.classList.remove('hidden');
        }
        
        // Close edit modal
        function closeEditModal() {
            editModal.classList.add('hidden');
        }
        
        // Format date for display
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }
        
        // Format date for input field (YYYY-MM-DD)
        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        }
        
        // Show notification
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 flex items-center space-x-3 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            
            // Icon
            const icon = document.createElement('i');
            icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            // Message
            const messageEl = document.createElement('span');
            messageEl.textContent = message;
            
            // Close button
            const closeBtn = document.createElement('button');
            closeBtn.className = 'ml-4 text-white hover:text-gray-200';
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            closeBtn.addEventListener('click', () => {
                document.body.removeChild(notification);
            });
            
            // Append elements
            notification.appendChild(icon);
            notification.appendChild(messageEl);
            notification.appendChild(closeBtn);
            
            // Add to body
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 5000);
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadJobs();
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === editModal) {
                    closeEditModal();
                }
            });
            
            // Set up escape key to close modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeEditModal();
                }
            });
        });
    </script>
</body>
</html>

