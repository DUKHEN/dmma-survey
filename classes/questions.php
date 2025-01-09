<?php

require_once 'database.php';

class Question {
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

    // Method to create a new question
    public function addQuestion($survey_id, $question_text) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO `questions` (survey_id, question_text) VALUES (:survey_id, :question_text)");
            $stmt->bindparam(":survey_id", $survey_id);
            $stmt->bindparam(":question_text", $question_text);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updateQuestion($question_id, $question_text, $question_type) {
        try {
            $stmt = $this->conn->prepare("UPDATE `questions` SET question_text = :question_text, question_type = :question_type WHERE question_id = :question_id");
            $stmt->bindparam(":question_id", $question_id, PDO::PARAM_INT);
            $stmt->bindparam(":question_text", $question_text);
            $stmt->bindparam(":question_type", $question_type);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function viewQuestionsBySurvey($survey_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM questions WHERE survey_id = :survey_id");
            $stmt->bindparam(":survey_id", $survey_id); // Assuming you have $survey_id set from the URL
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Redirect URL method
    public function redirect($url){
      header("Location: $url");
    }
}
?>
