<?php
session_start();
require_once "../classes/Admin.php";
$admin = new Admin;
header('Content-Type: application/json');

if (
    !isset($_SESSION['is_admin']) ||
    $_SESSION['is_admin'] !== true ||
    !isset($_SESSION['admin_id'])
) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized: Please log in.']);
    exit;
}

// 2. GATEKEEPER: Is the user an Admin and what type of admin?
$allowed_roles = ['moderator', 'super_admin', 'superadmin'];
if (!isset($_SESSION['admin_role']) || !in_array($_SESSION['admin_role'], $allowed_roles, true)) {
    echo json_encode(['success' => false, 'error' => 'Access Denied: Admins only.']);
    exit;
}

// 3. VALIDATE PARAMETER
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid ID.']);
        exit;
    }

    // 4. FETCH DATA
    $user = $admin->get_user_by_id($id);

    // 5. CONTEXT CHECK: Is this user actually a tenant?
    if ($user) {
        if ($user['role_'] !== 'tenant') {
            echo json_encode(['success' => false, 'error' => 'Access Denied: User is not a tenant.']);
            exit;
        }

        // Success! Return the user data
        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Tenant not found.']);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided.']);
}

exit;
