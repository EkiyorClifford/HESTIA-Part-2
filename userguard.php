<?php
if(!isset($_SESSION['user_id'])){
    $_SESSION['error'] = "You must be logged in to access this page";
    header("Location: ../views/register.php");
    exit();
}

?>