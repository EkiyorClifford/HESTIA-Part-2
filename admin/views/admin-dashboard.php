<?php
require dirname(__DIR__, 2) . '/config/app.php';
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require_once BASE_PATH . '/admin/classes/Admin.php';
require_once BASE_PATH . '/admin/classes/Common.php';

$admin = new Admin();

// fetch data
$dashboard_totals = $admin->get_dashboard_totals();
$property_stats = $admin->get_property_status_stats();
$user_roles = $admin->get_user_role_stats();
$top_locations = $admin->get_top_locations();
$pending_properties = $admin->get_pending_properties();
$recent_properties = $admin->get_recent_properties();
$recent_applications = $admin->get_recent_applications();
$today = $admin->get_todays_activity();

// process data
$stats = array_merge([
    'total_users' => 0,
    'total_properties' => 0,
    'total_inspections' => 0,
    'total_applications' => 0,
], $dashboard_totals);

$property_counts = [
    'available' => 0,
    'taken' => 0,
    'inactive' => 0,
];

foreach (($property_stats ?: []) as $row) {
    $status_key = strtolower(trim($row['status'] ?? ''));
    if (array_key_exists($status_key, $property_counts)) {
        $property_counts[$status_key] = $row['count'] ?? 0;
    }
}

$user_counts = [
    'landlord' => 0,
    'tenant' => 0,
    'admin' => 1,
];

foreach ($user_roles as $row) {
    $role_key = strtolower(trim($row['role_'] ?? ''));
    if (array_key_exists($role_key, $user_counts)) {
        $user_counts[$role_key] = $row['count'] ?? 0;
    }
}

$today = array_merge([
    'new_users' => 0,
    'new_props' => 0,
    'inspections' => 0,
], $today);
// progress bar calculations
$total_properties = max(1, $stats['total_properties']);
$available_width = min(100, ($property_counts['available'] / $total_properties) * 100);
$taken_width = min(100, ($property_counts['taken'] / $total_properties) * 100);
$inactive_width = min(100, ($property_counts['inactive'] / $total_properties) * 100);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Hestia</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co/ccncV96R/Hestia-favicon.png">
    <link rel="shortcut icon" href="https://i.ibb.co/ccncV96R/Hestia-favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Serif:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/admin.css" rel="stylesheet">
