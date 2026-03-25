<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hestia | find your place</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/register.css">
    <link rel="stylesheet" href="../assets/global.css">
</head>
<body>
    <div class="auth-container">
        <!-- LEFT SIDE - HESTIA STORY -->
        <div class="auth-hero">
            <div class="logo-container">
                <img src="../image/hestia logo.png" alt="Hestia Logo">
            </div>
            <!-- <div class="hestia-logo">HESTIA</div> -->
            <h1>find your place in a better rental market</h1>
            <p>Join thousands who are renting smarter—with transparency, control, and no hidden fees.</p>

            <ul class="features-list">
                <li><i class="fas fa-shield-alt"></i> identity-verified users only</li>
                <li><i class="fas fa-eye"></i> transparent application status</li>
                <li><i class="fas fa-home"></i> direct landlord communication</li>
                <li><i class="fas fa-ban"></i> no hidden fees or surprises</li>
            </ul>
        </div>

        <!-- RIGHT SIDE - AUTHENTICATION -->
        <div class="auth-form-section">
            <div class="form-container">
                <!-- Exit button - refined -->
                <div class="exit-wrapper">
                    <a href="index.php" class="exit-btn">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
                <div class="form-header">
                    <h2>Welcome Home</h2>
                    <p>Sign in or create your account</p>
                </div>
                <!-- Tabs -->
                <div class="auth-tabs">
                    <button class="tab-btn active" id="loginTab">Login</button>
                    <button class="tab-btn" id="registerTab">Register</button>
                </div>

                <!-- Login Form -->
                <form class="auth-form" id="loginForm" action="../process/process_login.php" method="post">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['feedback'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['feedback']; unset($_SESSION['feedback']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Your Email</label>
                        <input type="email" name="login_email" id="login_email" class="form-control" placeholder="you@example.com" required autocapitalize="none" autocorrect="off" spellcheck="false" autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="login_password" id="login_password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <a href="../admin/views/admin-login.php">Admin Login</a> 
                        </label>
                        <a href="#" class="forgot-link">Forgot?</a>
                    </div>

                    <button class="btn-primary" name="loginbtn" id="loginbtn">SIGN IN →</button>

                    <div class="divider">
                        <span>or continue with</span>   
                    </div>

                    <button class="btn-google">
                            <i class="fab fa-google"></i>
                        Google
                    </button>

                    <div class="auth-switch">
                        New to Hestia? <a href="#" id="switchToRegister">CREATE ACCOUNT</a>
                    </div>
                </form>

                <!-- Register Form (hidden initially) -->
                <form class="auth-form" id="registerForm" style="display: none;" action="../process/process_register.php" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6">
                        <label>First Name</label>
                        <input type="text" name="fname" id="fname" class="form-control" placeholder="Mujab" required>
                        </div>
                        <div class="form-group col-md-6">
                        <label>Last Name</label>
                        <input type="text" name="lname" id="lname" class="form-control" placeholder="Daoud" required>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" required autocapitalize="none" autocorrect="off" spellcheck="false" autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="0712345678" required autocapitalize="none" autocorrect="off" spellcheck="false" autocomplete="tel">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="create password" required autocapitalize="none" autocorrect="off" spellcheck="false" autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="confirm password" required autocapitalize="none" autocorrect="off" spellcheck="false" autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label for="">I am a</label>
                        <select name="role" id="role" class="form-control">
                            <option value="">Select Role</option>
                            <option value="tenant">Tenant-Looking for a place</option>
                            <option value="landlord">Landlord-Own a place</option>
                        </select>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" required> 
                            <span>Accept Terms & Privacy</span>
                        </label>
                    </div>

                    <button class="btn-primary" name="registerbtn" id="registerbtn">CREATE ACCOUNT →</button>

                    <div class="divider">
                        <span>or</span>
                    </div>

                    <button class="btn-google">
                        <i class="fab fa-google"></i>
                        Sign Up With Google
                    </button>

                    <div class="auth-switch">
                        Already have an account? <a href="#" id="switchToLogin">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        function showLogin() {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        }

        function showRegister() {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.style.display = 'block';
            loginForm.style.display = 'none';
        }

        loginTab.addEventListener('click', showLogin);
        registerTab.addEventListener('click', showRegister);
        
        if (switchToRegister) {
            switchToRegister.addEventListener('click', (e) => {
                e.preventDefault();
                showRegister();
            });
        }
        
        if (switchToLogin) {
            switchToLogin.addEventListener('click', (e) => {
                e.preventDefault();
                showLogin();
            });
        }
    </script>
</body>
</html>