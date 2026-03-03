<?php
session_start();
require_once '../userguard.php';
require_once '../classes/User.php';
$user = new User();
$usr_id = $_SESSION['user_id'];
$usr = $user->get_user($usr_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Profile - HESTIA Property Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: linear-gradient(90deg, #1A0F1E 0%, #5A2E55 100%) !important;
            box-shadow: 0 4px 15px rgba(26, 15, 30, 0.3);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #fff !important;
            font-size: 1.8rem;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            transition: all 0.3s ease;
            margin: 0 5px;
            font-weight: 500;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: #fff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        main {
            flex-grow: 1;
            padding: 60px 0;
        }

        .page-title {
            font-weight: 800;
            color: #1A0F1E;
            margin-bottom: 40px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 30px;
        }

        /* Profile Header */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1A0F1E 0%, #5A2E55 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .profile-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1A0F1E;
        }

        .profile-info p {
            color: #666;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .profile-meta {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .meta-item {
            background: #f0f0f0;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #666;
            font-weight: 500;
        }

        /* Section Title */
        .section-title {
            font-weight: 700;
            color: #1A0F1E;
            margin-bottom: 25px;
            font-size: 1.5rem;
            border-bottom: 2px solid #C44536;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(198, 69, 54, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            border-radius: 4px;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #E67E51 0%, #C44536 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(198, 69, 54, 0.5);
            color: white;
        }

        .btn-secondary {
            background: #f0f0f0;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #333;
            border-radius: 4px;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
            color: #333;
        }

        .btn-group-custom {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Tabs Navigation */
        .nav-tabs {
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 30px;
        }

        .nav-tabs .nav-link {
            color: #666;
            border: none;
            font-weight: 600;
            padding: 12px 20px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-tabs .nav-link:hover {
            color: #C44536;
        }

        .nav-tabs .nav-link.active {
            color: #C44536;
            background: transparent;
            border: none;
            border-bottom: 3px solid #C44536;
        }

        /* Property Card */
        .property-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .property-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e8ecf1 0%, #d4dce8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #bbb;
            position: relative;
            overflow: hidden;
        }

        .property-image::after {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #C44536;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .property-card.saved .property-image::after {
            content: '❤ Saved';
        }

        .property-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .property-name {
            font-weight: 700;
            color: #1A0F1E;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .property-location {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .property-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: #C44536;
            margin-bottom: 15px;
        }

        .property-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #666;
        }

        .property-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .property-actions {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .property-actions button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #C44536;
            color: white;
        }

        .btn-view:hover {
            background: #E67E51;
        }

        .btn-remove {
            background: #f0f0f0;
            color: #666;
        }

        .btn-remove:hover {
            background: #e0e0e0;
        }

        /* Application Item */
        .application-item {
            padding: 20px;
            background: #f9f9f9;
            border-radius: 12px;
            border-left: 4px solid #C44536;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .application-item:hover {
            background: #f0f0f0;
            transform: translateX(5px);
        }

        .application-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .application-title {
            font-weight: 700;
            color: #1A0F1E;
            margin-bottom: 5px;
        }

        .application-status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .application-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .detail-item {
            color: #666;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 3px;
        }

        /* Inspection Item */
        .inspection-item {
            padding: 20px;
            background: white;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .inspection-item:hover {
            border-color: #C44536;
            box-shadow: 0 6px 15px rgba(196, 69, 54, 0.1);
        }

        .inspection-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .inspection-status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-scheduled {
            background: #cfe2ff;
            color: #084298;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .inspection-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 15px 0;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            margin: 15px 0;
        }

        .inspection-detail {
            font-size: 0.9rem;
        }

        .inspection-detail strong {
            color: #1A0F1E;
            display: block;
            margin-bottom: 5px;
        }

        /* Review Item */
        .review-item {
            padding: 20px;
            background: #f9f9f9;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .review-item:hover {
            background: #f0f0f0;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .review-property-name {
            font-weight: 700;
            color: #1A0F1E;
        }

        .review-date {
            color: #999;
            font-size: 0.85rem;
        }

        .review-rating {
            color: #ffc107;
            margin-bottom: 12px;
            font-size: 1.1rem;
        }

        .review-text {
            color: #666;
            line-height: 1.6;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .review-meta {
            display: flex;
            gap: 20px;
            font-size: 0.85rem;
            color: #999;
        }

        /* Verification Section */
        .verification-list {
            list-style: none;
        }

        .verification-item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .verification-item:hover {
            background: #f0f0f0;
        }

        .verification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .verification-icon.verified {
            background: #d4edda;
            color: #155724;
        }

        .verification-icon.pending {
            background: #fff3cd;
            color: #856404;
        }

        .verification-content {
            flex-grow: 1;
        }

        .verification-content h5 {
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .verification-content p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .verification-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-verified {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1A0F1E;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #f9f9f9;
            border-radius: 12px;
        }

        .empty-state-icon {
            font-size: 3rem;
            color: #bbb;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #666;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
            margin-bottom: 20px;
        }
        /* added design */


        .wishlist-btn{
            background: #A8B4A5;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(198, 69, 54, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px;
        }
        .wishlist-btn a{
            text-decoration: none;
            color: #C44536;
        }
        .wishlist-btn:hover {
            background: #C44536;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(198, 69, 54, 0.5);
        }
        .wishlist-btn:hover a{
            color: white;
        }
        .wishlist-btn i{
            color: #C44536;
        }
        .wishlist-btn:hover i{
            color: white;
        }

        /* Footer */
        .footer {
            background: linear-gradient(90deg, #343a40 0%, #495057 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .inspection-details {
                grid-template-columns: 1fr;
            }

            main {
                padding: 40px 0;
            }

            .page-title {
                margin-bottom: 30px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home"></i> HESTIA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="properties.php">Properties</a></li>
                    <li class="nav-item"><a class="nav-link" href="about us.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <h1 class="page-title"><i class="fas fa-user"></i> Tenant Profile</h1>

        <!-- Profile Header Section -->
        <div class="card">
            <div class="card-body">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h3>Stephanie Obianuju</h3>
                        <p><i class="fas fa-envelope"></i> stephanie.obianuju@email.com</p>
                        <p><i class="fas fa-phone"></i> +234 704 5678 333</p>
                        <p><i class="fas fa-map-marker-alt"></i> Ikeja, LG</p>
                        <div class="profile-meta">
                            <span class="meta-item"><i class="fas fa-calendar-alt"></i> Member since 2023</span>
                            <span class="meta-item"><i class="fas fa-star"></i> 4.8/5 Rating</span>
                        </div>
                    </div>
                </div>
                <div class="row btn-group-custom">
                    <button class="col-md-6 btn btn-primary"><i class="fas fa-edit"></i> Edit Profile</button>
                    <button class="col-md-6 btn btn-secondary"><i class="fas fa-camera"></i> Change Photo</button>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <a href="wishlist.php">
            <div class="stat-card">
                <div class="stat-value">5</div>
                <div class="stat-label">Saved Properties</div>
            </div>
            </a>
            <div class="stat-card">
                <div class="stat-value">3</div>
                <div class="stat-label">Active Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">2</div>
                <div class="stat-label">Booked Inspections</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">12</div>
                <div class="stat-label">Reviews Given</div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="saved-tab" data-bs-toggle="tab" data-bs-target="#saved" type="button" role="tab">
                    <i class="fas fa-heart"></i> Saved Properties
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab">
                    <i class="fas fa-file-alt"></i> Applications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inspections-tab" data-bs-toggle="tab" data-bs-target="#inspections" type="button" role="tab">
                    <i class="fas fa-eye"></i> Inspections
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    <i class="fas fa-comments"></i> My Reviews
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verification" type="button" role="tab">
                    <i class="fas fa-check-circle"></i> Verification
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" style="margin-top: 30px;">
            <!-- Saved Properties Tab -->
            <div class="tab-pane fade show active" id="saved" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="property-card saved">
                            <div class="property-image">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="property-info">
                                <div class="property-name">Modern Downtown Apartment</div>
                                <div class="property-location"><i class="fas fa-map-marker-alt"></i> GRA, Port Harcourt</div>
                                <div class="property-price"> ₦2,500,000/yr</div>
                                <div class="property-meta">
                                    <div class="property-meta-item"><i class="fas fa-door-open"></i> 2 Beds</div>
                                    <div class="property-meta-item"><i class="fas fa-bath"></i> 2 Bath</div>
                                    <div class="property-meta-item"><i class="fas fa-ruler"></i> 900 sqft</div>
                                </div>
                                <div class="property-actions">
                                    <button class="btn-view"><i class="fas fa-eye"></i> View</button>
                                    <button class="btn-remove"><i class="fas fa-trash"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="property-card saved">
                            <div class="property-image">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="property-info">
                                <div class="property-name">Luxury Studio with Balcony</div>
                                <div class="property-location"><i class="fas fa-map-marker-alt"></i> Ajah Lekki, Lagos</div>
                                <div class="property-price"> ₦ 4,000,000/year</div>
                                <div class="property-meta">
                                    <div class="property-meta-item"><i class="fas fa-door-open"></i> 1 Bed</div>
                                    <div class="property-meta-item"><i class="fas fa-bath"></i> 1 Bath</div>
                                    <div class="property-meta-item"><i class="fas fa-ruler"></i> 650 sqft</div>
                                </div>
                                <div class="property-actions">
                                    <button class="btn-view"><i class="fas fa-eye"></i> View</button>
                                    <button class="btn-remove"><i class="fas fa-trash"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="property-card saved">
                            <div class="property-image">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="property-info">
                                <div class="property-name">Spacious Family Home</div>
                                <div class="property-location"><i class="fas fa-map-marker-alt"></i> Ajah Lekki, Lagos</div>
                                <div class="property-price"> ₦110,200,000/yr</div>
                                <div class="property-meta">
                                    <div class="property-meta-item"><i class="fas fa-door-open"></i> 3 Beds</div>
                                    <div class="property-meta-item"><i class="fas fa-bath"></i> 2 Bath</div>
                                    <div class="property-meta-item"><i class="fas fa-ruler"></i> 1,200 sqft</div>
                                </div>
                                <div class="property-actions">
                                    <button class="btn-view"><i class="fas fa-eye"></i> View</button>
                                    <button class="btn-remove"><i class="fas fa-trash"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="wishlist-btn">
                            <a href="wishlist.php"><i class="fas fa-heart me-2"></i>Take me to my wishlist</a>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Applications Tab -->
            <div class="tab-pane fade" id="applications" role="tabpanel">
                <div class="application-item">
                    <div class="application-header">
                        <div>
                            <div class="application-title"><i class="fas fa-home"></i> Modern Downtown Apartment</div>
                            <div style="color: #666; font-size: 0.9rem;">Ajah Lekki, Lagos</div>
                        </div>
                        <span class="application-status status-approved"><i class="fas fa-check"></i> Approved</span>
                    </div>
                    <div class="application-details">
                        <div class="detail-item">
                            <span class="detail-label">Yearly Rent</span>
                            ₦2,500,000
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Move-in Date</span>
                            March 1, 2024
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Lease Term</span>
                            12 Months
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Applied</span>
                            Jan 20, 2024
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-file-contract"></i> View Agreement</button>
                </div>

                <div class="application-item">
                    <div class="application-header">
                        <div>
                            <div class="application-title"><i class="fas fa-home"></i> Luxury Studio with Balcony</div>
                            <div style="color: #666; font-size: 0.9rem;">Maryland Lekki, Lagos</div>
                        </div>
                        <span class="application-status status-pending"><i class="fas fa-hourglass-half"></i> Under Review</span>
                    </div>
                    <div class="application-details">
                        <div class="detail-item">
                            <span class="detail-label">Yearly Rent</span>
                            ₦1,800,000
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Move-in Date</span>
                            Flexible
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Lease Term</span>
                            6 Months
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Applied</span>
                            Jan 15, 2024
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i> Edit Application</button>
                </div>
            </div>

            <!-- Inspections Tab -->
            <div class="tab-pane fade" id="inspections" role="tabpanel">
                <div class="inspection-item">
                    <div class="inspection-header">
                        <div>
                            <div class="application-title"><i class="fas fa-home"></i> Modern Downtown Apartment</div>
                            <div style="color: #666; font-size: 0.9rem;">Ajah Lekki, Lagos</div>
                        </div>
                        <span class="inspection-status-badge status-scheduled"><i class="fas fa-calendar-check"></i> Scheduled</span>
                    </div>
                    <div class="inspection-details">
                        <div class="inspection-detail">
                            <strong>Date & Time</strong>
                            January 30, 2024 at 2:00 PM
                        </div>
                        <div class="inspection-detail">
                            <strong>Location</strong>
                            123 Premium Street, Ajah Lekki
                        </div>
                        <div class="inspection-detail">
                            <strong>Agent</strong>
                            Sarah Johnson
                        </div>
                        <div class="inspection-detail">
                            <strong>Contact</strong>
                            +234 802 123 4567
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-map-marked-alt"></i> Get Directions</button>
                </div>

                <div class="inspection-item">
                    <div class="inspection-header">
                        <div>
                            <div class="application-title"><i class="fas fa-home"></i> Luxury Studio with Balcony</div>
                            <div style="color: #666; font-size: 0.9rem;">Maryland Lekki, Lagos</div>
                        </div>
                        <span class="inspection-status-badge status-completed"><i class="fas fa-check-circle"></i> Completed</span>
                    </div>
                    <div class="inspection-details">
                        <div class="inspection-detail">
                            <strong>Date & Time</strong>
                            January 25, 2024 at 10:00 AM
                        </div>
                        <div class="inspection-detail">
                            <strong>Location</strong>
                            456 Garden Avenue, Maryland
                        </div>
                        <div class="inspection-detail">
                            <strong>Agent</strong>
                            Michael Chen
                        </div>
                        <div class="inspection-detail">
                            <strong>Rating</strong>
                            ⭐⭐⭐⭐⭐
                        </div>
                    </div>
                    <button class="btn btn-secondary btn-sm"><i class="fas fa-star"></i> Leave Review</button>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="review-item">
                    <div class="review-header">
                        <div class="review-property-name">Modern Downtown Apartment</div>
                        <div class="review-date">January 28, 2024</div>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="review-text">
                        Excellent property! The apartment was exactly as described, clean, and well-maintained. The landlord was very responsive and helpful throughout the process. Highly recommend!
                    </div>
                    <div class="review-meta">
                        <span><i class="fas fa-user"></i> Tenant Review</span>
                        <span><i class="fas fa-calendar"></i> Jan 28, 2024</span>
                    </div>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="review-property-name">Luxury Studio with Balcony</div>
                        <div class="review-date">January 25, 2024</div>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <div class="review-text">
                        Great location and amenities. The studio was perfect for my needs. The only minor issue was the parking situation, but overall a very positive experience.
                    </div>
                    <div class="review-meta">
                        <span><i class="fas fa-user"></i> Tenant Review</span>
                        <span><i class="fas fa-calendar"></i> Jan 25, 2024</span>
                    </div>
                </div>
            </div>

            <!-- Verification Tab -->
            <div class="tab-pane fade" id="verification" role="tabpanel">
                <ul class="verification-list">
                    <li class="verification-item">
                        <div class="verification-icon verified">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Email Address</h5>
                            <p>Verified on Dec 15, 2023</p>
                        </div>
                        <span class="verification-badge badge-verified"><i class="fas fa-check"></i> Verified</span>
                    </li>
                    <li class="verification-item">
                        <div class="verification-icon verified">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Phone Number</h5>
                            <p>Verified on Dec 20, 2023</p>
                        </div>
                        <span class="verification-badge badge-verified"><i class="fas fa-check"></i> Verified</span>
                    </li>
                    <li class="verification-item">
                        <div class="verification-icon verified">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Government ID</h5>
                            <p>Verified on Jan 5, 2024</p>
                        </div>
                        <span class="verification-badge badge-verified"><i class="fas fa-check"></i> Verified</span>
                    </li>
                    <li class="verification-item">
                        <div class="verification-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Background Check</h5>
                            <p>Pending review - Usually completes within 2-3 business days</p>
                        </div>
                        <span class="verification-badge badge-pending"><i class="fas fa-hourglass-half"></i> Pending</span>
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2024 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
