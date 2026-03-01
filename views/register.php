<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hestia | Welcome Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background:linear-gradient(135deg,#FAF8F5,#F0EDE9);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:20px;
    color:#1A0F1E;
}

.auth-container{
    display:flex;
    width:100%;
    max-width:1100px;
    min-height:700px;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 20px 60px rgba(26,15,30,0.15);
}

/* LEFT SIDE */

.auth-hero{
    flex:1;
    background:linear-gradient(135deg,#1A0F1E,#5A2E55);
    color:white;
    padding:60px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    position:relative;
}

.auth-hero::before{
    content:'';
    position:absolute;
    top:-50%;
    right:-20%;
    width:300px;
    height:300px;
    background:linear-gradient(45deg,#C44536,#E67E51);
    border-radius:50%;
    opacity:0.15;
}

.hestia-logo{
    font-size:2.8rem;
    font-weight:800;
    margin-bottom:20px;
    letter-spacing:1.5px;
    position:relative;
}

.auth-hero h1{
    font-size:2.5rem;
    margin-bottom:20px;
    line-height:1.2;
    position:relative;
}

.auth-hero p{
    font-size:1.1rem;
    opacity:.9;
    line-height:1.6;
    max-width:400px;
    margin-bottom:40px;
    position:relative;
}

.features-list{
    list-style:none;
    margin-top:40px;
    position:relative;
}

.features-list li{
    display:flex;
    align-items:center;
    margin-bottom:20px;
}

.features-list i{
    color:#E67E51;
    margin-right:15px;
    font-size:1.2rem;
}

/* RIGHT SIDE */

.auth-form-section{
    flex:1;
    background:white;
    padding:60px 50px;
    display:flex;
    align-items:center;
}

.form-container{
    width:100%;
    max-width:400px;
    margin:auto;
}

.form-header{
    text-align:center;
    margin-bottom:40px;
}

.form-header h2{
    font-size:2rem;
    margin-bottom:10px;
}

.form-header p{
    color:#5A2E55;
}

/* TABS */

.auth-tabs{
    display:flex;
    background:#F0EDE9;
    border-radius:50px;
    padding:5px;
    margin-bottom:30px;
}

.tab-btn{
    flex:1;
    padding:14px;
    border:none;
    background:transparent;
    border-radius:50px;
    font-weight:600;
    cursor:pointer;
    color:#5A2E55;
}

.tab-btn.active{
    background:white;
    color:#C44536;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

/* FORM */

.auth-form{
    display:none;
}

.auth-form.active{
    display:block;
}

.form-group{
    margin-bottom:25px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    font-size:.95rem;
}

.form-control{
    width:100%;
    padding:16px 18px;
    border:1.5px solid #E0D6DE;
    border-radius:10px;
    font-size:1rem;
}

.form-control:focus{
    outline:none;
    border-color:#C44536;
    box-shadow:0 0 0 3px rgba(196,69,54,.1);
}

.form-options{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    font-size:.95rem;
}

.remember-me{
    display:flex;
    align-items:center;
}

.remember-me input{
    margin-right:8px;
}

.forgot-link{
    color:#C44536;
    text-decoration:none;
    font-weight:600;
}

.forgot-link:hover{
    text-decoration:underline;
}

/* BUTTONS */

.btn-primary{
    width:100%;
    padding:17px;
    background:linear-gradient(90deg,#C44536,#E67E51);
    border:none;
    border-radius:10px;
    color:white;
    font-weight:700;
    font-size:1.05rem;
    cursor:pointer;
    margin-bottom:25px;
}

.btn-primary:hover{
    transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(196,69,54,.25);
}

/* DIVIDER */

.divider{
    display:flex;
    align-items:center;
    margin:30px 0;
    color:#5A2E55;
    font-size:.9rem;
}

.divider::before,
.divider::after{
    content:'';
    flex:1;
    height:1px;
    background:rgba(90,46,85,.2);
}

.divider span{
    padding:0 15px;
}

/* GOOGLE */

.btn-google{
    width:100%;
    padding:16px;
    background:white;
    border:1.5px solid #5A2E55;
    border-radius:10px;
    color:#5A2E55;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:12px;
}

.btn-google:hover{
    background:rgba(90,46,85,.05);
}

/* SWITCH */

.auth-switch{
    text-align:center;
    margin-top:30px;
    color:#5A2E55;
    font-size:.95rem;
}

.auth-switch a{
    color:#C44536;
    font-weight:700;
    text-decoration:none;
}

.auth-switch a:hover{
    text-decoration:underline;
}

.exit-btn{
    width: 20%;
    padding:26px;
    background:white;
    border:1.5px none #5A2E55;
    border-radius:10px;
    color:#5A2E55;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:12px;
}
.exit-btn button{
    background:transparent;
    border:none;
    cursor:pointer;
    font-size:1.2rem;
    color:#5A2E55;
}
.nice{
    display:flex;
    align-items:center;
    justify-content:flex-start;
    margin-bottom:20px;

}
.exit-btn:hover{
    background:rgba(90,46,85,.05);
}
.exit-btn i{
    font-size:1.2rem;
}
.exit-btn:hover i{
    color:#C44536;
}
.exit{
    text-decoration:none;
}

/* media queri */

@media(max-width:900px){
    .auth-container{
        flex-direction:column;
        min-height:auto;
    }

    .auth-hero,
    .auth-form-section{
        padding:40px 30px;
    }
}

@media(max-width:480px){
    body{padding:10px;}

    .auth-container{border-radius:16px;}

    .auth-hero,
    .auth-form-section{
        padding:30px 20px;
    }

    .hestia-logo{font-size:2.2rem;}

    .auth-hero h1{font-size:2rem;}
}
</style>
</head>

<body>
<div class="container">
    

    <div class="auth-container">
        

        <div class="auth-hero">
        <div class="nice">
            <a href="index.php" class="exit">
                <button class="exit-btn"><i class="fa-solid fa-arrow-left-long">
            </i></button>
            </a>
        </div>
            
            <div class="hestia-logo">HESTIA</div>
            <h1>Find Your Place in a Better Rental Market</h1>
            <p>Join thousands of tenants and landlords who are renting smarter—with transparency, control, and no hidden fees.</p>

            <ul class="features-list">
                <li><i class="fas fa-shield-alt"></i> Identity-verified users only</li>
                <li><i class="fas fa-eye"></i> Transparent application status</li>
                <li><i class="fas fa-home"></i> Direct landlord communication</li>
                <li><i class="fas fa-ban"></i> No hidden fees or surprises</li>
            </ul>
        </div>

        <div class="auth-form-section">
            <div class="form-container">

                <div class="form-header">
                    <h2>Welcome Home</h2>
                    <p>Sign in to your Hestia account or create a new one</p>
                </div>

                <div class="auth-tabs">
                    <button class="tab-btn active">Log In</button>
                    <button class="tab-btn">Register</button>
                </div>

                <form class="auth-form active">

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" class="form-control" placeholder="you@example.com" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox">
                            <label>Remember me</label>
                        </div>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button class="btn-primary">Sign In to Hestia</button>

                    <div class="divider">
                        <span>or continue with</span>
                    </div>

                    <button class="btn-google">
                        <i class="fab fa-google"></i>
                        Sign in with Google
                    </button>

                    <div class="auth-switch">
                        New to Hestia?
                        <a href="#">Sign up</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
