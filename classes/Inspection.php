<?php
require_once "Db.php";
require_once "PropertyTracker.php";
class Inspection extends Db {
    private $dbconn;
    
    public function __construct() {
        $this->dbconn = $this->connect();
    }
    
    // Fetch inspections for a Tenant
    public function get_tenant_inspections($user_id) {//for dashboard
            $sql = "SELECT i.*, p.title, p.prop_address, l.lga_name 
                    FROM inspections i 
                    JOIN properties p ON i.property_id = p.property_id 
                    JOIN lgas l ON p.lga_id = l.lga_id 
                    WHERE i.user_id = ? ORDER BY i.inspection_date DESC";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //method to ensure a landlord is not inspecting his own property
    public function is_landlord_own_property($prop_id, $landlord_id) {
        $sql = "SELECT * FROM properties WHERE property_id = ? AND user_id = ?";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$prop_id, $landlord_id]);
        $result =  $stmt->rowCount() > 0;
        if($result) {
            return true;
        }
        return false;
    }

        // Get applications for the Tenant Dashboard
    public function get_tenant_applications($user_id) {
        $sql = "SELECT a.*, p.title, p.amount, p.listing_type FROM applications a JOIN properties p ON a.property_id = p.property_id WHERE a.user_id = ? ORDER BY a.created_at DESC";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch inspections for a Landlord (to approve/reject)
    public function get_landlord_inspections($landlord_id, $limit = null) {
        $sql = "SELECT i.*, p.title, u.first_name, u.last_name, u.p_number 
                FROM inspections i 
                JOIN properties p ON i.property_id = p.property_id 
                JOIN users u ON i.user_id = u.id 
                WHERE p.user_id = ? ORDER BY i.created_at DESC";
        $stmt = $this->dbconn->prepare($sql . ($limit !== null ? " LIMIT " . $limit : ""));
        $stmt->execute([$landlord_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create an inspection request
    public function request_inspection($prop_id, $user_id, $date) {
        try{
            // Check if user is landlord of this property
            if ($this->is_landlord_own_property($prop_id, $user_id)) {
                return "error:own_property";
            }
        $property_stmt = $this->dbconn->prepare("SELECT status, approval_status FROM properties WHERE property_id = ?");//instead of $sql
        $property_stmt->execute([$prop_id]);
        $property = $property_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$property || ($property['status'] ?? '') !== 'available' || ($property['approval_status'] ?? '') !== 'approved') {
            return false;
        }
        // Check if a pending request already exists for this property by this user
        $check = "SELECT * FROM inspections WHERE property_id = ? AND user_id = ? AND status = 'pending'";
        $stmt = $this->dbconn->prepare($check);
        $stmt->execute([$prop_id, $user_id]);
        
        if ($stmt->rowCount() > 0) {
            return false; // Already requested
        }

        $sql = "INSERT INTO inspections (property_id, user_id, inspection_date, status) VALUES (?, ?, ?, 'pending')";
        $stmt= $this->dbconn->prepare($sql);
        $data= $stmt->execute([$prop_id, $user_id, $date]);
        if(!$data) {
            error_log("Failed to insert. PDO Error: " . print_r($stmt->errorInfo(), true));//was having issues so i had to use this debug erro message to find out why
        } else {
            $tracker = new PropertyTracker();
            $tracker->increment_inspection_count($prop_id);
        }
        return $data;

        } catch (PDOException $e) {
            error_log("Exception in request_inspection: " . $e->getMessage());
            return false;
        }
    }  

    // Update inspection status (For Cancel, Approve, or Reject)
    public function update_inspection_status($inspection_id, $status) {
        $sql = "UPDATE inspections SET status = ? WHERE inspection_id = ?";
        $stmt= $this->dbconn->prepare($sql);
        $data= $stmt->execute([$status, $inspection_id]);
        return $data;
    }

    public function update_inspection_status_for_landlord($inspection_id, $landlord_id, $status) {
        try {
        $sql = "UPDATE inspections i JOIN properties p ON i.property_id = p.property_id SET i.status = ? WHERE i.inspection_id = ? AND p.user_id = ?";
        $stmt = $this->dbconn->prepare($sql);
        return $stmt->execute([$status, $inspection_id, $landlord_id]);
        } catch (PDOException $e) {
            // error_log("Exception in update_inspection_status_for_landlord: " . $e->getMessage());
            return false;
        }
    }

    // Update inspection date (For Rescheduling)
    public function reschedule_inspection($inspection_id, $new_date) {
        try {
        $sql = "UPDATE inspections SET inspection_date = ?, status = 'pending' WHERE inspection_id = ?";
        $stmt = $this->dbconn->prepare($sql);
        $data = $stmt->execute([$new_date, $inspection_id]);
        return $data;
        } catch (PDOException $e) {
            // error_log("Exception in reschedule_inspection: " . $e->getMessage());
            return false;
        }
    }

    //method that checks if a user has applied to inspect a property
    public function check_inspection($property_id, $user_id){
        try {
        $sql = "SELECT * FROM inspections WHERE property_id = ? AND user_id = ? AND (status = 'pending' OR status = 'approved')";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$property_id, $user_id]);
        $result = $stmt->rowCount() > 0;
        if($result) {
            return true;
        }
        return false;
        } catch (PDOException $e) {
            error_log("Failed to check inspection: " . $e->getMessage());
            return false;
        }
    }
}