<?php

require_once 'database.php';

class Survey {
    private $conn;

    // Constructor
    public function __construct(){
      $database = new Database();
      $db = $database->dbConnection();
      $this->conn = $db;
    }


    // Execute queries SQL
    public function runQuery($sql){
      $stmt = $this->conn->prepare($sql);
      return $stmt;
    }

    // Method to create a new survey
    public function createSurvey($title, $description, $created_by) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO `surveys` (title, description, created_at) VALUES (:title, :description, NOW())");
            $stmt->bindparam(":title", $title);
            $stmt->bindparam(":description", $description);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateSurvey($survey_id, $title, $description) {
        try {
            $stmt = $this->conn->prepare("UPDATE `surveys` SET title = :title, description = :description WHERE survey_id = :survey_id");
            $stmt->bindparam(":title", $title);
            $stmt->bindparam(":description", $description);
            $stmt->bindparam(":survey_id", $survey_id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function moveToTrash($survey_id) {
        try {
            $stmt = $this->conn->prepare("UPDATE `surveys` SET status = 'trashed' WHERE survey_id = :survey_id");
            $stmt->bindparam(":survey_id", $survey_id);
            $stmt->execute();
            return $stmt->rowCount() > 0; // Return true if rows were updated
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function viewSurvey($survey_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `surveys` WHERE survey_id = :survey_id");
            $stmt->bindparam(":survey_id", $survey_id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getSurveysByStatus($status) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM `surveys` WHERE status = :status ORDER BY created_at DESC");
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            // Fetch all results as an array
            $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $surveys; // Return the array of results
        } catch (PDOException $e) {
            error_log("Error fetching surveys by status: " . $e->getMessage());
            return []; // Return an empty array on failure
        }
    }
    


    // Redirect URL method
    public function redirect($url){
      header("Location: $url");
    }
}
?>
