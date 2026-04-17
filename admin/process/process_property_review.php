<?php
require dirname(__DIR__, 2) . '/config/app.php';
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header('Location: ../views/admin-login.php');
    exit();
}

require_once BASE_PATH . '/admin/classes/Admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/property_management.php');
    exit();
}

$property_id = $_POST['property_id'] ?? 0;
$action = strtolower(trim($_POST['action'] ?? ''));
$rejection_reason = trim($_POST['rejection_reason'] ?? '');

if ($property_id <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    $_SESSION['error'] = 'Invalid moderation request.';
    header('Location: ../views/property_management.php');
    exit();
}

if ($action === 'reject' && $rejection_reason === '') {
    $_SESSION['error'] = 'A rejection reason is required.';
    header('Location: ../property-review.php?id=' . $property_id);
    exit();
}

$admin = new Admin();
$result = $admin->review_property($property_id, $action, $rejection_reason !== '' ? $rejection_reason : null);

if ($result) {
    $_SESSION['feedback'] = $action === 'approve'
        ? 'Property approved and made available.'
        : 'Property rejected with feedback for the landlord.';
} else {
    $_SESSION['error'] = 'Unable to update this property review.';
}

header('Location: ../views/admin-dashboard.php');
exit();
