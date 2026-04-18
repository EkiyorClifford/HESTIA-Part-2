<?php
require dirname(__DIR__, 2) . '/config/app.php';
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit();
}

require_once BASE_PATH . '/admin/classes/Admin.php';
$admin = new Admin();
$admin_id = $_SESSION['admin_id'];
$admin_data = $admin->get_admin_details($admin_id);

if (!$admin_data) {
    $_SESSION['error'] = 'Admin account not found.';
    header('Location: admin-login.php');
    exit();
}
//sidebar
$active_admin_page = 'profile';
$page_heading = 'Update Profile';
$page_subheading = 'Maintain your admin account details from a consistent workspace.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | Hestia Admin</title>
    <link rel="icon" type="image/svg+xml" href="../../favicon.svg">
    <link rel="icon" type="image/png" href="../../favicon.png">
    <link rel="shortcut icon" href="../../favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/admin.css">
    <link rel="stylesheet" href="../assets/update_admin.css">
</head>
<body>
    <?php include BASE_PATH . '/admin/partials/navbar.php'; ?>

    <main class="admin-page">
        <div class="container">
            <div class="admin-shell">
                <?php include BASE_PATH . '/admin/partials/sidebar.php'; ?>

                <div class="admin-content">
                    <?php include BASE_PATH . '/partials/messages.php'; ?>

                    <div class="text-start mb-4">
                        <a href="admin-dashboard.php" class="view-link">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>

                    <div class="profile-shell-card">
                        <div class="profile-hero">
                            <div>
                                <div class="sidebar-title text-white mb-2" style="color: rgba(255,255,255,0.7) !important;">Account Settings</div>
                                <h2 class="mb-2">Update Admin Profile</h2>
                                <p>Refresh your name and password while keeping your admin email locked as the account identifier.</p>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="profile-avatar"><?= htmlspecialchars(strtoupper(substr($admin_data['first_name'], 0, 1))) ?></div>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($admin_data['first_name'] . ' ' . $admin_data['last_name']) ?></div>
                                    <div class="profile-role mt-2"><?= htmlspecialchars(ucfirst($admin_data['role'])) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-form-wrap">
                            <h4 class="mb-2">Personal details</h4>
                            <p class="profile-note">Password fields are optional. Leave them blank to keep your current password.</p>

                            <form action="../process/process_update_profile.php" method="POST" class="row g-4">
                                <div class="col-md-6">
                                    <label for="fname" class="form-label admin-label">First Name</label>
                                    <input type="text" class="form-control admin-input" id="fname" name="fname" value="<?= htmlspecialchars($admin_data['first_name']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="lname" class="form-label admin-label">Last Name</label>
                                    <input type="text" class="form-control admin-input" id="lname" name="lname" value="<?= htmlspecialchars($admin_data['last_name']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label admin-label">Email Address</label>
                                    <input type="email" class="form-control admin-input" id="email" name="email" value="<?= htmlspecialchars($admin_data['email']) ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label admin-label">Role</label>
                                    <input type="text" class="form-control admin-input" value="<?= htmlspecialchars(ucfirst($admin_data['role'])) ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label admin-label">New Password</label>
                                    <input type="password" class="form-control admin-input" id="password" name="password" placeholder="Leave blank to keep current password">
                                </div>

                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label admin-label">Confirm New Password</label>
                                    <input type="password" class="form-control admin-input" id="confirm_password" name="confirm_password" placeholder="Repeat the new password">
                                </div>

                                <div class="col-12 profile-actions">
                                    <a href="admin-dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-warning text-white">Save Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
