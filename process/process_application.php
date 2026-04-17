<?php
require dirname(__DIR__) . '/config/app.php';
session_start();
require_once BASE_PATH . '/classes/Applications.php';
require_once BASE_PATH . '/classes/Common.php';
require_once BASE_PATH . '/userguard.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'  || !isset($_POST['application_btn'])){
    Common::redirect_to_property_details(0);
}
//getting and validation
$property_id = $_POST['property_id'];
$user_id = $_SESSION['user_id'];
$message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    $_SESSION['error'] = "Administrator accounts cannot submit rental applications.";
    Common::redirect_to_property_details($property_id);
}

if($property_id <= 0 || empty($message)){
    $_SESSION['error'] = "Please enter a message before applying.";
    Common::redirect_to_property_details($property_id);
}

//length of message
if(strlen($message) > 600){
    $_SESSION['error'] = "Message too long. Please keep it under 600 characters.";
    Common::redirect_to_property_details($property_id);
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
    Common::redirect_to_property_details($property_id);
}else{
    $_SESSION['error'] = "Failed to submit application";
    Common::redirect_to_property_details($property_id);
}
?>
