<?php
session_start();
require_once '../classes/Inspection.php';
//require userguard
require_once '../userguard.php';


$insp = new Inspection();



$user_id = $_SESSION['user_id'];

// --- ACTION 1: NEW REQUEST (POST) ---
if (isset($_POST['request_btn'])) {
    $prop_id = $_POST['property_id'];
    $date = $_POST['inspection_date'];

    if (empty($date)) {
        $_SESSION['error'] = "Please select a valid date.";
        header("Location: ../views/property-details.php?id=$prop_id");
        exit();
    }

    $result = $insp->request_inspection($prop_id, $user_id, $date);

    if ($result) {
        $_SESSION['feedback'] = "Inspection request sent successfully!";
    } else {
        $_SESSION['error'] = "Failed to send request. Try again.";
    }
    header("Location: ../tenant/tenant-profile.php#inspections");
    exit();
}

// --- ACTION 2: CANCEL (GET) ---
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    $insp_id = $_GET['id'];
    
    // Safety check: Ensure the inspection belongs to this user before deleting/updating
    // (You can add a check inside the method)
    $result = $insp->update_inspection_status($insp_id, 'rejected'); // We use 'rejected' or add 'cancelled' to ENUM

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