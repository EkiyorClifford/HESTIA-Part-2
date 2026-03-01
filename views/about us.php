<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - HESTIA Property Rentals</title>
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

        .hero-section {
            background: linear-gradient(135deg, #1A0F1E 0%, #5A2E55 30%, #8C3E2C 100%);
            color: white;
            padding: 80px 0 100px;
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

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            font-weight: 300;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .about-intro {
            background: white;
            padding: 60px 0;
            margin: -50px 0 40px 0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .about-intro h2 {
            color: #1A0F1E;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .about-intro p {
            color: #555;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .intro-highlight {
            color: #C44536;
            font-weight: 700;
        }

        .feature-section {
            padding: 60px 0;
            background: white;
            margin-bottom: 60px;
        }

        .feature-section h2 {
            text-align: center;
            color: #1A0F1E;
            font-weight: 800;
            font-size: 2.3rem;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
        }

        .feature-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }

        .feature-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 35px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: #C44536;
            box-shadow: 0 15px 40px rgba(196, 69, 54, 0.15);
            background: white;
        }

        .feature-icon {
            font-size: 3rem;
            color: #C44536;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-card h4 {
            color: #1A0F1E;
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #666;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .team-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .team-section h2 {
            text-align: center;
            color: #1A0F1E;
            font-weight: 800;
            font-size: 2.3rem;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
        }

        .team-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }

        .team-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .team-img {
            height: 250px;
            overflow: hidden;
            background: linear-gradient(135deg, #1A0F1E 0%, #5A2E55 100%);
        }

        .team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .team-card:hover .team-img img {
            transform: scale(1.05);
        }

        .team-info {
            padding: 25px;
        }

        .team-info h5 {
            color: #1A0F1E;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .team-role {
            color: #C44536;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .stats-section {
            padding: 60px 0;
            background: linear-gradient(90deg, #1A0F1E 0%, #5A2E55 100%);
            color: white;
        }

        .stat-box {
            text-align: center;
            margin-bottom: 40px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #E67E51;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        .gallery-section {
            padding: 60px 0;
            background: white;
        }

        .gallery-section h2 {
            text-align: center;
            color: #1A0F1E;
            font-weight: 800;
            font-size: 2.3rem;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
        }

        .gallery-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }

        .gallery-img {
            height: 280px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .gallery-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-img:hover img {
            transform: scale(1.1);
        }

        .map-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .map-section h2 {
            text-align: center;
            color: #1A0F1E;
            font-weight: 800;
            font-size: 2.3rem;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 20px;
        }

        .map-section h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 2px;
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            height: 500px;
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

        .cta-section {
            padding: 40px;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border-radius: 15px;
            text-align: center;
            color: white;
            margin-bottom: 60px;
        }

        .cta-section h3 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .cta-section p {
            font-size: 1.05rem;
            margin-bottom: 25px;
        }

        .btn-primary {
            background: white;
            color: #C44536;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px;
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #f0f0f0;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            color: #C44536;
        }

        .timeline {
            position: relative;
            padding: 40px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #C44536 0%, #E67E51 100%);
        }

        .timeline-item {
            margin-bottom: 50px;
            position: relative;
        }

        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 0;
            margin-right: auto;
            width: calc(50% - 30px);
            text-align: right;
        }

        .timeline-item:nth-child(even) .timeline-content {
            margin-left: auto;
            margin-right: 0;
            width: calc(50% - 30px);
            text-align: left;
        }

        .timeline-dot {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border: 4px solid #C44536;
            border-radius: 50%;
            top: 0;
            z-index: 2;
        }

        .timeline-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .timeline-content h4 {
            color: #C44536;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .timeline-content p {
            color: #666;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.2rem;
            }

            .timeline::before {
                left: 30px;
            }

            .timeline-item:nth-child(odd) .timeline-content,
            .timeline-item:nth-child(even) .timeline-content {
                width: calc(100% - 80px);
                margin-left: 60px;
                text-align: left;
            }

            .timeline-dot {
                left: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">HESTIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="properties.php">Browse Properties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Login / Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>About HESTIA</h1>
            <p>Revolutionizing the property rental market with trust, innovation, and excellence</p>
        </div>
    </section>
    

    <main>
        <!-- About Intro -->
        <div class="container">
            <section class="about-intro">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h2>Your Trusted Property Partner</h2>
                        <p>At <span class="intro-highlight">HESTIA</span>, we believe that finding the perfect home should be simple, transparent, and worry-free. Founded in 2020, we've been dedicated to revolutionizing the property rental industry by connecting quality properties with discerning renters across the nation.</p>
                        <p>Our mission is to transform the way people search, compare, and rent properties. We combine cutting-edge technology with human expertise to provide an unparalleled experience that builds lasting relationships between landlords and tenants.</p>
                        <p>With over <span class="intro-highlight">10,000+ verified properties</span> and <span class="intro-highlight">50,000+ satisfied customers</span>, HESTIA has become the go-to platform for property rentals in Nigeria.</p>
                    </div>
                    <div class="col-lg-6 logo-gradient">
                        <img src="image/ChatGPT Image Feb 4, 2026, 10_33_10 PM.png" alt="Premium Properties" class="img-fluid rounded-4" style="box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);">
                    </div>
                </div>
            </section>
        </div>

        <!-- Features Section -->
        <section class="feature-section">
            <div class="container">
                <h2>Why Choose HESTIA?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fa-solid fa-house"></i></div>
                            <h4>Wide Selection</h4>
                            <p>Browse thousands of verified properties from cozy studios to luxury penthouses. Our diverse portfolio ensures there's something for everyone's budget and lifestyle.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fa-solid fa-circle-check"></i></div>
                            <h4>Verified Listings</h4>
                            <p>Every property undergoes rigorous verification and quality checks. Our team personally inspects properties to guarantee authenticity and meet our high standards.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fa-solid fa-comment-dots"></i></div>
                            <h4>24/7 Support</h4>
                            <p>Our dedicated customer service team is available round the clock. Whether you have questions or need assistance, we're here to help you find your perfect home.</p>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mt-2">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
                            <h4>Secure Transactions</h4>
                            <p>Your safety is paramount. We use advanced encryption and secure payment gateways to protect your personal information and financial data.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                            <h4>Quick Booking</h4>
                            <p>Our streamlined booking process gets you from search to confirmation in minutes. No hassle, no delays—just efficient service.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon"><i class="fa-solid fa-earth-africa"></i></div>
                            <h4>Nationwide Coverage</h4>
                            <p>Find properties across major cities and towns nationwide. Our extensive network ensures access to the best properties in your desired location.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Verified Properties</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Happy Customers</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Satisfaction Rate</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="stat-box">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Customer Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Story Timeline -->
        <section class="container py-5">
            <h2 style="text-align: center; color: #1A0F1E; font-weight: 800; font-size: 2.3rem; margin-bottom: 50px; position: relative; padding-bottom: 20px;">Our Journey<span style="display: block; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 4px; background: linear-gradient(90deg, #C44536 0%, #E67E51 100%); border-radius: 2px;"></span></h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2020 - Founded</h4>
                        <p>HESTIA was born with a vision to transform the property rental market and make quality housing accessible to everyone.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2021 - First 1,000 Properties</h4>
                        <p>We reached a major milestone, partnering with verified landlords across major cities to build our initial property portfolio.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2022 - 10,000+ Properties</h4>
                        <p>Our platform expanded to include over 10,000 verified properties, becoming the leading property rental platform in the nation.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2023-Present - Market Leader</h4>
                        <p>Today, HESTIA serves 50,000+ happy customers with a 98% satisfaction rate, continuously innovating to improve the rental experience.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section">
            <div class="container">
                <h2>Featured Properties</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="gallery-img">
                            <img src="image/4BED-MASSIONETTE-1B.png" alt="Cozy Maisonette - 4 Bedrooms" loading="lazy">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gallery-img">
                            <img src="image/5-BED-DUPE-500MI.png" alt="Luxury Condo - Premium Living" loading="lazy">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gallery-img">
                            <img src="image/NEW-5-850.png" alt="Charming Mansion - 5 Bedrooms" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="map-section">
            <div class="container">
                <h2>Find Us Across Nigeria</h2>
                <p style="text-align: center; color: #666; margin-bottom: 40px; font-size: 1.05rem;">HESTIA operates nationwide with properties in all major cities and strategic locations</p>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3939.8293635885577!2d3.8367437!3d6.5243801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bf53280e7745d%3A0x4d8f8f8f8f8f8f8f!2sLagos%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1704091234567" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="container">
            <div class="cta-section">
                <h3>Ready to Find Your Perfect Home?</h3>
                <p>Join thousands of satisfied customers who have found their ideal properties through HESTIA</p>
                <a href="properties.php" class="btn btn-primary">Browse Properties Now</a>
            </div>
        </section>
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
