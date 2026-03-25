<?php
session_start();
require_once '../userguard.php';
require_once '../classes/User.php';
require_once '../classes/Property.php';
require_once '../classes/Wishlist.php';
require_once '../classes/Inspection.php';

$user = new User();
$property = new Property();
$wishlistObj = new Wishlist();
$inspectionObj = new Inspection();

$usr_id = $_SESSION['user_id'];
$usr = $user->get_user_by('id', $usr_id);

// Fetch Data for Tabs & Stats
$my_wishlist = $wishlistObj->get_user_wishlist($usr_id);
$my_inspections = $inspectionObj->get_tenant_inspections($usr_id);
$my_applications = $inspectionObj->get_tenant_applications($usr_id);

// Dynamic Stats Count
$count_saved = count($my_wishlist);
$count_apps = count($my_applications);
$count_inspections = count($my_inspections);
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
        <!-- this is for feedback messages -->
        <?php if(isset($_SESSION['feedback'])){ ?>
        <div class="alert alert-success alert-dismissible fade show mt-3">
            <?php echo $_SESSION['feedback']; unset($_SESSION['feedback']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
    <?php }; ?>

    <!-- this is for error messages -->
    <?php if(isset($_SESSION['error'])){ ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php }; ?>
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
                <div class="stat-value"><?php echo $count_saved; ?></div>
                <div class="stat-label">Saved Properties</div>
            </div>
            </a>
            <div class="stat-card">
                <div class="stat-value"><?php echo $count_apps; ?></div>
                <div class="stat-label">Active Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $count_inspections; ?></div>
                <div class="stat-label">Booked Inspections</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">0</div>
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
            <!-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verification" type="button" role="tab">
                    <i class="fas fa-check-circle"></i> Verification
                </button>
            </li> -->
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" style="margin-top: 30px;">
            <!-- Inside Saved Properties Tab -->
        <div class="tab-pane fade show active" id="saved" role="tabpanel">
            <div class="row">
                <?php if(!empty($my_wishlist)){ ?>
                <?php foreach($my_wishlist as $w){ ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card property-card h-100">
                                <div class="position-relative">
                                    <img src="../upload/properties/<?php echo $w['thumbnail'] ?? 'default.png'; ?>" class="card-img-top" style="height:180px; width:100%; object-fit:cover;">
                                </div>
                                <div class="card-body">
                                    <div class="card-title fw-bold text-truncate mb-2 h5" style="text-transform: capitalize;" title="<?php echo htmlspecialchars($w['title']); ?>"><?php echo htmlspecialchars($w['title']); ?></div>
                                    <div class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1 text-danger"></i> <?php echo $w['lga_name']; ?></div>
                                    <div class="text-danger fw-bold mb-3 h5">₦<?php echo number_format($w['amount']); ?></div>
                                    
                                    <div class="property-actions d-flex gap-2">
                                        <a href="../views/property-details.php?property_id=<?php echo $w['property_id']; ?>" class="btn btn-sm btn-primary w-100">View</a>
                                        <a href="../process/process_wishlist.php?prop_id=<?php echo $w['property_id']; ?>" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">You haven't saved any properties yet.</p>
                        <a href="properties.php" class="btn btn-primary btn-sm">Browse Properties</a>
                    </div>
                <?php } ?>
            </div>
        </div>

            <!-- Applications Tab -->
        <div class="tab-pane fade" id="applications" role="tabpanel">
            <?php 
            $apps = $inspectionObj->get_tenant_applications($usr_id);
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
            <?php if(!empty($my_inspections)){ ?>
                <?php foreach($my_inspections as $insp){
                    $status = $insp['status'];
                    $insp_date = date('M d, Y', strtotime($insp['inspection_date']));
                    $statusColor = ($status == 'approved') ? 'success' : (($status == 'rejected') ? 'danger' : 'warning');
                ?>
                    <div class="inspection-item">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="inspection-header">
                            <h5 class="mb-1 fw-bold">
                                <strong><i class="fas fa-home me-1"></i><?php echo htmlspecialchars($insp['title']); ?></strong>
                            </h5>
                        </div>
                        <div class="mt-1">
                            <p class="small text-muted mb-0">
                                <i class="fas fa-map-marker-alt me-1"></i> <?php echo $insp['lga_name']; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <span class="badge bg-<?php echo $statusColor; ?> p-2">
                            <?php echo ucfirst($status); ?>
                        </span>
                    </div>
                </div>
                <div class="mt-3 small text-secondary">
                    <i class="fas fa-calendar-alt me-2"></i> Scheduled Date: 
                    <span class="text-dark fw-bold"><?php echo $insp_date; ?></span>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a href="../views/property-details.php?property_id=<?php echo $insp['property_id']; ?>" class="btn btn-sm btn-outline-primary">
                        View Property
                    </a>
                </div>
            </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-center py-5 text-muted">No inspection requests found.</p>
            <?php } ?>
        </div>
            <!-- Reviews Tab -->
        <div class="tab-pane fade" id="reviews" role="tabpanel">
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
