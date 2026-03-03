<?php
if(isset($_SESSION['error'])){
echo "<p class='alert alert-danger'>".$_SESSION['error']."</p>";
unset($_SESSION['error']);
}
if(isset($_SESSION['feedback'])){
echo "<p class='alert alert-success'>".$_SESSION['feedback']."</p>";
unset($_SESSION['feedback']);}
?>