<?php
session_start();
require_once '../classes/State.php';
require_once '../classes/Property.php';
require_once '../partials/messages.php';
require_once '../userguard.php';

$state = new State();
$propertyObj = new Property();
$states = $state->get_active_states();
$lglist = '<option value="" selected disabled>— select LGA —</option>';
$ptypes = $state->get_property_types();
$amenities = $propertyObj->get_all_amenities();
$saved_data = $_SESSION['form_data'] ?? [];
$saved_amenities = isset($saved_data['amenities']) && is_array($saved_data['amenities'])
    ? array_map('intval', $saved_data['amenities'])
    : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property | HESTIA</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/add_property.css">
   
</head>
<body>
    <!-- Header with Navigation - HESTIA style -->
    <?php include '../partials/nav.php'; ?>

    <!-- Main Content -->
    <main class="container" style="margin-top: 100px;">
        <div class="form-container">
            <?php include '../partials/messages.php'; ?>
            <h2>Add property</h2>
            <div class="form-subtitle">List your space with HESTIA</div>
            
            <form action="../process/process_add_property.php" method="POST" enctype="multipart/form-data">
                <!-- Basic Information Section -->
                <div class="section-title">Basic Information</div>
                
                <!-- Title (from DB: title) -->
                <div class="mb-4">
                    <label for="title" class="form-label required">Property Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="e.g., Cozy 2-Bedroom Apartment" value="<?php echo isset($saved_data['title']) ? htmlspecialchars($saved_data['title']) : ''; ?>" required>
                </div>
                
                <!-- Description (from DB: description) -->
                <div class="mb-4">
                    <label for="description" class="form-label required">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe your property..." required><?php echo isset($saved_data['description']) ? htmlspecialchars($saved_data['description']) : ''; ?></textarea>
                </div>
                
                <div class="row">
                    <!-- Property Type (from DB: property_type_id - would need to fetch from types table) -->
                    <div class="col-md-6 mb-4">
                        <label for="property_type_id" class="form-label required">Property Type</label>
                        <select class="form-select" id="property_type_id" name="property_type_id" required>
                            <option value="" selected disabled>— select type —</option>
                            <?php foreach ($ptypes as $ptype) { ?>
                                <option value="<?php echo $ptype['type_id']; ?>" <?php echo (isset($saved_data['property_type_id']) && $saved_data['property_type_id'] == $ptype['type_id']) ? 'selected' : ''; ?>><?php echo $ptype['type_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <!-- Listing Type (from DB: listing_type - rent/sale) -->
                    <div class="col-md-6 mb-4">
                        <label for="listing_type" class="form-label required">Listing Type</label>
                        <select class="form-select" id="listing_type" name="listing_type" required>
                            <option value="" selected disabled>— select —</option>
                            <option value="rent" <?php echo (isset($saved_data['listing_type']) && $saved_data['listing_type'] == 'rent') ? 'selected' : ''; ?>>For Rent</option>
                            <option value="sale" <?php echo (isset($saved_data['listing_type']) && $saved_data['listing_type'] == 'sale') ? 'selected' : ''; ?>>For Sale</option>
                        </select>
                    </div>
                </div>
                
                <!-- Price (from DB: amount) -->
                <div class="mb-4">
                    <label for="amount" class="form-label required">Price</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-1 border-end-0 rounded-start-16" style="border-color: var(--hestia-stone-warm);">₦</span>
                        <input type="number" step="0.01" class="form-control rounded-start-0" id="amount" name="amount" placeholder="250,000" value="<?php echo isset($saved_data['amount']) ? htmlspecialchars($saved_data['amount']) : ''; ?>" required>
                    </div>
                </div>
                
                <!-- Location Section -->
                <div class="section-title">Location</div>
                
                <div class="row">
                    <!-- State (from DB: state_id) -->
                    <div class="col-md-6 mb-4">
                        <label for="state_id" class="form-label required">State</label>
                        <select class="form-select" id="state_id" name="state_id" required>
                            <option value="" selected disabled>— select state —</option>
                            <?php foreach($states as $state){ ?>
                                <option value="<?php echo $state['state_id']; ?>" <?php echo (isset($saved_data['state_id']) && $saved_data['state_id'] == $state['state_id']) ? 'selected' : ''; ?>><?php echo $state['state_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <!-- LGA (from DB: lga_id) -->
                    <div class="col-md-6 mb-4">
                        <label for="lga_id" class="form-label required">Local Government</label>
                        <select class="form-select" id="lga_id" name="lga_id" required>
                            <?php echo $lglist; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Property Address (from DB: prop_address) -->
                <div class="mb-4">
                    <label for="prop_address" class="form-label required">Street Address</label>
                    <input type="text" class="form-control" id="prop_address" name="prop_address" placeholder="123, Mind Your Biz Street" value="<?php echo isset($saved_data['prop_address']) ? htmlspecialchars($saved_data['prop_address']) : ''; ?>" required>
                </div>
                
                <!-- Property Details Section -->
                <div class="section-title">Property Details</div>
                
                <div class="row">
                    <!-- Bedrooms (from DB: bedroom) -->
                    <div class="col-md-4 mb-4">
                        <label for="bedroom" class="form-label required">Bedrooms</label>
                        <input type="number" class="form-control" id="bedroom" name="bedroom" placeholder="2" min="0" value="<?php echo isset($saved_data['bedroom']) ? htmlspecialchars($saved_data['bedroom']) : ''; ?>" required>
                    </div>
                    
                    <!-- Furnished (from DB: furnished) -->
                    <div class="col-md-4 mb-4">
                        <label for="furnished" class="form-label required">Furnished</label>
                        <select class="form-select" id="furnished" name="furnished" required>
                            <option value="" selected disabled>— select —</option>
                            <option value="Furnished" <?php echo (isset($saved_data['furnished']) && $saved_data['furnished'] == 'Furnished') ? 'selected' : ''; ?>>Furnished</option>
                            <option value="Unfurnished" <?php echo (isset($saved_data['furnished']) && $saved_data['furnished'] == 'Unfurnished') ? 'selected' : ''; ?>>Unfurnished</option>
                        </select>
                    </div>
                    
                    <!-- Moderation status -->
                    <div class="col-md-4 mb-4">
                        <label class="form-label">Moderation</label>
                        <input type="text" class="form-control" value="Submitted for admin review" readonly>
                        <small class="text-muted">New properties stay pending until an admin approves them.</small>
                    </div>
                </div>
                
                <!-- Images Section -->
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
                                    <?= in_array($amenity_id, $saved_amenities, true) ? 'checked' : '' ?>
                                >
                                <label class="form-check-label" for="amenity-<?= $amenity_id ?>">
                                    <?= htmlspecialchars($amenity['amenity_name'] ?? 'Amenity') ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100" name="add_property">Submit property for review</button>
                
                <div class="text-center mt-4">
                    <span class="status-badge"><i class="far fa-clock me-1"></i> Listing will be reviewed before it appears publicly</span>
                </div>
            </form>
        </div>
        <?php unset($_SESSION['form_data']); ?>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>© 2026 HESTIA Property Rentals. All rights reserved.</p>
            <p class="small opacity-50">est. with warmth in Lagos</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#state_id").change(function(){
                var selectedstate = $(this).val();
                if(selectedstate) {
                    $.ajax({
                        url: "../process/process_lga.php",
                        type: "POST",
                        data: {state_id: selectedstate},
                        success: function(response) {
                            $("#lga_id").html(response);
                        },
                        error: function() {
                            alert("Error loading LGAs");
                        }
                    });
                } else {
                    $("#lga_id").html('<option value="" selected disabled>— select LGA —</option>');
                }
            });
        });
    </script>
</body>
</html>
