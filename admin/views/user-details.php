<?php
require dirname(__DIR__, 2) . '/config/app.php';
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require_once BASE_PATH . '/admin/classes/Admin.php';
$admin = new Admin;

$filter = $_GET['filter'] ?? 'all';
$keyword = $_GET['search'] ?? '';
if(!empty($keyword)){
    $users = $admin->search_users($keyword, $filter);
}else{
    $users = $admin->get_users($filter);
}
// -----------------------

$total= $admin->get_dashboard_totals();
$role_stats = $admin->get_user_role_stats();
$active_stats = $admin->get_user_active_stats();

$role_count_map = ['landlord' => 0, 'tenant' => 0];
foreach (($role_stats ?: []) as $row) {
    $role_key = strtolower(trim($row['role_'] ?? ''));
    if (array_key_exists($role_key, $role_count_map)) {
        $role_count_map[$role_key] = (int) ($row['count'] ?? 0);
    }
}

$active_count_map = ['yes' => 0, 'no' => 0];
foreach (($active_stats ?: []) as $row) {
    $status_key = strtolower(trim($row['is_active'] ?? ''));
    if (array_key_exists($status_key, $active_count_map)) {
        $active_count_map[$status_key] = (int) ($row['count'] ?? 0);
    }
}
//sidebarz
$active_admin_page = 'users';
$page_heading = 'User Management';
$page_subheading = 'Search, review, and moderate landlord and tenant accounts.';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Hestia Admin</title>
    <link rel="icon" type="image/svg+xml" href="../../favicon.svg">
    <link rel="icon" type="image/png" href="../../favicon.png">
    <link rel="shortcut icon" href="../../favicon.ico">
    
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
    <?php include BASE_PATH . '/admin/partials/navbar.php'; ?>

    <main class="admin-page">
    <div class="container">
        <div class="admin-shell">
            <?php include BASE_PATH . '/admin/partials/sidebar.php'; ?>

            <div class="admin-content">
    <!-- Tenant Details Modal -->
    <div class="modal fade" id="tenantModal" tabindex="-1" aria-labelledby="tenantName" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                
                <div class="modal-header" style="background: var(--orange-light); border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title" id="tenantName">Tenant Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="tenantModalBody">
                    <!-- Loading Spinner -->
                    <div class="text-center p-4">
                        <div class="spinner-border text-orange" role="status"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
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
            <div class="col-md-3 col-6">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <div class="text-uppercase small text-secondary fw-semibold tracking-wide">Total Users</div>
                        <div class="display-6 fw-bold text-dark mt-1"><?= number_format($total['total_users']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <div class="text-uppercase small text-secondary fw-semibold">Active</div>
                        <div class="display-6 fw-bold text-success mt-1"><?= number_format($active_count_map['yes']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <div class="text-uppercase small text-secondary fw-semibold">Landlords</div>
                        <div class="display-6 fw-bold text-primary mt-1"><?= number_format($role_count_map['landlord']) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 bg-white shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        <div class="text-uppercase small text-secondary fw-semibold">Tenants</div>
                        <div class="display-6 fw-bold text-info mt-1"><?= number_format($role_count_map['tenant']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
    <div class="mb-4 d-flex gap-2">
        <a href="?filter=all" class="btn rounded-pill <?= $filter=='all'?'btn-warning':'btn-light' ?>">All Users</a>
        <a href="?filter=landlord" class="btn rounded-pill <?= $filter=='landlord'?'btn-warning':'btn-light' ?>">Landlords</a>
        <a href="?filter=tenant" class="btn rounded-pill <?= $filter=='tenant'?'btn-warning':'btn-light' ?>">Tenants</a>
    </div>

        <!-- Search Bar -->
        <form method="GET" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-warning"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, or phone..." value="<?= htmlspecialchars($keyword) ?>">
                    </div>
                    <input type="hidden" name="filter" value="<?= $filter ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
                <div class="col-md-2">
                    <?php if(!empty($_GET['search'])){ ?>
                        <a href="user-details.php?filter=<?= $filter ?>&search=" class="btn btn-outline-secondary py-2">
                            Clear Search
                        </a>
                    <?php }; ?>
                </div>
            </div>
        </form>
        <!-- Users Table -->
        <div class="section-card col-md-9">
                    <div class="section-title">
                        <i class="fas fa-users mb-2"></i></i> ALL USERS
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($users)){ ?>
                                    <?php foreach($users as $user){ ?>
                                    <tr>
                                        <td style="text-transform: capitalize;">
                                            <?= htmlspecialchars($user["first_name"]. " ". $user['last_name']) ?>
                                        </td>
                                        <td><?= htmlspecialchars(ucfirst($user['email'])) ?></td>
                                        <td><?= htmlspecialchars(ucfirst($user['role_'])) ?></td>
                                        <td id="status-container-<?= $user['id'] ?>">
                                            <?php 
                                            $status = $user['is_active'];
                                            $badgeClass = 'badge-active';
                                            if($status === 'yes') $badgeClass = 'badge-active';
                                            if($status === 'no') $badgeClass = 'badge-inactive';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($user['role_'] == 'tenant'){ ?>
                                                <button class="view-link border-0 bg-transparent btn-tenant" data-id="<?= $user['id'] ?>">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </button>
                                            <?php } else { ?>
                                                <a href="landlord-details.php?id=<?= $user['id'] ?>" class="view-link">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            <?php }; ?>
                                            
                                            <button class="btn-toggle border-0 bg-transparent ms-3" 
                                                    data-id="<?= $user['id'] ?>" 
                                                    data-status="<?= $user['is_active'] ?>"
                                                    title="Toggle Status">
                                                <i class="fas <?= $user['is_active'] === 'yes' ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger' ?> fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php }; ?>
                                <?php }; ?>
                            </tbody>
                        </table>
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
        const modal = new bootstrap.Modal(document.getElementById('tenantModal'));
        const tenantModalBody = document.getElementById('tenantModalBody');

        function getInitial(value) {
            return value && value.length ? value[0].toUpperCase() : '?';
        }

        document.querySelectorAll(".btn-tenant").forEach(button => {
            button.addEventListener('click', function(){
                const userID = this.getAttribute('data-id');

                tenantModalBody.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-orange" role="status"></div></div>';
                modal.show();
                
                fetch(`../process/process_get-tenant.php?id=${userID}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success || !data.user) {
                        throw new Error(data.error || 'Unable to load tenant details.');
                    }

                    const user = data.user;
                    tenantModalBody.innerHTML =  `<div class="text-center mb-3">
                        <div class="user-avatar m-auto" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            ${getInitial(user.first_name)}${getInitial(user.last_name)}
                        </div>
                        <h4 class="mt-2">${user.first_name} ${user.last_name}</h4>
                        <span class="badge badge-active">${user.role_}</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Email:</strong> ${user.email}</li>
                        <li class="list-group-item"><strong>Phone:</strong> ${user.p_number || 'N/A'}</li>
                        <li class="list-group-item"><strong>Role:</strong> ${user.role_}</li>                        
                        <li class="list-group-item"><strong>Joined:</strong> ${user.created_at}</li>
                        <li class="list-group-item"><strong>Status:</strong> ${user.is_active === 'yes' ? 'Active' : 'Inactive'}</li>
                    </ul>`;
                })
                .catch(error => {
                    tenantModalBody.innerHTML = `<div class="alert alert-danger mb-0">${error.message}</div>`;
                });
            });
        });

             // Toast function
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('statusToast');
            const toastMessage = document.getElementById('toastMessage');
            
            // 1. Set the message
            toastMessage.textContent = message;
            
            // 2. Set the color (success = green, danger = red)
            toastEl.classList.remove('bg-success', 'bg-danger');
            toastEl.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
            
            // 3. Initialize and show the Bootstrap Toast
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 }); // Visible for 3 seconds
            toast.show();
        }

        // for disabling the user
        document.querySelectorAll(".btn-toggle").forEach(button => {
            button.addEventListener('click', function(){
                const userID = this.getAttribute("data-id");
                const current = this.getAttribute('data-status');
                const icon = this.querySelector('i');
                const badgeContainer = document.getElementById(`status-container-${userID}`);

                //just to see it
                this.style.opacity= '0.5';
                
                // 1. Prepare the data as FormData for POST
                const formData = new FormData();
                formData.append('id', userID);
                formData.append('is_active', current);
                formData.append('type', 'user');
                
                fetch(`../process/process_toggle.php`, {
                    method: 'POST',
                    body: formData
                })
                .then(res=>res.json())
                .then(data => {
                    if(data.success){
                        const newStatus = data.new_status;
                        //CHANGE BTN
                        this.setAttribute('data-status', newStatus);

                        //change icon
                        if(newStatus === 'yes'){
                            icon.className = 'fas fa-toggle-on text-success fa-lg'
                        }else{
                            icon.className = 'fas fa-toggle-off text-danger fa-lg'
                        }
                        // change the badge
                        const badgeClass = (newStatus === 'yes') ? 'badge-active' : 'badge-inactive';
                        const label = (newStatus === 'yes') ? 'Active' : 'Inactive';
                        badgeContainer.innerHTML = `<span class="badge ${badgeClass}">${label}</span>`;
                        
                        // Show toast
                        showToast(`User ${newStatus === 'yes' ? 'activated' : 'disabled'} successfully!`, 'success');
                    } else {
                        showToast(data.error || data.message || 'Update failed.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'danger');
                })
                .finally(()=>{
                    this.style.opacity = '1';
                })
            })
        });
   

    </script>
    <!-- Toast Container (Positioned in the top-right) -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="statusToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                <!-- Message goes here via JS -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
</body>
</html>
