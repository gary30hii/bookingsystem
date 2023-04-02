<?php
// Retrieve the value of the "reservation_id" input field from the URL query string using the $_GET superglobal variable
$reservation_id = $_GET["reservation_id"];

// Check if the "reservation_id" parameter is empty or not present in the URL query string
if (empty($reservation_id)) {
    // If the "reservation_id" parameter is empty, redirect the user to the "updatebooking.php" page using the header() function
    header("Location:updatebooking.php");
}
?>
