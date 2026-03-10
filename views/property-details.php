<?php
session_start();
require_once '../classes/Property.php';

// Fix: Match the key 'id' sent from properties.php
$id = $_GET['property_id'] ?? null; 
if (!$id) {
    header("Location: ../views/properties.php");
    exit();
}

$property = new Property();
$details = $property->get_property_by_id($id);

// Redirect if property doesn't exist
if (!$details) {
    header("Location: ../views/properties.php");
    exit();
}

$images = $property->get_images($id);
// Fix: Get dynamic amenities
$amenities = $property->get_amenities_by_property($id); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($details['title']); ?> - HESTIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/property-details.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <?php include '../partials/nav.php'; ?>

    <main class="container flex-grow-1 py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../views/index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="../views/properties.php">Properties</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($details['title']); ?></li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Main Image -->
                <?php $main_img = (!empty($images)) ? $images[0]['image_path'] : 'default.png'; ?>
                <img src="../upload/properties/<?php echo $main_img; ?>" alt="Property Main Image" class="property-image mb-4 w-100 rounded-3 shadow-sm" style="height: 450px; object-fit: cover;">

                <!-- Dynamic Gallery -->
                <div class="gallery row g-2">
                    <?php foreach($images as $key => $img): if($key == 0) continue; // Skip main image ?>
                    <div class="col-md-4">
                        <div class="gallery-item">
                            <img src="../upload/properties/<?php echo $img['image_path']; ?>" class="img-fluid rounded-2" style="height: 150px; width: 100%; object-fit: cover;">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Dynamic Description -->
                <div class="property-details-section mt-5">
                    <h3><i class="fas fa-file-alt text-primary"></i> Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($details['description'])); ?></p>
                </div>

                <!-- Dynamic Amenities -->
                <div class="property-details-section">
                    <h3><i class="fas fa-check-circle text-primary"></i> Amenities</h3>
                    <?php if(!empty($amenities)): ?>
                    <ul class="amenities-list d-flex flex-wrap list-unstyled">
                        <?php foreach($amenities as $amt): ?>
                        <li class="me-4 mb-2"><i class="fas fa-check text-success me-2"></i><?php echo htmlspecialchars($amt['amenity_name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                        <p class="text-muted small">No specific amenities listed.</p>
                    <?php endif; ?>
                </div>

                <!-- Location -->
                <div class="property-details-section">
                    <h3><i class="fas fa-map-marker-alt text-primary"></i> Location</h3>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($details['prop_address']); ?>, <?php echo $details['lga_name']; ?>, <?php echo $details['state_name']; ?></p>
                    <div class="map-container rounded-3 overflow-hidden shadow-sm mt-3">
                        <!-- Map can stay static for now, or use Google Maps API with address string -->
                        <iframe width="100%" height="300" src="https://maps.google.com/maps?q=<?php echo urlencode($details['prop_address']); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                    </div>
                </div>
            </div>

            <!-- Sidebar Card -->
            <div class="col-lg-4">
                <div class="property-details-card shadow-lg p-4 bg-white rounded-4 sticky-top" style="top: 100px;">
                    <div class="mb-3">
                        <span class="badge bg-<?php echo ($details['listing_type'] == 'sale') ? 'success' : 'primary'; ?> me-2">For <?php echo ucfirst($details['listing_type']); ?></span>
                        <span class="badge bg-info">Verified</span>
                    </div>
                    <h2 class="h4 fw-bold"><?php echo htmlspecialchars($details['title']); ?></h2>
                    <div class="price-tag h3 text-danger fw-bold my-3">₦<?php echo number_format($details['amount'], 2); ?></div>

                    <div class="specs mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-bed me-2 text-muted"></i> Bedrooms</span>
                            <span class="fw-bold"><?php echo $details['bedroom']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-couch me-2 text-muted"></i> Furnished</span>
                            <span class="fw-bold"><?php echo $details['furnished']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-home me-2 text-muted"></i> Type</span>
                            <span class="fw-bold"><?php echo $details['type_name']; ?></span>
                        </div>
                    </div>

                    <hr>

                    <!-- Inspection/Request Action -->
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'tenant'): ?>
                        <form action="../process/process_inspection.php" method="POST">
                            <input type="hidden" name="property_id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <label class="small fw-bold">Select Inspection Date</label>
                                <input type="date" name="inspection_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" name="request_btn" class="btn btn-primary w-100 py-2 mb-2">Request Viewing</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning small">Please login as a tenant to book inspections.</div>
                    <?php endif; ?>
                    
                    <button class="btn btn-outline-secondary w-100 mb-2"><i class="far fa-heart me-2"></i> Save to Wishlist</button>
                </div>
            </div>
        </div>
    </main>

    <?php include '../partials/footer.php'; ?>
</body>
</html>