<?php
session_start();
require_once '../classes/Admin.php';
$admin = new Admin;
$user = $admin->get_user_by_id(1);
// echo "<pre>";
// print_r($user);
// echo "</pre>";
// die();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Details | Hestia</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- IBM Plex -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #f8f9fa;
            padding: 2rem 0;
        }
        :root {
            --orange: #E04E1A;
        }
        .text-orange { color: var(--orange); }
        .bg-orange-light { background-color: #FFE4D6; }
        .border-orange { border-color: var(--orange); }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back button -->
        <div class="mb-4">
            <a href="javascript:history.back()" class="text-decoration-none text-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>

        <!-- Landlord Profile Card -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4 flex-wrap">
                    <!-- Avatar -->
                    <div class="rounded-circle bg-orange-light d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; border: 2px solid var(--orange);">
                        <span class="fs-2 fw-semibold text-orange"><?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?></span>
                    </div>
                    
                    <!-- Info -->
                    <div>
                        <h2 class="fw-semibold mb-2"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>
                        <p class="mb-1 text-secondary"><i class="fas fa-envelope text-orange me-2"></i><?php echo $user['email']; ?></p>
                        <p class="mb-1 text-secondary"><i class="fas fa-phone text-orange me-2"></i><?php echo $user['p_number']; ?></p>
                        <p class="mb-2 text-secondary"><i class="fas fa-map-marker-alt text-orange me-2"></i><?php echo $user['address']; ?></p>
                        
                        <!-- Stats -->
                        <div class="d-flex gap-4 mt-2">
                            <div><span class="fw-bold fs-5 text-orange">12</span> <span class="text-secondary">Total</span></div>
                            <div><span class="fw-bold fs-5 text-orange">8</span> <span class="text-secondary">Active</span></div>
                            <div><span class="fw-bold fs-5 text-orange">4.8</span> <span class="text-secondary">Rating</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Properties Section -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="fas fa-building text-orange fs-4"></i>
            <h4 class="fw-semibold mb-0">Properties Owned (12)</h4>
        </div>

        <!-- Properties Grid -->
        <div class="row g-4">
            <!-- Property Card 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Luxury 3-Bedroom Apartment</h5>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-map-marker-alt text-orange me-2"></i>Ikoyi, Lagos
                        </p>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-ruler-combined text-orange me-2"></i>4.5 Bathrooms
                        </p>
                        <h5 class="text-orange fw-bold mt-3">₦150,000,000</h5>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mt-2">Active</span>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Listed: Jan 15, 2026</small>
                            <a href="#" class="text-orange text-decoration-none fw-medium">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Card 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Modern 2-Bedroom Flat</h5>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-map-marker-alt text-orange me-2"></i>Lekki Phase 1, Lagos
                        </p>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-ruler-combined text-orange me-2"></i>2 Bathrooms
                        </p>
                        <h5 class="text-orange fw-bold mt-3">₦85,000,000</h5>
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 mt-2">Pending</span>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Listed: Feb 3, 2026</small>
                            <a href="#" class="text-orange text-decoration-none fw-medium">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Card 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Cozy Studio Apartment</h5>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-map-marker-alt text-orange me-2"></i>Yaba, Lagos
                        </p>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-ruler-combined text-orange me-2"></i>1 Bathroom
                        </p>
                        <h5 class="text-orange fw-bold mt-3">₦35,000,000</h5>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 mt-2">Inactive</span>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Listed: Dec 10, 2025</small>
                            <a href="#" class="text-orange text-decoration-none fw-medium">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Card 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Duplex with Pool</h5>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-map-marker-alt text-orange me-2"></i>Victoria Island, Lagos
                        </p>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-ruler-combined text-orange me-2"></i>5 Bathrooms
                        </p>
                        <h5 class="text-orange fw-bold mt-3">₦250,000,000</h5>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mt-2">Active</span>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Listed: Mar 1, 2026</small>
                            <a href="#" class="text-orange text-decoration-none fw-medium">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Card 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Commercial Space</h5>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-map-marker-alt text-orange me-2"></i>Ikeja, Lagos
                        </p>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-ruler-combined text-orange me-2"></i>2 Bathrooms
                        </p>
                        <h5 class="text-orange fw-bold mt-3">₦120,000,000</h5>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mt-2">Active</span>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Listed: Feb 20, 2026</small>
                            <a href="#" class="text-orange text-decoration-none fw-medium">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Card 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">4-Bedroom Terrace</h5>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-map-marker-alt text-orange me-2"></i>Surulere, Lagos
                        </p>
                        <p class="card-text text-secondary mb-2">
                            <i class="fas fa-ruler-combined text-orange me-2"></i>3 Bathrooms
                        </p>
                        <h5 class="text-orange fw-bold mt-3">₦95,000,000</h5>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 mt-2">Active</span>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-secondary">Listed: Jan 28, 2026</small>
                            <a href="#" class="text-orange text-decoration-none fw-medium">View <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State (if no properties) -->
        <!-- 
        <div class="text-center py-5">
            <i class="fas fa-building fa-3x text-secondary opacity-50 mb-3"></i>
            <h5 class="text-secondary">No Properties Yet</h5>
            <p class="text-secondary">This landlord hasn't listed any properties.</p>
        </div>
        -->

        <!-- Footer -->
        <div class="text-center text-secondary small mt-5 pt-3 border-top">
            Landlord since March 2024
        </div>
    </div>
</body>
</html>