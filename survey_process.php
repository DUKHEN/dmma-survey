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
    $redirectUrl = 'index.php'; // Default redirection page

    switch ($action) {
        case 'moveToTrash':
            if (isset($_POST['survey_id'])) {
                // Convert survey_id to an integer
                $survey_id = intval($_POST['survey_id']);
                
                // Validate survey_id further if needed
                if ($survey_id > 0) {
                    // Attempt to move the survey to trash
                    if ($survey->moveToTrash($survey_id)) {
                        $_SESSION['message'] = 'Survey moved to trash successfully.';
                        $_SESSION['message_type'] = 'success';
                    } else {
                        $_SESSION['message'] = 'Error moving survey to trash.';
                        $_SESSION['message_type'] = 'danger';
                    }
                } else {
                    // survey_id is invalid after conversion
                    $_SESSION['message'] = 'Invalid survey ID. Survey ID must be greater than 0. '
                    . 'Value: ' . $survey_id . ' (Type: ' . gettype($survey_id) . ')';
                    $_SESSION['message_type'] = 'warning';
                }
            } else {
                // survey_id is not set or not numeric
                $_SESSION['message'] = 'Survey ID not provided or invalid.';
                $_SESSION['message_type'] = 'warning';
            }
            break;
    

        case 'createSurvey':
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $created_by = $_SESSION['user_id'] ?? 1; // Default to 1 if user_id is not set

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
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $status = ($_POST['status'] === 'active') ? 'active' : 'inactive';

            if ($survey_id > 0 && !empty($title) && !empty($description)) {
                if ($survey->updateSurvey($survey_id, $title, $description, $status)) {
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

        case 'updateQuestion':
            $question_id = intval($_POST['question_id'] ?? 0);
            $question_text = trim($_POST['question_text'] ?? '');
            $question_type = trim($_POST['question_type'] ?? '');
            $survey_id = intval($_POST['survey_id'] ?? 0);
        
            if ($question_id > 0 && !empty($question_text) && !empty($question_type) && $survey_id > 0) {
                if ($question->updateQuestion($question_id, $question_text, $question_type)) {
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
