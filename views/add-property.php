<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property - HESTIA Property Rentals</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(90deg, #4A90E2 0%, #357ABD 100%) !important;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.2);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 800;
            color: #fff !important;
            font-size: 1.8rem;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-nav .nav-link {
            color: #fff !important;
            transition: all 0.3s ease;
            margin: 0 5px;
            font-weight: 500;
        }
        .navbar-nav .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        .form-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 45px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }
        .form-container h2 {
            font-weight: 800;
            color: #4A90E2;
            margin-bottom: 30px;
            text-align: center;
            font-size: 1.8rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
            outline: none;
        }
        .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 10px 12px;
            transition: all 0.3s ease;
        }
        .form-select:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
        }
        .form-check {
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            background: #f8f9fa;
            transition: background 0.3s ease;
        }
        .form-check:hover {
            background: #e9ecef;
        }
        .form-check-input {
            border: 2px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 3px;
        }
        .form-check-input:checked {
            background-color: #4A90E2;
            border-color: #4A90E2;
        }
        .form-check-label {
            margin-left: 8px;
            cursor: pointer;
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(90deg, #4A90E2 0%, #357ABD 100%);
            border: none;
            padding: 14px 35px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #357ABD 0%, #4A90E2 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.4);
            color: white;
        }
        .amenities-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }
        .footer {
            background: linear-gradient(90deg, #343a40 0%, #495057 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
        }
        main {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <!-- Header with Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">HESTIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="properties.php">Properties</a></li>
                    <li class="nav-item"><a class="nav-link" href="property-details.php">Property Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="landlord-dashboard.php">Landlord Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="add-property.php">Add Property</a></li>
                    <li class="nav-item"><a class="nav-link" href="requests.php">Requests</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container flex-grow-1">
        <div class="form-container">
            <h2 class="text-center mb-4" style="color: #4A90E2;">Add New Property</h2>
            <form>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Property Title</label>
                        <input type="text" class="form-control" id="title" placeholder="e.g., Cozy Apartment" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Property Type</label>
                        <select class="form-select" id="type" required>
                            <option selected disabled>Select type</option>
                            <option>Apartment</option>
                            <option>House</option>
                            <option>Studio</option>
                            <option>Condo</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="3" placeholder="Describe your property..." required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Monthly Rent</label>
                        <input type="number" class="form-control" id="price" placeholder="1200" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bedrooms" class="form-label">Bedrooms</label>
                        <input type="number" class="form-control" id="bedrooms" placeholder="2" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="bathrooms" class="form-label">Bathrooms</label>
                        <input type="number" class="form-control" id="bathrooms" placeholder="1" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" placeholder="123 Mind your business Road" required>
                </div>
                <div class="mb-3">
                    <label for="images" class="form-label">Property Images</label>
                    <input type="file" class="form-control" id="images" multiple accept="image/*">
                    <small class="form-text text-muted">Upload multiple images of your property.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amenities</label>
                    <div class="amenities-checkboxes">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="parking">
                            <label class="form-check-label" for="parking">Parking</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pets">
                            <label class="form-check-label" for="pets">Pet Friendly</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="laundry">
                            <label class="form-check-label" for="laundry">Laundry</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gym">
                            <label class="form-check-label" for="gym">Gym</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pool">
                            <label class="form-check-label" for="pool">Pool</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="balcony">
                            <label class="form-check-label" for="balcony">Balcony</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Property</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2023 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
