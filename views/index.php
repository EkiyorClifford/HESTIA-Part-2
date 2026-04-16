<?php
session_start();
require_once __DIR__ . '/../classes/Property.php';

$propertyObj = new Property();
$featured_properties = $propertyObj->get_featured_properties(3);
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
<body data-hestia-page="home">
    <?php include __DIR__ . '/../partials/nav.php'; ?>

    <main>
        <section class="hero-section" id="hestiaHero">
            <div class="container">
                <div class="hestia-hero-default">
                    <h1>Rent Verified Homes, Directly</h1>
                    <p class="lead">
                        Browse verified properties and deal directly with landlords, no agents, no hidden fees.
                    </p>
                    <p class="small text-white-50 mt-3 mb-0 d-none" id="hestiaNightOwlLine">Still browsing at this hour? We respect the dedication.</p>
                    <a href="properties.php" class="btn btn-primary btn-lg me-3 mt-4">Browse Properties</a>
                </div>
                <div class="hestia-hero-olympus d-none">
                    <h1>Welcome to Mount Olympus</h1>
                    <p class="lead">The hearth approves this home search.</p>
                    <a href="properties.php" class="btn btn-primary btn-lg me-3 mt-4">Browse Properties</a>
                </div>
            </div>
        </section>

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

        <section class="container feature-section">
            <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 50px; text-align: center; color: #D4A574;">
                Featured Properties
            </h2>
            <div class="row">
                <?php if (!empty($featured_properties)) { ?>
                    <?php foreach ($featured_properties as $property) { ?>
                        <?php
                        $thumbnail = !empty($property['thumbnail']) ? '../upload/properties/' . $property['thumbnail'] : 'https://via.placeholder.com/600x400?text=Featured+Property';
                        $description = trim($property['description'] ?? '');
                        $excerpt = $description !== '' ? substr($description, 0, 110) . (strlen($description) > 110 ? '...' : '') : 'Featured property now available on Hestia.';
                        $tag = !empty($property['listing_type']) ? 'For ' . ucfirst($property['listing_type']) : 'Featured';
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 hestia-property-card position-relative">
                                <img src="<?= htmlspecialchars($thumbnail) ?>" class="card-img-top" alt="<?= htmlspecialchars($property['title'] ?? 'Featured property') ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($property['title'] ?? 'Untitled property') ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($excerpt) ?></p>
                                    <div class="small text-muted mb-3">
                                        <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars(($property['lga_name'] ?? 'Unknown area') . ', ' . ($property['state_name'] ?? 'Unknown state')) ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3 mt-auto">
                                        <span style="font-size: 1.4rem; font-weight: 800; color: #C44536;">&#8358;<?= number_format($property['amount'] ?? 0) ?><?= ($property['listing_type'] ?? '') === 'rent' ? '/yr' : '' ?></span>
                                        <span style="background: #e3f2fd; color: #C44536; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($tag) ?></span>
                                    </div>
                                    <a href="property-details.php?property_id=<?= $property['property_id'] ?? 0 ?>" class="btn btn-primary w-100">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-12">
                        <div class="text-center py-4 bg-light rounded">
                            <p class="mb-0 text-muted">No featured properties are available right now.</p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>

    <section class="cta-section">
        <div class="container">
            <h2>Find Your Place in a Better Rental Market</h2>
            <p>Join thousands of tenants and landlords who are renting smarter with transparency, control, and no hidden fees.</p>
            <a href="register.php" class="btn btn-light mx-3 mt-3 join-hestia">Join Hestia - It's Free</a>
        </div>
        <div class="text-center mt-3">
            <small>No credit card required. List or search in minutes.</small>
        </div>
    </section>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../partials/hestia-easter-scripts.php'; ?>
</body>
</html>
