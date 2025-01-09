<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky pt-3">
        <h6 class="sidebar-heading px-3 text-muted d-flex justify-content-between align-items-center fs-6">
            <span>Admin Dashboard</span>
        </h6>
        <ul class="nav flex-column">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

            <!-- Surveys -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?> fs-6" href="index.php">
                    <i class="bi bi-bar-chart-line"></i> Surveys
                </a>
            </li>

            <!-- Responses -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentPage == 'responses.php') ? 'active' : ''; ?> fs-6" href="responses.php">
                    <i class="bi bi-check2-circle"></i> Responses
                </a>
            </li>

            <!-- Accounts -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentPage == 'accounts.php') ? 'active' : ''; ?> fs-6" href="accounts.php">
                    <i class="bi bi-people"></i> Accounts
                </a>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentPage == 'settings.php') ? 'active' : ''; ?> fs-6" href="settings.php">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
        </ul>

        <hr class="my-4">

        <h6 class="sidebar-heading px-3 text-muted d-flex justify-content-between align-items-center fs-6">
            <span>Account</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <!-- Profile -->
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentPage == 'profile.php') ? 'active' : ''; ?> fs-6" href="profile.php">
                    <i class="bi bi-person-circle"></i> Profile
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item">
                <a class="nav-link text-danger fs-6" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
