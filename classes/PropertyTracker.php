<?php
require_once BASE_PATH . '/classes/Db.php';

class PropertyTracker extends Db {
    private $dbconn;
    
    public function __construct() {
        $this->dbconn = $this->connect();
    }


    // one method for all
    public function track_view($property_id, $user_id= null){
        try{
            // check if user is logged in, if yes use user_id, if not use ip address
            $ip_address = $_SERVER['REMOTE_ADDR'];//for the view count
            if ($ip_address === null) {
                $ip_address = 'unknown';
            }
            if ($user_id) {
                $check_sql = "SELECT * FROM property_views WHERE property_id = ? AND user_id = ? AND viewed_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)";
                $check_stmt = $this->dbconn->prepare($check_sql);
                $check_stmt->execute([$property_id, $user_id]);
                if ($check_stmt->rowCount() > 0) {
                    return 'already_counted'; // already tracked within the last 10 minutes
                }
            } else {
                $check_sql = "SELECT * FROM property_views WHERE property_id = ? AND ip_address = ? AND viewed_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)";
                $check_stmt = $this->dbconn->prepare($check_sql);
                $check_stmt->execute([$property_id, $ip_address]);
                if ($check_stmt->rowCount() > 0) {
                    return 'already_counted'; // already tracked within the last 10 minutes
                }
            }

            // insert into property_views table
            $sql = "INSERT INTO property_views (property_id, user_id, ip_address, viewed_at) VALUES (?, ?, ?, NOW())";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id, $user_id, $ip_address]);

            // update/insert view count
            $upsert_sql = "INSERT INTO property_stats(property_id, views_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE views_count = views_count + 1";
            $upsert_stmt = $this->dbconn->prepare($upsert_sql);
            $upsert_stmt->execute([$property_id]);

            return 'counted'; // newly added view

        }catch(PDOException $e){
            // $e->getMessage(); die();
            return false;
        }
    }

    public function increment_inspection_count($property_id) {
        try {
            $upsert_sql = "INSERT INTO property_stats(property_id, inspection_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE inspection_count = inspection_count + 1";
            $upsert_stmt = $this->dbconn->prepare($upsert_sql);
            $upsert_stmt->execute([$property_id]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    // method to track application
    public function track_application($property_id) {
        try {
            // upsert(update+insert. LOL)
            $upsert_sql = "INSERT INTO property_stats(property_id, application_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE application_count = application_count + 1";
            $upsert_stmt = $this->dbconn->prepare($upsert_sql);
            $upsert_stmt->execute([$property_id]);
            
            return 'counted';
        } catch(PDOException $e) {
            // $e->getMessage(); die();
            return false;
        }
    }


    // method to count views, apllications and inspections
    public function count_stats($property_id) {
        try {
            $sql = "SELECT
                        COALESCE(ps.views_count, 0) AS views_count,
                        (
                            SELECT COUNT(*)
                            FROM applications a
                            WHERE a.property_id = ?
                        ) AS application_count,
                        (
                            SELECT COUNT(*)
                            FROM inspections i
                            WHERE i.property_id = ?
                        ) AS inspection_count
                    FROM (
                        SELECT ? AS property_id
                    ) AS ref
                    LEFT JOIN property_stats ps ON ps.property_id = ref.property_id";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id, $property_id, $property_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'views_count' => 0,
                'application_count' => 0,
                'inspection_count' => 0,
            ];
        } catch(PDOException $e) {
            // $e->getMessage(); die();
            return false;
        }
    }

    //method to get last viewed
    public function get_last_viewed($property_id, $user_id = null) {
        try {
            $ip_address= $_SERVER['REMOTE_ADDR'];
            if($user_id) {
                $sql = "SELECT viewed_at FROM property_views WHERE property_id = ? AND user_id = ? ORDER BY viewed_at DESC LIMIT 1";
                $stmt = $this->dbconn->prepare($sql);
                $stmt->execute([$property_id, $user_id]);
            } else {
                $sql = "SELECT viewed_at FROM property_views WHERE property_id = ? AND ip_address = ? ORDER BY viewed_at DESC LIMIT 1";
                $stmt = $this->dbconn->prepare($sql);
                $stmt->execute([$property_id, $ip_address]);
            }
            return $stmt->fetch(PDO::FETCH_ASSOC);
           
        } catch(PDOException $e) {
            // $e->getMessage(); die();
            return false;
        }
    }

    //method to get count of views
    public function get_view_count($property_id) {
        try {
            $sql = "SELECT views_count FROM property_stats WHERE property_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // $e->getMessage(); die();
            return false;
        }
    }
}


