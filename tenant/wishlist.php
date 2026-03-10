<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - HESTIA Property Rentals</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/wishlist.css">
    
</head>
<body>
    <!-- Header with Navigation -->
    <?php include_once "../partials/nav.php"; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-heart me-2"></i>My Wishlist</h1>
            <p>Your favorite properties in one place</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container flex-grow-1 pb-5">
        <!-- Wishlist Controls -->
        <div class="wishlist-controls">
            <div class="wishlist-count">
                You have <span>4</span> properties saved
            </div>
            <div>
                <button class="btn-secondary"><i class="fas fa-download"></i> Download List</button>
                <button class="btn-secondary ms-2"><i class="fas fa-share-alt"></i> Share Wishlist</button>
            </div>
        </div>

        <!-- Sort Section -->
        <div class="sort-section">
            <label for="sortBy"><i class="fas fa-sort me-2"></i>Sort by:</label>
            <select id="sortBy" class="form-select w-100" style="max-width: 300px;">
                <option selected>Recently Added</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Bedrooms: Low to High</option>
                <option>Bedrooms: High to Low</option>
                <option>Location</option>
            </select>
        </div>

        <!-- Wishlist Items -->
        <div class="wishlist-grid">
            <!-- Property 1 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge">For Sale</span>
                <img src="image/4BED-MASSIONETTE-1B.png" class="card-img-top" alt="Luxury Maisonette">
                <div class="card-body">
                    <h5 class="card-title">Luxury Maisonette</h5>
                    <p class="card-text">Beautiful 4-bedroom maisonette in the city center with premium finishes.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 4 Beds</span>
                        <span><i class="fas fa-bath"></i> 3 Baths</span>
                        <span><i class="fas fa-ruler-combined"></i> 2,500 sqft</span>
                    </div>
                    <div class="card-price">₦850,000,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>

            <!-- Property 2 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge rent">For Rent</span>
                <img src="image/4-DETACHED-700M.png" class="card-img-top" alt="Spacious House">
                <div class="card-body">
                    <h5 class="card-title">Spacious House</h5>
                    <p class="card-text">Family-friendly detached house with modern amenities and patio.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 4 Beds</span>
                        <span><i class="fas fa-bath"></i> 3 Baths</span>
                        <span><i class="fas fa-ruler-combined"></i> 3,000 sqft</span>
                    </div>
                    <div class="card-price">₦2,500,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>

            <!-- Property 3 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge">For Sale</span>
                <img src="image/5-BED-DUPE-500MI.png" class="card-img-top" alt="Luxury Condo">
                <div class="card-body">
                    <h5 class="card-title">Luxury Condo</h5>
                    <p class="card-text">Sleek luxury apartment perfect for modern living with city views.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 1 Bed</span>
                        <span><i class="fas fa-bath"></i> 1 Bath</span>
                        <span><i class="fas fa-ruler-combined"></i> 800 sqft</span>
                    </div>
                    <div class="card-price">₦500,000,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>

            <!-- Property 4 -->
            <div class="card">
                <button class="remove-btn" title="Remove from wishlist"><i class="fas fa-trash-alt"></i></button>
                <span class="card-badge rent">For Rent</span>
                <img src="image/2BEDROOM3BED.png" class="card-img-top" alt="Modern Apartments">
                <div class="card-body">
                    <h5 class="card-title">Modern Apartments</h5>
                    <p class="card-text">High-end apartment with stunning city views and premium facilities.</p>
                    <div class="card-specs">
                        <span><i class="fas fa-bed"></i> 2 Beds</span>
                        <span><i class="fas fa-bath"></i> 2 Baths</span>
                        <span><i class="fas fa-ruler-combined"></i> 1,200 sqft</span>
                    </div>
                    <div class="card-price">₦8,000,000</div>
                    <div class="card-actions">
                        <a href="property-details.php" class="btn-primary">View Details</a>
                        <button class="btn-secondary" style="flex: 0.5;"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty Wishlist Message (commented out, can be shown if no items) -->
        <!-- 
        <div class="empty-wishlist">
            <i class="fas fa-heart-broken"></i>
            <h3>Your wishlist is empty</h3>
            <p>Browse properties and add them to your wishlist to keep track of your favorites.</p>
            <a href="properties.php" class="btn-primary"><i class="fas fa-search me-2"></i>Browse Properties</a>
        </div>
        -->
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2026 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
