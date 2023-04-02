<?php
// Start a new session
session_start();

// Get the admin session variable
$admin = $_SESSION['admin'];

// If the admin session variable is empty, redirect to the login page
if (empty($admin)) {
    // Store the current URL in the session
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    
    // Set a flag to indicate that the user is not logged in
    $_SESSION["noadmin"] = true;
    
    // Redirect to the login page
    header("Location:login.php");
}
?>
