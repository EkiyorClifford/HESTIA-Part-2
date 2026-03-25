<?php
$base_url = "/Hestia-PHP/";
$active_admin_page = $active_admin_page ?? 'dashboard';
?>
<aside class="admin-sidebar">
    <div class="sidebar-card">
        <div class="sidebar-title">Navigation</div>
        <ul class="sidebar-nav">
            <li>
                <a href="<?= $base_url ?>admin/views/admin-dashboard.php" class="sidebar-link <?= $active_admin_page === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= $base_url ?>admin/views/user-details.php" class="sidebar-link <?= $active_admin_page === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </li>
            <li>
                <a href="<?= $base_url ?>views/properties.php" class="sidebar-link">
                    <i class="fas fa-building"></i>
                    <span>Properties</span>
                </a>
            </li>
            <li>
                <a href="<?= $base_url ?>admin/views/admin-dashboard.php#recent-applications" class="sidebar-link">
                    <i class="fas fa-file-signature"></i>
                    <span>Applications</span>
                </a>
            </li>
            <li>
                <a href="<?= $base_url ?>views/index.php" class="sidebar-link" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Site</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-meta">
            <span class="label">Signed In As</span>
            <span class="value"><?= htmlspecialchars($_SESSION['admin_role'] ?? 'staff') ?></span>
        </div>

        <div class="sidebar-meta">
            <span class="label">Today</span>
            <span class="value"><?= date('F j, Y') ?></span>
        </div>
    </div>
</aside>
