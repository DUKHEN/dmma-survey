<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/surveys.php';
require_once 'classes/questions.php';

$survey = new Survey();
$question = new Question();

if (isset($_GET['id'])) {
    $survey_id = $_GET['id'];


    $stmt = $survey->viewSurvey($survey_id);
    $survey_details = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt_questions = $question->viewQuestionsBySurvey($survey_id);
    $survey_questions = $stmt_questions->fetchALL(PDO::FETCH_ASSOC);

    // If survey details are found, display them
    if ($survey_details && $survey_questions) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <!-- Head metas, css, and title -->
                <?php require_once 'includes/head.php'; ?>
            </head>

            <body>
                <main class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
                    <div class="px-5 py-4 m-4 bg-light rounded-3 w-100 h-75 align-content-center">
                        <div class="container-fluid p-5 h-100">
                        <!-- Bootstrap carousel for the survey -->
                        <div id="surveyCarousel" class="carousel slide h-100" data-bs-interval="false">
                            <div class="carousel-inner h-100">
                                
                                <!-- First slide: Survey Title and Description -->
                                <div class="carousel-item h-100 align-content-center active">
                                    <div class="text-center">
                                        <h1 class="display-5 fw-bold"><?php echo htmlspecialchars($survey_details['title']); ?></h1>
                                        <p class="fs-4"><?php echo htmlspecialchars($survey_details['description']); ?></p>
                                        <button class="btn btn-primary btn-lg" data-bs-target="#surveyCarousel" data-bs-slide="next">Take Survey</button>
                                    </div>
                                </div>

                                <!-- Survey Questions -->
                                <?php foreach ($survey_questions as $index => $question) { ?>
                                    <div class="carousel-item h-100 align-content-center">
                                        <div class="text-center">
                                            <h4><?php echo htmlspecialchars($question['question_text']); ?></h4> <!-- Use $question here -->

                                            <!-- Rating faces from 1 (unhappy) to 5 (happy) -->
                                            <div class="rating mt-4 d-flex justify-content-center">
                                                <div class="rating-face-box">
                                                    <span class="rating-face" data-rating="1" onclick="selectRating(<?php echo $question['question_id']; ?>, 1)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-emoji-angry" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                            <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.5 3.5 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.5 4.5 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683m6.991-8.38a.5.5 0 1 1 .448.894l-1.009.504c.176.27.285.64.285 1.049 0 .828-.448 1.5-1 1.5s-1-.672-1-1.5c0-.247.04-.48.11-.686a.502.502 0 0 1 .166-.761zm-6.552 0a.5.5 0 0 0-.448.894l1.009.504A1.94 1.94 0 0 0 5 6.5C5 7.328 5.448 8 6 8s1-.672 1-1.5c0-.247-.04-.48-.11-.686a.502.502 0 0 0-.166-.761z"/>
                                                        </svg>
                                                    </span>                                                    
                                                </div>
                                                <div class="rating-face-box">
                                                    <span class="rating-face" data-rating="2" onclick="selectRating(<?php echo $question['question_id']; ?>, 2)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-emoji-frown" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
                                                            <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.5 3.5 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.5 4.5 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5"></path>
                                                        </svg>
                                                    </span>                                                    
                                                </div>
                                                <div class="rating-face-box">
                                                    <span class="rating-face" data-rating="3" onclick="selectRating(<?php echo $question['question_id']; ?>, 3)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-emoji-neutral" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                            <path d="M4 10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5m3-4C7 5.672 6.552 5 6 5s-1 .672-1 1.5S5.448 8 6 8s1-.672 1-1.5m4 0c0-.828-.448-1.5-1-1.5s-1 .672-1 1.5S9.448 8 10 8s1-.672 1-1.5"/>
                                                        </svg>
                                                    </span>                                                     
                                                </div>
                                                <div class="rating-face-box">
                                                    <span class="rating-face" data-rating="4" onclick="selectRating(<?php echo $question['question_id']; ?>, 4)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-emoji-smile" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                            <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5"/>
                                                        </svg>
                                                    </span>                                                     
                                                </div>
                                                <div class="rating-face-box">
                                                    <span class="rating-face" data-rating="5" onclick="selectRating(<?php echo $question['question_id']; ?>, 5)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-emoji-laughing" viewBox="0 0 16 16">
                                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                            <path d="M12.331 9.5a1 1 0 0 1 0 1A5 5 0 0 1 8 13a5 5 0 0 1-4.33-2.5A1 1 0 0 1 4.535 9h6.93a1 1 0 0 1 .866.5M7 6.5c0 .828-.448 0-1 0s-1 .828-1 0S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 0-1 0s-1 .828-1 0S9.448 5 10 5s1 .672 1 1.5"/>
                                                        </svg>
                                                    </span>                                                     
                                                </div>
                                            </div>

                                            <!-- Next button -->
                                            <button class="btn btn-primary mt-4" data-bs-target="#surveyCarousel" data-bs-slide="next">Next</button>
                                        </div>
                                    </div>
                                <?php } ?>

                                <!-- Final slide: Submit Survey -->
                                <div class="carousel-item h-100 align-content-center">
                                    <div class="text-center">
                                        <h4>You have completed the survey!</h4>
                                        <button class="btn btn-success" onclick="submitSurvey()">Submit Survey</button>
                                    </div>
                                </div>

                            </div>
                            <!-- Carousel controls (if user wants to go back) -->
                            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#surveyCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#surveyCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button> -->
                        </div>
                    </div>
                </main>

                <!-- Hidden form to collect survey answers -->
                <form id="surveyForm" method="POST" action="submit_survey.php">
                    <input type="hidden" name="survey_id" value="<?php echo htmlspecialchars($survey_details['survey_id']); ?>">
                    <input type="hidden" name="answers" id="answersInput">
                </form>

                <!-- JavaScript to handle rating selection and submission -->
                <script>
                    // Store answers
                    let answers = {};

                    // Function to select rating for a question
                    function selectRating(questionId, rating) {
                        // Store the rating
                        answers[questionId] = rating;

                        // Highlight the selected rating
                        document.querySelectorAll(`.rating-face[data-rating]`).forEach(face => {
                            face.classList.remove('selected');
                        });
                        document.querySelectorAll(`.rating-face[data-rating="${rating}"]`).forEach(face => {
                            face.classList.add('selected');
                        });
                    }

                    // Function to submit the survey
                    function submitSurvey() {
                        // Pass the answers to the hidden input field
                        document.getElementById('answersInput').value = JSON.stringify(answers);

                        // Submit the form
                        document.getElementById('surveyForm').submit();
                    }
                </script>

                <!-- JS, Popper.js, and jQuery -->
                <?php require_once 'includes/footer.php'; ?>
            </body>
        </html>

        <?php
    } else {
        echo "Survey or questions not found!";
    }
} else {
    echo "No survey ID provided!";
}
?>