<?php
require_once 'classes/surveys.php';
$survey = new Survey();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question_text'];

    $query = "UPDATE questions SET question_text = :question_text WHERE question_id = :question_id";
    $stmt = $survey->runQuery($query);
    $stmt->bindParam(':question_text', $question_text);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();

    // Redirect back to the survey page
    header('Location: survey_details.php?id=' . $survey_id);
    exit();
}
?>
