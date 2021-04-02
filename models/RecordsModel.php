<?php

require_once 'Database.php';

/*
 * Handles resources -> list of them, count
 */
class RecordsModel
{

    private $db;

    /**
     * RecordsModel constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    public function insertNewRecord($record){
        try{
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("INSERT INTO records (first_name, last_name, action_type, action_time, resource_name ) 
                                                VALUES (:first_name, :last_name, :action_type, :action_time, :resource_name)");

//            $record['action_time'] =
            return $stmt->execute($record);
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }
}