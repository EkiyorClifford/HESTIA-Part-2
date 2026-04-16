<?php
session_start();
require_once __DIR__ . '/../userguard.php';
require_once __DIR__ . '/../classes/User.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../views/register.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/update-profile.php');
    exit();
}

$user = new User();
$current_user = $user->get_user_by('id', $user_id);

if (!$current_user) {
    $_SESSION['error'] = 'User account not found.';
    header('Location: ../views/register.php');
    exit();
}

$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$errors = [];

if ($fname === '' || $lname === '' || $email === '' || $phone === '') {
    $errors[] = 'All profile fields are required.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}

if ($user->email_exists($email, $user_id)) {
    $errors[] = 'That email address is already in use.';
}

if ($password !== '' || $confirm_password !== '') {
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
}

if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
    header('Location: ../views/update-profile.php');
    exit();
}

$payload = [
    'fname' => $fname,
    'lname' => $lname,
    'email' => $email,
    'pnumber' => $phone,
];

if ($password !== '') {
    $payload['password'] = $password;
}

$result = $user->save($payload, $user_id);

if ($result) {
    $_SESSION['user_name'] = $fname . ' ' . $lname;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_phone'] = $phone;
    $_SESSION['feedback'] = 'Profile updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update profile. Please try again.';
}

header('Location: ../views/update-profile.php');
exit();
