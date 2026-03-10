<?php
session_start();
require_once '../userguard.php';
require_once '../classes/User.php';
require_once '../classes/Property.php';
$user = new User();
$property = new Property();
$usr_id = $_SESSION['user_id'];
$usr = $user->get_user_by('id', $usr_id);
$inspections = $property->get_tenant_inspections($usr_id);
$date = !empty($inspections[0]['inspection_date']) ? date('M d, Y', strtotime($inspections[0]['inspection_date'])) : 'Not scheduled yet';
$statusColor = 'warning';

if($inspections[0]['status'] == 'approved'){
    $statusColor = 'success';
}elseif($inspections[0]['status'] == 'rejected'){
    $statusColor = 'danger';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Profile - HESTIA Property Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/tenant-profile.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include '../partials/nav.php'; ?>
    <!-- Main Content -->
    <main class="container">
        <h1 class="page-title"><i class="fas fa-user"></i> <?php echo $usr['first_name'] . ' ' . $usr['last_name']; ?>'s Profile</h1>

        <!-- Profile Header Section -->
        <div class="card">
            <div class="card-body">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo $usr['first_name'] . ' ' . $usr['last_name']; ?></h3>
                        <p><i class="fas fa-envelope"></i> <?php echo $usr['email']; ?></p>
                        <p><i class="fas fa-phone"></i> <?php echo $usr['p_number']; ?></p>
                        <p><i class="fas fa-map-marker-alt"></i>No 24,Circular Road. Ikoyi, Lagos </p>
                        <div class="profile-meta">
                            <span class="meta-item"><i class="fas fa-calendar-alt"></i> Member since <?php echo date('Y', strtotime($usr['created_at'])); ?></span>
                            <span class="meta-item"><i class="fas fa-star"></i> 4.8/5 Rating</span>
                        </div>
                    </div>
                </div>
                <div class="row btn-group-custom">
                    <button class="col-md-6 btn btn-primary"><i class="fas fa-edit"></i> Edit Profile</button>
                    <button class="col-md-6 btn btn-secondary"><i class="fas fa-camera"></i> Change Photo</button>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <a href="wishlist.php">
            <div class="stat-card">
                <div class="stat-value">5</div>
                <div class="stat-label">Saved Properties</div>
            </div>
            </a>
            <div class="stat-card">
                <div class="stat-value">3</div>
                <div class="stat-label">Active Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">2</div>
                <div class="stat-label">Booked Inspections</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">12</div>
                <div class="stat-label">Reviews Given</div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="saved-tab" data-bs-toggle="tab" data-bs-target="#saved" type="button" role="tab">
                    <i class="fas fa-heart"></i> Saved Properties
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab">
                    <i class="fas fa-file-alt"></i> Applications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inspections-tab" data-bs-toggle="tab" data-bs-target="#inspections" type="button" role="tab">
                    <i class="fas fa-eye"></i> Inspections
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    <i class="fas fa-comments"></i> My Reviews
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verification" type="button" role="tab">
                    <i class="fas fa-check-circle"></i> Verification
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" style="margin-top: 30px;">
            <!-- Inside Saved Properties Tab -->
        <div class="tab-pane fade show active" id="saved" role="tabpanel">
            <div class="row">
                <?php 
                $wishlist = $property->get_user_wishlist($usr_id);
                if(!empty($wishlist)){
                    foreach($wishlist as $w){ 
                ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="property-card saved border rounded p-2">
                            <div class="property-image mb-2">
                                <img src="../upload/properties/<?php echo $w['thumbnail'] ?? 'default.png'; ?>" class="img-fluid rounded" style="height:150px; width:100%; object-fit:cover;">
                            </div>
                            <div class="property-info">
                                <div class="fw-bold"><?php echo htmlspecialchars($w['title']); ?></div>
                                <div class="small text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo $w['lga_name']; ?>, <?php echo $w['state_name']; ?></div>
                                <div class="text-danger fw-bold my-2">₦<?php echo number_format($w['amount'], 2); ?></div>
                                
                                <div class="property-actions d-flex gap-2">
                                    <a href="../views/property-details.php?id=<?php echo $w['id']; ?>" class="btn btn-sm btn-primary flex-grow-1">View</a>
                                    <a href="../process/process_wishlist.php?prop_id=<?php echo $w['id']; ?>" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    };
                }else{
                ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">You haven't saved any properties yet.</p>
                        <a href="properties.php" class="btn btn-primary btn-sm">Browse Properties</a>
                    </div>
                <?php };
                ?>
            </div>
        </div>

            <!-- Applications Tab -->
        <div class="tab-pane fade" id="applications" role="tabpanel">
            <?php 
            $apps = $property->get_tenant_applications($usr_id);
            if(!empty($apps)){
                foreach($apps as $app){ 
                    $appColor = ($app['status'] == 'accepted') ? 'success' : (($app['status'] == 'pending') ? 'info' : 'danger');
            ?>
                <div class="application-item mb-3 p-3 border rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($app['title']); ?></h5>
                            <p class="small text-muted mb-0">Rent: ₦<?php echo number_format($app['amount'], 2); ?></p>
                        </div>
                        <span class="badge bg-<?php echo $appColor; ?>">
                            <i class="fas fa-file-contract me-1"></i> <?php echo ucfirst($app['status']); ?>
                        </span>
                    </div>
                    <div class="mt-3 small text-secondary">
                        Applied on: <?php echo date('M d, Y', strtotime($app['created_at'])); ?>
                    </div>
                </div>
            <?php 
                }
            }else{
            ?>
                <p class="text-center py-5">You haven't applied for any properties yet.</p>
            <?php }
            ?>
        </div>

            <!-- Inspections Tab -->
        <div class="tab-pane fade" id="inspections" role="tabpanel">
            <?php if(!empty($inspections)){ ?>
                <?php foreach($inspections as $insp){
                
                ?>
                    <div class="inspection-item mb-3 p-3 border rounded shadow-sm bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($insp['title']); ?></h5>
                                <p class="small text-muted mb-0"><i class="fas fa-map-marker-alt"></i> <?php echo $insp['lga_name']; ?></p>
                            </div>
                            <span class="badge bg-<?php echo $statusColor; ?> p-2">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </div>
                        
                        <div class="mt-3 small text-secondary">
                            <i class="fas fa-calendar-alt me-2"></i> Date: 
                            <span class="text-dark fw-bold"><?php echo $date ?></span>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <!-- Link to existing details page -->
                            <a href="../views/property-details.php?id=<?php echo $insp['property_id']; ?>" class="btn btn-sm btn-outline-primary">
                                View Property
                            </a>

                            <?php if($status == 'pending'): ?>
                                <a href="../process/process_inspection.php?action=cancel&id=<?php echo $insp['inspection_id']; ?>" 
                                class="btn btn-sm btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to cancel?')">
                                    Cancel
                                </a>
                            <?php endif; ?>

                            <?php if($status == 'approved' || $status == 'pending'): ?>
                                <!-- Trigger Modal -->
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reschedModal<?php echo $insp['inspection_id']; ?>">
                                    Reschedule
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- RESCHEDUL Modal -->
                    <div class="modal fade" id="reschedModal<?php echo $insp['inspection_id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-sm">
                            <form action="../process/process_inspection.php" method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">New Date</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="reschedule">
                                    <input type="hidden" name="inspection_id" value="<?php echo $insp['inspection_id']; ?>">
                                    <input type="date" name="new_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Update Date</button>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php } ?>
            <?php } else { ?>
                <p class="text-center py-5 text-muted">No inspection requests found.</p>
            <?php } ?>
        </div>
            <!-- Reviews Tab -->
        <div     class="tab-pane fade" id="reviews" role="tabpanel">
            <div class="coming-soon-container">
                <div class="coming-soon-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <h3 class="coming-soon-title">Reviews Coming Soon</h3>
                <p class="coming-soon-text">
                    We're collecting tenant experiences for this property. 
                    Check back in the coming weeks for authentic reviews.
                </p>
                <div class="coming-soon-progress">
                    <div class="progress-bar"></div>
                </div>
                <p class="coming-soon-note">
                    <i class="far fa-bell"></i> 
                    Be the first to leave a review when you book this property
                </p>
                <button class="btn-notify">
                    <i class="far fa-bell"></i> Notify Me When Reviews Arrive
                </button>
            </div>
        </div>
            
            <!-- Verification Tab -->
            <!-- <div class="tab-pane fade" id="verification" role="tabpanel">
                <ul class="verification-list">
                    <li class="verification-item">
                        <div class="verification-icon verified">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Email Address</h5>
                            <p>Verified on Dec 15, 2023</p>
                        </div>
                        <span class="verification-badge badge-verified"><i class="fas fa-check"></i> Verified</span>
                    </li>
                    <li class="verification-item">
                        <div class="verification-icon verified">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Phone Number</h5>
                            <p>Verified on Dec 20, 2023</p>
                        </div>
                        <span class="verification-badge badge-verified"><i class="fas fa-check"></i> Verified</span>
                    </li>
                    <li class="verification-item">
                        <div class="verification-icon verified">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Government ID</h5>
                            <p>Verified on Jan 5, 2024</p>
                        </div>
                        <span class="verification-badge badge-verified"><i class="fas fa-check"></i> Verified</span>
                    </li>
                    <li class="verification-item">
                        <div class="verification-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Background Check</h5>
                            <p>Pending review - Usually completes within 2-3 business days</p>
                        </div>
                        <span class="verification-badge badge-pending"><i class="fas fa-hourglass-half"></i> Pending</span>
                    </li>
                </ul>
            </div> -->
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2024 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
