<?php
session_start();
require_once '../userguard.php';
require_once '../classes/User.php';

$userObj = new User();
$user_id = $_SESSION['user_id'] ?? 0;
$user = $userObj->get_user_by('id', $user_id);

if (!$user) {
    $_SESSION['error'] = 'User account not found.';
    header('Location: register.php');
    exit();
}

$dashboard_link = ($_SESSION['user_role'] ?? '') === 'landlord'
    ? '../landlord/landlord-profile.php'
    : '../tenant/tenant-profile.php';
$is_tenant_profile = ($_SESSION['user_role'] ?? '') === 'tenant';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | Hestia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/update-profile.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body class="<?= $is_tenant_profile ? 'tenant-dashboard-page tenant-profile-editor-page' : '' ?>">
    <?php include '../partials/nav.php'; ?>

    <main class="profile-editor-page">
        <div class="container">
            <?php include '../partials/messages.php'; ?>

            <div class="profile-editor-shell">
                <section class="profile-intro-card">
                    <p class="eyebrow">Account Settings</p>
                    <h1>Update profile</h1>
                    <p>Keep your contact details current and optionally change your password from one focused page.</p>

                    <div class="identity-chip">
                        <div class="avatar-badge"><?= htmlspecialchars(strtoupper(substr($user['first_name'], 0, 1))) ?></div>
                        <div>
                            <strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                            <span><?= htmlspecialchars(ucfirst($user['role_'])) ?></span>
                        </div>
                    </div>

                    <a href="<?= htmlspecialchars($dashboard_link) ?>" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to dashboard
                    </a>
                </section>

                <section class="profile-form-card">
                    <div class="section-heading">
                        <h2>Personal details</h2>
                        <p>Password fields are optional. Leave them blank to keep your current password.</p>
                    </div>

                    <form action="../process/process_update_profile.php" method="POST" class="row g-4">
                        <div class="col-md-6">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['p_number']) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>

                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat the new password">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Role</label>
                            <div class="readonly-pill"><?= htmlspecialchars(ucfirst($user['role_'])) ?></div>
                        </div>

                        <div class="col-12 form-actions">
                            <a href="<?= htmlspecialchars($dashboard_link) ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Profile</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
