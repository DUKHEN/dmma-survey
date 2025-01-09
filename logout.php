<?php
// Start session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page or homepage
header("Location: login.php"); // Change to your desired page
exit();
?>
