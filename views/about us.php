<?php
require dirname(__DIR__) . '/config/app.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - HESTIA Property Rentals</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="icon" type="image/png" href="../favicon.png">
    <link rel="shortcut icon" href="../favicon.ico">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/about_us.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <!-- Navigation -->
    <?php include BASE_PATH . '/partials/nav.php'; ?>

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
                        <picture>
                            <source srcset="image/optimized/ChatGPT Image Feb 4, 2026, 10_33_10 PM.webp" type="image/webp">
                            <source srcset="image/optimized/ChatGPT Image Feb 4, 2026, 10_33_10 PM.png" type="image/png">
                            <img src="image/optimized/ChatGPT Image Feb 4, 2026, 10_33_10 PM.png" alt="Premium Properties" class="img-fluid rounded-4" style="box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);" loading="lazy" decoding="async">
                        </picture>
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
                            <picture>
                            <source srcset="../image/optimized/4BED-MASSIONETTE-1B.webp" type="image/webp">
                            <source srcset="../image/optimized/4BED-MASSIONETTE-1B.png" type="image/png">
                            <img src="../image/optimized/4BED-MASSIONETTE-1B.png" alt="Cozy Maisonette - 4 Bedrooms" loading="lazy" decoding="async">
                        </picture>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gallery-img">
                            <picture>
                            <source srcset="../image/optimized/5-BED-DUPE-500MI.webp" type="image/webp">
                            <source srcset="../image/optimized/5-BED-DUPE-500MI.png" type="image/png">
                            <img src="../image/optimized/5-BED-DUPE-500MI.png" alt="Luxury Condo - Premium Living" loading="lazy" decoding="async">
                        </picture>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="gallery-img">
                            <picture>
                            <source srcset="../image/optimized/NEW-5-850.webp" type="image/webp">
                            <source srcset="../image/optimized/NEW-5-850.png" type="image/png">
                            <img src="../image/optimized/NEW-5-850.png" alt="Charming Mansion - 5 Bedrooms" loading="lazy" decoding="async">
                        </picture>
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
