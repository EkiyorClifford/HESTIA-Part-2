<?php
require dirname(__DIR__) . '/config/app.php';
session_start();
require_once '../classes/Property.php';
require_once '../classes/User.php';
require_once '../classes/Wishlist.php';
include_once '../classes/PropertyTracker.php';
include_once '../classes/Inspection.php';
include_once '../classes/Applications.php';

$id = $_GET['property_id'] ?? 0;
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

$user_id = (int) ($_SESSION['user_id'] ?? 0);
$is_owner = $user_id > 0 && (int) $details['user_id'] === $user_id;
$is_admin_viewer = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
$is_publicly_visible = ($details['approval_status'] ?? '') === 'approved' && ($details['status'] ?? '') === 'available';


//prevent props that admin has not approved from being viewed by non admins or non owners
if (!$is_publicly_visible && !$is_owner && !$is_admin_viewer) {
    $_SESSION['error'] = 'That property is not currently available to the public.';
    header("Location: ../views/properties.php");
    exit();
}

$user = new User();
$user_details = $user->get_user_by('id', $details['user_id']);

$images = $property->get_property_images($id);
$amenities = $property->get_property_amenities($id); 

$Pt1 = new PropertyTracker();

$views = $Pt1->track_view($id, $user_id);
$tracker = $Pt1->count_stats($id);
$last_viewed = $Pt1->get_last_viewed($id, $user_id);

// check inspection

$can_request = true;
$inspection_check = false;

$inspect = new Inspection();
$can = false;
if ($user_id > 0) {
    $can = $inspect->is_landlord_own_property($id, $user_id);
    if($can) {
        $can_request = false;
    }
    $check_inspection = $inspect->check_inspection($id, $user_id);
    if($check_inspection) {
        $inspection_check = true;
    }
}

$wishlist = new Wishlist();
$is_saved = false;
if (!empty($_SESSION['user_id'])) {
    $is_saved = $wishlist->is_property_saved($_SESSION['user_id'], $id);
}

//application

