<?php
session_start();
require_once '../classes/Property.php';

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['user_role'] ?? null;

if (!$user_id || $user_role !== 'landlord') {
    $_SESSION['error'] = 'Unauthorized action.';
    header('Location: ../views/register.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../landlord/landlord-profile.php');
    exit();
}

$property_id = (int) ($_POST['property_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($property_id <= 0 || $action !== 'delete') {
    $_SESSION['error'] = 'Invalid property action.';
    header('Location: ../landlord/landlord-profile.php');
    exit();
}

$property = new Property();

if ($property->update_status($property_id, $user_id, 'deleted')) {
    $_SESSION['feedback'] = 'Property deleted successfully.';
} else {
    $_SESSION['error'] = 'Unable to delete property.';
}

header('Location: ../landlord/landlord-profile.php#properties-section');
exit();
