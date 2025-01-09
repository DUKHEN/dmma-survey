<?php
session_start();

// Show PHP errors (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/surveys.php';
require_once 'classes/questions.php';

$survey = new Survey();
$question = new Question();

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'moveToTrash':
            if (isset($_POST['survey_id']) && is_numeric($_POST['survey_id'])) {
                $survey_id = intval($_POST['survey_id']);
                if ($survey->moveToTrash($survey_id)) {
                    echo "success";
                } else {
                    http_response_code(500);
                    echo "Error moving survey to trash.";
                }
            } else {
                http_response_code(400);
                echo "Invalid survey ID.";
            }
            break;

        case 'createSurvey':
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $created_by = $_SESSION['user_id'] ?? 1; // Default to 1 if user_id is not set

            if (!empty($title) && !empty($description)) {
                if ($survey->createSurvey($title, $description, $created_by)) {
                    echo "success";
                } else {
                    http_response_code(500);
                    echo "Error creating survey.";
                }
            } else {
                http_response_code(400);
                echo "Title and description cannot be empty.";
            }
            break;

        case 'updateSurvey':
            $survey_id = intval($_POST['survey_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $status = ($_POST['status'] === 'active') ? 'active' : 'inactive';

            if ($survey_id > 0 && !empty($title) && !empty($description)) {
                if ($survey->updateSurvey($survey_id, $title, $description, $status)) {
                    echo "success";
                } else {
                    http_response_code(500);
                    echo "Error updating survey.";
                }
            } else {
                http_response_code(400);
                echo "Invalid input for survey update.";
            }
            break;

        case 'updateQuestion':
            $question_id = intval($_POST['question_id'] ?? 0);
            $question_text = trim($_POST['question_text'] ?? '');
            $question_type = trim($_POST['question_type'] ?? '');
            $survey_id = intval($_POST['survey_id'] ?? 0);
        
            if ($question_id > 0 && !empty($question_text) && !empty($question_type) && $survey_id > 0) {
                if ($question->updateQuestion($question_id, $question_text, $question_type)) {
                    echo "success";
                } else {
                    http_response_code(500);
                    echo "Error updating question.";
                }
            } else {
                http_response_code(400);
                echo "Invalid input for question update.";
            }
            break;
        
        case 'deleteQuestion': 
            $question_id = intval($_POST['question_id'] ?? 0);

            if ($question_id > 0) {
                if ($question->deleteQuestion($question_id)) {
                    echo "success";
                } else {
                    http_response_code(500);
                    echo "Error deleting question.";
                }
            } else {
                http_response_code(400);
                echo "Invalid question ID.";
            }
            break;

        default:
            http_response_code(400);
            echo "Invalid action.";
            break;
    }
} else {
    http_response_code(405); // Method not allowed
    echo "Invalid request method.";
}
?>
