<?php
session_start();

if(isset($_POST['uploadbtn'])) {
    $fname=$_FILES['dp']['name'];
    $ftmp=$_FILES['dp']['tmp_name'];
    $ferror=$_FILES['dp']['error'];
    $fsize=$_FILES['dp']['size'];

    if($ferror) {
        $_SESSION['message']="Please upload a valid file";
        header("location:../upload.php");
        exit;
    }

    $max_size=2*1024*1024;

    if($fsize>$max_size) {
        $_SESSION['message']="File is too large. Maximum accepted is 2mb";
        header("location:../upload.php");
        exit;
    }

    $allowed=['png','jpg','gif','jpeg'];
    $user_ext=strtolower(pathinfo($fname,PATHINFO_EXTENSION));

    if(!in_array($user_ext,$allowed)) {
        $_SESSION['message']="Only image with extension png, jpg, gif and jpeg allowed";
        header("location:../upload.php");
        exit;
    }

    $unique_filename=uniqid("chopchop_").time().".".$user_ext;
    $res=move_uploaded_file($ftmp,"../upload/".$unique_filename);

    if($res) {
        $_SESSION['message']="Dp uploaded successfully";
        header("location:../upload.php");
        exit;
    }else{
        $_SESSION['message']="Unable to upload file";
        header("location:../upload.php");
        exit;
    }
}
