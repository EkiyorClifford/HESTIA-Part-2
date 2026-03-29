<?php
session_start();
require_once "../classes/Admin.php";
$admin = new Admin;
header('Content-Type: application/json');

// 1. Check if the user is even logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Please log in.']);
    exit;
}

// 2. Check if the user is an Admin
$allowed_roles = ['moderator', 'super_admin'];
if (!isset($_SESSION['admin_role']) || !in_array($_SESSION['admin_role'], $allowed_roles)) {
    echo json_encode(['success' => false, 'message' => 'Access Denied: Only moderators and superadmins can toggle status.']);
    exit;
}

if(isset($_POST['id']) && isset($_POST['type'])){
    $id = intval($_POST['id']);
    $type = $_POST['type'];
    
    // Validate ID
    if($id <= 0){
        echo json_encode(['success' => false, 'error' => 'Invalid ID']);
        exit;
    }
    
    if ($type === 'property') {
        // Handle property toggle
        $property = $admin->get_property_by_id($id);
        
        if (!$property) {
            echo json_encode(['success' => false, 'error' => 'Property not found']);
            exit;
        }
        
        $current = $property['status'];
        $target_status = ($current === "available") ? 'inactive' : 'available';
        
        if($admin->update_property_status($id, $target_status)){
            echo json_encode([
                'success' => true, 
                'new_status' => $target_status
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'error' => 'Database update failed. (Is the ID correct or value already set?)'
            ]);
        }
        
    } elseif ($type === 'user') {
        // Handle user toggle
        // Validation to stop user from deactivating their own account
        if ($id == $_SESSION['admin_id']) {
            echo json_encode(['success' => false, 'error' => 'You cannot deactivate your own account']);
            exit;
        }
        
        $current = $admin->get_user_active_status($id);

        if ($current === null) {
            echo json_encode(['success' => false, 'error' => 'User not found']);
            exit;
        }

        $target_status = ($current === "yes") ? 'no' : 'yes';
        
        if($admin->update_user_status($id, $target_status)){
            echo json_encode([
                'success' => true, 
                'new_status' => $target_status
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'error' => 'Database update failed. (Is the ID correct or value already set?)'
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid toggle type']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}


exit;