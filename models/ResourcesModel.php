<?php

require_once 'Database.php';


/*
 * Handles resources -> list of them, count
 */
class ResourcesModel
{

    private $db;

    /**
     * ResourcesModel constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    public function insertNewResource($resourceName){
        try{
            $conn = $this->db->getConnection();

            $prep = $conn->prepare("INSERT INTO resources (resource_name) VALUES (:resource_name)");
            $prep->bindParam(':resource_name', $resourceName, PDO::PARAM_STR);

            return $prep->execute();
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

    public function getResourceNames()
    {
        try{
            $conn = $this->db->getConnection();
            $retArr = [];
            $result = $conn->query("SELECT resource_name FROM resources")->fetchAll(PDO::FETCH_NUM);
            foreach($result as $key => &$value)
                $retArr[] = $value[0];

            return $retArr;
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

    public function getCount()
    {
        try{
            $conn = $this->db->getConnection();
            return $conn->query("SELECT COUNT(*) FROM resources")->fetchColumn(0);
        } catch(PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }

}