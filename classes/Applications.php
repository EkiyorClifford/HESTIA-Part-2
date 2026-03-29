<?php
require_once "Db.php";
require_once "PropertyTracker.php";

class Applications extends Db{

    private $dbconn;

    public function __construct()
    {
        $this->dbconn = $this->connect();
    }

    private function track_application($property_id){
        $tracker = new PropertyTracker();
        $tracker->track_application($property_id);
    }
    //
    public function apply($property_id, $user_id, $message){
        //wanna start a transaction so if one thing fails, the system shuts down
        try{
            $this->dbconn->beginTransaction();
              //CHECK IF PROPERTY IS EVEN AVAILABLE
            $property_sql = "SELECT `status` FROM properties WHERE property_id = ?";
            $property_stmt = $this->dbconn->prepare($property_sql);
            $property_stmt->execute([$property_id]);
            $property = $property_stmt->fetch(PDO::FETCH_ASSOC);
            if(!$property || $property['status'] !== 'available'){
                return "error:property_not_available";
            }
            // insert into the db but check first if there is any aopplication
            $check_sql= "SELECT `status` FROM applications WHERE property_id = ? AND user_id = ? LIMIT 1";
            $check_stmt = $this->dbconn->prepare($check_sql);
            $check_stmt->execute([$property_id, $user_id]);
            $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
            if($existing && $existing['status'] === 'pending'){
                return "error:already_applied";
            }
            //insert
            $sql = "INSERT INTO applications(property_id, user_id, `message`, status) VALUES (?, ?, ?, 'pending')";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id, $user_id, $message]);

            // update
            $this->track_application($property_id);
            $this->dbconn->commit();
            return "success:application_submitted";
        }catch(PDOException $e){
            $this->dbconn->rollBack();
            // echo $e->getMessage(); die();
            return "error:system_failure";
        }
    }
}

?>


