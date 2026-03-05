<?php
require_once "Db.php";

class Dev extends Db{

    private $dbconn;
    public function __construct(){
        $this->dbconn = $this->connect();//this is the database connection, stored in the $dbconn property. Inherit from Db class.

    }
    //method to get general and single sessions from the db
    public function get_session_bylevel($levelname="general"){
        try{
            $sql = "SELECT * FROM `sessions` JOIN tracks ON ses_track_id = trk_id  WHERE trk_level=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$levelname]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }catch(PDOException $e){
            return false;
        }
    }
    //method to save filename
        public function save_filename($filename, $user_id) {
        try{
            $sql="UPDATE users SET usr_image=? WHERE usr_id=?";
            $stmt=$this->dbconn->prepare($sql);
            $res=$stmt->execute([$filename, $user_id]);
            return $res;
        }catch(PDOException $e) {
            //echo $e->getMessage(); die;
            return false;
        }
    }
    //method to update to db
    public function update_profile($fname, $lname, $level, $bio, $user_id){
        try{
            $sql = "UPDATE users SET usr_fname=?, usr_lname=?, usr_level=?, usr_summary=? WHERE usr_id=?";
            $stmt = $this->dbconn->prepare($sql);
            $result = $stmt->execute([$fname, $lname, $level, $bio, $user_id]);
            if($result){
                $_SESSION['success'] = "Profile updated successfully";
                return true;
            }else{
                $_SESSION['error'] = "Failed to update profile";
                return false;
            }
        }catch(PDOException $e){
            error_log("Update profile error: " . $e->getMessage());
            return false;
        }
    }
    //method login
    public function Login($email,$password){
        try{
            // $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "SELECT * FROM users WHERE usr_email=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$email]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            if($record){//email is correct
                $stored_password = $record['usr_password_hash'];
                $check = password_verify($password,$stored_password);//true o false 
                if($check){//password is correct, return the person's id in session
                    // $_SESSION['user_id'] = $record['usr_id'];
                    return $record['usr_id'];
                }else{
                    $_SESSION['error'] = "Wrong password";
                    return false;
                }
            }else{//the email coming from the form does not exist on the database
                $_SESSION['error'] = "Wrong email";
                return false;
            }
        }catch(PDOException $e){
            return false;
        }
    }
    public function get_user($user_id){
        try{
            $sql = "SELECT * FROM users WHERE usr_id=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            return $record;
        }catch(PDOException $e){
            //echo $e->getMessage(); die();
            return false;
        }
    }
    //method to 
    public function insert_conversation($title,$content,$created_by){
        try{
            $sql = "INSERT INTO conversations(con_title, con_content, con_created_by) VALUES (?, ?, ?)";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$title,$content,$created_by]);//why is it in array? this is because we are using prepared statements
            $id = $this->dbconn->lastInsertId();
            $_SESSION['feedback'] = "Topic $title was created successfully";
            return $id;
        }catch(PDOException $e){
            $_SESSION['error'] = "Failed to create topic";
            //echo $e->getMessage(); die();
            return false;
        }
    }
    //method nlogout

    public function logout(){
        session_destroy();
    }

    //write a method that checks if an email has been registered before
    public function check_email($email){
        try {
            $sql = 'SELECT * FROM users WHERE usr_email =?';
            $stmt = $this ->dbconn->prepare($sql);
            $stmt->execute([$email]);
            //$data = $stmt->fetch(PDO::FETCH_ASSOC);//why are we using PDO::FETCH_ASSOC?
            $data = $stmt->rowCount();
            return $data;
        } catch (PDOException $e) {
            // echo $e->getMessage(); die();
            return false;
        }
    }
    //a method that registers a user
    public function register($firstname, $lastname, $email, $password){
        try{
            $sql = 'INSERT INTO users(usr_firstname, usr_lastname, usr_email, usr_password_hash) VALUES (?, ?, ?, ?)';
            $stmt = $this ->dbconn->prepare($sql);
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([$firstname, $lastname, $email, $hashed]);
            $usr_id = $this->dbconn->lastInsertId();
            return $usr_id;
        }catch(PDOException $e){
            return false;
        }
        
    }
}





?>