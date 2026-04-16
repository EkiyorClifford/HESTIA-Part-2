<?php
session_start();
require_once __DIR__ . '/../classes/Wishlist.php';

if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    $_SESSION['error'] = 'Administrators cannot save properties to the wishlist.';
    $redirect = $_SERVER['HTTP_REFERER'] ?? '../views/properties.php';
    header('Location: ' . $redirect);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/register.php");
    exit();
}

$prop_id = $_GET['prop_id'] ?? 0;
$user_id = $_SESSION['user_id'];

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
