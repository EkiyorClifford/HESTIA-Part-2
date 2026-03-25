<?php
session_start();
require_once '../userguard.php';
require_once '../classes/Property.php';
require_once '../classes/User.php';

if (($_SESSION['user_role'] ?? '') !== 'landlord') {
    header('Location: ../tenant/tenant-profile.php');
    exit();
}

$propObj = new Property();
$userObj = new User();
$user_id = (int) $_SESSION['user_id'];

// fetch data
$stats = $propObj->get_landlord_dashboard_stats($user_id);
$my_properties = $propObj->get_landlord_properties($user_id);
$recent_applications = $propObj->get_landlord_applications($user_id, 8);
$userdeets = $userObj->get_user_by('id', $user_id);

// process data
$stats = array_merge([
    'total_properties' => 0,
    'available' => 0,
    'taken' => 0,
    'inactive' => 0,
    'applications' => 0,
], $stats ?: []);

$landlord_nav_items = [
    ['href' => '../landlord/landlord-profile.php', 'label' => 'Dashboard', 'icon' => 'fas fa-home', 'active' => true],
    ['href' => '#properties-section', 'label' => 'My Properties', 'icon' => 'fas fa-building'],
    ['href' => '../landlord/add-property.php', 'label' => 'Add Property', 'icon' => 'fas fa-plus-circle'],
    ['href' => '#applications-section', 'label' => 'Applications', 'icon' => 'fas fa-file-alt'],
    ['href' => '../views/index.php', 'label' => 'View Site', 'icon' => 'fas fa-external-link-alt'],
];

function landlord_status_badge(string $status): string
{
    $status = strtolower(trim($status));

    if ($status === 'available') {
        return 'badge-available';
    }

    if ($status === 'taken') {
        return 'badge-rented';
    }

    return 'badge-inactive';
}

