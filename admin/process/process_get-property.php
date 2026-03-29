<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

require_once "../classes/Admin.php";

$admin = new Admin();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $property_id = (int)$_GET['id'];
    $property = $admin->get_property_by_id($property_id);
    
    if ($property) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'property' => $property]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Property not found']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid property ID']);
}
?>
