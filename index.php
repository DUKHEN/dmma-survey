<?php
session_start(); // Add this at the top of the file

// Show PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/surveys.php';

$survey = new Survey();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Head metas, css, and title -->
    <?php require_once 'includes/head.php'; ?>
    <style>
        .modal-body input, .modal-body textarea {
            width: 100%;
        }

        .modal-body textarea {
            height: 100px; /* Adjust the height as needed */
            resize: vertical; /* Allow resizing vertically */
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
        }

        .btn-danger.me-auto {
            margin-right: auto; /* Move the button to the far left */
        }
    </style>
</head>
<body>
    <!-- Header banner -->
    <?php require_once 'includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <?php require_once 'includes/sidebar.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Surveys</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="me-2">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                  <!-- Add Survey button -->
                  <div class="col-md-4">
                      <div class="card text-center border-0 h-100">
                          <button type="button" class="btn btn-success w-100 h-100" data-bs-toggle="modal" data-bs-target="#createSurvey">
                              <i class="bi bi-plus-lg display-5"></i>
                              <p class="mt-2">Create Survey</p>
                          </button>
                      </div>
                  </div>

                  <?php
                      // Fetch all surveys from the database
                      $query = "SELECT * FROM surveys WHERE status != 'trashed'";
                      $stmt = $survey->runQuery($query);
                      $stmt->execute();
                      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                      // Loop through and display each survey in 3-column layout
                      foreach ($results as $row) {
                          echo '<div class="col-md-4">';
                          echo    '<div class="card h-100 d-flex flex-column">'; // Make the card a flex container
                          echo        '<div class="card-body flex-grow-1">'; // Allow card-body to grow and push buttons down
                          echo            '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                          echo            '<p class="card-text text-truncate-multiline">' . htmlspecialchars($row['description']) . '</p>';
                          echo        '</div>';
                          echo        '<div class="card-footer bg-white mt-auto d-flex justify-content-start">'; // Ensure buttons are at the bottom
                          echo            '<a href="survey_details.php?id=' . $row['survey_id'] . '" class="btn btn-primary btn-sm">View Details</a>';
                          echo            '<button class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#trashModal" onclick="setSurveyId(' . $row['survey_id'] . ')"><i class="bi bi-trash"></i></button>';
                          echo        '</div>';
                          echo    '</div>';
                          echo '</div>';
                      }
                  ?>
                </div>


                <!-- Modal for creating a survey -->
                <div class="modal fade" id="createSurvey" tabindex="-1" aria-labelledby="createSurveyLabel" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content p-4">
                          <div class="modal-header">
                              <h5 class="modal-title" id="createSurveyLabel">New survey</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form method="POST" action="">
                            <div class="modal-body py-2">
                              <div class="mb-3">
                                  <label for="title" class="col-form-label">Title:</label>
                                  <input type="text" class="form-control" id="title" name="title" required>
                              </div>
                              <div class="mb-3">
                                  <label for="description" class="col-form-label">Description:</label>
                                  <textarea class="form-control h-2" id="description" name="description" required></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="confirmCreateSurvey">Create Survey</button>
                            </div>
                          </form>
                      </div>
                  </div>
                </div>

                <!-- Modal for moving to trash -->
                <div class="modal fade" id="trashModal" tabindex="-1" aria-labelledby="trashModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-4">
                            <div class="modal-header">
                                <h5 class="modal-title" id="trashModalLabel">Move to trash</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-2">
                                <p>Are you sure you want to move this survey to the trash?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmMoveToTrash">Move to Trash</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="alertContainer" class="custom-alert"></div>

            </main>
        </div>
    </div>
    <!-- Footer scripts, and functions -->
    <?php require_once 'includes/footer.php'; ?>
    
</body>
</html>

