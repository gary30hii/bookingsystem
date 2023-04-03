<?php
// Get the value of the "customer_id" parameter from the URL
$customer_id = $_GET["customer_id"];

// If the "customer_id" parameter is empty or not set, redirect the user to "managecustomer.php"
if (empty($customer_id)) {
    header("Location:managecustomer.php");
}

?>