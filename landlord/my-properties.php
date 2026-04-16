<?php
session_start();
require_once __DIR__ . '/../userguard.php';
require_once __DIR__ . '/../classes/Property.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Landlord.php';

if (($_SESSION['user_role'] ?? '') !== 'landlord') {
    header('Location: ../tenant/tenant-profile.php');
    exit();
}

$propObj = new Property();
$userObj = new User();
$Landlord = new Landlord();
$user_id = $_SESSION['user_id'] ?? 0;
$userdeets = $userObj->get_user_by('id', $user_id);
$active_landlord_page = 'properties';

$properties_per_page = 10;
$property_page = max(1, ($_GET['page'] ?? 1));
$property_total = $propObj->get_landlord_property_count($user_id);
$property_pages = max(1, ceil($property_total / $properties_per_page));
$property_page = min($property_page, $property_pages);
$property_offset = ($property_page - 1) * $properties_per_page;
$my_properties = $propObj->get_landlord_properties($user_id, $properties_per_page, $property_offset);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Properties | Hestia</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/landlord-profile.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <button class="btn btn-primary mobile-menu-btn d-lg-none position-fixed bottom-0 end-0 m-3 rounded-pill shadow" type="button" data-bs-toggle="offcanvas" data-bs-target="#landlordSidebar" style="z-index: 1040;">
        <i class="fas fa-bars"></i> Menu
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="landlordSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Hestia<span>.</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <?php $sidebar_mode = 'mobile'; include __DIR__ . '/partials/sidebar.php'; ?>
    </div>

    <div class="dashboard-container">
        <?php $sidebar_mode = 'desktop'; include __DIR__ . '/partials/sidebar.php'; ?>

        <main class="main-content">
            <?php include __DIR__ . '/../partials/messages.php'; ?>

            <section class="welcome-section">
                <div class="welcome-copy">
                    <p class="eyebrow">Portfolio</p>
                    <h1><?= htmlspecialchars($userdeets['first_name'] ?? 'Landlord') ?>, here is your property desk</h1>
                    <p>Everything you own is listed with the most recently added properties first.</p>
                </div>
                <div class="summary-panel">
                    <div class="summary-item">
                        <span class="summary-label">Total Properties</span>
                        <span class="summary-value"><?= number_format($property_total) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Page</span>
                        <span class="summary-value"><?= number_format($property_page) ?>/<?= number_format($property_pages) ?></span>
                    </div>
                </div>
            </section>

            <section class="card section-card mt-4">
                <div class="section-header">
                    <div class="section-copy">
                        <h2 class="section-title">My Properties</h2>
                        <p>Use this page to review approvals, jump into edits, or remove listings you no longer want live.</p>
                    </div>
                    <a href="../landlord/add-property.php" class="quick-action-btn"><i class="fas fa-plus-circle"></i><span>Add Property</span></a>
                </div>

                <?php if (!empty($my_properties)) { ?>
                    <div class="table-note mb-3">Sorted by date added (newest first).</div>
                    <div class="table-responsive">
                        <table class="table landlord-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Approval</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($my_properties as $property) { ?>
                                    <?php $activity_stamp = $property['updated_at'] ?: $property['created_at']; ?>
                                    <tr>
                                        <td>
                                            <div class="property-cell">
                                                <div class="property-thumb">
                                                    <?php if (!empty($property['thumbnail'])) { ?>
                                                        <img src="../upload/properties/<?= htmlspecialchars($property['thumbnail']) ?>" alt="<?= htmlspecialchars($property['title'] ?? 'Property image') ?>">
                                                    <?php } else { ?>
                                                        <span><?= htmlspecialchars(strtoupper(substr($property['title'] ?? 'P', 0, 1))) ?></span>
                                                    <?php } ?>
                                                </div>
                                                <div class="property-stack">
                                                    <div>
                                                        <div class="property-name"><?= htmlspecialchars($property['title'] ?? 'Untitled property') ?></div>
                                                        <div class="property-meta"><?= htmlspecialchars(($property['lga_name'] ?? 'Unknown area') . ', ' . ($property['state_name'] ?? 'Unknown state')) ?></div>
                                                    </div>
                                                    <?php if (($property['approval_status'] ?? '') === 'rejected' && !empty($property['rejection_reason'])) { ?>
                                                        <div class="small text-danger">Rejected: <?= htmlspecialchars($property['rejection_reason']) ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge-status <?= $Landlord->approval_badge($property['approval_status'] ?? 'pending') ?>"><?= htmlspecialchars(ucfirst($property['approval_status'] ?? 'pending')) ?></span></td>
                                        <td><span class="badge-status <?= $Landlord->landlord_status_badge($property['status'] ?? 'inactive') ?>"><?= htmlspecialchars(ucfirst($property['status'] ?? 'inactive')) ?></span></td>
                                        <td>&#8358;<?= number_format(($property['amount'] ?? 0)) ?></td>
                                        <td><?= !empty($activity_stamp) ? htmlspecialchars(date('M d, Y', strtotime($activity_stamp))) : 'N/A' ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="../views/property-details.php?property_id=<?= $property['property_id'] ?>" class="action-btn view">View</a>
                                                <a href="../landlord/edit-property.php?id=<?= $property['property_id'] ?>" class="action-btn edit"><?= ($property['approval_status'] ?? '') === 'rejected' ? 'Edit & Resubmit' : 'Edit' ?></a>
                                                <form method="post" action="../process/process_property_action.php" onsubmit="return confirm('Delete this property?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="property_id" value="<?= $property['property_id'] ?>">
                                                    <input type="hidden" name="redirect_to" value="../landlord/my-properties.php">
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
                                <a href="?page=<?= $property_page - 1 ?>" class="action-btn view">Previous</a>
                            <?php } ?>
                            <span class="pagination-label">Page <?= $property_page ?> of <?= $property_pages ?></span>
                            <?php if ($property_page < $property_pages) { ?>
                                <a href="?page=<?= $property_page + 1 ?>" class="action-btn view">Next</a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty-state">
                        <i class="fas fa-building"></i>
                        <h3>No properties yet</h3>
                        <p>Your property list will appear here as soon as you publish your first listing.</p>
                        <a href="../landlord/add-property.php" class="btn btn-primary">Add Property</a>
                    </div>
                <?php } ?>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../partials/hestia-easter-scripts.php'; ?>
    <script>
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
