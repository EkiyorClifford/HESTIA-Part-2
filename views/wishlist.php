<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - HESTIA Property Rentals</title>
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
            background: linear-gradient(90deg, #1A0F1E 0%, #5A2E55 100%) !important;
            box-shadow: 0 4px 15px rgba(26, 15, 30, 0.3);
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
            position: relative;
        }
        .navbar-nav .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: #fff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }
        main {
            flex-grow: 1;
        }
        .page-header {
            background: linear-gradient(135deg, #1A0F1E 0%, #5A2E55 30%, #8C3E2C 100%);
            color: white;
            padding: 50px 0;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .page-header h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }
        .page-header p {
            font-size: 1.1rem;
            font-weight: 300;
            opacity: 0.95;
        }
        .wishlist-controls {
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .wishlist-count {
            font-size: 1.1rem;
            color: #1A0F1E;
            font-weight: 600;
        }
        .wishlist-count span {
            color: #C44536;
            font-weight: 800;
        }
        .btn-primary {
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(198, 69, 54, 0.4);
            color: white;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #E67E51 0%, #C44536 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(198, 69, 54, 0.6);
            color: white;
        }
        .btn-secondary {
            background: transparent;
            border: 2px solid #ddd;
            color: #333;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .btn-secondary:hover {
            border-color: #C44536;
            color: #C44536;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
            position: relative;
        }
        .card:hover .card-img-top {
            transform: scale(1.08);
        }
        .card-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2;
        }
        .card-badge.rent {
            background: #4A90E2;
        }
        .card-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .card-title {
            font-weight: 700;
            color: #1A0F1E;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }
        .card-text {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
            flex-grow: 1;
        }
        .card-specs {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #666;
        }
        .card-specs span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .card-specs i {
            color: #C44536;
        }
        .card-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: #C44536;
            margin-bottom: 15px;
        }
        .card-actions {
            display: flex;
            gap: 10px;
        }
        .card-actions a,
        .card-actions button {
            flex: 1;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            font-size: 0.9rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .remove-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(220, 53, 69, 0.95);
            border: none;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.4);
            font-weight: bold;
            z-index: 3;
        }
        .remove-btn:hover {
            background: #dc3545;
            transform: scale(1.15);
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.5);
        }
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .empty-wishlist {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .empty-wishlist i {
            font-size: 4rem;
            color: #C44536;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        .empty-wishlist h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1A0F1E;
        }
        .empty-wishlist p {
            color: #666;
            font-size: 1.05rem;
            margin-bottom: 30px;
        }
        .footer {
            background: linear-gradient(90deg, #1A0F1E 0%, #2C1B2E 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
        }
        .footer p {
            margin: 0;
        }
        .sort-section {
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        .sort-section label {
            font-weight: 600;
            color: #1A0F1E;
            margin-bottom: 10px;
            display: block;
        }
        .sort-section select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sort-section select:focus {
            border-color: #C44536;
            box-shadow: 0 0 0 0.2rem rgba(196, 69, 54, 0.15);
            outline: none;
        }
        @media (max-width: 768px) {
            .wishlist-controls {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
            .wishlist-controls button {
                width: 100%;
            }
            .page-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header with Navigation -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">HESTIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="properties.php">Browse Properties</a></li>
                    <li class="nav-item"><a class="nav-link" href="about us.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link active" href="wishlist.php">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Login / Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-heart me-2"></i>My Wishlist</h1>
            <p>Your favorite properties in one place</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container flex-grow-1 pb-5">
        <!-- Wishlist Controls -->
        <div class="wishlist-controls">
            <div class="wishlist-count">
                You have <span>4</span> properties saved
            </div>
            <div>
                <button class="btn-secondary"><i class="fas fa-download"></i> Download List</button>
                <button class="btn-secondary ms-2"><i class="fas fa-share-alt"></i> Share Wishlist</button>
            </div>
        </div>

        <!-- Sort Section -->
        <div class="sort-section">
            <label for="sortBy"><i class="fas fa-sort me-2"></i>Sort by:</label>
            <select id="sortBy" class="form-select w-100" style="max-width: 300px;">
                <option selected>Recently Added</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Bedrooms: Low to High</option>
                <option>Bedrooms: High to Low</option>
                <option>Location</option>
            </select>
        </div>

        <!-- Wishlist Items -->
        <div class="wishlist-grid">
            <!-- Property 1 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge">For Sale</span>
                <img src="image/4BED-MASSIONETTE-1B.png" class="card-img-top" alt="Luxury Maisonette">
                <div class="card-body">
                    <h5 class="card-title">Luxury Maisonette</h5>
                    <p class="card-text">Beautiful 4-bedroom maisonette in the city center with premium finishes.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 4 Beds</span>
                        <span><i class="fas fa-bath"></i> 3 Baths</span>
                        <span><i class="fas fa-ruler-combined"></i> 2,500 sqft</span>
                    </div>
                    <div class="card-price">₦850,000,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>

            <!-- Property 2 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge rent">For Rent</span>
                <img src="image/4-DETACHED-700M.png" class="card-img-top" alt="Spacious House">
                <div class="card-body">
                    <h5 class="card-title">Spacious House</h5>
                    <p class="card-text">Family-friendly detached house with modern amenities and patio.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 4 Beds</span>
                        <span><i class="fas fa-bath"></i> 3 Baths</span>
                        <span><i class="fas fa-ruler-combined"></i> 3,000 sqft</span>
                    </div>
                    <div class="card-price">₦2,500,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>

            <!-- Property 3 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge">For Sale</span>
                <img src="image/5-BED-DUPE-500MI.png" class="card-img-top" alt="Luxury Condo">
                <div class="card-body">
                    <h5 class="card-title">Luxury Condo</h5>
                    <p class="card-text">Sleek luxury apartment perfect for modern living with city views.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 1 Bed</span>
                        <span><i class="fas fa-bath"></i> 1 Bath</span>
                        <span><i class="fas fa-ruler-combined"></i> 800 sqft</span>
                    </div>
                    <div class="card-price">₦500,000,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>

            <!-- Property 4 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge rent">For Rent</span>
                <img src="image/2BEDROOM3BED.png" class="card-img-top" alt="Modern Apartments">
                <div class="card-body">
                    <h5 class="card-title">Modern Apartments</h5>
                    <p class="card-text">High-end apartment with stunning city views and premium facilities.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 2 Beds</span>
                        <span><i class="fas fa-bath"></i> 2 Baths</span>
                        <span><i class="fas fa-ruler-combined"></i> 1,200 sqft</span>
                    </div>
                    <div class="card-price">₦8,000,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty Wishlist Message (commented out, can be shown if no items) -->
        <!-- 
        <div class="empty-wishlist">
            <i class="fas fa-heart-broken"></i>
            <h3>Your wishlist is empty</h3>
            <p>Browse properties and add them to your wishlist to keep track of your favorites.</p>
            <a href="properties.php" class="btn-primary"><i class="fas fa-search me-2"></i>Browse Properties</a>
        </div>
        -->
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2026 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
