<?php
session_start();
require_once '../userguard.php';
require_once '../classes/Property.php';
require_once '../classes/User.php';
require_once '../classes/Inspection.php';
require_once '../classes/Landlord.php';

if (($_SESSION['user_role'] ?? '') !== 'landlord') {
    header('Location: ../tenant/tenant-profile.php');
    exit();
}

$propObj = new Property();
$userObj = new User();
$inspectionObj = new Inspection();
$user_id = $_SESSION['user_id'] ?? 0;
$active_landlord_page = 'dashboard';
$properties_per_page = 5;
$property_page = max(1, ($_GET['property_page'] ?? 1));
$property_total = $propObj->get_landlord_property_count($user_id);
$property_pages = max(1, ceil($property_total / $properties_per_page));
$property_page = min($property_page, $property_pages);
$property_offset = ($property_page - 1) * $properties_per_page;

// fetch data
$stats = $propObj->get_landlord_dashboard_stats($user_id);
$my_properties = $propObj->get_landlord_properties($user_id, $properties_per_page, $property_offset);
$recent_applications = $propObj->get_landlord_applications($user_id, 8);
$recent_inspections = $inspectionObj->get_landlord_inspections($user_id, 8);
$userdeets = $userObj->get_user_by('id', $user_id);

// process data
$stats = array_merge([
    'total_properties' => 0,
    'available' => 0,
    'taken' => 0,
    'inactive' => 0,
    'applications' => 0,
    'inspections' => 0,
], $stats ?: []);

