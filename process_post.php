<?php
session_start();
require_once "../classes/Post.php";

if(isset($_POST['uploadbtn'])) {
    $fname=$_FILES['image']['name'];
    $ftmp=$_FILES['image']['tmp_name'];
    $ferror=$_FILES['image']['error'];
    $fsize=$_FILES['image']['size'];

    if($ferror) {
        $_SESSION['error']="Please upload a valid file";
        header("location:../post.php");
        exit;
    }

    $max_size=2*1024*1024;

    if($fsize>$max_size) {
        $_SESSION['error']="File is too large. Maximum accepted is 2mb";
        header("location:../post.php");
        exit;
    }

    $allowed=['png','jpg','gif','jpeg'];
    $user_ext=strtolower(pathinfo($fname,PATHINFO_EXTENSION));

    if(!in_array($user_ext,$allowed)) {
        $_SESSION['error']="Only image with extension png, jpg, gif and jpeg allowed";
        header("location:../post.php");
        exit;
    }

    $unique_filename=uniqid("shareit_").time().".".$user_ext;
    $res=move_uploaded_file($ftmp,"../post/".$unique_filename);

    if($res) {
        $po=new Post;
        $post_added=$po->save_filename($unique_filename);
        if($post_added) {
        $_SESSION['success']="Dp uploaded successfully";
        header("location:../index.php");
        exit;
        }else{
            $_SESSION['error']="Unable to post your post";
            header("location:../post.php");
        exit;
        }
    }else{
        $_SESSION['error']="Unable to upload file";
        header("location:../post.php");
        exit;
    }
}
