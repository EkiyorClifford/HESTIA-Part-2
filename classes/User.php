<?php
require_once "Db.php";
class User extends Db {
    private $dbconn;

    public function __construct() {
        $this->dbconn = $this->connect();
    }

    public function get_property_stats_by_user($user_id) {
        try {
            $sql = "SELECT * FROM properties WHERE user_id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    //method to get user by field
    public function get_user_by($field, $value) {
        try {
            $allowed_fields = ['id', 'email', 'p_number'];
            if (!in_array($field, $allowed_fields)) return false;
            $sql = "SELECT * FROM users WHERE $field = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$value]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
        //method to get user role
    public function getUserRole($userId) {
        try {
            $sql = "SELECT role_ FROM users WHERE id = ?";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['role_'];
        } catch (PDOException $e) {
            return false;
        }
    }
    //method to save or update user
    public function save($data, $id = null) {
        try {//saw this on twitter, turns out you can pass this kind of conditional to save lines and time
            if ($id) {
                $sql = "UPDATE users SET first_name=?, last_name=?, email=?, p_number=?";
                $params = [$data['fname'], $data['lname'], $data['email'], $data['pnumber']];

                if (!empty($data['password'])) {
                    $sql .= ", p_word=?";
                    $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                $sql .= " WHERE id=?";
                $params[] = $id;
            } else {
                // INSERT logic tht is (Registration)
                $sql = "INSERT INTO users (first_name, last_name, email, p_number, p_word, role_) VALUES (?,?,?,?,?,?)";
                $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
                $params = [$data['fname'], $data['lname'], $data['email'], $data['pnumber'], $hashed, $data['role']];
            }

            $stmt = $this->dbconn->prepare($sql);
            return $stmt->execute($params) ? ($id ?? $this->dbconn->lastInsertId()) : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    //method to login
    public function login($email, $password) {
        $user = $this->get_user_by('email', $email);//apparently you can call methods within methods
        
        if ($user) {
            if ($user['is_active'] === 'no') {//incase I or an admin ban the user, they can't gain access again
                $_SESSION['error'] = "This account has been deactivated/banned.";
                return false;
            }

            if (password_verify($password, $user['p_word'])) {
                $_SESSION['user_role'] = $user['role_'];
                return $user['id'];
            }
            $_SESSION['error'] = "Invalid password.";
        } else {
            $_SESSION['error'] = "No account found with that email.";
        }
        return false;
    }

    // method to set user status (ban/deactivate)
    public function set_status($id, $status = 'no') {
        try {
            $sql = "UPDATE users SET is_active = ? WHERE id = ?";
            $stmt = $this->dbconn->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    // method to log out
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }

    // method to check if email exists and also if they want to update
    public function email_exists($email, $exclude_id = null) {
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if ($exclude_id) {
            $sql .= " AND id != $exclude_id";
        }

        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->rowCount() > 0;
    }
}
//testing testing
// $user = new User();
// echo "<pre>";
// print_r($user->get_user_by('id', 12));
// echo "</pre>";

?>
