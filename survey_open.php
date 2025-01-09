<?php
session_start();

// Show PHP errors (Disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/surveys.php';
require_once 'classes/questions.php';
include 'question_type.php';

// Check if survey ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $survey_id = $_GET['id'];

    // Fetch survey details
    $survey = new Survey();
    $question = new Question();
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
} else {
    echo "<p>Invalid survey ID!</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title><?php echo htmlspecialchars($survey_details['title']); ?></title>
    <style>
        .carousel-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .carousel-slide {
            display: none;
        }
        .carousel-slide.active {
            display: block;
        }
        .icon-choice {
            cursor: pointer;
            margin: 5px;
        }
        .icon-choice.active {
            border: 2px solid #007bff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<main class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="px-5 py-4 m-4 bg-light rounded-3 w-100 h-75 align-content-center">
        <div class="carousel-container">
            <!-- Survey Title and Description -->
            <div id="slide-0" class="carousel-slide active">
                <h1 ><?php echo htmlspecialchars($survey_details['title']); ?></h1>
                <p class="fs-6"><?php echo htmlspecialchars($survey_details['description']); ?></p>
                <button class="btn btn-primary" onclick="showSlide(1)">Take the Survey</button>
            </div>

            <!-- Questions -->
            <?php foreach ($questions as $index => $q): ?>
                <div id="slide-<?php echo $index + 1; ?>" class="carousel-slide" data-question-id="<?php echo htmlspecialchars($q['question_id']); ?>">
                    <h3 class="lh-base"><?php echo htmlspecialchars($q['question_text']); ?></h3>
                    <div class="rating d-flex fs-2 mt-3 justify-content-center align-items-center">
                        <?php echo questionType($q['question_type'], $index + 1, $q['question_id']); ?> <!-- Pass the slide index here -->
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-secondary back-btn" onclick="showSlide(<?php echo $index; ?>)">Back</button>
                        <?php if ($index + 1 < count($questions)): ?>
                        <button class="btn btn-primary next-btn" onclick="showSlide(<?php echo $index + 2; ?>)">Next</button>
                        <?php else: ?>
                        <button class="btn btn-success submit-btn" id="confirmSubmitSurvey" onclick="setSurveyId(<?php echo $survey_id; ?>)">Submit</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>


            <div id="thankYouSlide" class="carousel-slide">
                <div id="lottie-confetti" class="ani"></div>
                <h3>Thank you for participating in the survey!</h3>
                <p>The page will reload in <span id="countdown">5</span> second(s).</p>
                <button id="reloadNowBtn" class="btn btn-primary">Reload Now</button>
            </div>
        </div>
    </div>
</main>
    <script>
        let currentSlide = 0;

        function showSlide(slideIndex) {
            // Hide all slides
            const slides = document.querySelectorAll('.carousel-slide');
            slides.forEach(slide => slide.classList.remove('active'));

            // Show the targeted slide
            if (slideIndex >= 0 && slideIndex < slides.length) {
                slides[slideIndex].classList.add('active');
                currentSlide = slideIndex;
            }
        }

        function submitSurvey() {
            alert("Survey submitted!"); // Replace with actual submission logic
            showSlide('thank-you');
        }
    </script>
        <?php require_once 'includes/footer.php'; ?>

</body>
</html>
