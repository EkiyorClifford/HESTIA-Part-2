<?php
$localConfig = BASE_PATH . '/classes/config.php';
$exampleConfig = BASE_PATH . '/classes/config.example.php';
require_once file_exists($localConfig) ? $localConfig : $exampleConfig;
class Db{
    private $dbhost = DBHOST;
    private $dbname = DBNAME;
    private $dbuser = DBUSER;
    private $dbpass = DBPASS;
    protected function connect(){
         $dns = "mysql:host=$this->dbhost;dbname=$this->dbname";
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            try{
                return new PDO($dns, $this->dbuser, $this->dbpass, $options);
            } catch(PDOException $e) {
                //echo $e->getMessage()
                die("Database connection failed" . $e->getMessage());
                // return false;
            }
            catch (Exception $e) { 
                //echo $e->getMessage();
                die("Database connection failed" . $e->getMessage());
                // return false;
            } 
    }
}
