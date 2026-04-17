<?php
require_once BASE_PATH . '/admin/classes/Db.php';

class Admin extends Db {
    private $dbconn;

    public function __construct() {
        $this->dbconn = $this->connect();
    }

    // Authenticate admin login
    public function admin_login($email, $password) {
        try {
            $sql = "SELECT * FROM admins WHERE email = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$email]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($record) {
                $stored_password = $record['adm_password'];
                if (password_verify($password, $stored_password)) {
                    $_SESSION['admin_id'] = $record['admin_id'];
                    $_SESSION['admin_role'] = $record['role'];
                    return $record;
                }
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    // Get admin details by ID
    public function get_admin_details($id){
        try{
            $sql = "SELECT * FROM admins WHERE admin_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$id]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            return $record;
        }catch(PDOException $e){
            // echo $e->getMessage();
            // die();
            return false;
        }
    }

    // Fetch all users with optional role filter
    public function get_users($filter = 'all'){
        try{
            $sql = "SELECT * FROM users";
            $params = [];

            if ($filter !== 'all') {
                $sql .= " WHERE role_ = ?";
                $params[] = $filter;
            }

            $sql .= " ORDER BY created_at DESC";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        }catch(PDOException $e){
            // echo $e->getMessage();
            // die();
            return false;
        }
    }

    // Get property dashboard statistics
    public function get_property_dashboard_totals() {
        $defaults = [
            'total_properties' => 0,
            'active_properties' => 0,
            'inactive_properties' => 0,
        ];

        try {
            $stats = $defaults;
            $stats['total_properties'] = $this->dbconn->query("SELECT COUNT(property_id) FROM properties")->fetchColumn();
            $stats['active_properties'] = $this->dbconn->query("SELECT COUNT(property_id) FROM properties WHERE approval_status = 'approved' AND (status = 'available' OR status = 'taken')")->fetchColumn();
            $stats['inactive_properties'] = $this->dbconn->query("SELECT COUNT(property_id) FROM properties WHERE approval_status <> 'approved' OR status = 'inactive' OR status = 'deleted'")->fetchColumn();
            return $stats;
        } catch (PDOException $e) {
            // echo $e->getMessage(); die();
            return $defaults;
        }
    }

    // Get main dashboard totals
    public function get_dashboard_totals() {
        $defaults = [
            'total_users' => 0,
            'total_properties' => 0,
            'total_inspections' => 0,
            'total_applications' => 0
        ];

        try {
            $stats = $defaults;
            $stats['total_users'] = $this->dbconn->query("SELECT COUNT(id) FROM users")->fetchColumn();
            $stats['total_properties'] = $this->dbconn->query("SELECT COUNT(property_id) FROM properties")->fetchColumn();
            $stats['total_inspections'] = $this->dbconn->query("SELECT COUNT(*) FROM inspections")->fetchColumn();
            $stats['total_applications'] = $this->dbconn->query("SELECT COUNT(*) FROM applications")->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            return $defaults;
        }
    }

    // Search users by keyword with optional role filter
     public function search_users($keyword, $filter = 'all') {
        $sql = "SELECT * FROM users WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR p_number LIKE ?)";
        $term = "%$keyword%";
        $params = [$term, $term, $term, $term];

        if ($filter !== 'all') {
            $sql .= " AND role_ = ?";
            $params[] = $filter;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search properties by keyword with optional status filter
    public function search_properties($keyword, $filter = 'all') {
        $sql = "SELECT p.*, s.state_name, l.lga_name, pt.type_name 
                FROM properties p 
                JOIN states s ON p.state_id = s.state_id 
                JOIN lgas l ON p.lga_id = l.lga_id 
                JOIN property_types pt ON p.property_type_id = pt.type_id 
                WHERE (`description` LIKE ? OR title LIKE ? OR listing_type LIKE ? OR amount LIKE ? OR `status` LIKE ? OR approval_status LIKE ? OR prop_address LIKE ? OR s.state_name LIKE ? OR l.lga_name LIKE ?)";
        $term = "%$keyword%";
        $params = [$term, $term, $term, $term, $term, $term, $term, $term, $term];

        if ($filter !== 'all') {
            $sql .= " AND status = ?";
            $params[] = $filter;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get today's activity statistics
    public function get_todays_activity() {
        try {
            $stats = [];
            
            // 1. New Users Today
            $stmt = $this->dbconn->query("SELECT COUNT(id) FROM users WHERE DATE(created_at) = CURDATE()");
            $stats['new_users'] = $stmt->fetchColumn();

            // 2. New Properties Today
            $stmt = $this->dbconn->query("SELECT COUNT(property_id) FROM properties WHERE DATE(created_at) = CURDATE()");
            $stats['new_props'] = $stmt->fetchColumn();

            // 3. Inspections Scheduled Today
            $stmt = $this->dbconn->query("SELECT COUNT(inspection_id) FROM inspections WHERE DATE(inspection_date) = CURDATE()");
            $stats['inspections'] = $stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            return ['new_users' => 0, 'new_props' => 0, 'inspections' => 0];
        }
    }

    // Get property status statistics
    public function get_property_status_stats() {
        try{
        $sql = "SELECT status, COUNT(*) as count FROM properties GROUP BY status";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $res= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }catch(PDOException $e){
        // echo $e->getMessage;
        // die();
        return [];
    }
    }

    // Get user role statistics
    public function get_user_role_stats() {
        try{
        $sql = "SELECT role_, COUNT(*) as count FROM users GROUP BY role_";
        $stmt = $this->dbconn->prepare($sql);
        $stmt -> execute();
        $data =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
        }catch(PDOException $e){
         // echo $e->getMessage;
        // die();
        return [];
        }
    }

    // Get user active/inactive statistics
    public function get_user_active_stats() {
        try {
            $sql = "SELECT is_active, COUNT(*) as count FROM users GROUP BY is_active";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Get pending properties for admin review
    public function get_pending_properties($limit = 5) {
        try {
            $sql = "SELECT p.*, t.type_name, s.state_name, l.lga_name, u.first_name, u.last_name, u.email
                    FROM properties p
                    JOIN property_types t ON p.property_type_id = t.type_id
                    JOIN states s ON p.state_id = s.state_id
                    JOIN lgas l ON p.lga_id = l.lga_id
                    JOIN users u ON p.user_id = u.id
                    WHERE p.approval_status = 'pending'
                    ORDER BY p.created_at DESC
                    LIMIT :limit";

            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Get top property locations by state
    public function get_top_locations() {
        try{
        $sql = "SELECT s.state_name, COUNT(p.property_id) as count 
                FROM properties p 
                JOIN states s ON p.state_id = s.state_id 
                GROUP BY s.state_name 
                ORDER BY count DESC LIMIT 4";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $res;
        } catch(PDOException $e){
            // echo $e->getMessage();
            // die();
        return [];
        }
    }

    // Get recent approved properties for dashboard table
    public function get_recent_properties($limit = 5) {
        try {
            $sql = "SELECT p.*, t.type_name, s.state_name, l.lga_name 
                FROM properties p 
                JOIN property_types t ON p.property_type_id = t.type_id
                JOIN states s ON p.state_id = s.state_id
                JOIN lgas l ON p.lga_id = l.lga_id
                WHERE p.approval_status = 'approved'
                ORDER BY p.created_at DESC LIMIT :limit";
            
            $stmt = $this->dbconn->prepare($sql);
            
            // Explicitly bind as an Integer
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error if needed: echo $e->getMessage();
            return [];
        }
    }

    // Get recent property applications
    public function get_recent_applications($limit = 5) {
        try {
            $sql = "SELECT a.*, p.property_id, p.title, u.first_name, u.last_name
                    FROM applications a
                    JOIN properties p ON a.property_id = p.property_id
                    JOIN users u ON a.user_id = u.id
                    ORDER BY a.created_at DESC
                    LIMIT :limit";

            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Get all properties with full details
    public function get_all_properties() {
        try {
            $sql = "SELECT p.*, t.type_name, s.state_name, l.lga_name, u.first_name, u.last_name, u.email, u.p_number
                    FROM properties p 
                    JOIN property_types t ON p.property_type_id = t.type_id
                    JOIN states s ON p.state_id = s.state_id
                    JOIN lgas l ON p.lga_id = l.lga_id
                    JOIN users u ON p.user_id = u.id
                    ORDER BY p.created_at DESC";
            
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Update admin profile information
    public function update_admin_profile($id, $data) {
        try {
            $sql = "UPDATE admins SET first_name = ?, last_name = ?";
            $params = [$data['first_name'], $data['last_name']];

            if (!empty($data['password'])) {
                $sql .= ", adm_password = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $sql .= " WHERE admin_id = ?";
            $params[] = $id;

            $stmt = $this->dbconn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Review and approve/reject property
    public function review_property($id, $action, $rejection_reason = null) {
        try {
            if ($action === 'approve') {
                $sql = "UPDATE properties
                        SET approval_status = 'approved',
                            status = 'available',
                            rejection_reason = NULL,
                            updated_at = NOW()
                        WHERE property_id = ?";
                $stmt = $this->dbconn->prepare($sql);
                return $stmt->execute([$id]);
            }

            if ($action === 'reject') {
                $sql = "UPDATE properties
                        SET approval_status = 'rejected',
                            status = CASE WHEN status = 'deleted' THEN 'deleted' ELSE 'inactive' END,
                            rejection_reason = ?,
                            updated_at = NOW()
                        WHERE property_id = ?";
                $stmt = $this->dbconn->prepare($sql);
                return $stmt->execute([$rejection_reason, $id]);
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
