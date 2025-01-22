<nav class="col-md-2 d-none d-md-block bg-light sidebar vh-100">
    <div class="sidebar-sticky p-4">
        <ul class="nav flex-column">
            <?php 
            $currentURI = $_SERVER['REQUEST_URI'];

            // Check if the current page is related to Surveys
            $isSurveyPage = strpos($currentURI, 'index.php') !== false || strpos($currentURI, 'survey_details.php') !== false;

            // Check if the current page is related to Orders
            $isOrderPage = strpos($currentURI, 'index.php.php') !== false || strpos($currentURI, 'survey_trashed.php') !== false;
            ?>

            <!-- Dashboard -->
            <li class="nav-item p-2">
                <a class="nav-link <?php echo $isSurveyPage ? 'active' : ''; ?> fs-6" href="index.php">
                    <i class="bi bi-bar-chart-line mx-2"></i> Surveys
                </a>
            </li>

            <!-- Order -->
            <li class="nav-item p-1">
                <a class="nav-link <?php echo $isTrashedPage ? 'active' : ''; ?> fs-6" href="survey_trashed.php">
                    <i class="bi bi-trash mx-2"></i> Trashed
                </a>
            </li>
        </ul>
    </div>
</nav>
