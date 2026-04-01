<?php
session_start();
require_once '../classes/Property.php';
require_once '../classes/Common.php'; 

$property = new Property();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: ../views/register.php");
    exit();
}

if (isset($_POST['updatebtn'])) {
    $prop_id = $_POST['property_id'] ?? 0;

    // 1. check if the user owns the property
    $existing = $property->get_property_by_id($prop_id);
    if (!$existing || $existing['user_id'] != $user_id) {
        $_SESSION['error'] = "Unauthorized action.";
        header("Location: ../landlord/landlord-profile.php");
        exit();
    }

    // 2. Sanitize Inputs
    $title = Common::cleandata($_POST['title']);
    $description = Common::cleandata($_POST['description']);
    $type_id =  $_POST['property_type_id'] ?? 0;
    $lga_id =  $existing['lga_id'] ?? 0;
    $state_id =  $existing['state_id'] ?? 0;
    $listing_type = $_POST['listing_type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $bedroom = $_POST['bedroom'] ?? '';
    $furnished = $_POST['furnished'] ?? '';
    $address = Common::cleandata($_POST['prop_address']);
    $status = $_POST['status'] ?? 'available';

    // 3. Validation
    $errors = [];

    if (empty($title) || empty($description) || empty($address) || empty($amount)) {
        $errors[] = "All required fields must be filled.";
    }

    if (!is_numeric($amount) || !is_numeric($bedroom) || $type_id <= 0) {
        $errors[] = "Invalid numeric data provided.";
    }

    if (!in_array($listing_type, ['rent', 'sale'])) {
        $errors[] = "Invalid listing type.";
    }

    if (!in_array($furnished, ['Furnished', 'Unfurnished'], true)) {
        $errors[] = "Invalid furnished option.";
    }

    if (!in_array($status, ['available', 'taken', 'inactive', 'deleted'], true)) {
        $errors[] = "Invalid property status.";
    }

    if (strlen($title) > 255) {
        $errors[] = "Title is too long.";
    }

    // 4. Handle Errors
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: ../landlord/edit-property.php?id=" . $prop_id);
        exit();
    }

    // 5.insertion
    $data = [
        'user_id'      => $user_id,
        'property_type_id' => $type_id,
        'bedroom'      => $bedroom,
        'furnished'    => $furnished,
        'lga_id'       => $lga_id,
        'state_id'     => $state_id,
        'listing_type' => $listing_type,
        'amount'       => $amount,
        'title'        => $title,
        'description'  => $description,
        'address'      => $address,
        'status'       => $status
    ];

    $current_approval_status = strtolower(($existing['approval_status'] ?? ''));
    $was_rejected = $current_approval_status === 'rejected';

    if (in_array($current_approval_status, ['pending', 'rejected'], true)) {
        $data['approval_status'] = 'pending';
        $data['rejection_reason'] = null;
        $data['status'] = 'inactive';
    }

    $result = $property->save_property($data, $prop_id);

    if ($result) {
        $_SESSION['feedback'] = $was_rejected
            ? "Property updated and resubmitted for admin review."
            : "Property updated successfully!";
        header('Location: ../landlord/landlord-profile.php');
    } else {
        $_SESSION['error'] = "Failed to update property. Please try again.";
        header("Location: ../landlord/edit-property.php?id=" . $prop_id);
    }
    exit();

} else {
    header('Location: ../landlord/landlord-profile.php');
    exit();
}
