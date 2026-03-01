<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - HESTIA Property Rentals</title>
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
        .text-hearth {
            color: #C44536;
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

        .hero-section {
            background: linear-gradient(135deg, #1A0F1E 0%, #5A2E55 30%, #8C3E2C 100%);
            color: white;
            padding: 100px 0 120px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }

        .hero-section .lead {
            font-size: 1.5rem;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
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

        .started-btn {
            border: 2px solid rgba(255, 255, 255, 0.7);
            background: transparent;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 4px;
            padding: 10px 28px;
        }

        .started-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: #C44536;
            color: white;
            transform: translateY(-2px);
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

        .feature-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        }

        .feature-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 50px;
            text-align: center;
            color: #333;
            position: relative;
        }

        .feature-section h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            margin: 20px auto 0;
            border-radius: 2px;
        }

        .feature-card {
            text-align: center;
            padding: 40px 30px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #C44536;
        }

        .feature-card h4 {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.3rem;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.8;
        }

        .cta-section {
            background: linear-gradient(135deg, #5A2E55 0%, #8C3E2C 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .join-hestia {
            background: white;
            color: #8C3E2C;
            font-weight: 700;
            padding: 12px 30px;
            border-radius: 4px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .join-hestia:hover {
            background: #f0f0f0;
            color: #C44536;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.6);
        }
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        }

        .stat-item {
            text-align: center;
            padding: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #8C3E2C;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            color: #666;
            font-weight: 600;
        }

        .footer {
            background: linear-gradient(90deg, #1A0F1E 0%, #2C1B2E 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
        }

        .footer p {
            margin: 0;
            opacity: 0.9;
        }

        .footer-links {
            margin-bottom: 30px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            transition: opacity 0.3s ease;
            opacity: 0.8;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        section {
            padding: 60px 0;
        }

        main {
            flex-grow: 1;
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
                <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact</a> 
                </li>
                <li class="nav-item">
                <a class="nav-link" href="about us.php">About</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>
 

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <h1>Rent Verified Homes, Directly</h1>
                    <p class="lead">
                    Browse verified properties and deal directly with landlords — no agents, no hidden fees.
                    </p>
                <a href="properties.php" class="btn btn-primary btn-lg me-3 mt-4" >Browse Properties</a>
            </div>
        </section>

         <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Active Properties</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Happy Tenants</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">1K+</div>
                            <div class="stat-label">Verified Landlords</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Customer Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="h2 fw-bold text-center mb-4">How Hestia Works</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card h-100 text-center p-4 border-0 shadow-sm">
                            <i class="fa-solid fa-magnifying-glass fs-2 text-hearth mb-2"></i>
                            <h5 class="fw-bold">Browse & Save</h5>
                            <p class="text-muted mb-0">Explore verified listings & save favorites.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 text-center p-4 border-0 shadow-sm">
                            <i class="fa-regular fa-calendar-check fs-2 text-hearth mb-2"></i>
                            <h5 class="fw-bold">Book & Inspect</h5>
                            <p class="text-muted mb-0">Book inspections directly with landlords.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card h-100 text-center p-4 border-0 shadow-sm">
                            <i class="fa-solid fa-shield fs-2 text-hearth mb-2"></i>
                            <h5 class="fw-bold">Secure & Move</h5>
                            <p class="text-muted mb-0">Complete agreements & move in with confidence.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Featured Properties Section -->
        <section class="container feature-section">
            <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 50px; text-align: center; color: #D4A574;">
                Featured Properties
            </h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://i.pinimg.com/474x/38/e4/b8/38e4b8a28a28a228c3eec995a0be3a39.jpg" class="card-img-top" alt="Cozy Apartment">
                        <div class="card-body">
                            <h5 class="card-title">Modern Downtown Apartment</h5>
                            <p class="card-text">Luxurious 2-bedroom apartment with stunning city views, modern amenities, and walking distance to restaurants.</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="font-size: 1.4rem; font-weight: 800; color: #C44536;">₦500,000/yr</span>
                                <span style="background: #e3f2fd; color: #8C3E2C; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Affordable</span>
                            </div>
                            <a href="property-details.php" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://i.ibb.co/7tT4PLkL/29-Two-Story-Suburban-Houses-That-Embrace-Family-Friendly-Living.jpg" class="card-img-top" alt="Spacious House">
                        <div class="card-body">
                            <h5 class="card-title">Family-Friendly House</h5>
                            <p class="card-text">Beautiful 4-bedroom house with spacious garden, garage, and great neighborhood. Perfect for families.</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="font-size: 1.4rem; font-weight: 800; color: #C44536;">₦6,500,000/yr</span>
                                <span style="background: #e3f2fd; color: #C44536; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">Premium 
                                    Listing</span>
                            </div>
                            <a href="property-details.php" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://korrecthomes.ng/wp-content/uploads/2024/05/Studio-Aprtment-1536x864.webp" class="card-img-top" alt="Modern Studio">
                        <div class="card-body">
                            <h5 class="card-title">Chic Studio Loft</h5>
                            <p class="card-text">Contemporary studio with high ceilings, open concept design, and modern finishes. Ideal for professionals.</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="font-size: 1.4rem; font-weight: 800; color: #C44536;">₦900,000/yr</span>
                                <span style="background: #e3f2fd; color: #C44536; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">In Demand</span>
                            </div>
                            <a href="property-details.php" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </main>

     <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <h2>Find Your Place in a Better Rental Market</h2>
                <p>Join thousands of tenants and landlords who are renting smarter—with transparency, control, and no hidden fees.</p>
                <a href="register.php" class="btn btn-light mx-3 mt-3 join-hestia">Join Hestia — It's Free</a>
            </div>
            <div class="text-center mt-3">
            <small>No credit card required. List or search in minutes.</small>
            </div>
        </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4">
                    <h5 style="color: white; font-weight: 700; margin-bottom: 15px;">About HESTIA</h5>
                    <p style="opacity: 0.8; line-height: 1.6;">Your trusted platform for finding the perfect rental property with verified listings and secure transactions.</p>
                </div>
                <div class="col-md-4">
                    <h5 style="color: white; font-weight: 700; margin-bottom: 15px;">Quick Links</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 8px;"><a href="properties.php" style="color: white; text-decoration: none; opacity: 0.8; transition: opacity 0.3s;">Browse Properties</a></li>
                        <li style="margin-bottom: 8px;"><a href="register.php" style="color: white; text-decoration: none; opacity: 0.8; transition: opacity 0.3s;">Register</a></li>
                        <li style="margin-bottom: 8px;"><a href="contact.php" style="color: white; text-decoration: none; opacity: 0.8; transition: opacity 0.3s;">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 style="color: white; font-weight: 700; margin-bottom: 15px;">Contact Us</h5>
                    <p style="opacity: 0.8; margin-bottom: 8px;">Email: info@hestia.com</p>
                    <p style="opacity: 0.8;">Phone: +234 811 2177 604</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <p class="text-center">&copy; 2026 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
