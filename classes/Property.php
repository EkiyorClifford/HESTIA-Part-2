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
                        listing_type=?, amount=?, title=?, `description`=?, prop_address=?, status=?";
                $params = [
                    $data['property_type_id'], $data['bedroom'], $data['furnished'], $data['lga_id'], $data['state_id'],
                    $data['listing_type'], $data['amount'], $data['title'], $data['description'], $data['address'], 
                    $data['status']
                ];

                if (array_key_exists('approval_status', $data)) {
                    $sql .= ", approval_status=?";
                    $params[] = $data['approval_status'];
                }

                if (array_key_exists('rejection_reason', $data)) {
                    $sql .= ", rejection_reason=?";
                    $params[] = $data['rejection_reason'];
                }

                $sql .= " WHERE property_id=? AND user_id=?";
                $params[] = $id;
                $params[] = $data['user_id'];
            } else {
                $sql = "INSERT INTO properties (user_id, property_type_id, bedroom, furnished, lga_id, state_id, 
                        listing_type, amount, title, `description`, prop_address, status, approval_status, rejection_reason) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $params = [
                    $data['user_id'], $data['property_type_id'], $data['bedroom'], $data['furnished'], $data['lga_id'], 
                    $data['state_id'], $data['listing_type'], $data['amount'], $data['title'], $data['description'],
                    $data['prop_address'], $data['status'] ?? 'inactive', $data['approval_status'] ?? 'pending', $data['rejection_reason'] ?? null
                ];
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

    public function update_property_status($id, $new_status) {
        try {
            $sql = "UPDATE properties SET status = ? WHERE property_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $result = $stmt->execute([$new_status, $id]);
            return ($result && $stmt->rowCount() > 0);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update_featured_status($id, $is_featured) {
        try {
            $is_featured = $is_featured ? 1 : 0;
            $sql = "UPDATE properties
                    SET is_featured = ?,
                        featured_at = CASE WHEN ? = 1 THEN NOW() ELSE NULL END
                    WHERE property_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $result = $stmt->execute([$is_featured, $is_featured, $id]);
            return ($result && $stmt->rowCount() > 0);
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
                WHERE p.approval_status = 'approved' AND p.status = 'available'";
        
        $params = [];

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

    public function get_featured_properties($limit = 3) {
        try {
            if (!is_numeric($limit) || $limit < 1) {
                $limit = 3;
            }
            $sql = "SELECT p.*, s.state_name, l.lga_name, pt.type_name,
                    (SELECT image_path FROM property_images WHERE property_id = p.property_id AND is_primary = 1 LIMIT 1) AS thumbnail
                    FROM properties p
                    JOIN states s ON p.state_id = s.state_id
                    JOIN lgas l ON p.lga_id = l.lga_id
                    JOIN property_types pt ON p.property_type_id = pt.type_id
                    WHERE p.is_featured = 1
                    AND p.approval_status = 'approved'
                    AND p.status = 'available'
                    ORDER BY p.featured_at DESC, p.created_at DESC
                    LIMIT " . $limit;
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Get property by id
    public function get_property_by_id($id, $options = []) {
        try {
            $sql = "SELECT p.*, pt.type_name, s.state_name, l.lga_name, u.first_name, u.last_name, u.p_number, u.email 
                    FROM properties p 
                    JOIN property_types pt ON p.property_type_id = pt.type_id 
                    JOIN states s ON p.state_id = s.state_id 
                    JOIN lgas l ON p.lga_id = l.lga_id 
                    JOIN users u ON p.user_id = u.id WHERE p.property_id = ?";
            $params = [$id];

            if (!empty($options['public_only'])) {
                $sql .= " AND p.approval_status = 'approved' AND p.status = 'available'";
            }

            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
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




    public function get_landlord_properties($user_id, $limit = null, $offset = 0) {
        try {
            $limit = $limit !== null ? max(1, (int) $limit) : null;
            $offset = max(0, (int) $offset);

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
                    ORDER BY COALESCE(p.updated_at, p.created_at) DESC, p.created_at DESC";

            if ($limit !== null) {
                $sql .= " LIMIT " . $limit . " OFFSET " . $offset;
            }

            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function get_property_images($property_id) {
        try {
            $sql = "SELECT * FROM property_images WHERE property_id = ? ORDER BY is_primary DESC, image_id ASC";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function get_property_amenities($property_id) {
        try {
            $sql = "SELECT a.amenity_name
                    FROM property_amenities pa
                    JOIN amenities a ON pa.amenity_id = a.amenity_id
                    WHERE pa.property_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function get_landlord_property_count($user_id) {
        try {
            $sql = "SELECT COUNT(*)
                    FROM properties
                    WHERE user_id = ? AND (deleted_at IS NULL AND status <> 'deleted')";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function get_landlord_dashboard_stats($user_id) {
        $defaults = [
            'total_properties' => 0,
            'available' => 0,
            'taken' => 0,
            'inactive' => 0,
            'applications' => 0,
            'inspections' => 0,
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
                if (array_key_exists($status, $stats)) {
                    $stats[$status] = (int) ($row['count'] ?? 0);
                }
            }

            $stats['total_properties'] = $this->get_landlord_property_count($user_id);

            $sql = "SELECT COUNT(*)
                    FROM applications a
                    JOIN properties p ON a.property_id = p.property_id
                    WHERE p.user_id = ? AND (p.deleted_at IS NULL AND p.status <> 'deleted')";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $stats['applications'] = (int) $stmt->fetchColumn();

            $sql = "SELECT COUNT(*)
                    FROM inspections i
                    JOIN properties p ON i.property_id = p.property_id
                    WHERE p.user_id = ? AND (p.deleted_at IS NULL AND p.status <> 'deleted')";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $stats['inspections'] = (int) $stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            return $defaults;
        }
    }

    public function get_landlord_applications($user_id, $limit = null) {
        try {
            $limit = $limit !== null ? max(1, (int) $limit) : null;

            $sql = "SELECT a.application_id AS app_id, a.*, p.title, p.property_id,
                    u.first_name, u.last_name, u.email
                    FROM applications a
                    JOIN properties p ON a.property_id = p.property_id
                    JOIN users u ON a.user_id = u.id
                    WHERE p.user_id = ? AND (p.deleted_at IS NULL AND p.status <> 'deleted')
                    ORDER BY a.created_at DESC";

            if ($limit !== null) {
                $sql .= " LIMIT " . $limit;
            }

            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
