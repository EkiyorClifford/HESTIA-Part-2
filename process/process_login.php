<?php
require dirname(__DIR__) . '/config/app.php';
session_start();
require_once BASE_PATH . '/classes/User.php';

$user = new User();

if (isset($_POST['loginbtn'])) {
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please provide both email and password.";
        header("location: ../views/register.php"); 
        exit();
    }

    $userId = $user->login($email, $password);

    if ($userId) {
        session_regenerate_id(true);
        session_unset();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = $user->getUserRole($userId);
        $_SESSION['user_email'] = $email;
        if($_SESSION['user_role'] == 'landlord') {
            header("location: ../landlord/landlord-profile.php"); 
        } else {
            header("location: ../tenant/tenant-profile.php"); 
        }
        exit();
    } else {
        header("location: ../views/register.php");
        exit();
    }
} else {
    header("location: ../views/register.php");
    exit();
}
