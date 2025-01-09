<?php
require_once 'database.php'; 

class Response {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->dbConnection();
    }

    // Method to add a new response
    public function addResponse($survey_id, $user_id, $response) {
        try {
            $sql = "INSERT INTO `responses` (survey_id, user_id, response) 
                    VALUES (:survey_id, :user_id, :response)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":survey_id", $survey_id, PDO::PARAM_INT);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":response", $response, PDO::PARAM_STR);
            $stmt->execute();
    
            return true;
        } catch (PDOException $e) {
            echo "Error adding response: " . $e->getMessage();
            return false;
        }
    }
    

    // Method to view responses for a specific survey
    public function viewResponsesBySurvey($survey_id) {
        try {
            $sql = "SELECT * FROM responses WHERE survey_id = :survey_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":survey_id", $survey_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching responses: " . $e->getMessage();
            return [];
        }
    }

    // Method to view responses for a specific question
    public function getResponsesByQuestion($survey_id, $question_id) {
        try {
            $sql = "SELECT response FROM responses WHERE survey_id = :survey_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':survey_id', $survey_id, PDO::PARAM_INT);
            $stmt->execute();
            $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Initialize an array to count options for the given question_id
            $optionCounts = [];
    
            foreach ($responses as $responseRow) {
                // Decode the JSON response column
                $responseData = json_decode($responseRow['response'], true);
    
                if (is_array($responseData) && isset($responseData[$question_id])) {
                    $selectedOption = $responseData[$question_id];
    
                    if (!isset($optionCounts[$selectedOption])) {
                        $optionCounts[$selectedOption] = 0;
                    }
    
                    $optionCounts[$selectedOption]++;
                }
            }
    
            return $optionCounts;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to update a response
    public function updateResponse($response_id, $response) {
        try {
            $sql = "UPDATE responses SET response = :response, updated_at = CURRENT_TIMESTAMP 
                    WHERE response_id = :response_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":response", $response, PDO::PARAM_STR);
            $stmt->bindParam(":response_id", $response_id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error updating response: " . $e->getMessage();
            return false;
        }
    }

    // Method to delete a response
    public function deleteResponse($response_id) {
        try {
            $sql = "DELETE FROM responses WHERE response_id = :response_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":response_id", $response_id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error deleting response: " . $e->getMessage();
            return false;
        }
    }

    public function getResponsesByQuestionWithDate($survey_id, $question_id, $startDate, $endDate) {
    try {
        $sql = "SELECT response FROM responses WHERE survey_id = :survey_id AND created_at BETWEEN :startDate AND :endDate";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':survey_id', $survey_id, PDO::PARAM_INT);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $stmt->execute();
        $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Process and count responses for the specific question ID
        $optionCounts = [];
        foreach ($responses as $responseRow) {
            $responseData = json_decode($responseRow['response'], true);
            if (isset($responseData[$question_id])) {
                $selectedOption = $responseData[$question_id];
                $optionCounts[$selectedOption] = ($optionCounts[$selectedOption] ?? 0) + 1;
            }
        }
        return $optionCounts;
    } catch (PDOException $e) {
        echo "Error fetching responses with date filter: " . $e->getMessage();
        return [];
    }
}

}
?>
