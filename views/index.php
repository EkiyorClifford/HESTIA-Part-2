<?php
session_start();

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - HESTIA Property Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/index.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <!-- Header with Navigation -->
    <?php include '../partials/nav.php'; ?>

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

    <?php include '../partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
