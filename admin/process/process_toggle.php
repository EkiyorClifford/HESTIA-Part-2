<?php
session_start();
ini_set('display_errors', '0');
require_once "../../classes/Property.php";
require_once "../../classes/User.php";
$propertyObj = new Property;
$userObj = new User;
header('Content-Type: application/json');

function toggle_json_response($payload) {
    echo json_encode($payload);
    exit;
}

// 1. Check if the user is even logged in
if (!isset($_SESSION['admin_id'])) {
    toggle_json_response(['success' => false, 'message' => 'Unauthorized: Please log in.']);
}

// 2. Check if the user is an Admin
$allowed_roles = ['moderator', 'super_admin'];
if (!isset($_SESSION['admin_role']) || !in_array($_SESSION['admin_role'], $allowed_roles)) {
    toggle_json_response(['success' => false, 'message' => 'Access Denied: Only moderators and superadmins can toggle status.']);
}

try {
    if(isset($_POST['id']) && isset($_POST['type'])){
        $id = intval($_POST['id']);
        $type = $_POST['type'];
        
        if($id <= 0){
            toggle_json_response(['success' => false, 'error' => 'Invalid ID']);
        }
        
        if ($type === 'property') {
            $property = $propertyObj->get_property_by_id($id);
            
            if (!$property) {
                toggle_json_response(['success' => false, 'error' => 'Property not found']);
            }
            
            $current = $property['status'];
            $target_status = ($current === "available") ? 'inactive' : 'available';
            
            if($propertyObj->update_property_status($id, $target_status)){
                toggle_json_response([
                    'success' => true, 
                    'new_status' => $target_status
                ]);
            }

            toggle_json_response([
                'success' => false, 
                'error' => 'Database update failed. (Is the ID correct or value already set?)'
            ]);
        } elseif ($type === 'featured') {
            $property = $propertyObj->get_property_by_id($id);

            if (!$property) {
                toggle_json_response(['success' => false, 'error' => 'Property not found']);
            }

            $current = $property['is_featured'] ?? 0;
            $target_featured = ($current == 1) ? 0 : 1;

            if ($target_featured === 1 && (($property['approval_status'] ?? '') !== 'approved' || ($property['status'] ?? '') !== 'available')) {
                toggle_json_response(['success' => false, 'error' => 'Only approved and available properties can be featured']);
            }

            if ($propertyObj->update_featured_status($id, $target_featured)) {
                toggle_json_response([
                    'success' => true,
                    'is_featured' => $target_featured
                ]);
            }

            toggle_json_response([
                'success' => false,
                'error' => 'Featured update failed.'
            ]);
        } elseif ($type === 'user') {
            if ($id == $_SESSION['admin_id']) {
                toggle_json_response(['success' => false, 'error' => 'You cannot deactivate your own account']);
            }
            
            $user = $userObj->get_user_by('id', $id);

            if (!$user) {
                toggle_json_response(['success' => false, 'error' => 'User not found']);
            }

            $current = $user['is_active'] ?? null;
            $target_status = ($current === "yes") ? 'no' : 'yes';
            
            if($userObj->set_status($id, $target_status)){
                toggle_json_response([
                    'success' => true, 
                    'new_status' => $target_status
                ]);
            }

            toggle_json_response([
                'success' => false, 
                'error' => 'Database update failed. (Is the ID correct or value already set?)'
            ]);
        }

        toggle_json_response(['success' => false, 'error' => 'Invalid toggle type']);
    }

    toggle_json_response(['success' => false, 'error' => 'Missing parameters']);
} catch (Throwable $e) {
    toggle_json_response([
        'success' => false,
        'error' => 'Server error while processing toggle.',
        'debug' => $e->getMessage()
    ]);
}
