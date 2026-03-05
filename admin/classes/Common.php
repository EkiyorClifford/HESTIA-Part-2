<?php

class Common{
public static function cleandata($dirty){
    $clean = trim($dirty);
    $clean = stripslashes($clean);
    $clean = htmlentities($clean);//find out differences btwn htmlentities and htmlspecialchars
    return $clean;
}

public static function is_email($email){
    $rsp = filter_var($email, FILTER_VALIDATE_EMAIL);//returns the variable if the pattern matches and returns false if the pattern does not match
    if($rsp === false){
        return false;
    } else{
        return true;
    }
}




}
//different filter_var
//filter_validate_email

?>