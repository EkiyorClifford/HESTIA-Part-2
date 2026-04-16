<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header("Location: views/admin-login.php");
    exit();
}

require_once __DIR__ . '/classes/Property.php';

$property_id = $_GET['id'] ?? 0;
$propertyObj = new Property();
$property = $propertyObj->get_property_by_id($property_id);

if (!$property) {
    $_SESSION['error'] = 'Property not found.';
    header('Location: views/admin-dashboard.php');
    exit();
}

$images = $propertyObj->get_property_images($property_id);
$amenities = $propertyObj->get_property_amenities($property_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Review | Hestia Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <main class="admin-page">
        <div class="container">
            <div class="admin-shell">
                <?php include __DIR__ . '/partials/sidebar.php'; ?>

                <div class="admin-content">
                    <?php include __DIR__ . '/../partials/messages.php'; ?>

                    <div class="text-start mb-4">
                        <a href="views/admin-dashboard.php" class="view-link">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>

                    <div class="section-card mb-4">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                            <div>
                                <div class="section-title mb-2">
                                    <i class="fas fa-building"></i> PROPERTY REVIEW
                                </div>
                                <h3 class="mb-2" style="text-transform: capitalize;"><?= htmlspecialchars($property['title'] ?? 'Untitled property') ?></h3>
                                <p class="mb-1 text-secondary">
                                    <?= htmlspecialchars(($property['prop_address'] ?? 'No address') . ', ' . ($property['lga_name'] ?? 'Unknown area') . ', ' . ($property['state_name'] ?? 'Unknown state')) ?>
                                </p>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <span class="badge bg-secondary">Approval: <?= htmlspecialchars(ucfirst($property['approval_status'] ?? 'pending')) ?></span>
                                    <span class="badge bg-dark">Status: <?= htmlspecialchars(ucfirst($property['status'] ?? 'inactive')) ?></span>
                                    <span class="badge bg-primary">For <?= htmlspecialchars(ucfirst($property['listing_type'] ?? 'rent')) ?></span>
                                </div>
                            </div>
                            <div class="text-lg-end">
                                <div class="h3 fw-bold text-success mb-1">&#8358;<?= number_format($property['amount'] ?? 0, 2) ?></div>
                                <div class="text-secondary">Submitted <?= !empty($property['created_at']) ? htmlspecialchars(date('F j, Y', strtotime($property['created_at']))) : 'N/A' ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="section-card mb-4">
                                <div class="section-title">
                                    <i class="fas fa-images"></i> PROPERTY DETAILS
                                </div>

                                <?php if (!empty($images)) { ?>
                                    <div class="row g-3 mb-4">
                                        <?php foreach ($images as $image) { ?>
                                            <div class="col-md-6">
                                                <img src="/Hestia-PHP/upload/properties/<?= htmlspecialchars($image['image_path']) ?>" alt="Property image" class="img-fluid rounded">
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="empty-state mb-4">
                                        <i class="fas fa-image"></i>
                                        <h6>No images uploaded</h6>
                                        <p class="small mb-0">This landlord submission does not include gallery images.</p>
                                    </div>
                                <?php } ?>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <p><strong>Property Type:</strong> <?= htmlspecialchars($property['type_name'] ?? 'N/A') ?></p>
                                        <p><strong>Bedrooms:</strong> <?= htmlspecialchars( ($property['bedroom'] ?? '0')) ?></p>
                                        <p><strong>Furnished:</strong> <?= htmlspecialchars($property['furnished'] ?? 'N/A') ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Landlord:</strong> <?= htmlspecialchars(trim(($property['first_name'] ?? '') . ' ' . ($property['last_name'] ?? ''))) ?></p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($property['email'] ?? 'N/A') ?></p>
                                        <p><strong>Phone:</strong> <?= htmlspecialchars($property['p_number'] ?? 'N/A') ?></p>
                                    </div>
                                </div>

                                <hr>

                                <h5>Description</h5>
                                <p class="text-secondary"><?= nl2br(htmlspecialchars($property['description'] ?? 'No description provided.')) ?></p>

                                <h5 class="mt-4">Amenities</h5>
                                <?php if (!empty($amenities)) { ?>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($amenities as $amenity) { ?>
                                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($amenity['amenity_name']) ?></span>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <p class="text-secondary mb-0">No amenities listed.</p>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="section-card mb-4">
                                <div class="section-title">
                                    <i class="fas fa-user-shield"></i> MODERATION
                                </div>

                                <?php if (($property['approval_status'] ?? 'pending') === 'rejected' && !empty($property['rejection_reason'])) { ?>
                                    <div class="alert alert-warning">
                                        <strong>Previous rejection reason:</strong><br>
                                        <?= nl2br(htmlspecialchars($property['rejection_reason'])) ?>
                                    </div>
                                <?php } ?>

                                <form method="post" action="process/process_property_review.php" class="mb-3">
                                    <input type="hidden" name="property_id" value="<?= $property['property_id'] ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check me-2"></i>Approve Property
                                    </button>
                                </form>

                                <form method="post" action="process/process_property_review.php">
                                    <input type="hidden" name="property_id" value="<?= $property['property_id'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <div class="mb-3">
                                        <label for="rejection_reason" class="form-label fw-semibold">Rejection reason</label>
                                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="5" placeholder="Explain what the landlord should fix before resubmitting." required><?= htmlspecialchars($property['rejection_reason'] ?? '') ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times me-2"></i>Reject Property
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
