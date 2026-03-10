<?php
session_start();
require_once '../classes/Property.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/register.php");
    exit();
}

$prop_id = $_GET['prop_id'];
$user_id = $_SESSION['user_id'];

$prop = new Property();
$result = $prop->toggle_wishlist($user_id, $prop_id);

if ($result == "added") {
    $_SESSION['feedback'] = "Property saved to wishlist!";
} else {
    $_SESSION['feedback'] = "Property removed from wishlist.";
}

// Redirect back to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();