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

public static function toggle_json_response($payload) {
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

public static function landlord_property_redirect($fallback = '../landlord/landlord-profile.php#properties-section') {
    $redirect_to = trim($_POST['redirect_to'] ?? '');

    if ($redirect_to === '../landlord/my-properties.php') {
        return $redirect_to;
    }

    if (preg_match('/^\.\.\/landlord\/landlord-profile\.php(?:\?property_page=\d+)?#properties-section$/', $redirect_to)) {
        return $redirect_to;
    }

    return $fallback;
}

public static function redirect_to_property_details($propertyId) {
    $location = '../views/property-details.php';
    if ($propertyId > 0) {
        $location .= '?property_id=' . $propertyId;
    }
    header('Location: ' . $location);
    exit;
}

public static function tenant_status_badge($status) {
    $status = strtolower(trim($status));

    if ($status === 'approved' || $status === 'accepted') {
        return 'badge-available';
    }

    if ($status === 'pending') {
        return 'badge-rented';
    }

    return 'badge-inactive';
}

}
//different filter_var
//filter_validate_email

?>