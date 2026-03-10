<?php
session_start();
require_once '../classes/property.php';
require_once '../classes/User.php';
$landlord = new Property();
$userObj = new User();
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $user = $userObj->get_user($user_id);
    $user_name = $user['first_name'] . ' ' . $user['last_name'];
    $user_role = $_SESSION['user_role'];
    $user_email = $_SESSION['user_email'];
    $user_phone = $_SESSION['user_phone'];
    $created_at = $user['created_at'];
}

if($_SESSION['user_role'] != 'landlord') {
    header('Location: ../views/index.php');
    exit();
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Profile - HESTIA Property Rentals</title>
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

        /* Trust Score Section */
        .trust-score-container {
            background: linear-gradient(135deg, #1A0F1E 0%, #5A2E55 100%);
            color: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .trust-score-header {
            margin-bottom: 30px;
        }

        .trust-score-header h2 {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        .trust-score-header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .circular-progress {
            width: 200px;
            height: 200px;
            margin: 0 auto 30px;
            position: relative;
        }

        .circular-progress svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .trust-score-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .trust-score-value h3 {
            font-size: 3rem;
            font-weight: 800;
            margin: 0;
        }

        .trust-score-value p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .score-label {
            display: inline-block;
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 20px;
            font-size: 0.95rem;
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

        /* Verification Section */
        .verification-list {
            list-style: none;
        }

        .verification-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .verification-item:last-child {
            border-bottom: none;
        }

        .verification-item:hover {
            background-color: #f9f9f9;
            padding-left: 25px;
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

        .verification-icon.unverified {
            background: #f8d7da;
            color: #721c24;
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
            padding: 5px 12px;
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

        .badge-unverified {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Rating Section */
        .rating-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .rating-stars {
            font-size: 1.3rem;
            color: #ffc107;
        }

        .rating-count {
            color: #666;
            font-size: 0.9rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 30px;
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

        /* Footer */
        .footer {
            background: linear-gradient(90deg, #343a40 0%, #495057 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .section-title {
            font-weight: 700;
            color: #1A0F1E;
            margin-bottom: 25px;
            font-size: 1.5rem;
            border-bottom: 2px solid #C44536;
            padding-bottom: 10px;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .trust-score-container {
                padding: 30px;
            }

            .circular-progress {
                width: 150px;
                height: 150px;
            }

            .trust-score-value h3 {
                font-size: 2.2rem;
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

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            padding: 20px;
            margin-bottom: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            border-left: 4px solid #C44536;
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            background: #f0f0f0;
            transform: translateX(5px);
        }

        .timeline-item h5 {
            margin-bottom: 5px;
            color: #1A0F1E;
            font-weight: 600;
        }

        .timeline-item p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .timeline-date {
            display: inline-block;
            background: #e8e8e8;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            color: #666;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include '../partials/nav.php'; ?>

    <!-- Main Content -->
    <main class="container">
        <h1 class="page-title"><i class="fas fa-user-circle"></i> Landlord Profile</h1>

        <!-- Trust Score Section -->
        <div class="trust-score-container">
            <div class="trust-score-header">
                <h2>Your Trust Score</h2>
                <p>Build your credibility in the HESTIA community</p>
            </div>

            <div class="circular-progress">
                <svg viewBox="0 0 120 120">
                    <!-- Background circle -->
                    <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="8"></circle>
                    <!-- Progress circle (85%) -->
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#E67E51" stroke-width="8" stroke-dasharray="266.5 314" stroke-linecap="round"></circle>
                </svg>
                <div class="trust-score-value">
                    <h3>85</h3>
                    <p>Score</p>
                </div>
            </div>

            <div class="rating-container" style="justify-content: center;">
                <div class="rating-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="rating-count">4.5/5 (128 reviews)</span>
            </div>

            <span class="score-label"><i class="fas fa-shield-alt"></i> Excellent</span>
        </div>

        <!-- Profile Header Section -->
        <div class="card">
            <div class="card-body">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo $user_name; ?></h3>
                        <p><i class="fas fa-envelope"></i> <?php echo $user_email; ?></p>
                        <p><i class="fas fa-phone"></i> <?php echo $user_phone; ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?php  ?></p>
                        <div class="profile-meta">
                            <span class="meta-item"><i class="fas fa-calendar-alt"></i> Member since <?php echo date('F j, Y', strtotime($created_at)); ?></span>
                            <span class="meta-item"><i class="fas fa-check-circle"></i> Verified User</span>
                        </div>
                    </div>
                </div>
                <div class="row btn-group-custom">
                    <button class="col-md-6 btn btn-primary"><i class="fas fa-edit"></i> Edit Profile</button>
                    <button class="col-md-6 btn btn-secondary"><i class="fas fa-camera"></i> Change Photo</button>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value"><?php  ?></div>
                    <div class="stat-label">Properties Listed</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Successful Rentals</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value">128</div>
                    <div class="stat-label">Total Reviews</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value">98%</div>
                    <div class="stat-label">Positive Rating</div>
                </div>
            </div>
        </div>

        <!-- Verification Section -->
        <div class="card" style="margin-top: 40px;">
            <div class="card-body">
                <h3 class="section-title"><i class="fas fa-certificate"></i> Verification Status</h3>
                <ul class="verification-list">
                    <li class="verification-item">
                        <div class="verification-icon verified">
                            <i class="mx-auto fas fa-check"></i>
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
                    <li class="verification-item">
                        <div class="verification-icon unverified">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="verification-content">
                            <h5>Bank Account</h5>
                            <p>Add your bank account to enable faster payments</p>
                        </div>
                        <span class="verification-badge badge-unverified"><i class="fas fa-exclamation-circle"></i> Not Verified</span>
                    </li>
                </ul>
                <div class="row btn-group-custom">
                    <button class="col-md-6 btn btn-primary"><i class="fas fa-plus"></i> Add Bank Account</button>
                    <button class="col-md-6 btn btn-secondary"><i class="fas fa-history"></i> View History</button>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="card" style="margin-top: 40px;">
            <div class="card-body">
                <h3 class="section-title"><i class="fas fa-history"></i> Recent Activity</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <h5><i class="fas fa-star"></i> Received a 5-star review</h5>
                        <p>Great landlord! Property was clean and well-maintained. Highly recommended!</p>
                        <span class="timeline-date">Jan 28, 2024</span>
                    </div>
                    <div class="timeline-item">
                        <h5><i class="fas fa-home"></i> New property listed</h5>
                        <p>Downtown Luxury Apartment - 2 Beds, 2 Baths</p>
                        <span class="timeline-date">Jan 25, 2024</span>
                    </div>
                    <div class="timeline-item">
                        <h5><i class="fas fa-handshake"></i> Rental completed</h5>
                        <p>Property: Suburban Family Home - Tenant moved out</p>
                        <span class="timeline-date">Jan 20, 2024</span>
                    </div>
                    <div class="timeline-item">
                        <h5><i class="fas fa-certificate"></i> Email verified</h5>
                        <p>Your email address has been successfully verified</p>
                        <span class="timeline-date">Dec 15, 2023</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Score Tips Section -->
        <div class="card" style="margin-top: 40px; margin-bottom: 40px;">
            <div class="card-body">
                <h3 class="section-title"><i class="fas fa-lightbulb"></i> How to Improve Your Trust Score</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div style="padding: 20px; background: #f9f9f9; border-radius: 10px; margin-bottom: 15px;">
                            <h5><i class="fas fa-check-circle" style="color: #28a745;"></i> Complete All Verifications</h5>
                            <p>Add your bank account and complete background checks to boost your score.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 20px; background: #f9f9f9; border-radius: 10px; margin-bottom: 15px;">
                            <h5><i class="fas fa-star" style="color: #ffc107;"></i> Maintain Good Reviews</h5>
                            <p>Respond promptly to tenant inquiries and provide excellent service.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 20px; background: #f9f9f9; border-radius: 10px; margin-bottom: 15px;">
                            <h5><i class="fas fa-clock" style="color: #17a2b8;"></i> Keep Profile Updated</h5>
                            <p>Regularly update your profile with current information and photos.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="padding: 20px; background: #f9f9f9; border-radius: 10px; margin-bottom: 15px;">
                            <h5><i class="fas fa-handshake" style="color: #C44536;"></i> Active Participation</h5>
                            <p>List properties regularly and maintain good communication.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add interactivity to verification badges and other elements
        document.querySelectorAll('.verification-item').forEach(item => {
            item.addEventListener('click', function() {
                console.log('Verification item clicked');
            });
        });

        // Animate stats on page load
        function animateStats() {
            const stats = document.querySelectorAll('.stat-value');
            stats.forEach(stat => {
                const finalValue = parseInt(stat.textContent);
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 30);
                
                const counter = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        stat.textContent = finalValue;
                        clearInterval(counter);
                    } else {
                        stat.textContent = currentValue;
                    }
                }, 20);
            });
        }

        // Call animate stats when page loads
        window.addEventListener('load', animateStats);
    </script>
</body>
</html>
