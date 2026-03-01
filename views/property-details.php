<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details - HESTIA Property Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .breadcrumb {
            background: transparent;
            padding: 20px 0;
            margin: 0;
        }
        .breadcrumb-item.active {
            color: #C44536;
        }
        .badge {
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
        }
        .badge-sale {
            background: #C44536;
            color: white;
        }
        .badge-rent {
            background: #4A90E2;
            color: white;
        }
        .badge-verified {
            background: #28a745;
            color: white;
        }
        .property-image {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }
        .property-image:hover {
            transform: scale(1.02);
        }
        .gallery {
            margin-top: 30px;
            gap: 15px;
        }
        .gallery-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            height: 150px;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        .property-details-card {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            position: sticky;
            top: 100px;
        }
        .property-details-card h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #1A0F1E;
        }
        .price-tag {
            font-size: 1.8rem;
            font-weight: 800;
            color: #C44536;
            margin-bottom: 20px;
            padding: 15px 0;
            border-top: 2px solid #f0f0f0;
            border-bottom: 2px solid #f0f0f0;
        }
        .spec-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px 0;
        }
        .spec-icon {
            font-size: 1.3rem;
            color: #C44536;
            margin-right: 12px;
            width: 35px;
            text-align: center;
        }
        .spec-text {
            flex-grow: 1;
        }
        .spec-label {
            font-size: 0.85rem;
            color: #888;
            font-weight: 500;
        }
        .spec-value {
            font-size: 1.05rem;
            color: #1A0F1E;
            font-weight: 600;
        }
        .amenities-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }
        .amenities-list li {
            background: linear-gradient(135deg, #fce4ec 0%, #f3e5f5 100%);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #C44536;
            border: 1px solid #f8bbd0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .amenities-list li i {
            font-size: 0.85rem;
        }
        .btn-primary {
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(198, 69, 54, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            border-radius: 4px;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #E67E51 0%, #C44536 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(198, 69, 54, 0.6);
            color: white;
        }
        .btn-outline-primary {
            border: 2px solid #C44536;
            color: #C44536;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }
        .btn-outline-primary:hover {
            background-color: #C44536;
            color: white;
            transform: translateY(-2px);
        }
        .property-details-section {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            margin-bottom: 30px;
        }
        .property-details-section h3 {
            font-weight: 800;
            color: #1A0F1E;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
            position: relative;
            padding-bottom: 12px;
        }
        .property-details-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }
        .property-details-section p {
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
            margin-bottom: 15px;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .feature-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .feature-box:hover {
            border-color: #C44536;
            box-shadow: 0 5px 15px rgba(196, 69, 54, 0.15);
        }
        .feature-box i {
            font-size: 2rem;
            color: #C44536;
            margin-bottom: 10px;
        }
        .feature-box h5 {
            color: #1A0F1E;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .feature-box p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
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
        @media (max-width: 768px) {
            .property-details-card {
                position: static;
                margin-bottom: 30px;
            }
            .property-image {
                height: 300px;
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
                    <li class="nav-item"><a class="nav-link" href="register.php">Login / Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container flex-grow-1 py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" style="text-decoration: none; color: #1A0F1E;">Home</a></li>
                <li class="breadcrumb-item"><a href="properties.php" style="text-decoration: none; color: #1A0F1E;">Properties</a></li>
                <li class="breadcrumb-item active">Property Details</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Main Property Content -->
            <div class="col-lg-8">
                <!-- Main Image -->
                <img src="image/4BED-MASSIONETTE-1B.png" alt="Luxury Maisonette" class="property-image mb-4">

                <!-- Gallery -->
                <div class="gallery row">
                    <div class="col-md-4">
                        <div class="gallery-item">
                            <img src="image/4-DETACHED-700M.png" alt="Gallery 1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gallery-item">
                            <img src="image/5-BED-DUPE-500MI.png" alt="Gallery 2">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gallery-item">
                            <img src="image/NEW-5-850.png" alt="Gallery 3">
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="property-details-section mt-5">
                    <h3><i class="fas fa-file-alt"></i> Description</h3>
                    <p>This stunning 4-bedroom maisonette is located in the prestigious city center, offering the perfect blend of modern luxury and comfort. With its elegant architecture and thoughtfully designed spaces, this property is ideal for families or professionals seeking a premium living experience.</p>
                    <p>The residence features spacious rooms with high ceilings, natural lighting, and premium finishes throughout. Each bedroom is ensuite with built-in wardrobes and modern fixtures. The open-plan living area seamlessly connects to a state-of-the-art kitchen equipped with quality appliances and granite countertops.</p>
                    <p>The property includes exclusive amenities such as a private garage, landscaped courtyard, and 24/7 security. Its strategic location provides easy access to shopping malls, restaurants, hospitals, and educational institutions, making it the perfect urban retreat.</p>
                </div>

                <!-- Features Section -->
                <div class="property-details-section">
                    <h3><i class="fas fa-star"></i> Key Features</h3>
                    <div class="feature-grid">
                        <div class="feature-box">
                            <i class="fas fa-bed"></i>
                            <h5>4 Bedrooms</h5>
                            <p>Spacious master and guest suites</p>
                        </div>
                        <div class="feature-box">
                            <i class="fas fa-bath"></i>
                            <h5>3 Bathrooms</h5>
                            <p>Modern and fully equipped</p>
                        </div>
                        <div class="feature-box">
                            <i class="fas fa-ruler-combined"></i>
                            <h5>2,500 Sq ft</h5>
                            <p>Ample living space</p>
                        </div>
                        <div class="feature-box">
                            <i class="fas fa-shield-alt"></i>
                            <h5>24/7 Security</h5>
                            <p>Gated compound with guards</p>
                        </div>
                    </div>
                </div>

                <!-- Amenities Section -->
                <div class="property-details-section">
                    <h3><i class="fas fa-check-circle"></i> Amenities</h3>
                    <ul class="amenities-list">
                        <li><i class="fas fa-parking"></i> Private Parking</li>
                        <li><i class="fas fa-paw"></i> Pet Friendly</li>
                        <li><i class="fas fa-wind"></i> Air Conditioning</li>
                        <li><i class="fas fa-wifi"></i> High-Speed Internet</li>
                        <li><i class="fas fa-leaf"></i> Garden/Lawn</li>
                        <li><i class="fas fa-tv"></i> Smart Home Features</li>
                        <li><i class="fas fa-water"></i> Water Tank</li>
                        <li><i class="fas fa-power-off"></i> Generator Backup</li>
                    </ul>
                </div>

                <!-- Location Section -->
                <div class="property-details-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Location & Map</h3>
                    <p><strong>Address:</strong> 123 Premium Street, City Center, Lagos</p>
                    <p><strong>Nearby Attractions:</strong></p>
                    <ul style="padding-left: 20px; color: #555;">
                        <li>Shopping Malls - 0.5 km</li>
                        <li>International Hospitals - 1 km</li>
                        <li>Top-Rated Schools - 1.5 km</li>
                        <li>Fine Dining Restaurants - 0.8 km</li>
                        <li>Public Transportation - 300 meters</li>
                    </ul>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3939.8293635885577!2d3.8367437!3d6.5243801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bf53280e7745d%3A0x4d8f8f8f8f8f8f8f!2sLagos%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1704091234567" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Property Details Card -->
            <div class="col-lg-4">
                <div class="property-details-card">
                    <div style="margin-bottom: 16px;">
                        <span class="badge badge-sale me-2">For Sale</span>
                        <span class="badge badge-verified">Verified</span>
                    </div>
                    <h2>Luxury Maisonette</h2>
                    <div class="price-tag">₦850,000,000</div>

                    <!-- Quick Specs -->
                    <div class="spec-item">
                        <div class="spec-icon"><i class="fas fa-bed"></i></div>
                        <div class="spec-text">
                            <div class="spec-label">Bedrooms</div>
                            <div class="spec-value">4</div>
                        </div>
                    </div>

                    <div class="spec-item">
                        <div class="spec-icon"><i class="fas fa-bath"></i></div>
                        <div class="spec-text">
                            <div class="spec-label">Bathrooms</div>
                            <div class="spec-value">3</div>
                        </div>
                    </div>

                    <div class="spec-item">
                        <div class="spec-icon"><i class="fas fa-ruler-combined"></i></div>
                        <div class="spec-text">
                            <div class="spec-label">Square Footage</div>
                            <div class="spec-value">2,500 sq ft</div>
                        </div>
                    </div>

                    <div class="spec-item">
                        <div class="spec-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="spec-text">
                            <div class="spec-label">Location</div>
                            <div class="spec-value">City Center</div>
                        </div>
                    </div>

                    <div class="spec-item">
                        <div class="spec-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="spec-text">
                            <div class="spec-label">Year Built</div>
                            <div class="spec-value">2023</div>
                        </div>
                    </div>

                    <hr style="border-color: #e9ecef; margin: 20px 0;">

                    <h5 style="color: #1A0F1E; font-weight: 700; margin-bottom: 12px;">Property Type</h5>
                    <p style="color: #666; margin-bottom: 20px;">Maisonette (Townhouse)</p>

                    <h5 style="color: #1A0F1E; font-weight: 700; margin-bottom: 12px;">Status</h5>
                    <p style="color: #666; margin-bottom: 20px;">Ready to Occupy</p>

                    <!-- Contact Info -->
                    <div style="background: linear-gradient(135deg, #fce4ec 0%, #f3e5f5 100%); padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #f8bbd0;">
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 8px;"><i class="fas fa-phone" style="color: #C44536; margin-right: 8px;"></i>+234 (0) 811 2177 604</p>
                        <p style="color: #666; font-size: 0.9rem; margin: 0;"><i class="fas fa-envelope" style="color: #C44536; margin-right: 8px;"></i>info@hestia.com</p>
                    </div>

                    <!-- Action Buttons -->
                    <button class="btn btn-primary w-100 mb-2"><i class="fas fa-eye me-2"></i> Request Viewing</button>
                    <button class="btn btn-outline-primary w-100 mb-2"><i class="fas fa-heart me-2"></i> Add to Wishlist</button>
                    <button class="btn btn-outline-primary w-100"><i class="fas fa-share-alt me-2"></i> Share Property</button>
                </div>
            </div>
        </div>
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
