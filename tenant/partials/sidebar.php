<?php
$sidebar_mode = $sidebar_mode ?? 'desktop';
$sidebar_container_class = $sidebar_mode === 'mobile' ? 'offcanvas-body' : 'sidebar-desktop';
$active_tenant_page = $active_tenant_page ?? 'dashboard';
$tenant_nav_items = [
    ['href' => '../tenant/tenant-profile.php', 'label' => 'Dashboard', 'icon' => 'fas fa-home', 'key' => 'dashboard'],
    ['href' => '../tenant/tenant-profile.php#saved-section', 'label' => 'Saved Properties', 'icon' => 'fas fa-heart', 'key' => 'saved'],
    ['href' => '../tenant/tenant-profile.php#applications-section', 'label' => 'Applications', 'icon' => 'fas fa-file-alt', 'key' => 'applications'],
    ['href' => '../tenant/tenant-profile.php#inspections-section', 'label' => 'Inspections', 'icon' => 'fas fa-calendar-check', 'key' => 'inspections'],
    ['href' => '../views/properties.php', 'label' => 'Browse Properties', 'icon' => 'fas fa-search', 'key' => 'browse'],
    ['href' => '../tenant/wishlist.php', 'label' => 'Wishlist Page', 'icon' => 'fas fa-bookmark', 'key' => 'wishlist'],
];
?>
<div class="<?= htmlspecialchars($sidebar_container_class) ?>">
    <ul class="nav flex-column">
        <?php foreach ($tenant_nav_items as $item) { ?>
            <li class="nav-item">
                <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-link <?= $active_tenant_page === ($item['key'] ?? '') ? 'active' : '' ?>">
                    <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>

    <?php if ($sidebar_mode === 'mobile') { ?>
        <div class="offcanvas-profile">
            <div class="avatar-card"><?= htmlspecialchars(strtoupper(substr($tenant_user['first_name'] ?? 'T', 0, 1))) ?></div>
            <h4><?= htmlspecialchars(trim(($tenant_user['first_name'] ?? '') . ' ' . ($tenant_user['last_name'] ?? ''))) ?></h4>
            <p><?= htmlspecialchars($tenant_user['email'] ?? '') ?></p>
            <form method="post" action="../process/process_logout.php" class="w-100 mt-3">
                <button type="submit" class="btn btn-light w-100">Logout</button>
            </form>
        </div>
    <?php } else { ?>
        <div class="sidebar-footer">
            <div class="small text-secondary">Signed in as</div>
            <div class="fw-semibold"><?= htmlspecialchars($tenant_user['email'] ?? '') ?></div>
        </div>
    <?php } ?>
</div>
