<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header('Location: ../views/admin-login.php');
    exit();
}

require_once '../classes/Admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/update-profile.php');
    exit();
}

$admin = new Admin();
$admin_id = $_SESSION['admin_id'];
$current_admin = $admin->get_admin_details($admin_id);

if (!$current_admin) {
    $_SESSION['error'] = 'Admin account not found.';
    header('Location: ../views/admin-login.php');
    exit();
}

$first_name = trim($_POST['fname'] ?? '');
$last_name = trim($_POST['lname'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$errors = [];

if ($first_name === '' || $last_name === '') {
    $errors[] = 'First name and last name are required.';
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
    'first_name' => $first_name,
    'last_name' => $last_name,
];

if ($password !== '') {
    $payload['password'] = $password;
}

if ($admin->update_admin_profile($admin_id, $payload)) {
    $_SESSION['first_name'] = $first_name;
    $_SESSION['admin_name'] = trim($first_name . ' ' . $last_name);
    $_SESSION['feedback'] = 'Admin profile updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update admin profile.';
}

header('Location: ../views/update-profile.php');
exit();
