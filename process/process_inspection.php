<?php
session_start();
require_once '../classes/Property.php';
$prop = new Property();

$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch($action) {
    case 'request':
        $res = $prop->request_inspection($_POST['property_id'], $_SESSION['user_id'], $_POST['inspection_date']);
        break;

    case 'cancel':
        $res = $prop->update_inspection_status($_GET['id'], 'rejected'); // Or add a 'canceled' enum
        break;

    case 'reschedule':
        $res = $prop->reschedule_inspection($_POST['inspection_id'], $_POST['new_date']);
        break;
        
    case 'approve': // For Landlord
        $res = $prop->update_inspection_status($_GET['id'], 'approved');
        break;
}

if($res) {
    $_SESSION['feedback'] = "Inspection updated successfully.";
}
header("Location: " . $_SERVER['HTTP_REFERER']); // Sends them back to the page they were on
exit();