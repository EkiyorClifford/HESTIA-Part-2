<?php
require_once "Db.php";

class User extends Db{
    private $dbconn;
    public function __construct(){
        $this->dbconn = $this->connect();
    }
    public function insert_user($fname, $lname, $email, $pnumber, $password, $role){
        try{
            $sql = 'INSERT INTO users(first_name, last_name, email, p_number, p_word, role_) VALUES (?,?,?,?,?,?)';
            $stmt = $this->dbconn->prepare($sql);
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([$fname, $lname, $email, $pnumber, $hashed, $role]);
            $usr_id = $this->dbconn->lastInsertId();
            return $usr_id;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }


    public function findByEmail($email){
        try{
            $sql = 'SELECT * FROM users WHERE email=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

     //method to get user by id
    public function get_user($user_id){
        try{
            $sql = "SELECT * FROM users WHERE id=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            return $record;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }


    public function emailExists($email){
        try{
            $sql = 'SELECT id FROM users WHERE email=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$email]);
            $data = $stmt->rowCount();
            if($data > 0){
                return true;
            }
            return false;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }

    }

    // public function verifyPassword($email, $password){
    //     try{
    //         $sql = 'SELECT * FROM users WHERE email=?';
    //         $stmt = $this->dbconn->prepare($sql);
    //         $stmt->execute([$email]);
    //         $data = $stmt->fetch(PDO::FETCH_ASSOC);
    //         if($data){
    //             return password_verify($password, $data['p_word']);
    //         }
    //         return false;
    //     }catch(PDOException $e){
    //         //echo $e->getMessage(); exit();
    //         return false;
    //     }
    // }

    //method to log in
    public function login($email, $password){
        try{
            $sql = 'SELECT * FROM users WHERE email=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if($data){
                //should i have a session start here?
                //session_start();
                $stored_password = $data['p_word'];
                $check = password_verify($password, $stored_password);
                if($check){
                    return $data['id'];
                } else{
                    $_SESSION['error'] = "Wrong password";
                    return false;
                }
            }
            $_SESSION['error'] = "Wrong email";
            return false;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //method to log out
    public function logout(){
        session_destroy();
        return true;
    }

    public function update($id, $fname, $lname, $email, $pnumber, $password){
        try{
            $sql = 'UPDATE users SET first_name=?, last_name=?, email=?, p_number=?, p_word=? WHERE id=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$fname, $lname, $email, $pnumber, $password, $id]);
            return true;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    public function delete($id){//soft delete
        try{
            $sql = "UPDATE users SET is_active='no' WHERE id=?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$id]);
            return true;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }
    //method to update password

    public function updatePassword($id, $password){
        try{
            $sql = 'UPDATE users SET p_word=? WHERE id=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$password, $id]);
            return true;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    public function updateProperty($prop_id, $user_id, $bedroom, $listing_type, $amount, $status, $title, $description){
        try{
            $sql = 'UPDATE properties SET bedroom=?, furnished=?, listing_type=?, amount=?, `status`=?, title=?,`description`=?, updated_at=now(), updated_by=? WHERE property_id=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$prop_id, $user_id, $bedroom, $listing_type, $amount, $status, $title, $description, $prop_id]);
            return true;
        }catch(PDOException $e){
            //echo $e->getMessage(); die();
            return false;
        }
    }

}





?>