<?php
session_start();
require_once "../userguard.php";
require_once "../classes/Common.php";
require_once "../classes/Property.php";




if(!isset($_POST['add_property'])){
    header("Location: ../landlord/add-property.php");
    exit();
}

$property = new Property();

// 2. Sanitize 
$_SESSION['form_data'] = $_POST; //wanna save form data if validation fails

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
$user_id = $_SESSION['user_id'] ?? null;

// 3. Validation Logic
$errors = [];

if(!$user_id){
    $_SESSION['error'] = "Session expired. Please login again.";
    header("Location: ../views/register.php");
    exit();
}

if(empty($title) || empty($description) || empty($property_type_id) || empty($listing_type) || empty($amount) || empty($lga_id) || empty($prop_address)){
    $errors[] = "Please fill all required fields.";
}

if(!is_numeric($amount) || $amount <= 0) $errors[] = "Invalid price amount.";

// 4. Image Validation Logic
$max_files = 6;
$max_size = 5 * 1024 * 1024;
$allowed_types = ['image/jpeg','image/png','image/webp'];

if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])){
    $files = $_FILES['images'];
    $file_count = count($files['name']);

    if($file_count > $max_files){
        $errors[] = "Maximum $max_files images allowed.";
    }

    for($i=0; $i < $file_count; $i++){
        if($files['error'][$i] !== UPLOAD_ERR_OK){
            $errors[] = "Error uploading image: " . $files['name'][$i];
        }elseif($files['size'][$i] > $max_size){
            $errors[] = "Image " . $files['name'][$i] . " is too large (Max 5MB).";
        }elseif(!in_array($files['type'][$i], $allowed_types)){
            $errors[] = "Invalid format for " . $files['name'][$i];
        }
    }
}

// If errors dy, then send tem back jare
if(!empty($errors)){
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: ../landlord/add-property.php");
    exit();
}

// 5. Database Insertion
$data = [
    'user_id' => $user_id,
    'property_type_id' => $property_type_id,
    'bedroom' => $bedroom,
    'furnished' => $furnished,
    'lga_id' => $lga_id,
    'state_id' => $state_id,
    'listing_type' => $listing_type,
    'amount' => $amount,
    'title' => $title,
    'description' => $description,
    'prop_address' => $prop_address,
    'status' => 'inactive',
    'approval_status' => 'pending',
    'rejection_reason' => null
];
try {
    $property_id = $property->save_property($data);

    if($property_id){
        // Save images
        if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])){
            $property->save_images($property_id, $_FILES['images']);
        }

        // SUCCESS: but i have to clear form data
        unset($_SESSION['form_data']); 
        $_SESSION['feedback'] = "Property submitted successfully and is now awaiting admin review.";
        header("Location: ../landlord/landlord-profile.php");
        exit();
    } else {
        throw new Exception("Database insertion failed");
    }

} catch(Exception $e) {
    $_SESSION['error'] = "Something went wrong: " . $e->getMessage();
    header("Location: ../landlord/add-property.php");
    exit();
}
