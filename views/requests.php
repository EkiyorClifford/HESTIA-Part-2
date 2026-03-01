<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests - HESTIA Property Rentals</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }
        .navbar-nav .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        .request-item {
            margin-bottom: 25px;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #C44536;
            transition: all 0.3s ease;
        }
        .request-item:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }
        .request-item h5 {
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .request-item p {
            margin-bottom: 12px;
            color: #666;
            line-height: 1.6;
        }
        .request-item strong {
            color: #C44536;
        }
        .btn-primary {
            background: linear-gradient(90deg, #C44536 0%, #E67E51 100%);
            border: none;
            padding: 10px 22px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(198, 69, 54, 0.2);
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #E67E51 0%, #C44536 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(198, 69, 54, 0.3);
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            padding: 10px 22px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
            font-size: 0.9rem;
        }
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
        }
        .footer {
            background: linear-gradient(90deg, #1A0F1E 0%, #2C1B2E 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: auto;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
        }
        main {
            flex-grow: 1;
            padding: 60px 0;
        }
        h1 {
            font-weight: 800;
            color: #333;
            margin-bottom: 40px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <!-- Header with Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">HESTIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="properties.php">Properties</a></li>
                    <li class="nav-item"><a class="nav-link" href="property-details.php">Property Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="landlord-dashboard.php">Landlord Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="add-property.php">Add Property</a></li>
                    <li class="nav-item"><a class="nav-link active" href="requests.php">Requests</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        <h1 class="mb-4">Inspection & Rent Requests</h1>
        <div class="request-item">
            <h5>Inspection Request for Cozy Apartment</h5>
            <p><strong>Requester:</strong> Adaeze Brittany</p>
            <p><strong>Date:</strong> January 25, 2026</p>
            <p><strong>Message:</strong> I'd like to schedule an inspection for this weekend.</p>
            <button class="btn btn-primary me-2">Approve</button>
            <button class="btn btn-secondary">Decline</button>
        </div>
        <div class="request-item">
            <h5>Rent Request for Spacious House</h5>
            <p><strong>Requester:</strong> Chisom Obilo</p>
            <p><strong>Date:</strong> January 10, 2026</p>
            <p><strong>Message:</strong> Interested in renting this property starting next month.</p>
            <button class="btn btn-primary me-2">Approve</button>
            <button class="btn btn-secondary">Decline</button>
        </div>
        <div class="request-item">
            <h5>Inspection Request for Modern Studio</h5>
            <p><strong>Requester:</strong> Ageh Mujab</p>
            <p><strong>Date:</strong> January 8, 2026</p>
            <p><strong>Message:</strong> Can we set up a viewing for tomorrow?</p>
            <button class="btn btn-primary me-2">Approve</button>
            <button class="btn btn-secondary">Decline</button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; 2023 HESTIA Property Rentals. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
