<?php
session_start();
require_once "../classes/User.php"; // Adjust this path to where your class is defined

$user = new User();

if (isset($_POST['registerbtn'])) {
    // Sanitize inputs
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validation
    if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("location: ../views/register.php"); 
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("location: ../views/register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("location: ../views/register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters.";
        header("location: ../views/register.php");
        exit();
    }

    // Check if email exists
    if ($user->emailExists($email)) {
        $_SESSION['error'] = "Email is already registered.";
        header("location: ../views/register.php");
        exit();
    }

    // Insert User
    $data = [
    'fname'    => $_POST['fname'],
    'lname'    => $_POST['lname'],
    'email'    => $_POST['email'],
    'pnumber'  => $_POST['phone'],
    'password' => $_POST['password'],
    'role'     => $_POST['role']
    ];
    $result = $user->save($data);

    if ($result) {
        $_SESSION['user_id'] = $result;
        $_SESSION['user_role'] = $role;
        $_SESSION['user_name'] = $fname . ' ' . $lname;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['feedback'] = "Registration successful! Welcome to Hestia.";
        
        // Redirect based on role
        if ($role == 'landlord') {
            header("location: ../views/landlord-profile.php");
        } else {
            header("location: ../tenant/tenant-profile.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("location: ../views/register.php");
        exit();
    }
} else {
    header("location: ../views/register.php");
    exit();
}