<?php
require dirname(__DIR__) . '/config/app.php';
session_start();
require_once BASE_PATH . '/userguard.php';
require_once BASE_PATH . '/classes/User.php';
require_once BASE_PATH . '/classes/Wishlist.php';
require_once BASE_PATH . '/classes/Inspection.php';
require_once BASE_PATH . '/classes/Applications.php';
require_once BASE_PATH . '/classes/Common.php';

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

$count_saved = count($my_wishlist);
$count_apps = count($my_applications);
$count_inspections = count($my_inspections);
$count_pending_apps = 0;
foreach ($my_applications as $app) {
    if (strtolower($app['status'] ?? '') === 'pending') {
        $count_pending_apps++;
    }
}

$active_tenant_page = 'dashboard';

$common = new Common();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard | Hestia</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co/ccncV96R/Hestia-favicon.png">
    <link rel="shortcut icon" href="https://i.ibb.co/ccncV96R/Hestia-favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/landlord-profile.css">
    <link rel="stylesheet" href="../assets/tenant-profile.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body class="tenant-dashboard-page" data-hestia-dashboard="tenant">
    <?php include BASE_PATH . '/partials/nav.php'; ?>

    <button class="btn btn-primary mobile-menu-btn d-lg-none position-fixed bottom-0 end-0 m-3 rounded-pill shadow" type="button" data-bs-toggle="offcanvas" data-bs-target="#tenantSidebar" style="z-index: 1040;">
        <i class="fas fa-bars"></i> Menu
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="tenantSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Hestia<span>.</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <?php $sidebar_mode = 'mobile'; include BASE_PATH . '/tenant/partials/sidebar.php'; ?>
    </div>

    <div class="dashboard-container tenant-dashboard">
        <?php $sidebar_mode = 'desktop'; include BASE_PATH . '/tenant/partials/sidebar.php'; ?>

        <main class="main-content">
            <?php include BASE_PATH . '/partials/messages.php'; ?>

            <section class="welcome-section tenant-hero">
                <div class="welcome-copy">
                    <p class="eyebrow">Tenant Dashboard</p>
                    <h1>Welcome back, <?= htmlspecialchars($usr['first_name'] ?? 'Tenant') ?></h1>
                    <p>Track saved homes, keep an eye on applications, and stay on top of upcoming inspections from one calm workspace.</p>
                    <p class="small text-muted mt-2 mb-0 d-none" id="hestiaNightOwlLine">Still browsing at this hour? We respect the dedication.</p>
                </div>
                <div class="summary-panel">
                    <div class="summary-item">
                        <span class="summary-label">Saved Homes</span>
                        <span class="summary-value"><?= number_format($count_saved) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Applications</span>
                        <span class="summary-value"><?= number_format($count_apps) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Inspections</span>
                        <span class="summary-value"><?= number_format($count_inspections) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Pending</span>
                        <span class="summary-value"><?= number_format($count_pending_apps) ?></span>
                    </div>
                </div>
            </section>

            <div class="row g-4 stats-grid">
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format($count_saved) ?></div>
                        <div class="text-secondary">Saved Properties</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format($count_apps) ?></div>
                        <div class="text-secondary">Applications</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format($count_inspections) ?></div>
                        <div class="text-secondary">Inspections</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format($count_pending_apps) ?></div>
                        <div class="text-secondary">Pending Updates</div>
                    </div>
                </div>
            </div>

            <section class="section-block">
                <div class="section-header">
                    <h2 class="section-title">Quick Actions</h2>
                </div>
                <div class="quick-actions-grid">
                    <a href="../views/properties.php" class="quick-action-btn"><i class="fas fa-search"></i><span>Browse Properties</span></a>
                    <a href="#saved-section" class="quick-action-btn"><i class="fas fa-heart"></i><span>Saved Homes</span></a>
                    <a href="#applications-section" class="quick-action-btn"><i class="fas fa-file-alt"></i><span>Applications</span></a>
                    <a href="#inspections-section" class="quick-action-btn"><i class="fas fa-calendar-check"></i><span>Inspections</span></a>
                    <a href="../tenant/wishlist.php" class="quick-action-btn"><i class="fas fa-bookmark"></i><span>Wishlist Page</span></a>
                </div>
            </section>

            <div class="row g-4 align-items-start">
                <div class="col-xl-8">
                    <section class="card section-card" id="saved-section">
                        <div class="section-header">
                            <h2 class="section-title">Saved Properties</h2>
                            <a href="../views/properties.php" class="section-link">Browse more</a>
                        </div>

                        <?php if (!empty($my_wishlist)) { ?>
                            <div class="tenant-saved-grid">
                                <?php foreach ($my_wishlist as $w) { ?>
                                    <article class="saved-card">
                                        <div class="saved-card-media">
                                            <img src="../upload/properties/<?= htmlspecialchars($w['thumbnail'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($w['title'] ?? 'Saved property') ?>">
                                            <a href="../process/process_wishlist.php?prop_id=<?= $w['property_id'] ?>" class="saved-remove-btn" aria-label="Remove from saved properties">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                        <div class="saved-card-body">
                                            <div class="saved-card-top">
                                                <div>
                                                    <h3><?= htmlspecialchars($w['title'] ?? 'Untitled property') ?></h3>
                                                    <p><?= htmlspecialchars(($w['lga_name'] ?? 'Unknown area') . ', ' . ($w['state_name'] ?? 'Unknown state')) ?></p>
                                                </div>
                                                <span class="saved-card-tag"><?= htmlspecialchars(ucfirst($w['listing_type'] ?? 'rent')) ?></span>
                                            </div>
                                            <div class="saved-card-price">&#8358;<?= number_format($w['amount'] ?? 0) ?></div>
                                            <div class="saved-card-actions">
                                                <a href="../views/property-details.php?property_id=<?= $w['property_id'] ?>" class="action-btn edit">View Property</a>
                                                <a href="../tenant/wishlist.php" class="action-btn view">Wishlist</a>
                                            </div>
                                        </div>
                                    </article>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="empty-state">
                                <i class="fas fa-heart"></i>
                                <h3>No saved properties yet</h3>
                                <p>Save homes you like so they stay close while you compare options.</p>
                                <a href="../views/properties.php" class="btn btn-primary">Explore properties</a>
                            </div>
                        <?php } ?>
                    </section>

                    <section class="card section-card mt-4" id="inspections-section">
                        <div class="section-header">
                            <h2 class="section-title">Inspection Schedule</h2>
                        </div>

                        <?php if (!empty($my_inspections)) { ?>
                            <div class="applications-list">
                                <?php foreach ($my_inspections as $insp) { ?>
                                    <article class="application-card inspection-card">
                                        <div class="application-top">
                                            <div>
                                                <h3><?= htmlspecialchars($insp['title'] ?? 'Unknown property') ?></h3>
                                                <p><?= htmlspecialchars($insp['lga_name'] ?? 'Unknown area') ?></p>
                                            </div>
                                            <span class="badge-status <?= Common::tenant_status_badge($insp['status'] ?? 'pending') ?>"><?= htmlspecialchars(ucfirst($insp['status'] ?? 'pending')) ?></span>
                                        </div>
                                        <div class="application-meta">
                                            Scheduled for <?= !empty($insp['inspection_date']) ? htmlspecialchars(date('M j, Y', strtotime($insp['inspection_date']))) : 'N/A' ?>
                                        </div>
                                        <div class="saved-card-actions mt-3">
                                            <a href="../views/property-details.php?property_id=<? $insp['property_id'] ?>" class="action-btn view">View Property</a>
                                        </div>
                                    </article>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="empty-state empty-state-compact">
                                <i class="fas fa-calendar-check"></i>
                                <h3>No inspections booked</h3>
                                <p>Your upcoming property visits will appear here.</p>
                            </div>
                        <?php } ?>
                    </section>
                </div>

                <div class="col-xl-4">
                    <section class="card section-card" id="applications-section">
                        <div class="section-header">
                            <h2 class="section-title">Applications</h2>
                        </div>

                        <?php if (!empty($my_applications)) { ?>
                            <div class="applications-list">
                                <?php foreach ($my_applications as $app) { ?>
                                    <article class="application-card">
                                        <div class="application-top">
                                            <div>
                                                <h3><?= htmlspecialchars($app['title'] ?? 'Unknown property') ?></h3>
                                                <p><?= htmlspecialchars(ucfirst($app['listing_type'] ?? 'rent')) ?></p>
                                            </div>
                                            <span class="badge-status <?= Common::tenant_status_badge($app['status'] ?? 'pending') ?>"><?= htmlspecialchars(ucfirst($app['status'] ?? 'pending')) ?></span>
                                        </div>
                                        <div class="application-meta">Applied on <?= !empty($app['created_at']) ? htmlspecialchars(date('M j, Y', strtotime($app['created_at']))) : 'N/A' ?></div>
                                        <p class="tenant-amount">&#8358;<?= number_format(($app['amount'] ?? 0), 2) ?></p>
                                    </article>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="empty-state empty-state-compact">
                                <i class="fas fa-file-alt"></i>
                                <h3>No applications yet</h3>
                                <p>Once you apply for a property, status updates will show here.</p>
                            </div>
                        <?php } ?>
                    </section>

                    <section class="card section-card mt-4">
                        <div class="section-header">
                            <h2 class="section-title">Reviews</h2>
                        </div>
                        <div class="tenant-reviews-card" id="hestiaReviewsTeaser" role="button" tabindex="0" title="Try clicking three times">
                            <div class="tenant-reviews-icon"><i class="fas fa-pen-fancy"></i></div>
                            <h3>Reviews Coming Soon</h3>
                            <p>When post-visit reviews land, this panel will become your place for notes, ratings, and follow-up reflections.</p>
                            <div class="tenant-review-pill">
                                <i class="far fa-bell"></i>
                                Feature in progress
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include BASE_PATH . '/partials/hestia-easter-scripts.php'; ?>
    <script>
        document.querySelectorAll('.offcanvas .nav-link').forEach((link) => {
            link.addEventListener('click', () => {
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('tenantSidebar'));
                if (offcanvas) {
                    offcanvas.hide();
                }
            });
        });
    </script>
</body>
</html>
