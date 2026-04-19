<?php
require dirname(__DIR__, 2) . '/config/app.php';
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
    <link rel="icon" type="image/png" href="https://i.ibb.co/ccncV96R/Hestia-favicon.png">
    <link rel="shortcut icon" href="https://i.ibb.co/ccncV96R/Hestia-favicon.png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- IBM Plex -->
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Admin Login CSS -->
    <link href="../assets/admin-login.css" rel="stylesheet">
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
                    
                    <!-- Login Form -->
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
