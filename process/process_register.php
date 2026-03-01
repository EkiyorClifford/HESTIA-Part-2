<?php
session_start();
require_once "../classes/Common.php";
require_once "../classes/User.php";
echo "<pre>";
print_r($_POST);
echo "</pre>";

//instantiate an option of dev class so we can have access to the methods within the class
$user = new User();
// check if button was clicked(form was submitted?)
//woukld store error messages in $_SESSION['error]
//would store feedback in $_SESSION['feedback]


if(isset($_POST['registerbtn'])){
    //2. retrieve form data and store in variables //3. Sanitize
    $firstname =common::cleandata(($_POST['fname']));
    $lastname = common::cleandata($_POST['lname']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirmpass = $_POST['confirm_password'];
//4. Validate
    if(empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($role) || empty($password)){
        $_SESSION['error'] = "All fields are required";
        header("location:../views/register.php");
        exit();
    }else if($user -> emailExists($email) >0){
        $_SESSION['error'] = "Email already exists <a href='../views/login.php'>Login Here</a>";
        header("location:../views/register.php");
        exit();
    }
    else if(common::is_email($email) === false){//invalid email format
        $_SESSION['error'] = "Invalid email format";
        header("location:../views/register.php");
        exit();
    } else if($password != $confirmpass){
        $_SESSION['error'] = "Passwords do not match";
        header("location:../views/register.php");
        exit();
    } else{
        //5. Process
        $response = $user -> insert_user($firstname, $lastname, $email, $phone, $password, $role);
        if($response){
            $_SESSION['user_online'] = $response;
            header("location:../views/index.php");
            exit();
        }else{
            $_SESSION['error'] = "Registration failed";
            header("location:../views/register.php");
            exit();
        }
        

    }
} else{
   $_SESSION['error'] = "You need to complete the form";
   header("location:../views/register.php");
   exit();

}



?>

