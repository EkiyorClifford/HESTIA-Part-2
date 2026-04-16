<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true || empty($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

require_once __DIR__ . "/../../classes/Property.php";

$propertyObj = new Property();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $property_id = (int) $_GET['id'];
    $property = $propertyObj->get_property_by_id($property_id);
    
    if ($property) {
        $img_rows = $propertyObj->get_property_images($property_id);
        $image_paths = [];
        $primary_image = '';
        foreach ($img_rows ?: [] as $row) {
            if (!empty($row['image_path'])) {
                $image_paths[] = $row['image_path'];
                if (!empty($row['is_primary']) && (int) $row['is_primary'] === 1) {
                    $primary_image = $row['image_path'];
                }
            }
        }
        if ($primary_image === '' && !empty($image_paths[0])) {
            $primary_image = $image_paths[0];
        }
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'property' => $property,
            'image_paths' => $image_paths,
            'primary_image' => $primary_image,
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Property not found']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid property ID']);
}
?>
