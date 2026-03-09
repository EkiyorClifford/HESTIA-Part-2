<?php
require_once "Db.php";

class Post extends Db
{
    private $dbconn;

    public function __construct()
    {
       $this->dbconn=$this->connect(); 
    }

    public function save_filename($filename) {
        try{
            $sql="INSERT INTO posts(po_filename) VALUES(?)";
            $stmt=$this->dbconn->prepare($sql);
            $res=$stmt->execute([$filename]);
            return $res;
        }catch(PDOException $e) {
            //return $e->getMessage(); die;
            return false;
        }
    }

    public function fetch_all_files() {
        try{
            $sql="SELECT * FROM posts";
            $stmt=$this->dbconn->prepare($sql);
            $stmt->execute();
            $files=$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $files;
        }catch(PDOException $e) {
            //return $e->getMessage(); die;
            return false;
        }
    }

    public function delete_filename($file_id) {
        try{
            $sql="DELETE FROM posts WHERE po_id=?";
            $stmt=$this->dbconn->prepare($sql);
            $resp=$stmt->execute([$file_id]);
            return $resp;
        }catch(PDOException $e) {
            //return $e->getMessage(); die;
            return false;
        }
    }

    public function delete_file($id) {
        try{
            $sql="SELECT * FROM posts WHERE po_id=?";
            $stmt=$this->dbconn->prepare($sql);
            $stmt->execute([$id]);
            $resp=$stmt->fetch(PDO::FETCH_ASSOC);
            if(count($resp)>0) {
                $filetodelete="upload/".$resp['po_filename'];
                if(file_exists($filetodelete)) {
                    $res=unlink($filetodelete);
                    if($res) {
                        $finalres=$this->delete_filename($id);
                        return $finalres;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{

            }
        }catch(PDOException $e) {
            //return $e->getMessage(); die;
            return false;
        }
    }
}
