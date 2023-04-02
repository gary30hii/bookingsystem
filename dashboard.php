<?php

require_once("include/db.php"); // Include the database connection file
require_once("include/logout.php"); // Include the logout function

session_start(); // Start the session
$admin = $_SESSION['admin']; // Get the admin username from the session

if (empty($admin)) { // If the admin username is empty
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $_SESSION["noadmin"] = true; // Set a flag to indicate that there is no admin
    header("Location:login.php"); // Redirect to the login page
}

if (array_key_exists('logout', $_POST)) { // If the logout button is clicked
    logout(); // Call the logout function
}

$sql_admin = "SELECT * FROM admins"; // Select all admins from the admins table
$stmt = $ConnectingDB->query($sql_admin); // Execute the query

while ($DataRows = $stmt->fetch()) { // Loop through the results
    if ($admin == $DataRows["Username"]) { // If the admin username matches
        $admin_name = $DataRows["StaffName"]; // Set the admin name
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> Dashboard </title>
</head>

<body>

    <nav class="">
        <div class="container">
            <a class="navbar-brand" href="#">Booking System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checkavailability.php">Check Availability</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="newcustomerbooking.php">New Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="updatebooking.php">Edit Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="updatebooking.php">Cancel Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="managecustomer.php">Manage Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageadmin.php">Manage Admin</a>
                    </li>
                    <li class="nav-item">
                        <form method="post">
                            <input type="submit" name="logout" class="btn btn-danger nav-link" value="Log out">
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="container">
            <a href="#" style="color: white; text-decoration: none;"><i class="fas fa-blog"></i>Dashboard</a>
            <?php if (!empty($_SESSION['firstlogin'])) { ?>
                <div id="success" class="alert alert-success alert-dismissible fade show" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill" />
                    </svg>
                    <?php echo "Welcome back, " . $admin_name . " !!" ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            <div class="container">
                <div class="">
                    <div class="col">
                        <a href="newcustomerbooking.php"><button class="btn btn-outline-light w-100">New Booking</button></a>
                    </div>
                    <div class="col">
                        <a href="updatebooking.php"><button class="btn btn-outline-light w-100">Update Booking</button></a>
                    </div>
                    <div class="col">
                        <a href="newcustomer.php"><button class="btn btn-outline-light w-100">New Customer</button></a>
                    </div>
                    <div class="col">
                        <a href="managecustomer.php"><button class="btn btn-outline-light w-100">Update Customer</button></a>
                    </div>
                    <div class="col">
                        <a href="addnewadmin.php"><button class="btn btn-outline-light w-100">New Admin</button></a>
                    </div>
                    <div class="col">
                        <a href="manageadmin.php"><button class="btn btn-outline-light w-100">Update Admin</button></a>
                    </div>
                    <div class="col">
                        <a href="checkavailability.php"><button class="btn btn-outline-light w-100">Check Availability</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>