<?php
$base_url = "/Hestia-PHP/";
$admin_name = $_SESSION['admin_name'] ?? ($_SESSION['first_name'] ?? 'Administrator');
$page_heading = $page_heading ?? 'Admin Dashboard';
$page_subheading = $page_subheading ?? 'Manage core platform activity from one place.';
?>
<header class="admin-navbar">
    <div class="container">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <a class="admin-brand" href="<?= $base_url ?>admin/views/admin-dashboard.php">HESTIA<span>.</span></a>
                <div class="small text-secondary mt-1"><?= htmlspecialchars($page_subheading) ?></div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="fw-semibold"><?= htmlspecialchars($admin_name) ?></div>
                    <div class="small text-secondary"><?= htmlspecialchars($page_heading) ?></div>
                </div>
                <form method="post" action="<?= $base_url ?>admin/process/process_admin_logout.php" class="m-0">
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
