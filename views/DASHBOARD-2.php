<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hestia - Tenant Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --hestia-hearth: #C44536;
            --hestia-ember: #8C3E2C;
            --hestia-flame: #E67E51;
            --hestia-plum-dark: #1A0F1E;
            --hestia-plum-mid: #5A2E55;
            --hestia-stone-light: #FAF8F5;
            --hestia-stone-warm: #F0EDE9;
            --hestia-text-dark: #2C2C2C;
            --hestia-text-light: #666;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--hestia-stone-light) 0%, var(--hestia-stone-warm) 100%);
            color: var(--hestia-text-dark);
            min-height: 100vh;
        }

        /* TOP NAV */
        .top-nav {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--hestia-hearth);
            letter-spacing: 1px;
        }

        .role-switcher {
            display: flex;
            background: var(--hestia-stone-warm);
            border-radius: 50px;
            padding: 3px;
        }

        .role-btn {
            padding: 8px 20px;
            border-radius: 50px;
            border: none;
            background: transparent;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-btn.active {
            background: white;
            color: var(--hestia-hearth);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .notification-badge {
            position: relative;
            font-size: 1.3rem;
            color: var(--hestia-plum-mid);
        }

        .badge-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--hestia-flame);
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--hestia-plum-mid), var(--hestia-ember));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* MAIN LAYOUT */
        .dashboard-container {
            display: flex;
            min-height: calc(100vh - 72px);
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: white;
            padding: 2rem 0;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }

        .nav-item {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--hestia-text-light);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--hestia-stone-warm);
            color: var(--hestia-hearth);
            border-left: 4px solid var(--hestia-hearth);
        }

        .nav-item i {
            width: 20px;
        }

        .sidebar-divider {
            height: 1px;
            background: #eee;
            margin: 1.5rem 2rem;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .welcome-section {
            margin-bottom: 2rem;
        }

        .welcome-section h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            color: var(--hestia-plum-dark);
        }

        .stats-bar {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            flex: 1;
            border-top: 4px solid var(--hestia-flame);
        }

        .stat-card:nth-child(2) {
            border-top-color: var(--hestia-plum-mid);
        }

        .stat-card:nth-child(3) {
            border-top-color: var(--hestia-ember);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--hestia-text-light);
            font-size: 0.9rem;
        }

        /* ACTION CARDS */
        .section-title {
            font-size: 1.5rem;
            margin: 2.5rem 0 1.5rem;
            color: var(--hestia-plum-dark);
        }

        .action-cards {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: transform 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-3px);
        }

        .property-image {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            background: linear-gradient(135deg, #ddd, #eee);
        }

        .card-content {
            flex: 1;
        }

        .card-content h3 {
            margin-bottom: 0.5rem;
        }

        .card-details {
            color: var(--hestia-text-light);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .status-confirmed {
            background: rgba(140, 62, 44, 0.1);
            color: var(--hestia-ember);
        }

        .status-action {
            background: rgba(230, 126, 81, 0.1);
            color: var(--hestia-flame);
        }

        .card-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--hestia-hearth), var(--hestia-flame));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(198, 69, 54, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--hestia-plum-mid);
            color: var(--hestia-plum-mid);
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: rgba(90, 46, 85, 0.05);
        }

        /* RIGHT SIDEBAR */
        .right-sidebar {
            width: 300px;
            padding: 2rem;
            flex-shrink: 0;
        }

        .widget {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .widget-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .widget-title a {
            color: var(--hestia-hearth);
            font-size: 0.9rem;
            text-decoration: none;
        }

        .inspection-item, .property-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .inspection-item:last-child, .property-item:last-child {
            border-bottom: none;
        }

        .property-info {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .property-thumb {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            background: #eee;
        }

        .alert-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-left: 0.5rem;
        }

        .dot-red {
            background: var(--hestia-hearth);
        }

        .dot-orange {
            background: var(--hestia-flame);
        }

        .message-preview {
            padding: 1rem;
            background: var(--hestia-stone-warm);
            border-radius: 8px;
            margin-top: 1rem;
        }

        .message-author {
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .message-text {
            color: var(--hestia-text-light);
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
        }

        /* ACTIVITY FEED */
        .activity-feed {
            margin-top: 3rem;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-time {
            font-size: 0.8rem;
            color: var(--hestia-text-light);
            min-width: 80px;
        }

        .activity-text {
            flex: 1;
        }

        .activity-highlight {
            color: var(--hestia-hearth);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- TOP NAVIGATION -->
    <nav class="top-nav">
        <div class="logo">HESTIA</div>
        <div class="role-switcher">
            <button class="role-btn active">Tenant</button>
            <button class="role-btn">Landlord</button>
        </div>
        <div class="user-actions">
            <div class="notification-badge">
                <i class="far fa-bell"></i>
                <span class="badge-count">3</span>
            </div>
            <div class="user-avatar">JP</div>
        </div>
    </nav>

    <!-- MAIN Section -->
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <a href="#" class="nav-item active">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-file-alt"></i> My Applications
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-calendar-check"></i> My Inspections
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-heart"></i> Saved Properties
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-comments"></i> Messages
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-user-shield"></i> Profile & Trust Score
            </a>
            
            <div class="sidebar-divider"></div>
            
            <a href="#" class="nav-item">
                <i class="fas fa-question-circle"></i> Help Center
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </a>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- WELCOME & STATS -->
            <section class="welcome-section">
                <h1>Welcome back, Engr Clifford!</h1>
                <p>Here's your rental activity at a glance.</p>
                
                <div class="stats-bar">
                    <div class="stat-card">
                        <div class="stat-number">3</div>
                        <div class="stat-label">Applications Active</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">1</div>
                        <div class="stat-label">Inspections This Week</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">92/100</div>
                        <div class="stat-label">Trust Score</div>
                    </div>
                </div>
            </section>

            <!-- NEXT STEPS / ACTION CARDS -->
            <h2 class="section-title">Next Steps</h2>
            <div class="action-cards">
                <div class="action-card">
                    <div class="property-image"></div>
                    <div class="card-content">
                        <h3>Your inspection is confirmed!</h3>
                        <p class="card-details">Tomorrow, 3:00 PM • 5 Allen Avenue Ikeja, Lagos</p>
                        <span class="status-badge status-confirmed">CONFIRMED</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn-outline">View Details</button>
                        <button class="btn-primary">Add to Calendar</button>
                    </div>
                </div>
                
                <div class="action-card">
                    <div class="property-image"></div>
                    <div class="card-content">
                        <h3>Application requires your attention</h3>
                        <p class="card-details">Studio in Downtown • Landlord: Oyebola K.</p>
                        <p>Please confirm your ID verification to proceed.</p>
                        <span class="status-badge status-action">ACTION REQUIRED</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn-primary">Complete Now</button>
                    </div>
                </div>
            </div>

            <!-- RECENT ACTIVITY -->
            <h2 class="section-title">Recent Activity</h2>
            <div class="activity-feed">
                <div class="activity-item">
                    <div class="activity-time">10:30 AM</div>
                    <div class="activity-text">
                        Landlord <span class="activity-highlight">Alex R.</span> viewed your application for <span class="activity-highlight">2BHK Green Heights</span>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-time">Yesterday</div>
                    <div class="activity-text">
                        You saved <span class="activity-highlight">Modern Loft South End</span> to your wishlist
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-time">2 days ago</div>
                    <div class="activity-text">
                        Inspection scheduled for <span class="activity-highlight">Studio Downtown</span> (awaiting confirmation)
                    </div>
                </div>
            </div>
        </main>

        <!-- RIGHT SIDEBAR -->
        <aside class="right-sidebar">
            <!-- UPCOMING INSPECTIONS -->
            <div class="widget">
                <h3 class="widget-title">
                    Upcoming Inspections
                    <a href="#">View all</a>
                </h3>
                <div class="inspection-item">
                    <div>
                        <div><strong>Tomorrow, 3:00 PM</strong></div>
                        <div style="font-size: 0.9rem; color: var(--hestia-text-light);">123 Maple St.</div>
                    </div>
                    <span class="status-badge status-confirmed" style="font-size: 0.7rem;">Confirmed</span>
                </div>
                <div class="inspection-item">
                    <div>
                        <div><strong>Fri, 11:00 AM</strong></div>
                        <div style="font-size: 0.9rem; color: var(--hestia-text-light);">Studio Downtown</div>
                    </div>
                    <span class="status-badge" style="background: rgba(90, 46, 85, 0.1); color: var(--hestia-plum-mid); font-size: 0.7rem;">Pending</span>
                </div>
            </div>

            <!-- SAVED PROPERTIES -->
            <div class="widget">
                <h3 class="widget-title">
                    Saved Properties
                    <a href="#">View all</a>
                </h3>
                <div class="property-item">
                    <div class="property-info">
                        <div class="property-thumb"></div>
                        <div>
                            <div>Modern Loft</div>
                            <div style="font-size: 0.8rem; color: var(--hestia-text-light);">$1,850/mo</div>
                        </div>
                    </div>
                    <span class="alert-dot dot-red"></span>
                </div>
                <div class="property-item">
                    <div class="property-info">
                        <div class="property-thumb"></div>
                        <div>
                            <div>Garden Apartment</div>
                            <div style="font-size: 0.8rem; color: var(--hestia-text-light);">$1,600/mo</div>
                        </div>
                    </div>
                    <span class="alert-dot dot-orange"></span>
                </div>
            </div>

            <!-- MESSAGE PREVIEW -->
            <div class="widget">
                <h3 class="widget-title">Quick Message</h3>
                <div class="message-preview">
                    <div class="message-author">Landlord: Sarah M.</div>
                    <div class="message-text">"Are you available Friday at 11 for a quick video tour?"</div>
                    <button class="btn-primary" style="width: 100%;">Reply</button>
                </div>
            </div>
        </aside>
    </div>
</body>
</html>
