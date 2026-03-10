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
    //method to getuser deets
    public function getUserDeets($user_id){
        try{
            $sql = 'SELECT * FROM users WHERE id=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data;
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //method to get user role
    public function getUserRole($user_id){
        try{
            $sql = 'SELECT role_ FROM users WHERE id=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data['role_'];
        }catch(PDOException $e){
            //echo $e->getMessage(); exit();
            return false;
        }
    }

    //method to log in
    public function login($email, $password){
        try{
            $sql = 'SELECT * FROM users WHERE email=?';
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if($data){
                if(password_verify($password, $data['p_word'])){
                    $_SESSION['user_role'] = $data['role_']; 
                    return $data['id'];
                } else {
                    $_SESSION['error'] = "Invalid Password.";
                    return false;
                }
            }
            $_SESSION['error'] = "No account found with that email.";
            return false;
        } catch(PDOException $e){
            return false;
        }
    }

    //method to log out
    public function logout(){
        session_destroy();
        return true;
    }

    public function update_profile($id, $fname, $lname, $email, $pnumber, $password){
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

}





?>