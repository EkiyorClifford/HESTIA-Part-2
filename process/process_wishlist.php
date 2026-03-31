<?php
session_start();
require_once '../classes/Wishlist.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/register.php");
    exit();
}

$prop_id = (int) ($_GET['prop_id'] ?? 0);
$user_id = (int) $_SESSION['user_id'];

if ($prop_id <= 0) {
    $_SESSION['error'] = 'Invalid property selected.';
    header("Location: ../tenant/wishlist.php");
    exit();
}

$wishy = new Wishlist();
$result = $wishy->toggle_wishlist($user_id, $prop_id);

if (($result['status'] ?? '') === "added") {
    $_SESSION['feedback'] = "Property saved to wishlist!";
} elseif (($result['status'] ?? '') === "removed") {
    $_SESSION['feedback'] = "Property removed from wishlist.";
} else {
    $_SESSION['error'] = "Unable to update wishlist right now.";
}

// Redirect back to the previous page when available
$redirect = $_SERVER['HTTP_REFERER'] ?? '../tenant/wishlist.php';
header("Location: " . $redirect);
exit();
