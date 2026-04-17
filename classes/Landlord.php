<?php
require_once BASE_PATH . '/classes/Db.php';

class Landlord extends Db{

    private $dbconn;

    public function __construct(){
        $this->dbconn= $this->connect();
    }

    public function landlord_status_badge($status)
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

    public function application_badge($status)
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

    public function approval_badge($status)
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
