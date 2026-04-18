<?php
require dirname(__DIR__) . '/config/app.php';
session_start();
require_once BASE_PATH . '/userguard.php';
require_once BASE_PATH . '/classes/Property.php';

if (($_SESSION['user_role'] ?? '') !== 'landlord') {
    header('Location: ../tenant/tenant-profile.php');
    exit();
}

$property_id = (int) ($_GET['id'] ?? 0);
$propertyObj = new Property();

$property = $propertyObj->get_property_by_id($property_id);
if (!$property || (int) $property['user_id'] !== (int) $_SESSION['user_id']) {
    $_SESSION['error'] = 'Property not found.';
    header('Location: ../landlord/landlord-profile.php');
    exit();
}

$ptypes = $propertyObj->get_property_types();
$amenities = $propertyObj->get_all_amenities();
$selected_amenity_ids = $propertyObj->get_property_amenity_ids($property_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property | Hestia</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="icon" type="image/png" href="../favicon.png">
    <link rel="shortcut icon" href="../favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/add_property.css">
</head>
<body>
    <?php include BASE_PATH . '/partials/nav.php'; ?>

    <main class="container" style="margin-top: 100px;">
        <div class="form-container">
            <?php include BASE_PATH . '/partials/messages.php'; ?>
            <h2>Edit property</h2>
            <div class="form-subtitle">Update your listing details</div>

            <?php if (($property['approval_status'] ?? '') === 'rejected') { ?>
                <div class="alert alert-warning">
                    <strong>This property was rejected.</strong><br>
                    <?= !empty($property['rejection_reason']) ? nl2br(htmlspecialchars($property['rejection_reason'])) : 'Please update the listing and resubmit it for review.' ?>
                </div>
            <?php } elseif (($property['approval_status'] ?? '') === 'pending') { ?>
                <div class="alert alert-info">
                    This property is currently pending admin review. Saving changes will keep it in the review queue.
                </div>
            <?php } ?>

            <form action="../process/process_edit_property.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="property_id" value="<?= (int) $property['property_id'] ?>">

                <div class="section-title">Basic Information</div>
                <div class="mb-4">
                    <label for="title" class="form-label required">Property Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($property['title']) ?>" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label required">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($property['description']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="property_type_id" class="form-label required">Property Type</label>
                        <select class="form-select" id="property_type_id" name="property_type_id" required>
                            <?php foreach ($ptypes as $ptype) { ?>
                                <option value="<?= (int) $ptype['type_id'] ?>" <?= (int) $property['property_type_id'] === (int) $ptype['type_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ptype['type_name']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="listing_type" class="form-label required">Listing Type</label>
                        <select class="form-select" id="listing_type" name="listing_type" required>
                            <option value="rent" <?= $property['listing_type'] === 'rent' ? 'selected' : '' ?>>For Rent</option>
                            <option value="sale" <?= $property['listing_type'] === 'sale' ? 'selected' : '' ?>>For Sale</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="amount" class="form-label required">Price</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?= htmlspecialchars($property['amount']) ?>" required>
                </div>

                <div class="section-title">Location</div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($property['state_name']) ?>" readonly>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Local Government</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($property['lga_name']) ?>" readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="prop_address" class="form-label required">Street Address</label>
                    <input type="text" class="form-control" id="prop_address" name="prop_address" value="<?= htmlspecialchars($property['prop_address']) ?>" required>
                </div>

                <div class="section-title">Property Details</div>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label for="bedroom" class="form-label required">Bedrooms</label>
                        <input type="number" class="form-control" id="bedroom" name="bedroom" value="<?= htmlspecialchars($property['bedroom']) ?>" required>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="furnished" class="form-label required">Furnished</label>
                        <select class="form-select" id="furnished" name="furnished" required>
                            <option value="Furnished" <?= $property['furnished'] === 'Furnished' ? 'selected' : '' ?>>Furnished</option>
                            <option value="Unfurnished" <?= $property['furnished'] === 'Unfurnished' ? 'selected' : '' ?>>Unfurnished</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="status" class="form-label required">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available" <?= $property['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="taken" <?= $property['status'] === 'taken' ? 'selected' : '' ?>>Taken</option>
                            <option value="inactive" <?= $property['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="deleted" <?= $property['status'] === 'deleted' ? 'selected' : '' ?>>Deleted</option>
                        </select>
                    </div>
                </div>

                <div class="section-title">Media</div>
                <div class="mb-4">
                    <label for="images" class="form-label">Property Images</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                    <small class="form-text text-muted">Upload multiple images (JPEG, PNG, JPG). The first uploaded image will be used as cover photo.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label">Amenities</label>
                    <div class="amenities-checkboxes">
                        <?php foreach ($amenities as $amenity) { ?>
                            <?php $amenity_id = (int) ($amenity['amenity_id'] ?? 0); ?>
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="amenity-<?= $amenity_id ?>"
                                    name="amenities[]"
                                    value="<?= $amenity_id ?>"
                                    <?= in_array($amenity_id, $selected_amenity_ids, true) ? 'checked' : '' ?>
                                >
                                <label class="form-check-label" for="amenity-<?= $amenity_id ?>">
                                    <?= htmlspecialchars($amenity['amenity_name'] ?? 'Amenity') ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100" name="updatebtn"><?= ($property['approval_status'] ?? '') === 'rejected' ? 'Update and resubmit for review' : 'Update property' ?></button>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include BASE_PATH . '/partials/hestia-easter-scripts.php'; ?>
</body>
</html>
