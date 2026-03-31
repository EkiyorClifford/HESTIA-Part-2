<?php
session_start();
// $newpass= "molotov";
// $hash= password_hash($newpass, PASSWORD_DEFAULT);
// echo $hash;
// die();




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Hestia</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- IBM Plex -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #1A0F1E;
            --primary: #5A2E55;
            --primary-light: #8C3E2C;
            --accent: #C44536;
            --accent-light: #E67E51;
            --bg-light: #f5f7fa;
        }
        
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }
        
        /* Back button */
        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            z-index: 10;
        }
        
        .back-link:hover {
            color: var(--accent);
            transform: translateX(-3px);
        }
        
        /* Brand */
        .brand {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            z-index: 10;
        }
        
        .brand span {
            color: var(--accent);
        }
        
        /* Admin badge */
        .admin-badge {
            display: inline-block;
            background: var(--primary-dark);
            color: white;
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.2rem 0.8rem;
            border-radius: 40px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-left: 0.5rem;
            vertical-align: middle;
        }
        
        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(90, 46, 85, 0.08);
            background: white;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h2 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-dark);
        }
        
        .login-header p {
            color: var(--primary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .login-header p i {
            color: var(--accent);
            font-size: 0.8rem;
        }
        
        .admin-shield {
            width: 48px;
            height: 48px;
            background: var(--primary-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .admin-shield i {
            color: white;
            font-size: 1.5rem;
        }
        
        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: var(--primary);
            margin-bottom: 0.3rem;
        }
        
        .form-control {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(196, 69, 54, 0.1);
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-check-input {
            margin: 0;
            border-color: #e9ecef;
        }
        
        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        
        .forgot-link:hover {
            color: var(--accent);
        }
        
        .btn-login {
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            color: white;
            border: none;
            border-radius: 40px;
            padding: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 69, 54, 0.3);
        }
        
        .divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            color: var(--primary);
            font-size: 0.85rem;
            position: relative;
            z-index: 2;
        }
        
        .btn-google {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #e9ecef;
            border-radius: 40px;
            background: white;
            color: var(--primary-dark);
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-google:hover {
            background: var(--bg-light);
            border-color: var(--accent);
            color: var(--accent);
        }
        
        .btn-google i {
            color: var(--accent);
            margin-right: 0.5rem;
        }
        
        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.8rem;
            color: var(--primary);
            opacity: 0.7;
        }
        
        .admin-footer i {
            color: var(--accent);
            margin: 0 0.2rem;
        }
        
        @media (max-width: 768px) {
            .back-link { top: 1rem; left: 1rem; }
            .brand { top: 1rem; right: 1rem; font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="/Hestia-PHP/views/index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Site
    </a>
    
    <!-- Brand -->
    <div class="brand">
        HESTIA<span>.</span>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card p-4">
                    
                    <div class="login-header">
                        <h2>
                            Admin Access
                            <span class="admin-badge">Staff Only</span>
                        </h2>
                        <p>
                            Secure login for Hestia administrators
                            <i class="fas fa-lock"></i>
                        </p>
                    </div>
                    
                    <!-- Login Form - No tabs, just login -->
                    <form method="post" action="../process/process_admin_login.php">
                        <?php if(isset($_SESSION['error'])){ ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error']; ?>
                            </div>
                            <?php unset($_SESSION['error']); // Remove it so it doesn't show again on refresh ?>
                        <?php }; ?>
                        <?php  ?>
                        <!-- Email -->
                        <div class="mb-3">
                            <div class="form-label">ADMIN EMAIL</div>
                            <input type="email" name="email" class="form-control" placeholder="admin@hestia.com">
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <div class="form-label">PASSWORD</div>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>
                        
                        <!-- Remember Me & Forgot -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label small" for="remember">Keep me signed in</label>
                            </div>
                            <a href="#" class="forgot-link small">Forgot password?</a>
                        </div>
                        
                        <!-- Sign In Button -->
                        <button type="submit" class="btn btn-login w-100 mb-4" name="loginbtn">
                            ACCESS DASHBOARD <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        
                        <!-- Divider -->
                        <div class="divider">
                            <span>or continue with</span>
                        </div>
                        
                        <!-- Google Button -->
                        <button type="button" class="btn-google">
                            <i class="fab fa-google"></i> Google Workspace
                        </button>
                    </form>
                    
                    <!-- Admin Footer -->
                    <div class="admin-footer">
                        Authorized personnel only <i class="fas fa-crown"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
