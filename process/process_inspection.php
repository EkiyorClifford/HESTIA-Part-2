<?php
require dirname(__DIR__) . '/config/app.php';
session_start();
require_once BASE_PATH . '/classes/Inspection.php';
require_once BASE_PATH . '/classes/Common.php';
//require userguard
require_once BASE_PATH . '/userguard.php';

$insp = new Inspection();



$user_id = $_SESSION['user_id'];

// --- ACTION 1: NEW REQUEST (POST) ---
if (isset($_POST['request_btn'])) {
    $prop_id = $_POST['property_id'];
    $date = $_POST['inspection_date'];

    if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        $_SESSION['error'] = "Administrator accounts cannot request viewings.";
        Common::redirect_to_property_details($prop_id);
    }

    // Check if user is trying to inspect their own property(thieves)
    if ($insp->is_landlord_own_property($prop_id, $user_id)) {
        $_SESSION['error'] = "You cannot request an inspection for your own property.";
        Common::redirect_to_property_details($prop_id);
    }

    if (empty($date)) {
        $_SESSION['error'] = "Please select a valid date.";
        Common::redirect_to_property_details($prop_id);
    }

    // Check if user has already requested inspection for this property
    if ($insp->check_inspection($prop_id, $user_id) !== false) {
        $_SESSION['error'] = "You have already requested an inspection for this property.";
        Common::redirect_to_property_details($prop_id);
    }

    $result = $insp->request_inspection($prop_id, $user_id, $date);

    if ($result === "error:own_property") {
        $_SESSION['error'] = "You cannot request an inspection for your own property.";
    } elseif ($result) {
        $_SESSION['feedback'] = "Inspection request sent successfully!";
    } else {
        $_SESSION['error'] = "Failed to send request. Try again.";
    }
    Common::redirect_to_property_details($prop_id);
}

// --- ACTION 2: CANCEL (GET) ---
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    $insp_id = $_GET['id'];
    
    // Safety check: Ensure the inspection belongs to this user before deleting/updating
    $result = $insp->update_inspection_status($insp_id, 'rejected'); //

    if ($result) {
        $_SESSION['feedback'] = "Inspection cancelled.";
    } else {
        $_SESSION['error'] = "Could not cancel inspection.";
    }
    header("Location: ../tenant/tenant-profile.php#inspections");
    exit();
}

// --- ACTION 3: RESCHEDULE (POST) ---
if (isset($_POST['action']) && $_POST['action'] == 'reschedule') {
    $insp_id = $_POST['inspection_id'];
    $new_date = $_POST['new_date'];

    $result = $insp
    ->reschedule_inspection($insp_id, $new_date);

    if ($result) {
        $_SESSION['feedback'] = "Inspection date updated to " . date('M d, Y', strtotime($new_date));
    } else {
        $_SESSION['error'] = "Could not update date.";
    }
    header("Location: ../tenant/tenant-profile.php#inspections");
    exit();
}

// If no valid action is found
header("Location: ../tenant/tenant-profile.php");
exit();
?>
