<?php
require dirname(__DIR__, 2) . '/config/app.php';
// Process admin login
session_start();
require_once BASE_PATH . '/admin/classes/Admin.php';
$admin = new Admin;
if(isset($_POST['loginbtn'])){
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if(empty($email) || empty($password)){
        $_SESSION['error'] = "Please provide both email and password.";
        header("location: ../views/admin-login.php");
        exit();
    }
    
   $login = $admin->admin_login($email, $password);
   if($login){
    session_regenerate_id(true);
    session_unset();
    $_SESSION['admin_id'] = $login['admin_id'];
    $_SESSION['admin_role'] = $login['role'];
    $_SESSION['admin_name'] = trim(($login['first_name'] ?? '') . ' ' . ($login['last_name'] ?? ''));
    $_SESSION['first_name']= $login['first_name'];
    $_SESSION['is_admin']=true;
    header("location: ../views/admin-dashboard.php");
    exit();
   }else{
    $_SESSION['error'] = "Wrong email or password.";
    header("location: ../views/admin-login.php");
    exit();
   }
}
