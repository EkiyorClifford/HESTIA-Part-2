<?php
require dirname(__DIR__) . '/config/app.php';
session_start();

// Clear form data from session
if(isset($_POST['clear_form_data'])){
    unset($_SESSION['form_data']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
