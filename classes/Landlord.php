<?php
require_once "Db.php";

class Landlord extends Db{

    private $dbconn;

    public function __construct(){
        $this->dbconn= $this->connect();
    }

    //method for landlord badges
    function landlord_status_badge($status)
{
    $status = strtolower(trim($status));

    if ($status === 'available') {
        return 'badge-available';
    }

    if ($status === 'taken') {
        return 'badge-rented';
    }

    return 'badge-inactive';
}

function application_badge($status)
{
    $status = strtolower(trim($status));

    if ($status === 'approved' || $status === 'accepted') {
        return 'badge-available';
    }

    if ($status === 'rejected' || $status === 'declined') {
        return 'badge-inactive';
    }

    return 'badge-rented';
}

function approval_badge($status)
{
    $status = strtolower(trim($status));

    if ($status === 'approved') {
        return 'badge-available';
    }

    if ($status === 'rejected') {
        return 'badge-rejected';
    }

    return 'badge-rented';
}

}

?>