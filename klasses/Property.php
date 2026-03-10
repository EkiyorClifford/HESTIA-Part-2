<?php
require_once "Db.php";

class Property extends Db{

    private $dbconn;

    public function __construct()
    {
        $this->dbconn = $this->connect();
    }
        //default fir status is available. 


        //method to save filename and file
    public function save_property_images($property_id, $files) {
        // $files is $_FILES['images'] from your process page
        
        foreach ($files['name'] as $key => $name) {
            // Only proceed if there is no error for this specific file
            if ($files['error'][$key] == UPLOAD_ERR_OK) {
                
                // 1. Generate unique filename
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $new_filename = "hestia_" . time() . "_" . uniqid() . "." . $ext;
                
                // 2. Set the destination path (from process folder, go up one, then into upload/properties)
                $destination = "../upload/properties/" . $new_filename;

                // 3. Move the file from temp storage to your folder
                if (move_uploaded_file($files['tmp_name'][$key], $destination)) {
                    
                    // 4. Determine if this is the first image (Primary)
                    // If it's the first loop (key 0), set is_primary to 1, else 0
                    $is_primary = ($key === 0) ? 1 : 0;

                    // 5. Insert the filename (string), NOT the array, into the DB
                    $sql = "INSERT INTO property_images (property_id, image_path, is_primary) VALUES (?, ?, ?)";
                    $stmt = $this->dbconn->prepare($sql);
                    $stmt->execute([$property_id, $new_filename, $is_primary]);
                }
            }
        }
    }
    //a method to ctreate a new property{}
    public function create_property($ui,$pti,$broom,$furnished,$lga,$state_id,$ltype,$amount,$title,$description,$address){
        try{
            $sql = "INSERT INTO properties(user_id,property_type_id,bedroom,furnished,lga_id,state_id,listing_type,amount,title,`description`,`prop_address`) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$ui,$pti,$broom,$furnished,$lga,$state_id,$ltype,$amount,$title,$description,$address]);
            $prop_id = $this->dbconn->lastInsertId();
            return $prop_id;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //a method to save property amenities
    public function save_property_amenities($property_id, $amenities){
        $sql = "INSERT INTO property_amenities (property_id, amenity_id) VALUES (?, ?)";
        $stmt = $this->dbconn->prepare($sql);
        foreach($amenities as $amenity_id){
            if(is_numeric($amenity_id)){
                $stmt->execute([$property_id, $amenity_id]);
            }
        }
        return true;
    }

       //a method to fetch a property by id
    public function get_property_by_id($id){
        try{
            $sql = "SELECT * FROM properties WHERE property_id=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();    
            return false;
        }
    }

    

    // a method to fetch all available properties
    public function get_all_available_properties(){
        try{
            $sql = "SELECT * FROM properties WHERE `status`='available'";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //method to get recently listed properties
    public function get_recent_properties($limit = 12){
        try{
            $limit = max(1, $limit);
            $sql = "SELECT * FROM properties WHERE deleted_at IS NULL AND `status`='available' ORDER BY created_at DESC LIMIT $limit";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return false;
        }
    }

    //method to archive property (soft archive)
    public function archive_property($property_id){
        try{
            $sql = "UPDATE properties
                    SET `status`='inactive'
                    WHERE property_id=? AND deleted_at IS NULL";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id]);
            return $stmt->rowCount() > 0;
        }catch(PDOException $e){
            return false;
        }
    }

    //method to restore archived/deleted property
    public function restore_property($property_id, $status = 'available'){
        try{
            if(!is_numeric($property_id)){
                return false;
            }
            $allowed = ['available','taken','inactive'];
            if(!in_array($status, $allowed, true)){
                $status = 'available';
            }
            $sql = "UPDATE properties
                    SET `status`=?, deleted_at=NULL
                    WHERE property_id=? AND deleted_at IS NOT NULL";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$status, $property_id]);
            return $stmt->rowCount() > 0;
        }catch(PDOException $e){
            return false;
        }
    }

    //method to verify if a property belongs to a user
    public function belongs_to_user($property_id, $user_id){
        try{
            $sql = "SELECT COUNT(*) FROM properties WHERE property_id=? AND user_id=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$property_id, $user_id]);
            return ((int)$stmt->fetchColumn()) > 0;
        }catch(PDOException $e){
            return false;
        }
    }

    //method to validate and normalize filters before search
    public function validate_filters($filters = []){
        $clean = [];
        if(!is_array($filters)){
            return $clean;
        }

        if(isset($filters['lga_id']) && is_numeric($filters['lga_id'])){
            $clean['lga_id'] = $filters['lga_id'];
        }
        if(isset($filters['state_id']) && is_numeric($filters['state_id'])){
            $clean['state_id'] = $filters['state_id'];
        }
        if(isset($filters['property_type_id']) && is_numeric($filters['property_type_id'])){
            $clean['property_type_id'] = $filters['property_type_id'];
        }
        if(isset($filters['bedroom']) && is_numeric($filters['bedroom'])){
            $clean['bedroom'] = $filters['bedroom'];
        }
        if(isset($filters['min_amount']) && is_numeric($filters['min_amount'])){
            $clean['min_amount'] = $filters['min_amount'];
        }
        if(isset($filters['max_amount']) && is_numeric($filters['max_amount'])){
            $clean['max_amount'] = $filters['max_amount'];
        }

        if(isset($clean['min_amount'], $clean['max_amount']) && $clean['min_amount'] > $clean['max_amount']){
            $tmp = $clean['min_amount'];
            $clean['min_amount'] = $clean['max_amount'];
            $clean['max_amount'] = $tmp;
        }

        if(isset($filters['listing_type'])){
            $listing_type = strtolower(trim((string)$filters['listing_type']));
            if(in_array($listing_type, ['rent','sale'], true)){
                $clean['listing_type'] = $listing_type;
            }
        }

        if(isset($filters['furnished'])){
            $furnished = trim((string)$filters['furnished']);
            if(in_array($furnished, ['Furnished','Unfurnished'], true)){
                $clean['furnished'] = $furnished;
            }
        }

        if(isset($filters['status'])){
            $status = strtolower(trim((string)$filters['status']));
            if(in_array($status, ['available','taken','inactive','deleted'], true)){
                $clean['status'] = $status;
            }
        }

        $clean['limit'] = (isset($filters['limit']) && is_numeric($filters['limit'])) ? max(1, $filters['limit']) : 20;
        $clean['offset'] = (isset($filters['offset']) && is_numeric($filters['offset'])) ? max(0, $filters['offset']) : 0;

        return $clean;
    }
    ///one search method to get all properties by either lga or state or amount or property type id or listing type(rent,sale) bed room count or furnished status or property status
    public function search(array $filters = [], $limit = 20, $offset = 0) {

        $filters = $this->validate_filters($filters);

        try {

            $sql = "SELECT * FROM properties WHERE deleted_at IS NULL";
            $params = [];

            $map = [
                'status' => '`status` = ?',
                'lga_id' => 'lga_id = ?',
                'state_id' => 'state_id = ?',
                'property_type_id' => 'property_type_id = ?',
                'listing_type' => 'listing_type = ?',
                'bedroom' => 'bedroom = ?',
                'furnished' => 'furnished = ?'
            ];

            foreach ($map as $key => $clause) {
                if (isset($filters[$key]) && $filters[$key] !== '') {
                    $sql .= " AND $clause";
                    $params[] = $filters[$key];
                }
            }

            if (isset($filters['min_amount']) && $filters['min_amount'] !== '') {
                $sql .= " AND amount >= ?";
                $params[] = $filters['min_amount'];
            }

            if (isset($filters['max_amount']) && $filters['max_amount'] !== '') {
                $sql .= " AND amount <= ?";
                $params[] = $filters['max_amount'];
            }

            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;

            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }catch (PDOException $e) {
            return false;
        }
    }

    //method to fetch property stats for landlord dashboard
    public function get_property_stats_by_user($user_id){
        try{
            $sql = "SELECT
                        COUNT(*) AS total_properties,
                        SUM(CASE WHEN `status`='available' AND deleted_at IS NULL THEN 1 ELSE 0 END) AS available_properties,
                        SUM(CASE WHEN `status`='taken' AND deleted_at IS NULL THEN 1 ELSE 0 END) AS taken_properties,
                        SUM(CASE WHEN `status`='inactive' AND deleted_at IS NULL THEN 1 ELSE 0 END) AS inactive_properties,
                        SUM(CASE WHEN `status`='deleted' OR deleted_at IS NOT NULL THEN 1 ELSE 0 END) AS deleted_properties
                    FROM properties
                    WHERE user_id=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return false;
        }
    }


    //a method to fetch properties by user id
    public function get_properties_by_user_id($user_id){//landlord id
        try{
            $sql = "SELECT * FROM properties WHERE user_id=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //A METHOD TO UPDATE A PROPERTY
    public function update_property($prop_id,$user_id, $bedroom, $furnished, $ltype, $amount, $status, $title, $description){
        try{
            $sql = 'UPDATE properties SET bedroom=?, furnished=?, listing_type=?, amount=?, `status`=?, title=?,`description`=?, updated_at=now(), updated_by=? WHERE property_id=? AND user_id=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$bedroom, $furnished, $ltype, $amount, $status, $title, $description, $user_id, $prop_id,$user_id]);
            return $stmt->rowCount() > 0;      
            }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //A METHOD TO DELETE A PROPERTY
    public function delete_property($prop_id, $user_id){
        try{
            $sql = "UPDATE properties SET `status`='deleted', deleted_at=NOW() WHERE property_id=? AND user_id=? AND `status` != 'deleted'";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$prop_id, $user_id]);
            return $stmt->rowCount() > 0;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //a method
}

//testing testing

//     $prop = new Property();

//     //3) featured properties
//     $featured = $prop->get_featured_properties(6);

//     //4) recent properties
//     $recent = $prop->get_recent_properties(10);

//     //7) check ownership before update/delete
//     $is_owner = $prop->belongs_to_user(12, 3); // property_id, user_id

//     //10) validate filters before search, then use sanitized values
//     $raw_filters = [
//         'lga_id' => '5',
//         'state_id' => '1',
//         'listing_type' => 'rent',
//         'min_amount' => '100000',
//         'max_amount' => '500000',
//         'bedroom' => '3',
//         'furnished' => 'Furnished',
//         'status' => 'available',
//         'limit' => '20',
//         'offset' => '0'
//     ];
//     $filters = $prop->validate_filters($raw_filters);
//     $search_results = $prop->search($filters, $filters['limit'], $filters['offset']);

//     //12) landlord stats
//     $stats = $prop->get_property_stats_by_user(1);

//     echo "<pre>";
//     print_r([
//         'featured' => $featured,
//         'recent' => $recent,
//         'is_owner' => $is_owner,
//         'search_results' => $search_results,
//         'stats' => $stats
//     ]);
//     echo "</pre>";

?>
