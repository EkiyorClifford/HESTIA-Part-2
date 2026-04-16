<?php
session_start();
require_once __DIR__ . '/../classes/Property.php';
require_once __DIR__ . '/../classes/State.php';
require_once __DIR__ . '/../classes/PropertyTracker.php';
require_once __DIR__ . '/../classes/Wishlist.php';

$propObj = new Property();
$stateObj = new State();
$trackerObj = new PropertyTracker();
$wishlistObj = new Wishlist();

// 1. Fetch data for dropdowns
$states = $stateObj->get_active_states();
$ptypes = $stateObj->get_property_types();
$saved_ids = [];

if (isset($_SESSION['user_id'])) {
    $wishlist = $wishlistObj->get_user_wishlist($_SESSION['user_id']);
    $saved_ids = array_map('intval', array_column($wishlist, 'property_id'));
}

// 2. Fetch publicly visible properties based on filters
$filters = $_GET;
$all_props = $propObj->get_properties($filters);
// $view_counts = [];
// foreach($all_props as $prop) {
//     $view_counts[$prop['property_id']] = $trackerObj->get_view_count($prop['property_id']);
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - HESTIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Keep your existing Styles here -->
    <link rel="stylesheet" href="../assets/property.css"> <!-- Recommended: move styles to a CSS file -->
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/nav.php'; ?>

    <main class="container my-5">
        <h1 class="text-center mb-4" style="color: #C44536;">Find Your Next Home</h1>
        
        <!-- Filter Section -->
        <div class="filter-section shadow-sm p-4 bg-white rounded-15 mb-5">
            <form action="properties.php" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-12 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search"></i></span>
                            <input type="text" name="keyword" class="form-control border-start-0" placeholder="Search by title, street, or city..." value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Property Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <?php foreach($ptypes as $ptype){ ?>
                                <option value="<?php echo $ptype['type_id']; ?>" <?php echo (isset($_GET['type']) && $_GET['type'] == $ptype['type_id']) ? 'selected' : ''; ?>>
                                    <?php echo $ptype['type_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Min Price</label>
                        <input type="number" name="min_price" class="form-control" placeholder="₦ 0" value="<?php echo $_GET['min_price'] ?? ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Max Price</label>
                        <input type="number" name="max_price" class="form-control" placeholder="₦ Any" value="<?php echo $_GET['max_price'] ?? ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 py-2">Filter Properties</button>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-link p-0 text-decoration-none text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                        <i class="fas fa-sliders-h me-1"></i> Advanced filters
                    </button>
                    
                    <div class="collapse <?php echo (isset($_GET['furnished']) || isset($_GET['bedroom'])) ? 'show' : ''; ?>" id="advancedFilters">
                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <select name="furnished" class="form-select">
                                    <option value="">Any Furnishing</option>
                                    <option value="Furnished" <?php echo (isset($_GET['furnished']) && $_GET['furnished'] == 'Furnished') ? 'selected' : ''; ?>>Furnished</option>
                                    <option value="Unfurnished" <?php echo (isset($_GET['furnished']) && $_GET['furnished'] == 'Unfurnished') ? 'selected' : ''; ?>>Unfurnished</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="bedroom" class="form-control" placeholder="Min Bedrooms" value="<?php echo $_GET['bedroom'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="listing_type" class="form-select">
                                    <option value="">Rent or Sale</option>
                                    <option value="rent" <?php echo (isset($_GET['listing_type']) && $_GET['listing_type'] == 'rent') ? 'selected' : ''; ?>>For Rent</option>
                                    <option value="sale" <?php echo (isset($_GET['listing_type']) && $_GET['listing_type'] == 'sale') ? 'selected' : ''; ?>>For Sale</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <a href="properties.php" class="btn btn-outline-secondary w-100">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- PROPERTIES LIST -->
        <div class="row g-4">
            <?php if (!empty($all_props)){ ?>
                <?php foreach ($all_props as $p){ ?>
                    <div class="col-md-4">
                        <div class="card h-100 position-relative hestia-property-card">
                            
                            <!-- WISHLIST HEART ICON -->
                            <div class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                                <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true){ ?>
                                    <span class="btn btn-white btn-sm rounded-circle shadow-sm bg-white" style="cursor: not-allowed; opacity: 0.9;" title="Administrators cannot save properties to the wishlist" role="img" aria-label="Wishlist not available for administrators">
                                        <i class="far fa-heart text-secondary"></i>
                                    </span>
                                <?php } elseif (isset($_SESSION['user_id'])){ ?>
                                    <?php 
                                        $is_saved = in_array((int) $p['property_id'], $saved_ids, true);
                                    ?>
                                    <a href="../process/process_wishlist.php?prop_id=<?php echo $p['property_id']; ?>" 
                                    class="btn btn-white btn-sm rounded-circle shadow-sm bg-white" 
                                    title="<?php echo $is_saved ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>">
                                        <i class="<?php echo $is_saved ? 'fas fa-heart text-danger' : 'far fa-heart'; ?>"></i>
                                    </a>
                                <?php }else{ ?>
                                    <!-- If not logged in, clicking the heart takes them to login as tenant only o -->
                                    <a href="register.php" class="btn btn-white btn-sm rounded-circle shadow-sm bg-white">
                                        <i class="far fa-heart"></i>
                                    </a>
                                <?php }; ?>
                            </div>

                            <!-- Listing Type Badge -->
                            <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge <?php echo ($p['listing_type'] == 'sale') ? 'bg-success' : 'bg-primary'; ?>">
                                    For <?php echo ucfirst($p['listing_type']); ?>
                                </span>
                            </div>

                            <!-- Property Image -->
                            <?php 
                                $img = (!empty($p['thumbnail'])) ? $p['thumbnail'] : 'default_property.png';
                            ?>
                            <img src="../upload/properties/<?php echo $img; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($p['title']); ?>" style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body">
                                <h5 class="card-title text-truncate" style="text-transform: capitalize;"><?php echo htmlspecialchars($p['title']); ?></h5>
                                <p class="small text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i> 
                                    <?php echo htmlspecialchars($p['lga_name'] . ', ' . $p['state_name']); ?>
                                </p>
                                
                                <div class="specs mb-3 small">
                                    <span><i class="fas fa-bed"></i> <?php echo $p['bedroom']; ?> Beds</span> | 
                                    <span><i class="fas fa-couch"></i> <?php echo $p['furnished']; ?></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="price-text mb-0 fw-bold" style="color: #C44536;">
                                        ₦<?php echo number_format($p['amount']); ?>
                                        <?php if($p['listing_type'] == 'rent'){?>
                                            <small class="text-muted fw-normal" style="font-size: 0.7rem;">/ year</small>
                                        <?php }?>
                                    </p>
                                    <a href="../views/property-details.php?property_id=<?php echo $p['property_id']; ?>" class="btn btn-primary btn-sm">Details</a>
                                </div>
                                
                                <!-- View Count -->
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center property-card-footer">
                                    <div class="d-flex align-items-center gap-2 view-count" data-property-id="<?php echo $p['property_id']; ?>">
                                        <i class="far fa-eye text-muted small"></i>
                                        <span class="small text-muted view-count-number">
                                            <?php echo number_format($views= $trackerObj->get_view_count($p['property_id'])['views_count'] ?? 0); ?> views
                                        </span>
                                    </div>
                                    <small class="text-muted small listing-date">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <?php echo date('M d, Y', strtotime($p['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else{?>
                <div class="col-12 text-center py-5">
                    <h4 class="mt-3 text-muted">No properties found matching your search.</h4>
                    <a href="properties.php" class="btn btn-link">Clear all filters</a>
                </div>
            <?php } ?>
        </div>
    </main>

    <footer class="footer text-center mt-5">
        <div class="container">
            <p>&copy; 2026 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../partials/hestia-easter-scripts.php'; ?>
    <script>
    // Track views when user clicks on property details
    document.addEventListener('DOMContentLoaded', function() {
        const propertyLinks = document.querySelectorAll('a[href*="property-details.php"]');
        
        propertyLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const propertyId = href.match(/property_id=(\d+)/);//i use this to get the property id from the url
                
                if (propertyId) {
                    const propId = propertyId[1];
                    trackView(propId);
                }
            });
        });
        
        function trackView(propertyId) {
            const formData = new FormData();
            formData.append('property_id', propertyId);
            
            fetch('../api/track-view.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.view_count !== undefined) {
                    // Update the view count display
                    const viewCountElement = document.querySelector(`[data-property-id="${propertyId}"] .view-count-number`);
                    if (viewCountElement) {
                        viewCountElement.textContent = `${data.view_count} views`;
                    }
                }
            })
            .catch(error => {
                console.error('Error tracking view:', error);
            });
        }
    });
    </script>
</body>
</html>
