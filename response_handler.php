<?php
session_start();

// Show PHP errors (Disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/responses.php'; // Load Responses class

$response = new Response();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'submitSurvey':
            if (!empty($_POST['survey_id']) && !empty($_POST['responses'])) {
                $survey_id = intval($_POST['survey_id']);
                $user_id = $_SESSION['user_id'] ?? null; // Optional user ID (if logged in)
                $responsesData = $_POST['responses'];

                $response->addResponse($survey_id,$user_id,$responsesData);
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Survey ID or responses are missing."]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
            break;
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
