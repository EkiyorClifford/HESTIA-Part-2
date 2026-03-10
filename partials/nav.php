<?php
// 1. Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../classes/User.php';
$userObj = new User();

$user_name = "";
$user_role = $_SESSION['user_role'] ?? null;

// 2. Fetch logged-in user details only if session exists
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = $userObj->get_user_by('id', $user_id);
    
    if ($user) {
        $user_name = $user['first_name'] . ' ' . $user['last_name'];
    }
}
$base_url = "/Hestia-PHP/"; 
?>

<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Use absolute paths starting with / so they work from any folder -->
        <a class="navbar-brand" href="<?php echo $base_url; ?>views/index.php">HESTIA</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>views/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>views/properties.php">Browse</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>views/contact.php">Contact</a></li>

                <?php if(isset($_SESSION['user_id'])){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle me-1"></i> Hi, <?php echo htmlspecialchars($user_name); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php 
                                    echo ($user_role == 'landlord') 
                                        ? '../views/landlord-profile.php' 
                                        : '../tenant/tenant-profile.php'; 
                                ?>">
                                    <i class="fas fa-gauge-high me-2"></i>Dashboard
                                </a>
                            </li>
                            
                            <?php if($user_role == 'landlord'): ?>
                                <li><a class="dropdown-item" href="../landlord/add-property.php"><i class="fas fa-plus me-2"></i>Add Property</a></li>
                            <?php endif; ?>

                            <?php if($user_role == 'tenant'): ?>
                                <li><a class="dropdown-item" href="../tenant/wishlist.php"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
                            <?php endif; ?>

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="../process/process_logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light ms-lg-3" href="<?php echo $base_url; ?>views/register.php">Login / Register</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>