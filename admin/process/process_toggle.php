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
    echo json_encode(['success' => false, 'message' => 'Access Denied: Only moderators and superadmins can toggle user status.']);
    exit;
}

if(isset($_POST['id'])){
    $id = intval($_POST['id']);
    // instead of trusting frontend too much, maybe check the current status from DB. example:
    $current = $admin->get_user_active_status($id);

    if ($current === null) {
        echo json_encode(['success' => false, 'error' => 'User not found']);
        exit;
    }
    // Validate user ID
    if($id <= 0){
        echo json_encode(['success' => false, 'error' => 'Invalid user ID']);
        exit;
    }
    
    // Validation to stop user from deactivating their own account
    if ($id == $_SESSION['admin_id']) {
        echo json_encode(['success' => false, 'error' => 'You cannot deactivate your own account']);
        exit;
    }
    // get current status from db
    $current = $admin->get_user_active_status($id);

    if ($current === null) {
        echo json_encode(['success' => false, 'error' => 'User not found']);
        exit;
    }

    // 1. Determine exactly what we want the NEW status to be
    $target_status = ($current === "yes") ? 'no' : 'yes';
    
    // 2. Tell the DB: "Set this user to [target_status]"
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
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}


exit;