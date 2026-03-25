<?php
$landlord_name = trim(($userdeets['first_name'] ?? '') . ' ' . ($userdeets['last_name'] ?? ''));
$landlord_first_name = $userdeets['first_name'] ?? 'Landlord';
$landlord_initial = strtoupper(substr($landlord_first_name, 0, 1));
?>
<nav class="top-navbar d-flex align-items-center justify-content-between">
    <a href="../views/index.php" class="logo">Hestia<span>.</span></a>

    <div class="search-container d-none d-md-block">
        <i class="fas fa-search"></i>
        <input type="search" id="dashboardSearch" placeholder="Search properties or applications...">
    </div>

    <div class="nav-right d-flex align-items-center gap-3">
        <a href="#applications-section" class="nav-icon" aria-label="Applications">
            <i class="far fa-file-alt"></i>
        </a>
        <div class="profile-dropdown">
            <div class="profile-trigger">
                <div class="avatar-chip"><?= htmlspecialchars($landlord_initial) ?></div>
                <span class="d-none d-sm-inline"><?= htmlspecialchars($landlord_first_name) ?></span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="dropdown-menu-custom">
                <a href="../views/update-profile.php" class="dropdown-item"><i class="fas fa-user-pen"></i> Profile</a>
                <a href="#properties-section" class="dropdown-item"><i class="fas fa-building"></i> My Properties</a>
                <a href="#applications-section" class="dropdown-item"><i class="fas fa-file-alt"></i> Applications</a>
                <div class="dropdown-divider"></div>
                <form method="post" action="../process/process_logout.php" class="m-0">
                    <button type="submit" class="dropdown-item dropdown-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
