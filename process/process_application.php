<?php
session_start();
require_once "../classes/Applications.php";
//check if user is logged in
require_once "../userguard.php";

if($_SERVER['REQUEST_METHOD'] !== 'POST'  || !isset($_POST['application_btn'])){
    header("Location: ../views/property-details.php");
    exit();
}
//getting and validation
$property_id = intval($_POST['property_id']);
$user_id = $_SESSION['user_id'];
$message = htmlspecialchars(trim($_POST['message']));

if($property_id <= 0 || empty($message)){
    $_SESSION['error'] = "Invalid application data";
    header("Location: ../views/property-details.php?id=" . $property_id);
    exit();
}

//length of message
if(strlen($message) > 600){
    $_SESSION['error'] = "Message too long";
    header("Location: ../views/property-details.php?id=" . $property_id);
    exit();
}

// insert
$applications = new Applications();
$result = $applications->apply($property_id, $user_id, $message);

if($result){
    //IF RESULT STARTS WITH error: then it's an error Cause that is what i used in the applications class
    if(strpos($result, 'error:') === 0){
        $_SESSION['error'] = substr($result, 6);
    }else{
        $_SESSION['success'] = "Application submitted successfully";
    }
    header("Location: ../views/property-details.php?id=" . $property_id);
    exit();
}else{
    $_SESSION['error'] = "Failed to submit application";
    header("Location: ../views/property-details.php?id=" . $property_id);
    exit();
}