function application_badge(string $status): string
{
    $status = strtolower(trim($status));

    if ($status === 'approved' || $status === 'accepted') {
        return 'badge-available';
    }

    if ($status === 'rejected' || $status === 'declined') {
        return 'badge-inactive';
    }

    return 'badge-rented';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard | Hestia</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/landlord-profile.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <?php include 'partials/navbar.php'; ?>

    <button class="btn btn-primary mobile-menu-btn d-lg-none position-fixed bottom-0 end-0 m-3 rounded-pill shadow" type="button" data-bs-toggle="offcanvas" data-bs-target="#landlordSidebar" style="z-index: 1040;">
        <i class="fas fa-bars"></i> Menu
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="landlordSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Hestia<span>.</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <?php $sidebar_mode = 'mobile'; include 'partials/sidebar.php'; ?>
    </div>

    <div class="dashboard-container">
        <?php $sidebar_mode = 'desktop'; include 'partials/sidebar.php'; ?>

        <main class="main-content">
            <?php include '../partials/messages.php'; ?>

            <section class="welcome-section">
                <div class="welcome-copy">
                    <p class="eyebrow">Landlord Dashboard</p>
                    <h1>Welcome back, <?= htmlspecialchars($userdeets['first_name'] ?? 'Landlord') ?></h1>
                    <p>Manage listings, review applications, and keep your portfolio current from one page.</p>
                </div>
                <div class="summary-panel">
                    <div class="summary-item">
                        <span class="summary-label">Listed Properties</span>
                        <span class="summary-value"><?= number_format((int) $stats['total_properties']) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Applications</span>
                        <span class="summary-value"><?= number_format((int) $stats['applications']) ?></span>
                    </div>
                </div>
            </section>

            <div class="row g-4 stats-grid">
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format((int) $stats['total_properties']) ?></div>
                        <div class="text-secondary">Total Properties</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format((int) $stats['available']) ?></div>
                        <div class="text-secondary">Available</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format((int) $stats['taken']) ?></div>
                        <div class="text-secondary">Taken</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format((int) $stats['applications']) ?></div>
                        <div class="text-secondary">Applications</div>
                    </div>
                </div>
            </div>

            <section class="section-block">
                <div class="section-header">
                    <h2 class="section-title">Quick Actions</h2>
                </div>
                <div class="quick-actions-grid">
                    <a href="../landlord/add-property.php" class="quick-action-btn"><i class="fas fa-plus-circle"></i><span>Add Property</span></a>
                    <a href="#properties-section" class="quick-action-btn"><i class="fas fa-building"></i><span>Manage Properties</span></a>
                    <a href="#applications-section" class="quick-action-btn"><i class="fas fa-file-alt"></i><span>View Applications</span></a>
                    <a href="../views/properties.php" class="quick-action-btn"><i class="fas fa-globe"></i><span>View Marketplace</span></a>
                </div>
            </section>

            <div class="row g-4 align-items-start">
                <div class="col-xl-8">
                    <section class="card section-card" id="properties-section">
                        <div class="section-header">
                            <h2 class="section-title">Recent Properties</h2>
                            <a href="../landlord/add-property.php" class="section-link">Add another</a>
                        </div>

                        <?php if (!empty($my_properties)) { ?>
                            <div class="table-responsive">
                                <table class="table landlord-table" id="propertiesTable">
                                    <thead>
                                        <tr>
                                            <th>Property</th>
                                            <th>Status</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($my_properties as $property) { ?>
                                            <tr data-search="<?= htmlspecialchars(strtolower(trim(($property['title'] ?? '') . ' ' . ($property['state_name'] ?? '') . ' ' . ($property['lga_name'] ?? '') . ' ' . ($property['status'] ?? '')))) ?>">
                                                <td>
                                                    <div class="property-cell">
                                                        <div class="property-thumb">
                                                            <?php if (!empty($property['thumbnail'])) { ?>
                                                                <img src="../upload/properties/<?= htmlspecialchars($property['thumbnail']) ?>" alt="<?= htmlspecialchars($property['title']) ?>">
                                                            <?php } else { ?>
                                                                <span><?= htmlspecialchars(strtoupper(substr($property['title'] ?? 'P', 0, 1))) ?></span>
                                                            <?php } ?>
                                                        </div>
                                                        <div>
                                                            <div class="property-name"><?= htmlspecialchars($property['title'] ?? 'Untitled property') ?></div>
                                                            <div class="property-meta"><?= htmlspecialchars(($property['lga_name'] ?? 'Unknown area') . ', ' . ($property['state_name'] ?? 'Unknown state')) ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge-status <?= landlord_status_badge((string) ($property['status'] ?? 'inactive')) ?>"><?= htmlspecialchars(ucfirst($property['status'] ?? 'inactive')) ?></span></td>
                                                <td>&#8358;<?= number_format((float) ($property['amount'] ?? 0)) ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="../views/property-details.php?property_id=<?= (int) $property['property_id'] ?>" class="action-btn view">View</a>
                                                        <a href="../landlord/edit-property.php?id=<?= (int) $property['property_id'] ?>" class="action-btn edit">Edit</a>
                                                        <form method="post" action="../process/process_property_action.php" onsubmit="return confirm('Delete this property?');">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="property_id" value="<?= (int) $property['property_id'] ?>">
                                                            <button type="submit" class="action-btn delete">Delete</button>
                                                        </form>
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
                                <h3>No properties yet</h3>
                                <p>Your dashboard will start filling up once you list your first property.</p>
                                <a href="../landlord/add-property.php" class="btn btn-primary">Add Property</a>
                            </div>
                        <?php } ?>
                    </section>
                </div>

                <div class="col-xl-4">
                    <section class="card section-card" id="applications-section">
                        <div class="section-header">
                            <h2 class="section-title">Recent Applications</h2>
                        </div>

                        <?php if (!empty($recent_applications)) { ?>
                            <div class="applications-list">
                                <?php foreach ($recent_applications as $application) { ?>
                                    <article class="application-card" data-search="<?= htmlspecialchars(strtolower(trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? '') . ' ' . ($application['title'] ?? '') . ' ' . ($application['status'] ?? '')))) ?>">
                                        <div class="application-top">
                                            <div>
                                                <h3><?= htmlspecialchars(trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''))) ?></h3>
                                                <p><?= htmlspecialchars($application['title'] ?? 'Unknown property') ?></p>
                                            </div>
                                            <span class="badge-status <?= application_badge((string) ($application['status'] ?? 'pending')) ?>"><?= htmlspecialchars(ucfirst($application['status'] ?? 'pending')) ?></span>
                                        </div>
                                        <div class="application-meta"><?= htmlspecialchars($application['email'] ?? '') ?></div>
                                        <a href="../views/property-details.php?property_id=<?= (int) $application['property_id'] ?>" class="section-link">View property</a>
                                    </article>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="empty-state empty-state-compact">
                                <i class="fas fa-file-alt"></i>
                                <h3>No applications yet</h3>
                                <p>Applications from tenants will appear here when they start coming in.</p>
                            </div>
                        <?php } ?>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dashboardSearch = document.getElementById('dashboardSearch');
        const propertyRows = Array.from(document.querySelectorAll('#propertiesTable tbody tr'));
        const applicationCards = Array.from(document.querySelectorAll('.application-card'));

        if (dashboardSearch) {
            dashboardSearch.addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();

                propertyRows.forEach((row) => {
                    const haystack = row.dataset.search || '';
                    row.style.display = haystack.includes(keyword) ? '' : 'none';
                });

                applicationCards.forEach((card) => {
                    const haystack = card.dataset.search || '';
                    card.style.display = haystack.includes(keyword) ? '' : 'none';
                });
            });
        }

        document.querySelectorAll('.offcanvas .nav-link').forEach((link) => {
            link.addEventListener('click', () => {
                const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('landlordSidebar'));
                if (offcanvas) {
                    offcanvas.hide();
                }
            });
        });
    </script>
</body>
</html>