//lastly instantiate landlord obj
$Landlord = new Landlord();
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
                        <span class="summary-value"><?= number_format($stats['total_properties']) ?></span>
                    </div>
                <div class="summary-item">
                    <span class="summary-label">Applications</span>
                    <span class="summary-value"><?= number_format($stats['applications']) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Inspections</span>
                    <span class="summary-value"><?= number_format( $stats['inspections']) ?></span>
                </div>
            </div>
        </section>

            <div class="row g-4 stats-grid">
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format($stats['total_properties']) ?></div>
                        <div class="text-secondary">Total Properties</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format( $stats['available']) ?></div>
                        <div class="text-secondary">Available</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format( $stats['taken']) ?></div>
                        <div class="text-secondary">Taken</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format( $stats['applications']) ?></div>
                        <div class="text-secondary">Applications</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card h-100 dashboard-card">
                        <div class="stat-number"><?= number_format( $stats['inspections']) ?></div>
                        <div class="text-secondary">Inspections</div>
                    </div>
                </div>
            </div>

            <section class="section-block">
                <div class="section-header">
                    <h2 class="section-title">Quick Actions</h2>
                </div>
                <div class="quick-actions-grid">
                    <a href="../landlord/add-property.php" class="quick-action-btn"><i class="fas fa-plus-circle"></i><span>Add Property</span></a>
                    <a href="../landlord/my-properties.php" class="quick-action-btn"><i class="fas fa-building"></i><span>Manage Properties</span></a>
                    <a href="#applications-section" class="quick-action-btn"><i class="fas fa-file-alt"></i><span>View Applications</span></a>
                    <a href="#inspections-section" class="quick-action-btn"><i class="fas fa-calendar-check"></i><span>Review Inspections</span></a>
                    <a href="../views/properties.php" class="quick-action-btn"><i class="fas fa-globe"></i><span>View Marketplace</span></a>
                </div>
            </section>

            <div class="row g-4 align-items-start">
                <div class="col-xl-8">
                    <section class="card section-card" id="properties-section">
                        <div class="section-header">
                            <div class="section-copy">
                                <h2 class="section-title">Recent Properties</h2>
                                <p>Ranked by latest property activity, including recent edits and moderation updates.</p>
                            </div>
                            <a href="../landlord/my-properties.php" class="section-link">View all</a>
                        </div>

                        <?php if (!empty($my_properties)) { ?>
                            <div class="table-responsive">
                                <table class="table landlord-table" id="propertiesTable">
                                    <thead>
                                        <tr>
                                            <th>Property</th>
                                            <th>Approval</th>
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
                                                            <?php if (($property['approval_status'] ?? '') === 'rejected' && !empty($property['rejection_reason'])) { ?>
                                                                <div class="small text-danger mt-2">Rejected: <?= htmlspecialchars($property['rejection_reason']) ?></div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge-status <?= $Landlord->approval_badge($property['approval_status'] ?? 'pending') ?>"><?= htmlspecialchars(ucfirst($property['approval_status'] ?? 'pending')) ?></span></td>
                                                <td><span class="badge-status <?= $Landlord->landlord_status_badge($property['status'] ?? 'inactive') ?>"><?= htmlspecialchars(ucfirst($property['status'] ?? 'inactive')) ?></span></td>
                                                <td>&#8358;<?= number_format(($property['amount'] ?? 0)) ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="../views/property-details.php?property_id=<?=  $property['property_id'] ?>" class="action-btn view">View</a>
                                                        <a href="../landlord/edit-property.php?id=<?=  $property['property_id'] ?>" class="action-btn edit"><?= ($property['approval_status'] ?? '') === 'rejected' ? 'Edit & Resubmit' : 'Edit' ?></a>
                                                        <form method="post" action="../process/process_property_action.php" onsubmit="return confirm('Delete this property?');">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="property_id" value="<?=  $property['property_id'] ?>">
                                                            <input type="hidden" name="redirect_to" value="../landlord/landlord-profile.php?property_page=<?=  $property_page ?>#properties-section">
                                                            <button type="submit" class="action-btn delete">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if ($property_pages > 1) { ?>
                                <div class="dashboard-pagination mt-4">
                                    <?php if ($property_page > 1) { ?>
                                        <a href="?property_page=<?= $property_page - 1 ?>#properties-section" class="action-btn view">Previous</a>
                                    <?php } ?>
                                    <span class="pagination-label">Page <?= $property_page ?> of <?= $property_pages ?></span>
                                    <?php if ($property_page < $property_pages) { ?>
                                        <a href="?property_page=<?= $property_page + 1 ?>#properties-section" class="action-btn view">Next</a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
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
                            <!-- applications -->

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
                                            <span class="badge-status <?= $Landlord->application_badge($application['status'] ?? 'pending') ?>"><?= htmlspecialchars(ucfirst($application['status'] ?? 'pending')) ?></span>
                                        </div>
                                        <div class="application-meta"><?= htmlspecialchars($application['email'] ?? '') ?></div>
                                        <?php if (!empty($application['message'])) { ?>
                                            <p class="application-message"><?= htmlspecialchars($application['message']) ?></p>
                                        <?php } ?>
                                        <?php if (strtolower(($application['status'] ?? 'pending')) === 'pending') { ?>
                                            <div class="decision-actions">
                                                <form method="post" action="../process/process_property_action.php">
                                                    <input type="hidden" name="action" value="application_status">
                                                    <input type="hidden" name="application_id" value="<?= $application['app_id'] ?>">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="decision-btn approve" aria-label="Approve application">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="post" action="../process/process_property_action.php">
                                                    <input type="hidden" name="action" value="application_status">
                                                    <input type="hidden" name="application_id" value="<?= $application['app_id'] ?>">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="decision-btn reject" aria-label="Reject application">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php } ?>
                                        <a href="../views/property-details.php?property_id=<?= $application['property_id'] ?>" class="section-link">View property</a>
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
                <!-- inspections -->
                <section class="card section-card col-md-8" id="inspections-section">
                    <div class="section-header">
                        <h2 class="section-title">Inspection Requests</h2>
                    </div>

                    <?php if (!empty($recent_inspections)) { ?>
                            <div class="applications-list">
                                <?php foreach ($recent_inspections as $inspection) { ?>
                                    <article class="application-card inspection-card" data-search="<?= htmlspecialchars(strtolower(trim(($inspection['first_name'] ?? '') . ' ' . ($inspection['last_name'] ?? '') . ' ' . ($inspection['title'] ?? '') . ' ' . ($inspection['status'] ?? '')))) ?>">
                                        <div class="application-top">
                                            <div>
                                                <h3><?= htmlspecialchars(trim(($inspection['first_name'] ?? '') . ' ' . ($inspection['last_name'] ?? ''))) ?></h3>
                                                <p><?= htmlspecialchars($inspection['title'] ?? 'Unknown property') ?></p>
                                            </div>
                                            <span class="badge-status <?= $Landlord->application_badge($inspection['status'] ?? 'pending') ?>"><?= htmlspecialchars(ucfirst($inspection['status'] ?? 'pending')) ?></span>
                                        </div>
                                        <div class="application-meta">
                                            <?= htmlspecialchars($inspection['p_number'] ?? '') ?>
                                            <?php if (!empty($inspection['inspection_date'])) { ?>
                                                · <?= htmlspecialchars(date('M d, Y', strtotime($inspection['inspection_date']))) ?>
                                            <?php } ?>
                                        </div>
                                        <?php if (strtolower(($inspection['status'] ?? 'pending')) === 'pending') { ?>
                                            <div class="decision-actions">
                                                <form method="post" action="../process/process_property_action.php">
                                                    <input type="hidden" name="action" value="inspection_status">
                                                    <input type="hidden" name="inspection_id" value="<?= $inspection['inspection_id'] ?>">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="decision-btn approve" aria-label="Approve inspection">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="post" action="../process/process_property_action.php">
                                                    <input type="hidden" name="action" value="inspection_status">
                                                    <input type="hidden" name="inspection_id" value="<?= $inspection['inspection_id'] ?>">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="decision-btn reject" aria-label="Reject inspection">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php } ?>
                                        <a href="../views/property-details.php?property_id=<?= $inspection['property_id'] ?>" class="section-link">View property</a>
                                    </article>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="empty-state empty-state-compact">
                                <i class="fas fa-calendar-check"></i>
                                <h3>No inspections yet</h3>
                                <p>Inspection requests from tenants will show up here.</p>
                            </div>
                        <?php } ?>
                    </section>
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
                    const moat = row.dataset.search || '';
                    row.style.display = moat.includes(keyword) ? '' : 'none';
                });

                applicationCards.forEach((card) => {
                    const moat = card.dataset.search || '';
                    card.style.display = moat.includes(keyword) ? '' : 'none';
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
