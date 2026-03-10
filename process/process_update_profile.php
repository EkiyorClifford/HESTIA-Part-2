<?php

session_start();
require_once '../userguard.php';
require_once '../classes/User.php';

// Check if user is logged in. would have to change to updatebtn
if (!isset($_SESSION['user_id'])) {
    header("location: ../views/index.php");
    exit();
}

// Get user data from POST
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$role = $_POST['role'];

// Initialize User class
$user = new User();

// Update user
$result = $user->save([
    'fname' => $fname,
    'lname' => $lname,
    'email' => $email,
    'pnumber' => $phone
], $_SESSION['user_id']);

if ($result) {
    $_SESSION['feedback'] = "Profile updated successfully!";
    //rediect based on role
    if ($role === 'landlord') {
        header("location: ../landlord/landlord-profile.php");
    } else {
        header("location: ../tenant/tenant-profile.php");
    }
    exit();
} else {
    $_SESSION['error'] = "Failed to update profile. Please try again.";
    //rediect based on role
    if ($role === 'landlord') {
        header("location: ../landlord/landlord-profile.php");
    } else {
        header("location: ../tenant/tenant-profile.php");
    }
    exit();
}
