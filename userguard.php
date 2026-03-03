<?php
if(!isset($_SESSION['user_online'])){
    $_SESSION['error'] = "You must be logged in to access this page";
    header("Location: ../register.php");
    exit();
}

?>