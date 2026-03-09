<?php
session_start();
require_once "userguard.php";
require_once "../classes/Common.php";
require_once "../classes/Property.php";

if(!isset($_POST['add_property'])){
    $_SESSION['error'] = "Please submit the form to add a property";
    header("Location: ../views/add-property.php");
    exit();
}

$property = new Property();

// sanitize inputs
$title = Common::cleandata($_POST['title']);
$description = Common::cleandata($_POST['description']);
$property_type_id = Common::cleandata($_POST['property_type_id']);
$listing_type = Common::cleandata($_POST['listing_type']);
$amount = Common::cleandata($_POST['amount']);
$state_id = Common::cleandata($_POST['state_id']);
$lga_id = Common::cleandata($_POST['lga_id']);
$prop_address = Common::cleandata($_POST['prop_address']);
$bedroom = Common::cleandata($_POST['bedroom']);
$furnished = Common::cleandata($_POST['furnished']);
$status = isset($_POST['status']) ? Common::cleandata($_POST['status']) : 'available';

// get user
$user_id = $_SESSION['user_online'] ?? $_SESSION['user_id'] ?? null;

$errors = [];

if(!$user_id){
    $_SESSION['error'] = "You must be logged in to add a property";
    header("Location: ../views/add-property.php");
    exit();
}

if(empty($title) || empty($description) || empty($property_type_id) || empty($listing_type) || empty($amount) || empty($state_id) || empty($lga_id) || empty($prop_address) || empty($bedroom) || empty($furnished)){
    $errors[] = "All required fields must be filled out";
}

if(!is_numeric($property_type_id)){
    $errors[] = "Invalid property type";
}

if(!in_array($listing_type,['rent','sale'])){
    $errors[] = "Invalid listing type";
}

if(!is_numeric($amount) || $amount <= 0){
    $errors[] = "Invalid price amount";
}

if(!is_numeric($state_id) || !is_numeric($lga_id)){
    $errors[] = "Invalid location selected";
}

if(!is_numeric($bedroom) || $bedroom < 0){
    $errors[] = "Invalid bedroom count";
}

if(!in_array($furnished,['Furnished','Unfurnished'])){
    $errors[] = "Invalid furnished option";
}

if(strlen($title) < 5 || strlen($title) > 200){
    $errors[] = "Title must be between 5 and 200 characters";
}

if(strlen($description) < 20 || strlen($description) > 2000){
    $errors[] = "Description must be between 20 and 2000 characters";
}

if(!empty($errors)){
    $_SESSION['error'] = implode("<br>", $errors);
    $_SESSION['form_data'] = $_POST;
    header("Location: ../views/add-property.php");
    exit();
}


// validate filters
// IMAGE VALIDATION

$max_files = 6;
$max_size = 5 * 1024 * 1024;
$allowed_types = ['image/jpeg','image/png','image/gif'];

if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])){

    $files = $_FILES['images'];
    $file_count = count($files['name']);

    if($file_count > $max_files){
        $_SESSION['error'] = "Maximum $max_files images allowed";
        header("Location: ../views/add-property.php");
        exit();
    }

    for($i=0; $i < $file_count; $i++){

        if($files['error'][$i] !== UPLOAD_ERR_OK){
            $_SESSION['error'] = "Error uploading image";
            header("Location: ../views/add-property.php");
            exit();
        }

        if($files['size'][$i] > $max_size){
            $_SESSION['error'] = "Each image must be less than 5MB";
            header("Location: ../views/add-property.php");
            exit();
        }

        if(!in_array($files['type'][$i], $allowed_types)){
            $_SESSION['error'] = "Invalid image format";
            header("Location: ../views/add-property.php");
            exit();
        }

    }
}


// create property
try{

$property_id = $property->create_property(
    $user_id,
    $property_type_id,
    $bedroom,
    $furnished,
    $lga_id,
    $state_id,
    $listing_type,
    $amount,
    $title,
    $description,
    $prop_address
);

if(!$property_id){
    $_SESSION['error'] = "Failed to add property";
    header("Location: ../views/add-property.php");
    exit();
}


// save images
if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])){
    $property->save_property_images($property_id, $_FILES['images']);
}


// save amenities
if(!empty($_POST['amenities'])){
    $property->save_property_amenities($property_id, $_POST['amenities']);
}


$_SESSION['feedback'] = "Property added successfully!";
header("Location: ../views/landlord-dashboard.php");
exit();

}catch(Exception $e){

$_SESSION['error'] = "An error occurred";
header("Location: ../views/add-property.php");
exit();

}