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
        //wanna start a transaction so if one thing fails, the system shuts down since there are many things that are running in the method, wouldn't want 2 to complete and one fail.
        try{
            $this->dbconn->beginTransaction();
              //CHECK IF PROPERTY IS EVEN AVAILABLE
            $property_sql = "SELECT `status`, approval_status FROM properties WHERE property_id = ?";
            $property_stmt = $this->dbconn->prepare($property_sql);
            $property_stmt->execute([$property_id]);
            $property = $property_stmt->fetch(PDO::FETCH_ASSOC);
            if(!$property || $property['status'] !== 'available' || ($property['approval_status'] ?? '') !== 'approved'){
                $this->dbconn->rollBack();
                return "error:property_not_available";
            }
            // insert into the db but check first if there is any aopplication
            $check_sql= "SELECT `status` FROM applications WHERE property_id = ? AND user_id = ? AND (`status` = 'pending' OR `status` = 'approved') LIMIT 1";
            $check_stmt = $this->dbconn->prepare($check_sql);
            $check_stmt->execute([$property_id, $user_id]);
            $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
            if($existing){
                $this->dbconn->rollBack();
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

    public function update_application_status_for_landlord($application_id, $landlord_id, $status) {
        try {
            $sql = "UPDATE applications a
                    JOIN properties p ON a.property_id = p.property_id
                    SET a.status = ?
                    WHERE a.application_id = ? AND p.user_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            return $stmt->execute([$status, $application_id, $landlord_id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>