//toast
$toastMessage = $_SESSION['success'] ?? $_SESSION['feedback'] ?? $_SESSION['error'] ?? null;
$toastType = isset($_SESSION['error']) ? 'danger' : 'success';
unset($_SESSION['success'], $_SESSION['feedback'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($details['title']); ?> - HESTIA</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="icon" type="image/png" href="../favicon.png">
    <link rel="shortcut icon" href="../favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/property-details.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <?php include '../partials/nav.php'; ?>

    <main class="container flex-grow-1 py-5">
        <?php if ($toastMessage) { ?>
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
            <div id="statusToast" class="toast align-items-center text-white bg-<?php echo $toastType; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($toastMessage); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if (!$is_publicly_visible && ($is_owner || $is_admin_viewer)) { ?>
            <div class="alert alert-warning">
                <strong>Private listing:</strong>
                This property is currently <?= htmlspecialchars($details['approval_status'] ?? 'pending') ?> with status <?= htmlspecialchars($details['status'] ?? 'inactive') ?>, so it is not visible on the public marketplace.
                <?php if (($details['approval_status'] ?? '') === 'rejected' && !empty($details['rejection_reason'])) { ?>
                    <br><strong>Rejection reason:</strong> <?= nl2br(htmlspecialchars($details['rejection_reason'])) ?>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Main Image -->
                <?php $main_img = (!empty($images)) ? $images[0]['image_path'] : 'default.png'; ?>
                <picture>
                            <source srcset="../upload/properties/optimized/<?php echo pathinfo($main_img, PATHINFO_FILENAME); ?>.webp" type="image/webp">
                            <source srcset="../upload/properties/<?php echo $main_img; ?>" type="<?php echo pathinfo($main_img, PATHINFO_EXTENSION) === 'png' ? 'image/png' : 'image/jpeg'; ?>">
                            <img src="../upload/properties/<?php echo $main_img; ?>" alt="Property Main Image" class="property-image mb-4 w-100 rounded-3 shadow-sm" style="height: 450px; object-fit: cover;" loading="eager" fetchpriority="high" decoding="async">
                        </picture>

                <!-- Dynamic Gallery -->
                <div class="gallery row g-2 g-md-3">
                    <?php foreach($images as $key => $img){ if($key == 0) continue;?>
                    <div class="col-6 col-md-4">
                        <div class="gallery-item">
                            <picture>
                                <source srcset="../upload/properties/optimized/<?php echo pathinfo($img['image_path'], PATHINFO_FILENAME); ?>.webp" type="image/webp">
                                <source srcset="../upload/properties/<?php echo $img['image_path']; ?>" type="<?php echo pathinfo($img['image_path'], PATHINFO_EXTENSION) === 'png' ? 'image/png' : 'image/jpeg'; ?>">
                                <img src="../upload/properties/<?php echo $img['image_path']; ?>" class="img-fluid rounded-2" style="height: 150px; width: 100%; object-fit: cover;" loading="lazy" decoding="async">
                            </picture>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Property Description -->
                <div class="property-details-section mt-5">
                    <h3><i class="fas fa-file-alt text-primary"></i> Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($details['description'])); ?></p>
                </div>

                <!--  Amenities -->
                <div class="property-details-section">
                    <h3><i class="fas fa-check-circle text-primary"></i> Amenities</h3>
                    <?php if(!empty($amenities)){ ?>
                    <ul class="amenities-list d-flex flex-wrap list-unstyled">
                        <?php foreach($amenities as $amt){ ?>
                        <?php $iconClass = $property->getAmenityIconClass($amt['amenity_name']); ?>
                        <li class="me-4 mb-2"><i class="fas <?php echo htmlspecialchars($iconClass); ?> text-primary me-2"></i><?php echo htmlspecialchars($amt['amenity_name']); ?></li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                        <p class="text-muted small">No specific amenities listed.</p>
                    <?php } ?>
                </div>

                <!-- Location -->
                <div class="property-details-section">
                    <h3><i class="fas fa-map-marker-alt text-primary"></i> Location</h3>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($details['prop_address']); ?>, <?php echo $details['lga_name']; ?>, <?php echo $details['state_name']; ?></p>
                    <div class="map-container rounded-3 overflow-hidden shadow-sm mt-3">
                        <iframe width="100%" height="300" src="https://maps.google.com/maps?q=<?php echo urlencode($details['prop_address']); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                    </div>
                </div>
            </div>

            <!-- Sidebar Card -->
            <div class="col-lg-4">
                <div class="property-details-card shadow-lg p-4 bg-white rounded-4">
                    <!-- Badges Row -->
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <span class="badge bg-<?php echo ($details['listing_type'] == 'sale') ? 'success' : 'primary'; ?> px-3 py-2">
                            For <?php echo ucfirst($details['listing_type']); ?>
                        </span>
                        <span class="badge bg-info px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i> Verified
                        </span>
                        
                        <!-- High Demand Badge -->
                        <?php if(($details['view_count'] ?? 0) > 100){ ?>
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="fas fa-fire me-1"></i> High Demand
                            </span>
                        <?php } ?>
                    </div>
                    <h2 class="h4 fw-bold" style="text-transform: capitalize;"><?php echo htmlspecialchars($details['title']); ?></h2>
                    
                    <!-- Location line -->
                    <p class="text-secondary small mb-2">
                        <i class="fas fa-map-marker-alt me-1" style="color: #C44536;"></i>
                        <?php echo $details['lga_name']; ?>, <?php echo $details['state_name']; ?>
                    </p>
                    
                    <!-- Price -->
                    <div class="price-tag h3 text-danger fw-bold my-3">
                        ₦<?php echo number_format($details['amount'], 2); ?>
                        <?php if($details['listing_type'] == 'rent'){ ?>
                            <small class="text-muted fw-normal" style="font-size: 0.8rem;">/ year</small>
                        <?php } ?>
                    </div>

                    <!-- ENGAGEMENT DASHBOARD -->
                    <div class="bg-light rounded-4 p-3 mb-4">
                        <h6 class="text-secondary mb-3">
                            <i class="fas fa-chart-line me-2" style="color: #C44536;"></i>
                            Property Engagement
                        </h6>
                        
                        <div class="row g-2 text-center">
                            <!-- Views -->
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-eye fs-5 mb-1" style="color: #C44536;"></i>
                                    <span class="fw-bold fs-5"><?php echo number_format($tracker['views_count'] ?? 0); ?></span>
                                    <span class="small text-secondary">Views</span>
                                </div>
                            </div>
                            
                            <!-- Inspection Requests -->
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-calendar-check fs-5 mb-1" style="color: #C44536;"></i>
                                    <span class="fw-bold fs-5"><?php echo number_format($tracker['inspection_count'] ?? 0); ?></span>
                                    <span class="small text-secondary">Inspections</span>
                                </div>
                            </div>
                            
                            <!-- Applications -->
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-file-alt fs-5 mb-1" style="color: #C44536;"></i>
                                    <span class="fw-bold fs-5"><?php echo number_format($tracker['application_count'] ?? 0); ?></span>
                                    <span class="small text-secondary">Applications</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Urgency Messages -->
                        <div class="mt-3 pt-2 border-top">
                            <?php if(($tracker['views_count'] ?? 0) > 100){ ?>
                                <div class="d-flex align-items-center gap-2 text-warning mb-2">
                                    <i class="fas fa-fire"></i>
                                    <small class="fw-semibold">🔥 High demand! Over 100 views this month</small>
                                </div>
                            <?php } ?>
                            
                            <?php if(($tracker['application_count'] ?? 0) >= 3){ ?>
                                <div class="d-flex align-items-center gap-2 text-success mb-2">
                                    <i class="fas fa-users"></i>
                                    <small class="fw-semibold">📝 <?php echo $tracker['application_count']; ?> people applied this week</small>
                                </div>
                            <?php } ?>
                            
                            <?php if($last_viewed){ ?>
                                <?php 
                                    $last_viewed_time = strtotime($last_viewed['viewed_at']);
                                    $minutes_ago = floor((time() - $last_viewed_time) / 60);
                                    if($minutes_ago < 10){
                                ?>
                                <div class="d-flex align-items-center gap-2" style="color: #C44536;">
                                    <i class="fas fa-eye"></i>
                                    <small>Last viewed <?php echo $minutes_ago; ?> <?php echo $minutes_ago == 1 ? 'minute' : 'minutes'; ?> ago</small>
                                </div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Specs -->
                    <div class="specs mt-4">
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-bed"></i></div>
                            <div class="spec-text">
                                <div class="spec-label">Bedrooms</div>
                                <div class="spec-value"><?php echo $details['bedroom']; ?></div>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-couch"></i></div>
                            <div class="spec-text">
                                <div class="spec-label">Furnished</div>
                                <div class="spec-value"><?php echo $details['furnished']; ?></div>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-home"></i></div>
                            <div class="spec-text">
                                <div class="spec-label">Type</div>
                                <div class="spec-value"><?php echo $details['type_name']; ?></div>
                            </div>
                        </div>
                    </div>

                    <h5 style="color: #1A0F1E; font-weight: 700; margin-bottom: 12px; margin-left: 20px;">Status</h5>
                    <p style="color: #666; margin-bottom: 20px; margin-left: 20px; text-transform: capitalize; font-weight: 500;"><?php echo $details['status']; ?></p>

                    <hr>
                    
                    <!-- Listed Date -->
                    <div class="mb-3">
                        <small class="text-secondary">
                            <i class="far fa-calendar-alt me-1"></i>
                            Listed on <?php echo date('F j, Y', strtotime($details['created_at'])); ?>
                        </small>
                    </div>
                    
                    <!-- Contact Info -->
                    <div style="background: linear-gradient(135deg, #fce4ec 0%, #f3e5f5 100%); padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #f8bbd0;">
                        <h6 style="color: #1A0F1E; font-weight: 700; margin-bottom: 12px;">Contact Information</h6>
                        <!-- Divider -->
                        <hr style="margin: 12px 0;">
                        <!-- Name -->
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 8px;"><i class="fas fa-user" style="color: #C44536; margin-right: 8px;"></i><?php echo $user_details['first_name'] . ' ' . $user_details['last_name']; ?></p>
                        <!-- Phone Number -->
                        <p style="color: #666; font-size: 0.9rem; margin-bottom: 8px;"><i class="fas fa-phone" style="color: #C44536; margin-right: 8px;"></i><?php echo $user_details['p_number']; ?></p>
                        <!-- Email -->
                        <p style="color: #666; font-size: 0.9rem; margin: 0;"><i class="fas fa-envelope" style="color: #C44536; margin-right: 8px;"></i><?php echo $user_details['email']; ?></p>
                    </div>

                    <!-- Inspection/Request Action -->
                    <?php if ($is_admin_viewer) { ?>
                        <div class="alert alert-secondary small mb-3">
                            <i class="fas fa-user-shield me-2"></i>
                            Administrator accounts cannot request viewings. Use a tenant or landlord account to test this flow.
                        </div>
                    <?php } elseif(!$is_publicly_visible) { ?>
                        <div class="alert alert-warning small">This property cannot receive inspection requests until it is approved and available.</div>
                    <?php } elseif(isset($_SESSION['user_id']) && $can_request === true && $inspection_check === false) { ?>
                        <span class="">We recommend booking a viewing before applying.</span>
                        <form action="../process/process_inspection.php" method="POST">
                            <input type="hidden" name="property_id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <label class="small fw-bold">Select Inspection Date</label>
                                <input type="date" name="inspection_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" name="request_btn" class="btn btn-primary w-100 mb-2" style="background: #C44536; border: none;">
                                <i class="fas fa-eye me-2"></i> Request Viewing
                            </button>
                        </form>
                    <?php } elseif(!isset($_SESSION['user_id'])) { ?>
                        <div class="alert alert-warning small">Please login to book inspections</div>
                    <?php } elseif($can_request === false) { ?>
                        <div class="alert alert-warning small">You can't inspect your own property</div>
                    <?php } elseif($inspection_check === true) { ?>
                        <div class="alert alert-warning small">You have already requested an inspection for this property</div>
                    <?php } else { ?>
                        <div class="alert alert-warning small">Please login to book inspections</div>
                    <?php } ?>

                    <!-- Application Form -->
                    <?php if ($is_admin_viewer) { ?>
                        <div class="alert alert-secondary small mb-3">
                            <i class="fas fa-user-shield me-2"></i>
                            Administrator accounts cannot submit rental applications.
                        </div>
                    <?php } elseif(!$is_publicly_visible) { ?>
                        <div class="alert alert-warning small">
                            <i class="fas fa-info-circle me-2"></i>
                            This property is not accepting applications until it is approved and available.
                        </div>
                    <?php } elseif(isset($_SESSION['user_id']) && $can_request === true) { ?>
                    <form action="../process/process_application.php" method="POST">
                        <input type="hidden" name="property_id" value="<?php echo $id; ?>">
                        
                        <div class="mb-3">
                            <label class="small fw-bold">Application Message</label>
                            <textarea name="message" class="form-control" rows="3" 
                                    placeholder="Tell the landlord why you're interested in this property..." 
                                    maxlength="1000" required></textarea>
                            <small class="text-muted">Maximum 1000 characters</small>
                        </div>
                        
                        <button type="submit" name="application_btn" class="btn btn-primary w-100 mb-2" 
                                style="background: #C44536; border: none;">
                            <i class="fas fa-file-alt me-2"></i>APPLY NOW
                        </button>
                    </form>
                <?php } elseif(!isset($_SESSION['user_id'])) { ?>
                    <div class="alert alert-warning small">
                        <i class="fas fa-info-circle me-2"></i>
                        Please login to apply for properties.
                    </div>
                <?php } elseif($can_request === false) { ?>
                    <div class="alert alert-warning small">You can't apply to your own property</div>
                <?php } else { ?>
                    <div class="alert alert-warning small">
                        <i class="fas fa-info-circle me-2"></i>
                        Please login to apply for properties.
                    </div>
                <?php } ?>
                    
                    <!-- action buttons -->
                    <?php if ($is_admin_viewer) { ?>
                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" disabled title="Not available for administrators">
                        <i class="far fa-heart me-2"></i>
                        Wishlist unavailable for admin
                    </button>
                    <?php } else { ?>
                    <a href="../process/process_wishlist.php?prop_id=<?php echo $id; ?>" class="btn btn-outline-primary w-100 mb-2" style="border-color: #C44536; color: #C44536;">
                        <i class="<?php echo $is_saved ? 'fas fa-heart me-2 text-danger' : 'far fa-heart me-2'; ?>"></i>
                        <?php echo $is_saved ? 'Saved' : 'Save to Wishlist'; ?>
                    </a>
                    <?php } ?>
                    <button class="btn btn-outline-primary w-100 mb-2" style="border-color: #C44536; color: #C44536;">
                        <i class="fas fa-share-alt me-2"></i> Share Property
                    </button>
                </div>
            </div>
        </div>
    </main>

    <?php include '../partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($toastMessage) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('statusToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
                toast.show();
            }
        });
    </script>
    <?php } ?>
</body>
</html>
