<?php
session_start();

// Add authentication check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require_once '../classes/Admin.php';

$active_admin_page = 'properties';
$admin = new Admin();
$keyword = trim($_GET['search'] ?? '');
$filter = trim($_GET['filter'] ?? 'all');

if(!empty($keyword)){
    $all_properties = $admin->search_properties($keyword, $filter);
} else {
    $all_properties = $admin->get_all_properties();
}

$dashboard = $admin->get_property_dashboard_totals();
$toast_message = $_SESSION['feedback'] ?? $_SESSION['error'] ?? null;
$toast_type = isset($_SESSION['error']) ? 'danger' : 'success';
unset($_SESSION['feedback'], $_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Management | Hestia Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- IBM Plex Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        .tracking-wide { letter-spacing: 0.5px; }
        .nav-link.active.bg-warning { background: linear-gradient(135deg, #E04E1A, #F97316) !important; }
        .input-group-text { background: white; }
        .input-group:focus-within { box-shadow: 0 0 0 3px rgba(224, 78, 26, 0.1); border-radius: 6px; }
    </style>
</head>

<body>
    <?php include "../partials/navbar.php"; ?>

        <!-- Property Details Modal -->
    <div class="modal fade" id="propertyModal" tabindex="-1" aria-labelledby="propertyName" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                
                <div class="modal-header" style="background: var(--orange-light); border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title" id="propertyName">Property Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="propertyModalBody">
                    <!-- Loading Spinner -->
                    <div class="text-center p-4">
                        <div class="spinner-border text-orange" role="status"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <main class="admin-page">
    <div class="container">
        <div class="admin-shell">
            <?php include "../partials/sidebar.php"; ?>

            <div class="admin-content">
        <!-- back to the dashboard -->
         <div class="text-start mb-4">
            <a href="admin-dashboard.php" class="view-link">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
<!-- section admin header -->
        <div class="welcome-card">
            <div>
                <h3>Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Administrator') ?>!</h3>
                <p>Here's what's happening with your platform today.</p>
            </div>
            <div class="date-badge">
                <i class="far fa-calendar-alt me-2"></i> <?= date('F j, Y') ?>
            </div>
        </div>

<!-- Stats Bar -->
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-6">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <div class="text-uppercase small text-secondary fw-semibold tracking-wide">Total Properties</div>
                        <div class="display-6 fw-bold text-dark mt-1"><?= number_format($dashboard['total_properties']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <div class="text-uppercase small text-secondary fw-semibold">Active</div>
                        <div class="display-6 fw-bold text-success mt-1"><?= number_format($dashboard['active_properties']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <div class="text-uppercase small text-secondary fw-semibold">Inactive</div>
                        <div class="display-6 fw-bold text-primary mt-1"><?= number_format($dashboard['inactive_properties']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <form method="GET" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-warning"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, LGA, state, status, or address..." value="<?= htmlspecialchars($keyword) ?>">
                    </div>
                    <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
                <div class="col-md-2">
                    <?php if(!empty($_GET['search'])): ?>
                        <a href="property_management.php?filter=<?= urlencode($filter) ?>&search=" class="btn btn-outline-secondary py-2">
                            Clear Search
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
                                           <!-- property management -->
                            <div class="section-card">
                                <div class="section-title">
                                    <i class="fas fa-building"></i> PROPERTIES MANAGEMENT
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Property</th>
                                                <th>Location</th>
                                                <th>Approval</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($all_properties)) { ?>
                                                <?php foreach ($all_properties as $property) { ?>
                                                    <?php
                                                    $status = strtolower(trim($property['status'] ?? 'inactive'));
                                                    $approval_status = strtolower(trim($property['approval_status'] ?? 'pending'));
                                                    $badge_class = 'badge-inactive';
                                                    if ($status === 'available' || $status === 'active') {
                                                        $badge_class = 'badge-active';
                                                    } elseif ($status === 'taken' || $status === 'pending') {
                                                        $badge_class = 'badge-pending';
                                                    }

                                                    $approval_badge = 'badge-pending';
                                                    if ($approval_status === 'approved') {
                                                        $approval_badge = 'badge-active';
                                                    } elseif ($approval_status === 'rejected') {
                                                        $approval_badge = 'badge-inactive';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td style="text-transform: capitalize;">
                                                            <?= htmlspecialchars($property['title'] ?? 'Untitled property') ?>
                                                        </td>
                                                        <td style="text-transform: capitalize;">
                                                            <?= htmlspecialchars(($property['lga_name'] ?? 'Unknown area') . ', ' . ($property['state_name'] ?? 'Unknown state')) ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge <?= $approval_badge ?>"><?= htmlspecialchars(ucfirst($approval_status)) ?></span>
                                                        </td>
                                                        <td>&#8358;<?= number_format((float) ($property['amount'] ?? 0)) ?></td>
                                                        <td id="property-status-container-<?= $property['property_id'] ?>">
                                                            <span class="badge <?= $badge_class ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
                                                        </td>
                                                        <td>
                                                            <?php if ($approval_status === 'pending') { ?>
                                                                <a href="/Hestia-PHP/admin/property-review.php?id=<?= (int) $property['property_id'] ?>" class="view-link">
                                                                    <i class="fas fa-eye me-1"></i> Review
                                                                </a>
                                                            <?php } else { ?>
                                                                <button class="view-link border-0 bg-transparent btn-property" data-id="<?= $property['property_id'] ?>">
                                                                    <i class="fas fa-eye me-1"></i> View
                                                                </button>
                                                            <?php } ?>
                                                            
                                                            <button class="btn-toggle border-0 bg-transparent ms-3" 
                                                                    data-id="<?= $property['property_id'] ?>" 
                                                                    data-status="<?= $property['status'] ?>"
                                                                    title="Toggle Status">
                                                                <i class="fas <?= $property['status'] === 'available' ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger' ?> fa-lg"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">
                                                        <div class="empty-state">
                                                            <i class="fas fa-building"></i>
                                                            <h6>No properties found</h6>
                                                            <p class="small mb-0">Properties will appear here once landlords add them to the platform.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                </div>
        <!-- Pagination -->
    </div>
    </div>
    </div>
    </main>
<!-- bootstrap js -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const propertyModal = new bootstrap.Modal(document.getElementById('propertyModal'));
        const propertyModalBody = document.getElementById('propertyModalBody');

        // Property modal functionality
        document.querySelectorAll(".btn-property").forEach(button => {
            button.addEventListener('click', function(){
                const propertyID = this.getAttribute('data-id');

                propertyModalBody.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-orange" role="status"></div></div>';
                propertyModal.show();
                
                fetch(`../process/process_get-property.php?id=${propertyID}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success || !data.property) {
                        throw new Error(data.error || 'Unable to load property details.');
                    }

                    const property = data.property;
                    const images = property.images ? JSON.parse(property.images) : [];
                    const imageHtml = images.length > 0 
                        ? `<div class="mb-3">
                            <img src="../upload/properties/${images[0]}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover; width: 100%;">
                        </div>`
                        : '<div class="text-center p-3 bg-light rounded mb-3"><i class="fas fa-image fa-3x text-muted"></i><p class="text-muted mb-0 mt-2">No images available</p></div>';

                    propertyModalBody.innerHTML = `
                        ${imageHtml}
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-primary">${property.title}</h5>
                                <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i>${property.lga_name}, ${property.state_name}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <h4 class="text-success">&#8358;${parseFloat(property.amount).toLocaleString()}</h4>
                                <span class="badge ${property.status === 'available' ? 'badge-active' : property.status === 'taken' ? 'badge-pending' : 'badge-inactive'}">${property.status}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Type:</strong> ${property.type || 'N/A'}</p>
                                <p><strong>Bedrooms:</strong> ${property.bedrooms || 'N/A'}</p>
                                <p><strong>Bathrooms:</strong> ${property.bathrooms || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Landlord:</strong> ${property.first_name} ${property.last_name}</p>
                                <p><strong>Contact:</strong> ${property.email}</p>
                                <p><strong>Listed:</strong> ${new Date(property.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <p><strong>Description:</strong></p>
                            <p class="text-muted">${property.description || 'No description available'}</p>
                        </div>
                        <div class="text-center mt-3">
                            <a href="../views/property-details.php?property_id=${property.property_id}" class="btn btn-warning" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>View Full Property Page
                            </a>
                        </div>
                    `;
                })
                .catch(error => {
                    propertyModalBody.innerHTML = `<div class="alert alert-danger mb-0">${error.message}</div>`;
                });
            });
        });

        // Toast function
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('statusToast');
            const toastMessage = document.getElementById('toastMessage');
            
            toastMessage.textContent = message;
            toastEl.classList.remove('bg-success', 'bg-danger');
            toastEl.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
            
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        }

        // Property toggle functionality
        document.querySelectorAll(".btn-toggle").forEach(button => {
            button.addEventListener('click', function(){
                const propertyID = this.getAttribute("data-id");
                const current = this.getAttribute('data-status');
                const icon = this.querySelector('i');
                const badgeContainer = document.getElementById(`property-status-container-${propertyID}`);

                this.style.opacity = '0.5';
                
                const formData = new FormData();
                formData.append('id', propertyID);
                formData.append('status', current);
                formData.append('type', 'property');
                
                fetch(`../process/process_toggle.php`, {
                    method: 'POST',
                    body: formData
                })
                .then(res=>res.json())
                .then(data => {
                    if(data.success){
                        const newStatus = data.new_status;
                        this.setAttribute('data-status', newStatus);

                        if(newStatus === 'available'){
                            icon.className = 'fas fa-toggle-on text-success fa-lg'
                        }else{
                            icon.className = 'fas fa-toggle-off text-danger fa-lg'
                        }
                        
                        const badgeClass = (newStatus === 'available') ? 'badge-active' : 'badge-inactive';
                        const label = (newStatus === 'available') ? 'Available' : 'Inactive';
                        badgeContainer.innerHTML = `<span class="badge ${badgeClass}">${label}</span>`;
                        
                        showToast(`Property ${newStatus === 'available' ? 'activated' : 'deactivated'} successfully!`, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'danger');
                })
                .finally(()=>{
                    this.style.opacity = '1';
                })
            });
        });

        <?php if ($toast_message): ?>
        document.addEventListener('DOMContentLoaded', function () {
            showToast(<?= json_encode($toast_message) ?>, <?= json_encode($toast_type) ?>);
        });
        <?php endif; ?>
    </script>
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
        <div id="statusToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</body>
</html>
