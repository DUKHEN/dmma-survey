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
        case 'addQuestion':
            $survey_id = intval($_POST['survey_id'] ?? 0);
            $redirectUrl = "survey_details.php?id=$survey_id";
            $question_text = trim($_POST['question_text'] ?? '');
            $question_type = $_POST['question_type'] ?? null;
        
            // Handle question choices for dropdown
            $question_choices = null; // Default to null for non-dropdown types
            if ($question_type === 'dropdown') {
                $choices = $_POST['dropdown_options'] ?? [];
                $choices = array_map('trim', $choices); // Trim each choice
                $choices = array_filter($choices); // Remove empty values
                $question_choices = !empty($choices) ? json_encode($choices) : null; // Encode to JSON if not empty
            }
        
            if ($survey_id > 0 && !empty($question_text)) {
                // Call the addQuestion method, including question_choices
                if ($question->addQuestion($survey_id, $question_text, $question_type, $question_choices)) {
                    $_SESSION['message'] = 'Question added successfully.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error adding question.';
                    $_SESSION['message_type'] = 'danger';
                }
            } else {
                $_SESSION['message'] = 'Invalid input for adding question.';
                $_SESSION['message_type'] = 'warning';
            }
            break;        

        case 'editQuestion':
            $question_id = intval($_POST['question_id'] ?? 0);
            $question_text = trim($_POST['question_text'] ?? '');
            $question_type = $_POST['question_type'] ?? null;
            $survey_id = intval($_POST['survey_id'] ?? 0);
            $dropdown_options = $_POST['dropdown_options'] ?? null; // Capture dropdown options
            $redirectUrl = "survey_details.php?id=$survey_id";
        
            if ($question_id > 0 && $survey_id > 0 && !empty($question_text)) {
                // Update the question
                $success = $question->updateQuestion($question_id, $question_text, $question_type);
        
                // Handle dropdown options if question type is dropdown
                if ($success && $question_type === 'dropdown') {
                    $question->addQuestionChoices($question_id, $dropdown_options);
                }
        
                if ($success) {
                    $_SESSION['message'] = 'Question updated successfully.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error updating question.';
                    $_SESSION['message_type'] = 'danger';
                }
            } else {
                $_SESSION['message'] = 'Invalid input for question update.';
                $_SESSION['message_type'] = 'warning';
            }
            header("Location: $redirectUrl");
            break;
            

        case 'deleteQuestion':
            $question_id = intval($_POST['question_id'] ?? 0);
            $survey_id = intval($_POST['survey_id'] ?? 0);
            $redirectUrl = "survey_details.php?id=$survey_id";

            if ($question_id > 0) {
                if ($question->deleteQuestion($question_id)) {
                    $_SESSION['message'] = 'Question deleted successfully.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error deleting question.';
                    $_SESSION['message_type'] = 'danger';
                }
            } else {
                $_SESSION['message'] = 'Invalid question ID.';
                $_SESSION['message_type'] = 'warning';
            }
            break;

        case 'createSurvey':
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $created_by = $_SESSION['user_id'] ?? 1; // Default user ID if not set

            if (!empty($title) && !empty($description)) {
                if ($survey->createSurvey($title, $description, $created_by)) {
                    $_SESSION['message'] = 'Survey created successfully.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error creating survey.';
                    $_SESSION['message_type'] = 'danger';
                }
            } else {
                $_SESSION['message'] = 'Title and description cannot be empty.';
                $_SESSION['message_type'] = 'warning';
            }
            break;

        case 'updateSurvey':
            $survey_id = intval($_POST['survey_id'] ?? 0);
            $redirectUrl = "survey_details.php?id=$survey_id";
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($survey_id > 0 && !empty($title) && !empty($description)) {
                if ($survey->updateSurvey($survey_id, $title, $description)) {
                    $_SESSION['message'] = 'Survey updated successfully.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error updating survey.';
                    $_SESSION['message_type'] = 'danger';
                }
            } else {
                $_SESSION['message'] = 'Invalid input for survey update.';
                $_SESSION['message_type'] = 'warning';
            }
            break;

        default:
            $_SESSION['message'] = 'Invalid action.';
            $_SESSION['message_type'] = 'danger';
            break;
    }

    // Redirect back to the main page or a specific URL
    header("Location: $redirectUrl");
    exit();
} else {
    http_response_code(405); // Method not allowed
    $_SESSION['message'] = 'Invalid request method.';
    $_SESSION['message_type'] = 'danger';
    header("Location: index.php");
    exit();
}
?>
