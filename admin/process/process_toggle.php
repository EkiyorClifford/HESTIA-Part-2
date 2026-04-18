<?php
require dirname(__DIR__, 2) . '/config/app.php';
session_start();
ini_set('display_errors', '0');
require_once BASE_PATH . '/classes/Property.php';
require_once BASE_PATH . '/classes/User.php';
require_once BASE_PATH . '/classes/Common.php';
$propertyObj = new Property;
$userObj = new User;
header('Content-Type: application/json');


// 1. Check if the user is even logged in
if (!isset($_SESSION['admin_id'])) {
    Common::toggle_json_response(['success' => false, 'message' => 'Unauthorized: Please log in.']);
}

// 2. Check if the user is an Admin (align with process_get-tenant.php role values)
$allowed_roles = ['moderator', 'super_admin', 'superadmin'];
$admin_role = strtolower(trim($_SESSION['admin_role'] ?? ''));
if ($admin_role === '') {
    Common::toggle_json_response(['success' => false, 'message' => 'Access Denied: Admin role not set.']);
}
$role_ok = false;
foreach ($allowed_roles as $r) {
    if ($admin_role === strtolower($r)) {
        $role_ok = true;
        break;
    }
}
if (!$role_ok) {
    Common::toggle_json_response(['success' => false, 'message' => 'Access Denied: Only moderators and super admins can toggle status.']);
}

try {
    if(isset($_POST['id']) && isset($_POST['type'])){
        $id = intval($_POST['id']);
        $type = $_POST['type'];
        
        if($id <= 0){
            Common::toggle_json_response(['success' => false, 'error' => 'Invalid ID']);
        }
        
        if ($type === 'property') {
            $property = $propertyObj->get_property_by_id($id);
            
            if (!$property) {
                Common::toggle_json_response(['success' => false, 'error' => 'Property not found']);
            }
            
            $current = $property['status'];
            $target_status = ($current === "available") ? 'inactive' : 'available';
            
            if($propertyObj->update_property_status($id, $target_status)){
                Common::toggle_json_response([
                    'success' => true, 
                    'new_status' => $target_status
                ]);
            }

            Common::toggle_json_response([
                'success' => false, 
                'error' => 'Database update failed. (Is the ID correct or value already set?)'
            ]);
        } elseif ($type === 'featured') {
            $property = $propertyObj->get_property_by_id($id);

            if (!$property) {
                Common::toggle_json_response(['success' => false, 'error' => 'Property not found']);
            }

            $current = $property['is_featured'] ?? 0;
            $target_featured = ($current == 1) ? 0 : 1;

            if ($target_featured === 1 && (($property['approval_status'] ?? '') !== 'approved' || ($property['status'] ?? '') !== 'available')) {
                Common::toggle_json_response(['success' => false, 'error' => 'Only approved and available properties can be featured']);
            }

            if ($propertyObj->update_featured_status($id, $target_featured)) {
                Common::toggle_json_response([
                    'success' => true,
                    'is_featured' => $target_featured
                ]);
            }

            Common::toggle_json_response([
                'success' => false,
                'error' => 'Featured update failed.'
            ]);
        } elseif ($type === 'user') {
            if ($id == $_SESSION['admin_id']) {
                Common::toggle_json_response(['success' => false, 'error' => 'You cannot deactivate your own account']);
            }
            
            $user = $userObj->get_user_by('id', $id);

            if (!$user) {
                Common::toggle_json_response(['success' => false, 'error' => 'User not found']);
            }

            $current = $user['is_active'] ?? null;
            $target_status = ($current === "yes") ? 'no' : 'yes';
            
            if($userObj->set_status($id, $target_status)){
                Common::toggle_json_response([
                    'success' => true, 
                    'new_status' => $target_status
                ]);
            }

            Common::toggle_json_response([
                'success' => false, 
                'error' => 'Database update failed. (Is the ID correct or value already set?)'
            ]);
        }

        Common::toggle_json_response(['success' => false, 'error' => 'Invalid toggle type']);
    }

    Common::toggle_json_response(['success' => false, 'error' => 'Missing parameters']);
} catch (Throwable $e) {
    Common::toggle_json_response([
        'success' => false,
        'error' => 'Server error while processing toggle.',
        'debug' => $e->getMessage()
    ]);
}
