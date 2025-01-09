<?php
session_start();

// Show PHP errors (Disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/surveys.php';
require_once 'classes/questions.php';
require_once 'classes/responses.php'; // Assumes a class to handle responses

// Check if survey ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $survey_id = $_GET['id'];

    // Fetch survey details
    $survey = new Survey();
    $stmt = $survey->viewSurvey($survey_id);
    $survey_details = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$survey_details) {
        echo "<p>Survey not found!</p>";
        exit();
    }

    // Fetch questions
    $stmt = $survey->runQuery("SELECT * FROM questions WHERE survey_id = :survey_id");
    $stmt->bindParam(":survey_id", $survey_id, PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch responses
    $response = new Response();
    $stmt = $response->getResponsesBySurvey($survey_id);
    $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<p>Invalid survey ID!</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>Responses for <?php echo htmlspecialchars($survey_details['title']); ?></title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .container {
            margin: 20px auto;
            max-width: 800px;
        }
    </style>
</head>
<body>
<main class="container">
    <h1>Responses for: <?php echo htmlspecialchars($survey_details['title']); ?></h1>
    <p><?php echo htmlspecialchars($survey_details['description']); ?></p>

    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>User</th>
                <th>Response</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($responses as $response): ?>
                <?php
                $questionText = "Unknown Question";
                foreach ($questions as $question) {
                    if ($question['question_id'] == $response['question_id']) {
                        $questionText = $question['question_text'];
                        break;
                    }
                }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($questionText); ?></td>
                    <td><?php echo htmlspecialchars($response['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($response['response_value']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>
