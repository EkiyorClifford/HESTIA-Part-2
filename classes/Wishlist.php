<?php
require_once 'Db.php';
class Wishlist extends Db {
    private $dbconn;
    
    public function __construct() {
        $this->dbconn = $this->connect();
    }
    
    public function toggle_wishlist($user_id, $property_id) {
        try {
            // Check if it exists
            $check = $this->dbconn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND property_id = ?");
            $check->execute([$user_id, $property_id]);
            
            if ($check->rowCount() > 0) {
                // Remove
                $this->dbconn->prepare("DELETE FROM wishlist WHERE user_id = ? AND property_id = ?")->execute([$user_id, $property_id]);
                return ['status' => 'removed'];
            } else {
                // Add
                $this->dbconn->prepare("INSERT INTO wishlist (user_id, property_id) VALUES (?, ?)")->execute([$user_id, $property_id]);
                return ['status' => 'added'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function is_property_saved($user_id, $property_id) {
        $stmt = $this->dbconn->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND property_id = ? LIMIT 1");
        $stmt->execute([$user_id, $property_id]);
        return $stmt->fetchColumn() !== false;
    }

   public function get_user_wishlist($user_id) {
        $sql = "SELECT p.*, l.lga_name, s.state_name,
                (SELECT image_path FROM property_images WHERE property_id = p.property_id AND is_primary = 1 LIMIT 1) as thumbnail
                FROM wishlist w
                JOIN properties p ON w.property_id = p.property_id
                JOIN lgas l ON p.lga_id = l.lga_id
                JOIN states s ON p.state_id = s.state_id
                WHERE w.user_id = ?";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
