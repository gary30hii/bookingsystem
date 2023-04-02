<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/checkreservationid.php");
require_once("include/main.php");

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> Edit Reservation </title>
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

    <form class="" action="editbooking.php?reservation_id=<?php echo $reservation_id ?>" method="post" enctype="multipart/form-data">

        <label>
            <span class="FieldInfo">Reservation ID: </span>
        </label>
        <input type="text" id="country" name="reservation_id" value="<?php echo $reservation_id ?>" readonly>
        <label>
            <span class="FieldInfo">Car Model: </span>
        </label>
        <input class="form-control" list="car_model" name="car_model" required>
        <datalist id="car_model">
            <?php
            // Execute a SQL query to retrieve car data from the database
            $sql_cars = "SELECT * FROM cars";
            $stmt = $ConnectingDB->query($sql_cars);

            // Loop through each row of data returned by the query
            while ($DataRows = $stmt->fetch()) {
                // Extract the values of the "CarID", "CarModel", "CarType", and "Color" columns
            ?>
                <option value=" <?php echo $DataRows["CarID"] ?>"><?php echo $DataRows["CarModel"] ?> (<?php echo $DataRows["Color"] ?>) - <?php echo $DataRows["CarType"] ?></option>

            <?php } // End of the while loop 
            ?>
        </datalist>

        <button type="submit" class="btn btn-outline-light w-100" name="publish"><i class="fas fa-check"></i>Select</button>
    </form>

    <a href="edit.php?reservation_id=<?php echo $reservation_id ?>"><button class=""><i class="fas fa-arrow-left"></i>
            Back</button></a>

</body>

</html>