<?php
require dirname(__DIR__) . '/config/app.php';
session_start();
require_once BASE_PATH . '/classes/Property.php';
require_once BASE_PATH . '/classes/Inspection.php';
require_once BASE_PATH . '/classes/Applications.php';
require_once BASE_PATH . '/classes/Common.php';

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

$property_id = $_POST['property_id'] ?? 0;
$action = $_POST['action'] ?? '';

$property = new Property();

if ($action === 'delete') {
    if ($property_id <= 0) {
        $_SESSION['error'] = 'Invalid property action.';
        header('Location: ../landlord/landlord-profile.php');
        exit();
    }

    if ($property->update_status($property_id, $user_id, 'deleted')) {
        $_SESSION['feedback'] = 'Property deleted successfully.';
    } else {
        $_SESSION['error'] = 'Unable to delete property.';
    }

    header('Location: ' . Common::landlord_property_redirect());
    exit();
}

if ($action === 'application_status') {
    $application_id = $_POST['application_id'] ?? 0;
    $status = strtolower(trim($_POST['status'] ?? ''));
    $applications = new Applications();

    if ($application_id <= 0 || !in_array($status, ['approved', 'rejected'], true)) {
        $_SESSION['error'] = 'Invalid application action.';
    } elseif ($applications->update_application_status_for_landlord($application_id, $user_id, $status)) {
        $_SESSION['feedback'] = 'Application ' . $status . ' successfully.';
    } else {
        $_SESSION['error'] = 'Unable to update application status.';
    }

    header('Location: ../landlord/landlord-profile.php#applications-section');
    exit();
}

if ($action === 'inspection_status') {
    $inspection_id = $_POST['inspection_id'] ?? 0;
    $status = strtolower(trim($_POST['status'] ?? ''));
    $inspection = new Inspection();

    if ($inspection_id <= 0 || !in_array($status, ['approved', 'rejected'], true)) {
        $_SESSION['error'] = 'Invalid inspection action.';
    } elseif ($inspection->update_inspection_status_for_landlord($inspection_id, $user_id, $status)) {
        $_SESSION['feedback'] = 'Inspection ' . $status . ' successfully.';
    } else {
        $_SESSION['error'] = 'Unable to update inspection status.';
    }

    header('Location: ../landlord/landlord-profile.php#inspections-section');
    exit();
}

$_SESSION['error'] = 'Unknown action.';
header('Location: ../landlord/landlord-profile.php');
exit();
