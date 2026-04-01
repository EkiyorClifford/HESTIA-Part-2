<?php
session_start();
require_once '../userguard.php';
require_once '../classes/User.php';
require_once '../classes/Wishlist.php';
require_once '../classes/Inspection.php';
require_once '../classes/Applications.php';

$user = new User();
$wishlistObj = new Wishlist();
$inspectionObj = new Inspection();
$applicationsObj = new Applications();

$usr_id = $_SESSION['user_id'] ?? 0;
$usr = $user->get_user_by('id', $usr_id);
$tenant_user = $usr;

$my_wishlist = $wishlistObj->get_user_wishlist($usr_id);
$my_inspections = $inspectionObj->get_tenant_inspections($usr_id);
$my_applications = $applicationsObj->get_tenant_applications($usr_id);

$saved_count = count($my_wishlist);
$count_apps = count($my_applications);

$top_state = 'Lagos';
if (!empty($my_wishlist)) {
    $states = array_column($my_wishlist, 'state_name');
    $state_counts = array_count_values($states);
    arsort($state_counts);
    $top_state = array_key_first($state_counts);
}

$avg_price = 0;
if (!empty($my_wishlist)) {
    $total_price = array_sum(array_column($my_wishlist, 'amount'));
    $avg_price = $total_price / count($my_wishlist);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist | Hestia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/landlord-profile.css">
    <link rel="stylesheet" href="../assets/tenant-profile.css">
    <link rel="stylesheet" href="../assets/wishlist.css">
</head>
<body class="tenant-dashboard-page tenant-wishlist-page">
    <?php include_once "../partials/nav.php"; ?>

    <main class="container py-4 py-lg-5">
        <?php include '../partials/messages.php'; ?>

        <section class="wishlist-hero">
            <div class="wishlist-hero-copy">
                <p class="eyebrow">Wishlist</p>
                <h1><?= htmlspecialchars($tenant_user['first_name'] ?? 'Tenant') ?>, here are the homes you bookmarked</h1>
                <p>You have <?= number_format($saved_count) ?> saved <?= $saved_count === 1 ? 'property' : 'properties' ?>, with interest spread across <?= htmlspecialchars($top_state) ?> and beyond.</p>
            </div>
            <div class="wishlist-summary-grid">
                <div class="wishlist-summary-card">
                    <span class="summary-label">Saved Homes</span>
                    <span class="summary-value"><?= number_format($saved_count) ?></span>
                </div>
                <div class="wishlist-summary-card">
                    <span class="summary-label">Applications</span>
                    <span class="summary-value"><?= number_format(count($my_applications)) ?></span>
                </div>
                <div class="wishlist-summary-card">
                    <span class="summary-label">Inspections</span>
                    <span class="summary-value"><?= number_format(count($my_inspections)) ?></span>
                </div>
                <div class="wishlist-summary-card">
                    <span class="summary-label">Average Price</span>
                    <span class="summary-value">&#8358;<?= number_format($avg_price) ?></span>
                </div>
            </div>
        </section>

        <section class="wishlist-toolbar">
            <div class="wishlist-toolbar-copy">
                <h2>Shortlist</h2>
                <p>Sort, review, and prune your saved homes as you narrow things down.</p>
            </div>
            <div class="wishlist-toolbar-controls">
                <label for="wishlistSort" class="visually-hidden">Sort wishlist</label>
                <select id="wishlistSort" class="form-select">
                    <option value="recent">Recently saved</option>
                    <option value="price-asc">Price: low to high</option>
                    <option value="price-desc">Price: high to low</option>
                    <option value="title">Title A-Z</option>
                    <option value="location">Location A-Z</option>
                </select>
                <a href="../views/properties.php" class="btn btn-primary">Browse more homes</a>
            </div>
        </section>

        <?php if (!empty($my_wishlist)) { ?>
            <section class="wishlist-grid" id="wishlistGrid">
                <?php foreach ($my_wishlist as $index => $w) { ?>
                    <article
                        class="wishlist-card"
                        data-index="<?= (int) $index ?>"
                        data-title="<?= htmlspecialchars(strtolower($w['title'] ?? '')) ?>"
                        data-location="<?= htmlspecialchars(strtolower(($w['lga_name'] ?? '') . ' ' . ($w['state_name'] ?? ''))) ?>"
                        data-price="<?= (float) ($w['amount'] ?? 0) ?>"
                    >
                        <div class="wishlist-card-media">
                            <img src="../upload/properties/<?= htmlspecialchars($w['thumbnail'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($w['title'] ?? 'Saved property') ?>">
                            <span class="wishlist-badge <?= ($w['listing_type'] ?? 'rent') === 'sale' ? 'sale' : 'rent' ?>">
                                For <?= htmlspecialchars(ucfirst($w['listing_type'] ?? 'rent')) ?>
                            </span>
                            <a href="../process/process_wishlist.php?prop_id=<?= (int) $w['property_id'] ?>" class="wishlist-remove-btn" aria-label="Remove from wishlist">
                                <i class="fas fa-heart-crack"></i>
                            </a>
                        </div>
                        <div class="wishlist-card-body">
                            <div class="wishlist-card-top">
                                <div>
                                    <h3><?= htmlspecialchars($w['title'] ?? 'Untitled property') ?></h3>
                                    <p><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars(($w['lga_name'] ?? 'Unknown area') . ', ' . ($w['state_name'] ?? 'Unknown state')) ?></p>
                                </div>
                                <span class="wishlist-card-chip"><?= htmlspecialchars($w['furnished'] ?? 'N/A') ?></span>
                            </div>

                            <div class="wishlist-card-price">&#8358;<?= number_format((float) ($w['amount'] ?? 0)) ?></div>

                            <div class="wishlist-card-meta">
                                <span><i class="fas fa-bed"></i> <?= (int) ($w['bedroom'] ?? 0) ?> Beds</span>
                                <span><i class="fas fa-home"></i> <?= htmlspecialchars(ucfirst($w['status'] ?? 'available')) ?></span>
                            </div>

                            <div class="wishlist-card-actions">
                                <a href="../views/property-details.php?property_id=<?= (int) $w['property_id'] ?>" class="action-btn edit">View details</a>
                                <a href="../tenant/tenant-profile.php#applications-section" class="action-btn view">Track activity</a>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </section>
        <?php } else { ?>
            <section class="empty-wishlist-panel">
                <div class="empty-state">
                    <i class="fas fa-heart-crack"></i>
                    <h3>Your wishlist is empty</h3>
                    <p>Start saving the homes that feel promising so you can compare them later without losing track.</p>
                    <a href="../views/properties.php" class="btn btn-primary">Explore properties</a>
                </div>
            </section>
        <?php } ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sortSelect = document.getElementById('wishlistSort');
        const wishlistGrid = document.getElementById('wishlistGrid');

        if (sortSelect && wishlistGrid) {
            sortSelect.addEventListener('change', function () {
                const cards = Array.from(wishlistGrid.querySelectorAll('.wishlist-card'));

                cards.sort((a, b) => {
                    const sortMode = this.value;

                    if (sortMode === 'price-asc') {
                        return Number(a.dataset.price) - Number(b.dataset.price);
                    }

                    if (sortMode === 'price-desc') {
                        return Number(b.dataset.price) - Number(a.dataset.price);
                    }

                    if (sortMode === 'title') {
                        return a.dataset.title.localeCompare(b.dataset.title);
                    }

                    if (sortMode === 'location') {
                        return a.dataset.location.localeCompare(b.dataset.location);
                    }

                    return Number(a.dataset.index) - Number(b.dataset.index);
                });

                cards.forEach((card) => wishlistGrid.appendChild(card));
            });
        }
    </script>
</body>
</html>