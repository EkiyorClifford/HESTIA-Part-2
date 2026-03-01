<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - HESTIA Property Rentals</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
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
        .btn-primary {
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(198, 69, 54, 0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #E67E51 0%, #C44536 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(198, 69, 54, 0.4);
            color: white;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        .card-body {
            padding: 25px;
        }
        .card-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        .card-text {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .footer {
            background: linear-gradient(90deg, #1A0F1E 0%, #2C1B2E 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
        }
        .filter-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }
        .filter-section h3 {
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            font-size: 1.3rem;
        }
        .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 10px 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .form-select:focus {
            border-color: #C44536;
            box-shadow: 0 0 0 0.2rem rgba(196, 69, 54, 0.15);
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 10px 12px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #C44536;
            box-shadow: 0 0 0 0.2rem rgba(196, 69, 54, 0.15);
        }
        .property-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        main {
            flex-grow: 1;
        }
        h1 {
            font-weight: 800;
            color: #1A0F1E;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }
        .specs i {
            color: #C44536;
            margin-right: 5px;
        }
        .price-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: #C44536;
        }
         .map-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            height: 350px;
            margin-top: 20px;
        }
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
          .property-details-section p {
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- NAVY BAR -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">HESTIA</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="properties.php">Browse Properties</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="register.php">Login / Register</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container flex-grow-1 my-5">
        <h1 class="text-center mb-4" style="color: #C44536;">Available Properties</h1>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <h3>Filter Properties</h3>
            <div class="row g-3">
                <div class="col-md-3">
                    <select class="form-select">
                        <option selected>All Types</option>
                        <option>Apartment</option>
                        <option>House</option>
                        <option>Studio</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" placeholder="Min Price">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" placeholder="Max Price">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
            <div class="range-container">
        <div class="inputs mt-4">
        
        <select class="form-select">
            <option selected value="">Advanced filters</option>
            <option>Furnished/Unfurnished</option>
            <option>Pet-Friendly</option>
            <option>Amenities</option>
        </select>        
            </div>
        </div>

        <!-- Properties List -->
        <section class="mt-5 section-container">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card h-100">
                        <span class="badge bg-success mb-2">For Sale</span>
                        <img src="image/4BED-MASSIONETTE-1B.png" class="card-img-top" alt="4-bedroom cozy maisonette in city center" loading="lazy">
                        <div class="card-body">
                        <h5 class="card-title">Cozy Maisonette</h5>
                        <p class="card-text">A beautiful 4-bedroom maisonette in the city center.</p>
                        <div class="specs mb-3"><i class="fas fa-bed"></i> 4 Beds <i class="fas fa-bath"></i> 3 Baths <i class="fas fa-ruler-combined"></i> 2500 sqft</div>
                        <p class="price-text">₦850,000,000</p>
                        <a href="property-details.php" class="btn btn-primary">View Details</a>
                        <span class="badge verified ms-5">Verified</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                <div class="card h-100">
                        <span class="badge bg-primary mb-2">For Rent</span>
                    <img src="image/4-DETACHED-700M.png" class="card-img-top" alt="Spacious detached family house with patio" loading="lazy">
                    <div class="card-body">
                    <h5 class="card-title">Spacious House</h5>
                    <p class="card-text">A family-friendly house with a patio.</p>
                    <div class="specs mb-3"><i class="fas fa-bed"></i> 4 Beds <i class="fas fa-bath"></i> 3 Baths <i class="fas fa-ruler-combined"></i> 3000 sqft</div>
                    <p class="price-text">₦2,500,000</p>
                    <a href="property-details.php" class="btn btn-primary">View Details</a>
                    <span class="badge verified ms-5">Verified</span>
                    </div>
                </div>
                </div>

                <div class="col-md-4">
                <div class="card h-100">
                    <span class="badge bg-success mb-2">For Sale</span>

                    <img src="image/5-BED-DUPE-500MI.png" class="card-img-top" alt="Luxury condo apartment for modern living" loading="lazy">
                    <div class="card-body">
                    <h5 class="card-title">Luxury Condo</h5>
                    <p class="card-text">A sleek studio perfect for singles.</p>
                    <div class="specs mb-3"><i class="fas fa-bed"></i> 1 Bed <i class="fas fa-bath"></i> 1 Bath <i class="fas fa-ruler-combined"></i> 800 sqft</div>
                    <p class="price-text">₦500,000,000</p>
                    <a href="property-details.php" class="btn btn-primary">View Details</a>
                    <span class="badge verified ms-5">Verified</span>
                    </div>
                </div>
                </div>

                <div class="col-md-4">
                <div class="card h-100">
                    <span class="badge bg-primary mb-2">For Rent</span>

                    <img src="image/2BEDROOM3BED.png" class="card-img-top" alt="Luxury apartment with city skyline view" loading="lazy">
                    <div class="card-body">
                    <h5 class="card-title">Luxury Apartments</h5>
                    <p class="card-text">High-end apartment with city views.</p>
                    <div class="specs mb-3"><i class="fas fa-bed"></i> 2 Beds <i class="fas fa-bath"></i> 2 Baths <i class="fas fa-ruler-combined"></i> 1200 sqft</div>
                    <p class="price-text">₦8,000,000</p>
                    <a href="property-details.php" class="btn btn-primary">View Details</a>
                    <span class="badge verified ms-5">Verified</span>
                    </div>
                </div>
                </div>

                <div class="col-md-4">
                <div class="card h-100">
                    <span class="badge bg-success mb-2">For Sale</span>

                    <img src="image/NEW-5-850.png" class="card-img-top" alt="Large charming mansion in quiet neighborhood" loading="lazy">
                    <div class="card-body">
                    <h5 class="card-title">Charming Mansion</h5>
                    <p class="card-text">Quaint mansion in a quiet neighborhood.</p>
                    <div class="specs mb-3"><i class="fas fa-bed"></i> 5 Beds <i class="fas fa-bath"></i> 4 Baths <i class="fas fa-ruler-combined"></i> 4000 sqft</div>
                    <p class="price-text">₦389,800,000</p>
                    <a href="property-details.php" class="btn btn-primary">View Details</a>
                    <span class="badge verified ms-5">Verified</span>
                    </div>
                </div>
                </div>

                <div class="col-md-4">
                <div class="card h-100">
                    <span class="badge bg-primary mb-2">For Rent</span>

                    <img src="image/2-BED-3-BED.png" class="card-img-top" alt="Urban loft apartment in city center" loading="lazy">
                    <div class="card-body">
                    <h5 class="card-title">Urban Loft</h5>
                        <p class="card-text">Modern loft apartment in the heart of the city.</p>
                        <div class="specs mb-3"><i class="fas fa-bed"></i> 2 Beds <i class="fas fa-bath"></i> 2 Baths <i class="fas fa-ruler-combined"></i> 1100 sqft</div>
                        <p class="price-text">₦3,200,000</p>
                    <a href="property-details.php" class="btn btn-primary">View Details</a>
                    <span class="badge verified ms-5">Verified</span>
                    </div>
                </div>
                </div>
            </div>

            <!-- Location Section -->
                <div class="property-details-section mt-3">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Map</h3>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3939.8293635885577!2d3.8367437!3d6.5243801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bf53280e7745d%3A0x4d8f8f8f8f8f8f8f!2sLagos%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1704091234567" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2026 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <script src="bootstrap/js/bootstrap.js"></script>
</body>
</html>
