<?php
session_start();
require_once '../classes/Applications.php';
require_once '../userguard.php';

function redirect_to_property_details($propertyId) {
    $location = '../views/property-details.php';
    if ($propertyId > 0) {
        $location .= '?property_id=' . $propertyId;
    }
    header('Location: ' . $location);
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'  || !isset($_POST['application_btn'])){
    redirect_to_property_details(0);
}
//getting and validation
$property_id = $_POST['property_id'];
$user_id = $_SESSION['user_id'];
$message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

if($property_id <= 0 || empty($message)){
    $_SESSION['error'] = "Please enter a message before applying.";
    redirect_to_property_details($property_id);
}

//length of message
if(strlen($message) > 600){
    $_SESSION['error'] = "Message too long. Please keep it under 600 characters.";
    redirect_to_property_details($property_id);
}

// insert
$applications = new Applications();
//check if user has applied before

$result = $applications->apply($property_id, $user_id, $message);

if($result){
    //IF RESULT STARTS WITH error: then it's an error Cause that is what i used in the applications class
    if(strpos($result, 'error:') === 0){
        $errorCode = substr($result, 6);
        if ($errorCode === 'property_not_available') {
            $_SESSION['error'] = "This property is no longer available for applications.";
        } elseif ($errorCode === 'already_applied') {
            $_SESSION['error'] = "You have already applied for this property.";
        } else {
            $_SESSION['error'] = "We couldn't submit your application right now.";
        }
    }else{
        $_SESSION['success'] = "Application submitted successfully";
    }
    redirect_to_property_details($property_id);
}else{
    $_SESSION['error'] = "Failed to submit application";
    redirect_to_property_details($property_id);
}
?>
