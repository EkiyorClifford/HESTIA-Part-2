<?php
$sidebar_mode = $sidebar_mode ?? 'desktop';
$sidebar_container_class = $sidebar_mode === 'mobile' ? 'offcanvas-body' : 'sidebar-desktop';
?>
<div class="<?= htmlspecialchars($sidebar_container_class) ?>">
    <ul class="nav flex-column">
        <?php foreach ($landlord_nav_items as $item) { ?>
            <li class="nav-item">
                <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-link <?= !empty($item['active']) ? 'active' : '' ?>">
                    <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                    <span><?= htmlspecialchars($item['label']) ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>

    <?php if ($sidebar_mode === 'mobile') { ?>
        <div class="offcanvas-profile">
            <div class="avatar-card"><?= htmlspecialchars(strtoupper(substr($userdeets['first_name'] ?? 'L', 0, 1))) ?></div>
            <h4><?= htmlspecialchars(trim(($userdeets['first_name'] ?? '') . ' ' . ($userdeets['last_name'] ?? ''))) ?></h4>
            <p><?= htmlspecialchars($userdeets['email'] ?? '') ?></p>
            <form method="post" action="../process/process_logout.php" class="w-100 mt-3">
                <button type="submit" class="btn btn-light w-100">Logout</button>
            </form>
        </div>
    <?php } else { ?>
        <div class="sidebar-footer">
            <div class="small text-secondary">Signed in as</div>
            <div class="fw-semibold"><?= htmlspecialchars($userdeets['email'] ?? '') ?></div>
        </div>
    <?php } ?>
</div>
