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
                        listing_type=?, amount=?, title=?, `description`=?, prop_address=?, status=? 
                        WHERE property_id=? AND user_id=?";
                $params = [
                    $data['property_type_id'], $data['bedroom'], $data['furnished'], $data['lga_id'], $data['state_id'],
                    $data['listing_type'], $data['amount'], $data['title'], $data['description'], $data['address'], 
                    $data['status'], $id, $data['user_id']
                ];
            } else {
                $sql = "INSERT INTO properties (user_id, property_type_id, bedroom, furnished, lga_id, state_id, 
                        listing_type, amount, title, `description`, prop_address) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                $params = [
                    $data['user_id'], $data['property_type_id'], $data['bedroom'], $data['furnished'], $data['lga_id'], 
                    $data['state_id'], $data['listing_type'], $data['amount'], $data['title'], $data['description'], $data['prop_address']
                ];//i used params here to group it for easier execution
            }
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute($params);
            return $id ?? $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            // echo "Database Error: " . $e->getMessage();
            // exit();
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
        $sql = "SELECT p.*, s.state_name, l.lga_name, pt.type_name,
                (SELECT image_path FROM property_images WHERE property_id = p.property_id AND is_primary = 1 LIMIT 1) AS thumbnail
                FROM properties p
                JOIN states s ON p.state_id = s.state_id
                JOIN lgas l ON p.lga_id = l.lga_id 
                JOIN property_types pt ON p.property_type_id = pt.type_id
                WHERE p.status = ?";
        
        $params = [$filters['status'] ?? 'available'];

        // Property Type Filter
        if (!empty($filters['type'])) {
            $sql .= " AND p.property_type_id = ?";
            $params[] = $filters['type'];
        }
        // Price Filters
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.amount >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.amount <= ?";
            $params[] = $filters['max_price'];
        }
        // Advanced Filters
        if (!empty($filters['bedroom'])) {
            $sql .= " AND p.bedroom >= ?";
            $params[] = $filters['bedroom'];
        }
        if (!empty($filters['furnished'])) {
            $sql .= " AND p.furnished = ?";
            $params[] = $filters['furnished'];
        }
        if (!empty($filters['listing_type'])) {
            $sql .= " AND p.listing_type = ?";
            $params[] = $filters['listing_type'];
        }
        // Keyword Search
        if (!empty($filters['keyword'])) {
            $sql .= " AND (p.title LIKE ? OR p.prop_address LIKE ? OR l.lga_name LIKE ?)";
            $search = "%" . $filters['keyword'] . "%";
            $params[] = $search; $params[] = $search; $params[] = $search;
        }

        $sql .= " ORDER BY p.created_at DESC";
        
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    //get landlord properties
    public function get_landlord_prop($landlord){
        try{
            $sql= "SELECT * FROM properties WHERE user_id = ?";
            $stmt= $this->dbconn->prepare($sql);
            $stmt->execute([$landlord]);
            $res= $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }catch(PDOException $e){
            //echo $e->getMessage(); die();
            return false;
        }
    }

    public function get_landlord_dashboard_stats($user_id) {
        $defaults = [
            'total_properties' => 0,
            'available' => 0,
            'taken' => 0,
            'inactive' => 0,
            'applications' => 0,
        ];

        try {
            $stats = $defaults;

            $sql = "SELECT status, COUNT(*) AS count
                    FROM properties
                    WHERE user_id = ? AND (deleted_at IS NULL AND status <> 'deleted')
                    GROUP BY status";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $status = strtolower(trim($row['status'] ?? ''));
                $count = (int) ($row['count'] ?? 0);

                if (array_key_exists($status, $stats)) {
                    $stats[$status] = $count;
                }
            }

            $stats['total_properties'] = $stats['available'] + $stats['taken'] + $stats['inactive'];

            $sql = "SELECT COUNT(*)
                    FROM applications a
                    JOIN properties p ON a.property_id = p.property_id
                    WHERE p.user_id = ? AND (p.deleted_at IS NULL AND p.status <> 'deleted')";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $stats['applications'] = (int) $stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            return $defaults;
        }
    }

    public function get_landlord_properties($user_id, $limit = null) {
        try {
            $sql = "SELECT p.*, s.state_name, l.lga_name, pt.type_name,
                    (SELECT image_path
                     FROM property_images
                     WHERE property_id = p.property_id AND is_primary = 1
                     LIMIT 1) AS thumbnail
                    FROM properties p
                    JOIN states s ON p.state_id = s.state_id
                    JOIN lgas l ON p.lga_id = l.lga_id
                    JOIN property_types pt ON p.property_type_id = pt.type_id
                    WHERE p.user_id = ? AND (p.deleted_at IS NULL AND p.status <> 'deleted')
                    ORDER BY p.created_at DESC";

            $stmt = $this->dbconn->prepare($sql . ($limit !== null ? " LIMIT " . (int) $limit : ""));
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function get_landlord_applications($user_id, $limit = null) {
        try {
            $sql = "SELECT a.*, p.title, p.property_id, u.first_name, u.last_name, u.email
                    FROM applications a
                    JOIN properties p ON a.property_id = p.property_id
                    JOIN users u ON a.user_id = u.id
                    WHERE p.user_id = ? AND (p.deleted_at IS NULL AND p.status <> 'deleted')
                    ORDER BY a.created_at DESC";

            $stmt = $this->dbconn->prepare($sql . ($limit !== null ? " LIMIT " . (int) $limit : ""));
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
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
            JOIN amenities a ON pa.amenity_id = a.amenity_id 
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



    // Request to actually rent the place
    public function apply_for_property($prop_id, $user_id, $message = "") {
        $sql = "INSERT INTO applications (property_id, user_id, message) VALUES (?, ?, ?)";
        return $this->dbconn->prepare($sql)->execute([$prop_id, $user_id, $message]);
    }



    // Helper to check if property is saved (for the heart color)
    public function is_saved($user_id, $property_id) {
        $stmt = $this->dbconn->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND property_id = ?");
        $stmt->execute([$user_id, $property_id]);
        return $stmt->rowCount() > 0;
    }

}
