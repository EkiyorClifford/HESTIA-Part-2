<?php
require_once "Db.php";

class Property extends Db {
    private $dbconn;

    public function __construct() {
        $this->dbconn = $this->connect();
    }

    // Create & update
    public function save_property($data, $id = null) {
        try {
            if ($id) {
                $sql = "UPDATE properties SET property_type_id=?, bedroom=?, furnished=?, lga_id=?, state_id=?, 
                        listing_type=?, amount=?, title=?, description=?, prop_address=?, status=? 
                        WHERE property_id=? AND user_id=?";
                $params = [
                    $data['type_id'], $data['bedroom'], $data['furnished'], $data['lga_id'], $data['state_id'],
                    $data['listing_type'], $data['amount'], $data['title'], $data['description'], $data['address'], 
                    $data['status'], $id, $data['user_id']
                ];
            } else {
                $sql = "INSERT INTO properties (user_id, property_type_id, bedroom, furnished, lga_id, state_id, 
                        listing_type, amount, title, description, prop_address) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                $params = [
                    $data['user_id'], $data['type_id'], $data['bedroom'], $data['furnished'], $data['lga_id'], 
                    $data['state_id'], $data['listing_type'], $data['amount'], $data['title'], $data['description'], $data['address']
                ];//i used params here because it's more secure and it helps prevent SQL injection. Also, it's more readable and maintainable.
            }
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute($params);
            return $id ?? $this->dbconn->lastInsertId();//alternative for this? 
        } catch (PDOException $e) {
            return false;
        }
    }

    // Unified status updater
    public function update_status($property_id, $user_id, $status) {
        try {
            $deleted_at = ($status === 'deleted') ? date('Y-m-d H:i:s') : null;
            $sql = "UPDATE properties SET status = ?, deleted_at = ? WHERE property_id = ? AND user_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            return $stmt->execute([$status, $deleted_at, $property_id, $user_id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    //method to get prop types
    public function get_property_types() {
        try {
            $sql = "SELECT * FROM property_types";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Search & filter
    public function get_properties($filters = []) {
        try {
            $sql = "SELECT p.*, pt.type_name, s.state_name, l.lga_name, 
                    (SELECT image_path FROM property_images WHERE property_id = p.id AND is_primary = 1 LIMIT 1) as thumbnail 
                    FROM properties p 
                    JOIN property_types pt ON p.property_type_id = pt.type_id 
                    JOIN states s ON p.state_id = s.state_id 
                    JOIN lgas l ON p.lga_id = l.lga_id 
                    WHERE p.deleted_at IS NULL";

            $params = [];

            if (!empty($filters['user_id'])) { $sql .= " AND p.user_id = ?"; $params[] = $filters['user_id']; }
            if (!empty($filters['status'])) { $sql .= " AND p.status = ?"; $params[] = $filters['status']; }
            if (!empty($filters['type'])) { $sql .= " AND p.property_type_id = ?"; $params[] = $filters['type']; }
            if (!empty($filters['min_price'])) { $sql .= " AND p.amount >= ?"; $params[] = $filters['min_price']; }
            if (!empty($filters['max_price'])) { $sql .= " AND p.amount <= ?"; $params[] = $filters['max_price']; }
            if (!empty($filters['keyword'])) { 
                $sql .= " AND (p.title LIKE ? OR p.prop_address LIKE ?)"; 
                $term = "%".$filters['keyword']."%";
                array_push($params, $term, $term);
            }

            $sql .= " ORDER BY p.created_at DESC";
            if (!empty($filters['limit'])) { $sql .= " LIMIT " . (int)$filters['limit']; }

            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Get property by id
    public function get_property_by_id($id) {
        $sql = "SELECT p.*, pt.type_name, s.state_name, l.lga_name, u.first_name, u.last_name, u.p_number 
                FROM properties p 
                JOIN property_types pt ON p.property_type_id = pt.type_id 
                JOIN states s ON p.state_id = s.state_id 
                JOIN lgas l ON p.lga_id = l.lga_id 
                JOIN users u ON p.user_id = u.id WHERE p.property_id = ?";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Save images
    public function save_images($property_id, $files) {
        $sql = "INSERT INTO property_images (property_id, image_path, is_primary) VALUES (?, ?, ?)";
        $stmt = $this->dbconn->prepare($sql);
        
        foreach ($files['name'] as $k => $name) {
            if ($files['error'][$k] == UPLOAD_ERR_OK) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $filename = "hestia_" . time() . "_" . uniqid() . "." . $ext;
                if (move_uploaded_file($files['tmp_name'][$k], "../upload/properties/" . $filename)) {
                    $stmt->execute([$property_id, $filename, ($k === 0 ? 1 : 0)]);
                }
            }
        }
    }

    // Save amenities
    public function save_amenities($property_id, $amenities) {
        $this->dbconn->prepare("DELETE FROM property_amenities WHERE property_id=?")->execute([$property_id]);
        $stmt = $this->dbconn->prepare("INSERT INTO property_amenities (property_id, amenity_id) VALUES (?, ?)");
        foreach ($amenities as $a_id) { $stmt->execute([$property_id, $a_id]); }
    }

    public function get_amenities_by_property($id) {
    $sql = "SELECT a.amenity_name 
            FROM property_amenities pa 
            JOIN amenities a ON pa.amenity_id = a.id 
            WHERE pa.property_id = ?";
    $stmt = $this->dbconn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get images
    public function get_images($id) {
        $stmt = $this->dbconn->prepare("SELECT * FROM property_images WHERE property_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get stats
    public function get_stats($user_id) {
        $sql = "SELECT status, COUNT(*) as count FROM properties WHERE user_id = ? GROUP BY status";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Returns ['available' => 5, 'taken' => 2]
    }

    //inspections

    // Fetch inspections for a Tenant
    public function get_tenant_inspections($user_id) {
        $sql = "SELECT i.*, p.title, p.prop_address, l.lga_name 
                FROM inspections i 
                JOIN properties p ON i.property_id = p.id 
                JOIN lgas l ON p.lga_id = l.lga_id 
                WHERE i.user_id = ? ORDER BY i.inspection_date DESC";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch inspections for a Landlord (to approve/reject)
    public function get_landlord_inspections($landlord_id) {
        $sql = "SELECT i.*, p.title, u.first_name, u.last_name, u.p_number 
                FROM inspections i 
                JOIN properties p ON i.property_id = p.id 
                JOIN users u ON i.user_id = u.id 
                WHERE p.user_id = ? ORDER BY i.created_at DESC";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$landlord_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create an inspection request
    public function request_inspection($prop_id, $user_id, $date) {
        $sql = "INSERT INTO inspections (property_id, user_id, inspection_date, status) VALUES (?, ?, ?, 'pending')";
        return $this->dbconn->prepare($sql)->execute([$prop_id, $user_id, $date]);
    }    

    // Update inspection status (For Cancel, Approve, or Reject)
    public function update_inspection_status($inspection_id, $status) {
        $sql = "UPDATE inspections SET status = ? WHERE inspection_id = ?";
        return $this->dbconn->prepare($sql)->execute([$status, $inspection_id]);
    }

    // Update inspection date (For Rescheduling)
    public function reschedule_inspection($inspection_id, $new_date) {
        $sql = "UPDATE inspections SET inspection_date = ?, status = 'pending' WHERE inspection_id = ?";
        return $this->dbconn->prepare($sql)->execute([$new_date, $inspection_id]);
    }

    // Request to actually rent the place
    public function apply_for_property($prop_id, $user_id, $message = "") {
        $sql = "INSERT INTO applications (property_id, user_id, message) VALUES (?, ?, ?)";
        return $this->dbconn->prepare($sql)->execute([$prop_id, $user_id, $message]);
    }

    // Get applications for the Tenant Dashboard
    public function get_tenant_applications($user_id) {
        $sql = "SELECT a.*, p.title, p.amount, p.listing_type FROM applications a JOIN properties p ON a.property_id = p.id WHERE a.user_id = ? ORDER BY a.created_at DESC";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add to Property Class

    public function toggle_wishlist($user_id, $property_id) {
        try {
            // Check if it exists
            $check = $this->dbconn->prepare("SELECT wishlist_id FROM wishlist WHERE user_id = ? AND property_id = ?");
            $check->execute([$user_id, $property_id]);
            
            if ($check->rowCount() > 0) {
                // Already exists? Remove it (Unlike)
                $sql = "DELETE FROM wishlist WHERE user_id = ? AND property_id = ?";
                $this->dbconn->prepare($sql)->execute([$user_id, $property_id]);
                return "removed";
            } else {
                // Doesn't exist? Add it (Like)
                $sql = "INSERT INTO wishlist (user_id, property_id) VALUES (?, ?)";
                $this->dbconn->prepare($sql)->execute([$user_id, $property_id]);
                return "added";
            }
        } catch (PDOException $e) { return false; }
    }

        public function get_user_wishlist($user_id) {
            $sql = "SELECT p.*, pt.type_name, s.state_name, l.lga_name,
                    (SELECT image_path FROM property_images WHERE property_id = p.id AND is_primary = 1 LIMIT 1) as thumbnail
                    FROM wishlist w
                    JOIN properties p ON w.property_id = p.id
                    JOIN property_types pt ON p.property_type_id = pt.type_id
                    JOIN states s ON p.state_id = s.state_id
                    JOIN lgas l ON p.lga_id = l.lga_id
                    WHERE w.user_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Helper to check if property is saved (for the heart color)
        public function is_saved($user_id, $property_id) {
            $stmt = $this->dbconn->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND property_id = ?");
            $stmt->execute([$user_id, $property_id]);
            return $stmt->rowCount() > 0;
        }

}