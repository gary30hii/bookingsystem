<?php

ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/main.php"); 

// Get staff ID based on admin username
$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);
while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        $staff_id = $DataRows["StaffID"];
    }
}

// Redirect to selectcar.php if car model is not provided when publishing
if (isset($_POST["publish"])) {
    if (empty($_POST["car_model"])) {
        header("Location: selectcar.php");
    }
}
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> New Reservation </title>
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

    <form class="" action="newbooking.php" method="post" enctype="multipart/form-data">

        <label>
            <span class="">Car Model: </span>
        </label>
        <input class="form-control" list="car_model" name="car_model">
        <datalist id="car_model">
            <?php
            $sql_cars = "SELECT * FROM cars";  // SQL query to select all cars from the database
            $stmt = $ConnectingDB->query($sql_cars);  // Execute the SQL query and store the result in a variable

            while ($DataRows = $stmt->fetch()) {  // Loop through each row of the result set
            ?>
                <option value=" <?php echo $DataRows["CarID"]; ?>"><?php echo $DataRows["CarModel"] ?> (<?php echo $DataRows["Color"] ?>) - <?php echo $DataRows["CarType"] ?></option>
                <!-- For each row, create an option element for the datalist, displaying the car model, color and type, along with the car ID as the value -->
            <?php }
            ?>
        </datalist>

        <button type="submit" class="" name="publish"><i class="fas fa-check"></i>Select</button>
    </form>

    <a href="newcustomerbooking.php"><button class=""><i class="fas fa-arrow-left"></i>
            Back</button></a>

</body>

</html>