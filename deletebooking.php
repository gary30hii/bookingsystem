<?php

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/checkreservationid.php");
require_once("include/main.php");

$Success = false; // set default value of success variable to false

global $ConnectingDB; // reference to the database connection object
$sql_reservation = "SELECT * FROM reservations WHERE ReservationID = '$reservation_id'"; // query to retrieve reservation data
$stmt = $ConnectingDB->query($sql_reservation); // execute the query

// loop through the result set and extract reservation data
while ($DataRows = $stmt->fetch()) {
    $customers_id = $DataRows["CustomerID"]; // extract customer id from reservation data
    $car_id = $DataRows["CarID"]; // extract car id from reservation data
    $date1 =  $DataRows["PickUpDate"]; // extract pickup date from reservation data
    $date2 =  $DataRows["DropOffDate"]; // extract dropoff date from reservation data
}

if (isset($_POST["publish"])) { // check if the publish button was clicked
    $sql_delete = "DELETE FROM reservations WHERE ReservationID = '$reservation_id'"; // query to delete the reservation
    $Execute = $ConnectingDB->query($sql_delete); // execute the query
    $Success = true; // set success variable to true
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/78d9acbca6.js" crossorigin="anonymous"></script>

    <title> Delete Booking </title>
</head>

<body>

    <nav class="">
        <div class="container">
            
            <div class="navbar-brand">
                <a href="dashboard.php" id="Booking_System">Booking System</a>
            </div>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checkavailability.php"><i class="fa-solid fa-table-list"></i> Check Availability</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="newcustomerbooking.php"><i class="fa-solid fa-plus"></i> New Booking</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="updatebooking.php"><i class="fa-solid fa-pen"></i> Update Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="managecustomer.php"><i class="fa-solid fa-list-check"></i> Manage Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manageadmin.php"><i class="fa-solid fa-people-roof"></i> Manage Admin</a>
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

    <div class="main">

        <form class="" method="post" enctype="multipart/form-data">
            <div id="success" class="" role="alert" style="display: none;">
                Successfully deleted booking !!
                
            </div>
            <label>
                <span class="FieldInfo">Reservation ID: </span>
            </label>
            <input type="text" id="country" name="reservation_id" value="<?php echo $reservation_id ?>" readonly>

            <label for="car_model">Car Model: <?php echo $car_id; ?></label>
            <input type="hidden" name="car_model" value="<?php echo $car_id; ?>">

            <label>
                <span class="FieldInfo">Customer ID: </span>
            </label>
            <input class="form-control" name="customer_id" value="<?php echo $customers_id; ?>" readonly>

            <label for="pickupdate">Pick Up Date:</label>
            <input type="date" id="pickupdate" name="pick_up_date" value="<?php echo $date1; ?>" readonly>

            <label for="dropoffdate">Drop Off Date:</label>
            <input type="date" id="dropoffdate" name="drop_off_date" value="<?php echo $date2; ?>" readonly>

            <button type="submit" class="btn btn-outline-light w-100" name="publish" onClick='return confirmSubmit()'><i class="fas fa-trash"></i>Delete</button>
        </form>

        <a href="updatebooking.php"><button class=""><i class="fas fa-arrow-left"></i>
                Back</button></a>

    </div>

    <script type="text/javascript">
        var success = "<?php echo $Success ?>";

        if (success == true) {
            document.getElementById("success").style.display = "block";
        }

        function confirmSubmit() {
            var agree = confirm("Are you sure you want to delete the booking? You can’t undo this action. Click OK if you want to continue.");
            if (agree)
                return true;
            else
                return false;
        }
    </script>

</body>

</html>