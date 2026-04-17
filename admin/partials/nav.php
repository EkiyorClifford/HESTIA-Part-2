<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include BASE_PATH . '/admin/partials/navbar.php';
