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
            $stmt = $conn->prepare("INSERT INTO records (first_name, last_name, action_type, action_time, resource_name ) VALUES (:first_name, :last_name, :action_type, :action_time, :resource_name)");

            return $stmt->execute($record);
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }


    public function getDistinctPeople()
    {
        try{
            $conn = $this->db->getConnection();
            return $conn->query("SELECT id, first_name, last_name FROM records GROUP BY first_name, last_name")->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }


    public function getPersonTimestampsByResource($userFirstName, $userLastName, $resourceName)
    {
        try{
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT action_type, action_time FROM records WHERE first_name=:first_name AND last_name=:last_name AND resource_name=:resource_name");
            $stmt->execute(["first_name" => $userFirstName, "last_name" => $userLastName, "resource_name" => $resourceName]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }


    public function lastLeftByResource($resourceName)
    {
        try{
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT action_time FROM records WHERE action_type='Left' AND resource_name=:resource_name ORDER BY action_time DESC");
            $stmt->execute(["resource_name" => $resourceName]);
            return $stmt->fetchColumn(0);
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

}