<?php
require_once "Db.php";

class State extends Db{
    private $dbconn;
    public function __construct()
    {
        $this->dbconn = $this->connect();
    }
    
    public function get_states(){
        $sql = "SELECT * FROM states";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $states= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $states;
    }
    //method to get lgas by state id 
    public function get_lgas_by_state_id($state_id){
        $sql = "SELECT * FROM lgas WHERE state_id=?";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute([$state_id]);
        $lgas= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $lgas;
    }

    //method to get property type from database
    public function get_property_types(){
        $sql = "SELECT * FROM property_types";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $property_types= $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $property_types;
    }
    
}


//testing testing
// $state =new State();
// $ptypes = $state->get_property_types();
// $listing_types = $state->get_listing_type();
// echo "<pre>";
// print_r($listing_types);
// echo "</pre>";
?>