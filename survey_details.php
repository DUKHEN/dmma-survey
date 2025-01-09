<?php
session_start();

// Show PHP errors for debugging (Disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/surveys.php';
require_once 'classes/questions.php';
require_once 'classes/responses.php';
include 'question_type.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$survey = new Survey();
$question = new Question();
$response = new Response();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add question
    if (isset($_POST['add_question'])) {
        $question_text = trim($_POST['question_text']);
        $survey_id = $_POST['survey_id'];

        if (!empty($question_text)) {
            $question->addQuestion($survey_id, $question_text);
            header("Location: survey_details.php?id=" . $survey_id);
            exit();
        } else {
            $error = "Question text cannot be empty.";
        }
    }

    // Edit question
    if (isset($_POST['edit_question'])) {
        $question_id = $_POST['question_id'];
        $question_text = trim($_POST['question_text']);
        $survey_id = $_POST['survey_id'];

        if (!empty($question_text)) {
            $question->updateQuestion($question_id, $question_text);
            header("Location: survey_details.php?id=" . $survey_id);
            exit();
        } else {
            $error = "Question text cannot be empty.";
        }
    }
}

// Get survey details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $survey_id = $_GET['id'];
    $stmt = $survey->viewSurvey($survey_id);
    $survey_details = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$survey_details) {
        echo "<p>Survey not found!</p>";
        exit();
    }
} else {
    echo "<p>Invalid survey ID!</p>";
    exit();
}

$showResponses = isset($_GET['showResponses']) ? $_GET['showResponses'] === 'true' : true;
$dateFilter = isset($_GET['dateFilter']) ? $_GET['dateFilter'] : 'this_month';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

// Calculate date range for predefined filters
if ($dateFilter !== 'custom') {
    $currentDate = new DateTime();
    switch ($dateFilter) {
        case 'three_months':
            $startDate = $currentDate->modify('-3 months')->format('Y-m-d');
            $endDate = (new DateTime())->format('Y-m-d');
            break;
        case 'six_months':
            $startDate = $currentDate->modify('-6 months')->format('Y-m-d');
            $endDate = (new DateTime())->format('Y-m-d');
            break;
        case 'one_year':
            $startDate = $currentDate->modify('-1 year')->format('Y-m-d');
            $endDate = (new DateTime())->format('Y-m-d');
            break;
        default: // this_month
            $startDate = (new DateTime('first day of this month'))->format('Y-m-d');
            $endDate = (new DateTime('last day of this month'))->format('Y-m-d');
            break;
    }
}

// Fetch responses with the date filter applied
function getFilteredResponses($survey_id, $question_id, $startDate, $endDate, $response) {
    return $response->getResponsesByQuestionWithDate($survey_id, $question_id, $startDate, $endDate);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>Survey Details</title>
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Survey Details -->
                <div class="px-5 py-4 m-4 bg-light rounded-3 position-relative">
                    <button class="btn btn-light position-absolute top-0 end-0 m-3" type="button" data-bs-toggle="modal" data-bs-target="#editSurvey" title="Edit Survey">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </button>

                    <div class="container-fluid py-5 text-center">
                        <div class="timestamp">
                            <?php
                            $date = new DateTime($survey_details['created_at']);
                            echo $date->format('F j, Y, g:i A');
                            ?>
                        </div>

                        <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($survey_details['title']); ?></h1>
                        <p class="fs-4"><?php echo htmlspecialchars($survey_details['description']); ?></p>
                        <h6 class="mb-4">Status:
                            <strong class="<?php echo $survey_details['status'] === 'active' ? 'text-success' : 'text-danger'; ?>">
                                <?php echo $survey_details['status'] === 'active' ? 'Active' : 'Inactive'; ?>
                            </strong>
                        </h6>
                        <button class="btn btn-orange" id="viewResponses" data-bs-toggle="modal" data-bs-target="#responsesModal">
                            <i class="bi bi-bar-chart-line"></i> Responses
                        </button>
                        <a href="survey_open.php?id=<?php echo htmlspecialchars($survey_details['survey_id']); ?>" class="btn btn-orange">Open Survey</a>
                    </div>
                </div>


                <div class="px-5 m-4 rounded-3">
                    <div class="container-fluid">
                        <!-- Header Section -->
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Survey Questions</h1>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                                    <i class="bi bi-plus-lg"></i> Add Question
                                </button>
                                <button class="btn btn-light" type="button" onclick="printResponses()">
                                    <i class="bi bi-printer-fill"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Questions List -->
                        <div class="list-group" id="responsesPrint">
                            <?php
                            $stmt = $survey->runQuery("SELECT * FROM questions WHERE survey_id = :survey_id");
                            $stmt->bindParam(":survey_id", $survey_id, PDO::PARAM_INT);
                            $stmt->execute();
                            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($questions as $q) {
                            ?>
                                <div class="list-group-item my-2 border rounded shadow-sm p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 text-dark">
                                                <?php echo htmlspecialchars($q['question_text']); ?>
                                            </h6>
                                            
                                            <div class="rating disabled d-flex fs-2 mt-3">
                                                <?php 
                                                $optionCounts = getFilteredResponses($survey_id, $q['question_id'], $startDate, $endDate, $response);
                                                $slideIndex = $slideIndex ?? 0;
                                                $optionsHtml = questionType($q['question_type'], $slideIndex, $q['question_id']);
                                                $options = explode('</label>', $optionsHtml);
                                                $index = 1;
                            
                                                foreach ($options as $optionHtml): 
                                                    if (!empty(trim($optionHtml))) {
                                                        $responseCount = $optionCounts[$index] ?? 0;
                                                        echo "<div class=' test d-flex align-items-center text-center'>
                                                                $optionHtml</label>";
                                                        if ($showResponses) {
                                                            echo "<div class='response-count d-flex align-items-center text-center m-0 me-3'>
                                                                    <p class='fs-6 m-0'>" . $responseCount . "</p>
                                                                    <i class='bi bi-person-fill fs-6'></i>
                                                                    </div>";
                                                        }
                                                        echo "</div>";
                                                        $index++;
                                                    }
                                                endforeach;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn" type="button" id="dropdownRightMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownRightMenuButton">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    onclick="editQuestionModal(<?php echo $survey_id; ?>, <?php echo $q['question_id']; ?>, `<?php echo htmlspecialchars($q['question_text']); ?>`, `<?php echo htmlspecialchars($q['question_type']); ?>`)">
                                                        <i class="bi bi-pencil-square mx-1"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    onclick="setQuestionId(<?php echo $q['question_id']; ?>)">
                                                        <i class="bi bi-trash mx-1"></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            
                        </div>
                    </div>
                </div>

            <?php require_once 'modals.php'; ?>

            </main>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>