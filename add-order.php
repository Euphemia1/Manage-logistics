<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Order - Nyamula Logistics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2BC652;
            --primary-light: #e8f5e8;
            --primary-dark: #1e8c3d;
            --secondary-color: #f5f5f5;
            --text-color: #333;
            --text-light: #666;
            --white: #ffffff;
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
            background-color: #f8f9fa;
            color: var(--text-color);
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: var(--white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header h1 i {
            font-size: 32px;
        }

        .back-btn {
            background-color: var(--secondary-color);
            color: var(--text-color);
            padding: 12px 24px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
        }

        .form-container {
            background-color: var(--white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
        }

        .form-title {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: var(--primary-color);
            width: 16px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e1e1;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(43, 198, 82, 0.2);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 14px 30px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            text-align: center;
            min-width: 160px;
            justify-content: center;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(43, 198, 82, 0.3);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
        }

        .form-row-full {
            grid-column: 1 / -1;
        }

        .required {
            color: #e74c3c;
        }

        .success-message,
        .error-message {
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success-message {
            background-color: rgba(43, 198, 82, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(43, 198, 82, 0.3);
        }

        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            border: 1px solid rgba(231, 76, 60, 0.3);
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .header h1 {
                font-size: 24px;
            }

            .form-container {
                padding: 25px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }
        }

        .form-info {
            background-color: var(--primary-light);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            border-left: 4px solid var(--primary-color);
        }

        .form-info h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-info p {
            color: var(--text-light);
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav style="background-color: var(--primary-color); padding: 1rem 0; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <h1 style="color: white; margin: 0; font-size: 24px; font-weight: 600;">
                    <i class="fas fa-truck" style="margin-right: 10px;"></i>Nyamula Logistics
                </h1>
            </div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <a href="admin-dashboard.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="job-board.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clipboard-list"></i>Job Board
                </a>
                <a href="manage-orders.php" style="color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px; background-color: rgba(255,255,255,0.2);">
                    <i class="fas fa-box"></i>Orders
                </a>
                <a href="admin-logout.php" style="background-color: rgba(255,255,255,0.2); color: white; text-decoration: none; padding: 8px 16px; border-radius: 5px; transition: background-color 0.3s; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Add New Order</h1>
            <a href="manage-orders.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>

        <div class="form-info">
            <h3><i class="fas fa-info-circle"></i> Order Information</h3>
            <p>Please fill in all required fields to create a new order. Make sure all information is accurate before submitting.</p>
        </div>

        <div class="form-container">
            <h2 class="form-title">
                <i class="fas fa-shipping-fast"></i>
                Create New Order
            </h2>

            <form action="submit-cargo.php" method="POST" id="orderForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="order_id">
                            <i class="fas fa-hashtag"></i>
                            Order ID <span class="required">*</span>
                        </label>
                        <input type="text" id="order_id" name="order_id" required placeholder="Enter unique order ID">
                    </div>

                    <div class="form-group">
                        <label for="cargo_owner_name">
                            <i class="fas fa-user"></i>
                            Cargo Owner Name <span class="required">*</span>
                        </label>
                        <input type="text" id="cargo_owner_name" name="cargo_owner_name" required placeholder="Enter cargo owner name">
                    </div>

                    <div class="form-group">
                        <label for="cargo_type">
                            <i class="fas fa-boxes"></i>
                            Cargo Type <span class="required">*</span>
                        </label>
                        <input type="text" id="cargo_type" name="cargo_type" required placeholder="Enter cargo type">
                    </div>

                    <div class="form-group">
                        <label for="weight">
                            <i class="fas fa-weight-hanging"></i>
                            Weight (kg) <span class="required">*</span>
                        </label>
                        <input type="number" id="weight" name="weight" required placeholder="Enter weight in kg" min="1" step="0.1">
                    </div>

                    <div class="form-group">
                        <label for="origin">
                            <i class="fas fa-map-marker-alt"></i>
                            Origin <span class="required">*</span>
                        </label>
                        <input type="text" id="origin" name="origin" required placeholder="Enter pickup location">
                    </div>

                    <div class="form-group">
                        <label for="destination">
                            <i class="fas fa-map-marker-alt"></i>
                            Destination <span class="required">*</span>
                        </label>
                        <input type="text" id="destination" name="destination" required placeholder="Enter destination">
                    </div>

                    <div class="form-group">
                        <label for="dimensions">
                            <i class="fas fa-ruler-combined"></i>
                            Dimensions
                        </label>
                        <input type="text" id="dimensions" name="dimensions" placeholder="Enter dimensions (L x W x H)">
                    </div>

                    <div class="form-group">
                        <label for="start_date">
                            <i class="fas fa-calendar-alt"></i>
                            Start Date <span class="required">*</span>
                        </label>
                        <input type="date" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="status">
                            <i class="fas fa-info-circle"></i>
                            Status
                        </label>
                        <select id="status" name="status">
                            <option value="pending">Pending</option>
                            <option value="available" selected>Available</option>
                            <option value="in-transit">In Transit</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i>
                            Contact Phone
                        </label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter contact phone number">
                    </div>

                    <div class="form-group form-row-full">
                        <label for="notes">
                            <i class="fas fa-sticky-note"></i>
                            Additional Notes
                        </label>
                        <textarea id="notes" name="notes" placeholder="Enter any additional notes or special instructions..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Set today's date as default
        document.getElementById('start_date').value = new Date().toISOString().split('T')[0];

        // Form validation and submission
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Order...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual submission logic)
            fetch('submit-cargo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showMessage('Order created successfully!', 'success');
                    // Redirect to orders page after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'manage-orders.php';
                    }, 2000);
                } else {
                    showMessage('Error creating order: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Network error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        function resetForm() {
            document.getElementById('orderForm').reset();
            document.getElementById('start_date').value = new Date().toISOString().split('T')[0];
        }

        function showMessage(message, type) {
            // Remove existing messages
            const existingMessages = document.querySelectorAll('.success-message, .error-message');
            existingMessages.forEach(msg => msg.remove());
            
            // Create new message
            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
            messageDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
            `;
            
            // Insert message before form container
            const formContainer = document.querySelector('.form-container');
            formContainer.parentNode.insertBefore(messageDiv, formContainer);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }

        // Form validation
        const requiredFields = document.querySelectorAll('input[required], select[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#e1e1e1';
                }
            });
        });
    </script>
</body>
</html>

