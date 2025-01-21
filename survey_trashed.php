<?php
session_start();

// Show PHP errors for debugging (Disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/surveys.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$survey = new Survey();

// Fetch all trashed surveys
$trashedSurveys = $survey->getSurveysByStatus('trashed');

// Recover survey if requested
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recover_survey'])) {
//     $surveyId = intval($_POST['survey_id']);
//     $survey->updateSurveyStatus($surveyId, 'active');
//     header("Location: trashed_surveys.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>Trashed Surveys</title>
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php require_once 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Trashed</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="me-2">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        </div>
                    </div>
                </div>

                <?php if (count($trashedSurveys) > 0): ?>
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trashedSurveys as $survey): ?>
                    <tr>
                        <td class="text-truncate" style="max-width: 150px;">
                            <?php echo htmlspecialchars($survey['title']); ?>
                        </td>
                        <td>
                            <?php echo date('F j, Y, g:i A', strtotime($survey['created_at'])); ?>
                        </td>
                        <td>
                            <form method="POST" class="m-0">
                                <input type="hidden" name="survey_id" value="<?php echo $survey['survey_id']; ?>">
                                <button type="submit" name="recover_survey" class="btn btn-sm btn-success">
                                    Recover
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-center text-muted mt-4">No trashed surveys found.</p>
<?php endif; ?>

                </div>
            </main>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