</head>
<body>
    <?php include BASE_PATH . '/admin/partials/navbar.php'; ?>

    <main class="admin-page">
        <div class="container">
            <div class="admin-shell">
                <?php include BASE_PATH . '/admin/partials/sidebar.php'; ?>

                <div class="admin-content">
                    <div class="welcome-card">
                        <div>
                            <h3>Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Administrator') ?>!</h3>
                            <p class="lead">Everything you need to run Hestia — clean, live, and in one place.</p>
                        </div>
                        <div class="date-badge">
                            <i class="far fa-calendar-alt me-2"></i> <?= date('F j, Y') ?>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-title">
                            <i class="fas fa-bolt"></i> QUICK ACTIONS
                        </div>
                        <div class="quick-actions">
                            <a href="/Hestia-PHP/views/properties.php" class="quick-action-btn">
                                <i class="fas fa-building"></i>
                                <span>Browse Properties</span>
                            </a>
                            <a href="/Hestia-PHP/admin/views/user-details.php?filter=landlord" class="quick-action-btn">
                                <i class="fas fa-user-tie"></i>
                                <span>View Landlords</span>
                            </a>
                            <a href="/Hestia-PHP/admin/views/user-details.php?filter=tenant" class="quick-action-btn">
                                <i class="fas fa-user"></i>
                                <span>View Tenants</span>
                            </a>
                            <a href="#recent-applications" class="quick-action-btn">
                                <i class="fas fa-file-signature"></i>
                                <span>Recent Applications</span>
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-users"></i></div>
                                <div class="stat-label">TOTAL USERS</div>
                                <div class="stat-number"><?= number_format($stats['total_users']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-building"></i></div>
                                <div class="stat-label">PROPERTIES</div>
                                <div class="stat-number"><?= number_format($stats['total_properties']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-file-signature"></i></div>
                                <div class="stat-label">APPLICATIONS</div>
                                <div class="stat-number"><?= number_format($stats['total_applications']) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="section-card">
                                <div class="section-title">
                                    <i class="fas fa-hourglass-half"></i> PENDING PROPERTIES
                                </div>
                                <?php if (!empty($pending_properties)) { ?>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Landlord</th>
                                                    <th>Submitted</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pending_properties as $property) { ?>
                                                    <tr>
                                                        <td style="text-transform: capitalize;">
                                                            <?= htmlspecialchars($property['title'] ?? 'Untitled property') ?>
                                                        </td>
                                                        <td style="text-transform: capitalize;">
                                                            <?= htmlspecialchars(trim(($property['first_name'] ?? '') . ' ' . ($property['last_name'] ?? ''))) ?>
                                                        </td>
                                                        <td><?= !empty($property['created_at']) ? htmlspecialchars(date('M j, Y', strtotime($property['created_at']))) : 'N/A' ?></td>
                                                        <td>
                                                            <a href="/Hestia-PHP/admin/property-review.php?id=<?= $property['property_id'] ?>" class="view-link">
                                                                <i class="fas fa-eye me-1"></i> Review
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else { ?>
                                    <div class="empty-state" id="hestiaAdminPeaceEgg">
                                        <i class="fas fa-check-circle"></i>
                                        <h6>No pending properties</h6>
                                        <p class="small fst-italic text-secondary mb-2 mb-md-3">The queue is clean. Enjoy this suspicious peace.</p>
                                        <p class="small mb-0">New landlord submissions will appear here for review.</p>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="section-card">
                                <div class="section-title">
                                    <i class="fas fa-building"></i> RECENT PROPERTIES
                                </div>
                                <?php if (!empty($recent_properties)) { ?>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Status</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_properties as $property) { ?>
                                                    <?php
                                                    $status = strtolower(trim($property['status'] ?? 'inactive'));
                                                    $badge_class = 'badge-inactive';
                                                    if ($status === 'available' || $status === 'active') {
                                                        $badge_class = 'badge-active';
                                                    } elseif ($status === 'taken' || $status === 'pending') {
                                                        $badge_class = 'badge-pending';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td style="text-transform: capitalize;">
                                                            <?= htmlspecialchars(($property['title'] ?? 'Untitled property') . ', ' . ($property['lga_name'] ?? 'Unknown area')) ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge <?= $badge_class ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                                        </td>
                                                        <td>&#8358;<?= number_format($property['amount'] ?? 0) ?></td>
                                                        <td>
                                                            <div class="table-actions">
                                                                <a href="/Hestia-PHP/views/property-details.php?property_id=<?= $property['property_id'] ?>" class="view-link">
                                                                    <i class="fas fa-eye me-1"></i> View
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else { ?>
                                    <div class="empty-state">
                                        <i class="fas fa-building"></i>
                                        <h6>No properties yet</h6>
                                        <p class="small mb-0">Properties will appear here once landlords add them to the platform.</p>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="section-card" id="recent-applications">
                                <div class="section-title">
                                    <i class="fas fa-file-signature"></i> RECENT APPLICATIONS
                                </div>
                                <?php if (!empty($recent_applications)) { ?>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tenant</th>
                                                    <th>Property</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_applications as $application) { ?>
                                                    <?php $app_status = strtolower(trim($application['status'] ?? 'pending')); ?>
                                                    <tr>
                                                        <td style="text-transform: capitalize;">
                                                            <?= htmlspecialchars(trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''))) ?>
                                                        </td>
                                                        <td style="text-transform: capitalize;"><?= htmlspecialchars($application['title'] ?? 'Unknown property') ?></td>
                                                        <td>
                                                            <span class="badge <?= Common::application_status_badge($app_status) ?>">
                                                                <?= htmlspecialchars(ucfirst($app_status)) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="/Hestia-PHP/views/property-details.php?property_id=<?= $application['property_id'] ?>" class="view-link">
                                                                <i class="fas fa-eye me-1"></i> View Property
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else { ?>
                                    <div class="empty-state">
                                        <i class="fas fa-file-signature"></i>
                                        <h6>No applications yet</h6>
                                        <p class="small mb-0">Applications will show up here as soon as tenants submit them.</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="section-card">
                                <div class="section-title">
                                    <i class="fas fa-chart-pie"></i> PROPERTY STATUS
                                </div>
                                <div class="metric-stack">
                                    <div>
                                        <div class="stat-item">
                                            <span class="label">Available</span>
                                            <span class="value"><?= number_format($property_counts['available']) ?></span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: <?= $available_width ?>%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="stat-item">
                                            <span class="label">Taken</span>
                                            <span class="value"><?= number_format($property_counts['taken']) ?></span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width: <?= $taken_width ?>%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="stat-item">
                                            <span class="label">Inactive</span>
                                            <span class="value"><?= number_format($property_counts['inactive']) ?></span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" style="width: <?= $inactive_width ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-card">
                                <div class="section-title">
                                    <i class="fas fa-user-friends"></i> USER BREAKDOWN
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between py-3 border-bottom border-1" style="border-bottom-style: dashed !important; border-color: var(--border-light);">
                                        <span class="text-secondary">Landlords</span>
                                        <span class="fw-semibold" style="color: var(--orange-deep);"><?= number_format($user_counts['landlord']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between py-3 border-bottom border-1" style="border-bottom-style: dashed !important; border-color: var(--border-light);">
                                        <span class="text-secondary">Tenants</span>
                                        <span class="fw-semibold" style="color: var(--orange-deep);"><?= number_format($user_counts['tenant']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between py-3">
                                        <span class="text-secondary">Admins</span>
                                        <span class="fw-semibold" style="color: var(--orange-deep);"><?= number_format($user_counts['admin']) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="section-card">
                                <div class="section-title">
                                    <i class="fas fa-map-marker-alt"></i> TOP LOCATIONS
                                </div>
                                <?php if (!empty($top_locations)) { ?>
                                    <?php foreach ($top_locations as $location) { ?>
                                        <div class="location-item">
                                            <span class="name"><?= htmlspecialchars($location['state_name']) ?></span>
                                            <span class="count"><?= number_format($location['count']) ?> properties</span>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="empty-state">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <h6>No location data yet</h6>
                                        <p class="small mb-0">Location insights will appear once properties are available.</p>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="section-card">
                                <div class="section-title">
                                    <i class="fas fa-calendar-day"></i> TODAY'S NUMBERS
                                </div>
                                <div class="today-grid">
                                    <div class="today-item text-center">
                                        <div class="today-label" style="text-transform: uppercase; font-weight: 500;">New Users</div>
                                        <div class="today-number"><?= number_format($today['new_users']) ?></div>
                                    </div>
                                    <div class="today-item text-center">
                                        <div class="today-label" style="text-transform: uppercase; font-weight: 500;">New Properties</div>
                                        <div class="today-number"><?= number_format($today['new_props']) ?></div>
                                    </div>
                                    <div class="today-item full-width text-center muted">
                                        <div class="today-label" style="text-transform: uppercase; font-weight: 500;">Scheduled Inspections</div>
                                        <div class="today-number"><?= number_format($today['inspections']) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="footer-note">
                        <p>Admin dashboard MVP focused on properties and applications.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include BASE_PATH . '/partials/hestia-easter-scripts.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('hestiaAdminPeaceEgg') && window.hestiaEaster) {
                window.hestiaEaster.unlock('admin-peace');
            }
        });
    </script>

</body>
</html>
