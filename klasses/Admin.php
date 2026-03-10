<?php
require_once "Db.php";

class Admin extends Db{
    private $dbconn;
    public function __construct(){
        $this->dbconn = $this->connect();
    }

    //method to facilitate admin login and logout
    public function admin_login($email, $password){
        try{
            $sql = "SELECT * from admins WHERE adm_email =?";
            $stmt= $this->dbconn->prepare($sql);
            $stmt->execute([$email, $password]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            if($record){
                $stored_password = $record['adm_password'];
                $check = password_verify($password,$stored_password);
                if($check){
                    $_SESSION['admin_id'] = $record['adm_id'];
                    return true;
                }
            }
            $_SESSION['error'] = "Wrong email or password";
            return false;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }
    public function admin_logout(){
        session_destroy();
    }

    //method to allow only admins to add property type
    public function admin_prop_type($ptp_name){
        try{
            $sql = "INSERT INTO property_types(ptp_name) VALUES(?)";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$ptp_name]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }

    //a method to fetch all properties
    public function get_all_properties_Admin(){
        try{
            $sql = "SELECT * FROM properties";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }
}



?>