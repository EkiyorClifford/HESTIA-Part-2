<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require_once "../../classes/User.php";
require_once "../../classes/Property.php";

$userObj = new User();
$propertyObj = new Property();

$landlord_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($landlord_id <= 0) {
    header("Location: user-details.php");
    exit();
}

$landlord = $userObj->get_user_by('id', $landlord_id);
if (!$landlord || strtolower( ($landlord['role_'] ?? '')) !== 'landlord') {
    header("Location: user-details.php");
    exit();
}

$properties = $propertyObj->get_landlord_properties($landlord_id);
$stats = $propertyObj->get_landlord_dashboard_stats($landlord_id);

$active_admin_page = 'users';
$page_heading = 'Landlord Details';
$page_subheading = 'Review landlord profile information and the properties currently listed on the platform.';

$full_name = trim(($landlord['first_name'] ?? '') . ' ' . ($landlord['last_name'] ?? ''));
$landlord_status = strtolower(($landlord['is_active'] ?? 'no')) === 'yes' ? 'Active' : 'Inactive';
$status_badge_class = $landlord_status === 'Active' ? 'badge-active' : 'badge-inactive';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Details | Hestia Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        .profile-card {
            border: none;
            border-radius: 24px;
            background: linear-gradient(160deg, rgba(255,255,255,0.98), rgba(255,247,237,0.92));
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .profile-avatar {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #e04e1a, #f97316);
            box-shadow: 0 18px 32px rgba(224, 78, 26, 0.22);
        }

        .detail-chip {
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(249, 115, 22, 0.12);
            padding: 1rem;
            height: 100%;
        }

        .detail-chip .label {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 0.35rem;
        }

        .detail-chip .value {
            color: #111827;
            font-weight: 600;
            word-break: break-word;
        }

        .stat-card {
            border: none;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <?php include "../partials/navbar.php"; ?>

    <main class="admin-page">
        <div class="container">
            <div class="admin-shell">
                <?php include "../partials/sidebar.php"; ?>

                <div class="admin-content">
                    <div class="text-start mb-4">
                        <a href="user-details.php?filter=landlord" class="view-link">
                            <i class="fas fa-arrow-left me-2"></i>Back to User Management
                        </a>
                    </div>

                    <div class="card profile-card mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-center">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="profile-avatar">
                                        <?= htmlspecialchars(strtoupper(substr($landlord['first_name'] ?? '', 0, 1) . substr($landlord['last_name'] ?? '', 0, 1))) ?>
                                    </div>
                                    <div>
                                        <div class="text-uppercase small fw-semibold text-secondary mb-1">Landlord Profile</div>
                                        <h2 class="mb-1"><?= htmlspecialchars($full_name !== '' ? $full_name : 'Unnamed landlord') ?></h2>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <span class="badge <?= $status_badge_class ?>"><?= htmlspecialchars($landlord_status) ?></span>
                                            <span class="text-secondary small">Joined <?= htmlspecialchars(date('F j, Y', strtotime($landlord['created_at'] ?? 'now'))) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <a href="/Hestia-PHP/admin/views/user-details.php?search=<?= urlencode($landlord['email'] ?? '') ?>&filter=landlord" class="btn btn-outline-dark rounded-pill px-4">
                                    <i class="fas fa-users me-2"></i>Return to List
                                </a>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="detail-chip">
                                        <span class="label">Email</span>
                                        <span class="value"><?= htmlspecialchars($landlord['email'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-chip">
                                        <span class="label">Phone</span>
                                        <span class="value"><?= htmlspecialchars($landlord['p_number'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="detail-chip">
                                        <span class="label">Role</span>
                                        <span class="value"><?= htmlspecialchars(ucfirst($landlord['role_'] ?? 'landlord')) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center p-3">
                                    <div class="text-uppercase small text-secondary fw-semibold">Properties</div>
                                    <div class="display-6 fw-bold text-dark mt-1"><?= number_format((int) ($stats['total_properties'] ?? 0)) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center p-3">
                                    <div class="text-uppercase small text-secondary fw-semibold">Available</div>
                                    <div class="display-6 fw-bold text-success mt-1"><?= number_format((int) ($stats['available'] ?? 0)) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center p-3">
                                    <div class="text-uppercase small text-secondary fw-semibold">Applications</div>
                                    <div class="display-6 fw-bold text-primary mt-1"><?= number_format((int) ($stats['applications'] ?? 0)) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center p-3">
                                    <div class="text-uppercase small text-secondary fw-semibold">Inspections</div>
                                    <div class="display-6 fw-bold" style="color: var(--orange-deep);"><?= number_format((int) ($stats['inspections'] ?? 0)) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                            <div class="section-title mb-0">
                                <i class="fas fa-building"></i> LANDLORD PROPERTIES
                            </div>
                            <div class="small text-secondary">
                                <?= number_format(count($properties)) ?> listed <?= count($properties) === 1 ? 'property' : 'properties' ?>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Price</th>
                                        <th>Approval</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($properties)) { ?>
                                        <?php foreach ($properties as $property) { ?>
                                            <?php
                                            $approval_status = strtolower(trim( ($property['approval_status'] ?? 'pending')));
                                            $property_status = strtolower(trim( ($property['status'] ?? 'inactive')));

                                            $approval_badge = 'badge-pending';
                                            if ($approval_status === 'approved') {
                                                $approval_badge = 'badge-active';
                                            } elseif ($approval_status === 'rejected') {
                                                $approval_badge = 'badge-inactive';
                                            }

                                            $status_badge = 'badge-inactive';
                                            if ($property_status === 'available' || $property_status === 'active') {
                                                $status_badge = 'badge-active';
                                            } elseif ($property_status === 'taken' || $property_status === 'pending') {
                                                $status_badge = 'badge-pending';
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold"><?= htmlspecialchars($property['title'] ?? 'Untitled property') ?></div>
                                                    <div class="small text-secondary"><?= htmlspecialchars($property['prop_address'] ?? 'No address provided') ?></div>
                                                </td>
                                                <td><?= htmlspecialchars($property['type_name'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars(trim($property['lga_name'] . ', ' . $property['state_name'] , ' ,') ?: 'N/A') ?></td>
                                                <td>&#8358;<?= number_format( ($property['amount'] ?? 0)) ?></td>
                                                <td>
                                                    <span class="badge <?= $approval_badge ?>"><?= htmlspecialchars(ucfirst($approval_status)) ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $status_badge ?>"><?= htmlspecialchars(ucfirst($property_status)) ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($approval_status === 'pending') { ?>
                                                        <a href="/Hestia-PHP/admin/property-review.php?id=<?= $property['property_id'] ?>" class="view-link">
                                                            <i class="fas fa-eye me-1"></i> Review
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="/Hestia-PHP/views/property-details.php?property_id=<?= $property['property_id'] ?>" class="view-link" target="_blank" rel="noopener noreferrer">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="empty-state">
                                                    <i class="fas fa-building"></i>
                                                    <h6>No properties yet</h6>
                                                    <p class="small mb-0">This landlord has not listed any active property on the platform.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
