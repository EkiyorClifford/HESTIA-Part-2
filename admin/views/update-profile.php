<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit();
}

require_once "../classes/Admin.php";
$admin = new Admin();
$admin_id = (int) ($_SESSION['admin_id'] ?? 0);
$admin_data = $admin->get_admin_details($admin_id);

if (!$admin_data) {
    $_SESSION['error'] = 'Admin account not found.';
    header('Location: admin-login.php');
    exit();
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        .profile-shell-card {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .profile-hero {
            background: linear-gradient(135deg, var(--purple-deep), var(--purple));
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            gap: 1.5rem;
            align-items: center;
        }

        .profile-hero p {
            color: rgba(255, 255, 255, 0.82);
            margin: 0.4rem 0 0;
        }

        .profile-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .profile-role {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .profile-form-wrap {
            padding: 2rem;
        }

        .profile-note {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .form-label.admin-label {
            color: var(--text-secondary);
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .form-control.admin-input {
            border-radius: 14px;
            padding: 0.9rem 1rem;
            border: 1px solid var(--border-medium);
        }

        .form-control.admin-input:focus {
            border-color: var(--orange-deep);
            box-shadow: 0 0 0 0.2rem rgba(224, 78, 26, 0.12);
        }

        .form-control.admin-input[readonly] {
            background: #f7f1ee;
            color: var(--text-secondary);
        }

        .profile-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 767.98px) {
            .profile-hero {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include "../partials/navbar.php"; ?>

    <main class="admin-page">
        <div class="container">
            <div class="admin-shell">
                <?php include "../partials/sidebar.php"; ?>

                <div class="admin-content">
                    <?php include "../../partials/messages.php"; ?>

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
