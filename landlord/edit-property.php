<?php
session_start();
require_once '../userguard.php';
require_once '../classes/State.php';
require_once '../classes/Property.php';

if (($_SESSION['user_role'] ?? '') !== 'landlord') {
    header('Location: ../tenant/tenant-profile.php');
    exit();
}

$property_id = (int) ($_GET['id'] ?? 0);
$propertyObj = new Property();
$stateObj = new State();

$property = $propertyObj->get_property_by_id($property_id);
if (!$property || (int) $property['user_id'] !== (int) $_SESSION['user_id']) {
    $_SESSION['error'] = 'Property not found.';
    header('Location: ../landlord/landlord-profile.php');
    exit();
}

$states = $stateObj->get_active_states();
$lgas = $stateObj->get_active_lgas_by_state_id($property['state_id']);
$ptypes = $stateObj->get_property_types();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property | Hestia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/add_property.css">
</head>
<body>
    <?php include '../partials/nav.php'; ?>

    <main class="container" style="margin-top: 100px;">
        <div class="form-container">
            <?php include '../partials/messages.php'; ?>
            <h2>Edit property</h2>
            <div class="form-subtitle">Update your listing details</div>

            <form action="../process/process_edit_property.php" method="POST">
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
                        <label for="state_id" class="form-label required">State</label>
                        <select class="form-select" id="state_id" name="state_id" required>
                            <?php foreach ($states as $state) { ?>
                                <option value="<?= (int) $state['state_id'] ?>" <?= (int) $property['state_id'] === (int) $state['state_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($state['state_name']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="lga_id" class="form-label required">Local Government</label>
                        <select class="form-select" id="lga_id" name="lga_id" required>
                            <?php foreach ($lgas as $lga) { ?>
                                <option value="<?= (int) $lga['lga_id'] ?>" <?= (int) $property['lga_id'] === (int) $lga['lga_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lga['lga_name']) ?>
                                </option>
                            <?php } ?>
                        </select>
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
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100" name="updatebtn">Update property</button>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#state_id").change(function(){
                const selectedState = $(this).val();
                if (!selectedState) {
                    return;
                }

                $.ajax({
                    url: "../process/process_lga.php",
                    type: "POST",
                    data: { state_id: selectedState },
                    success: function(response) {
                        $("#lga_id").html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
