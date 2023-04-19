<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/checkreservationid.php");
require_once("include/main.php");

$Success = false;
$Failed = false;

// Current reservation details
$sql_original_reservation = "SELECT * FROM reservations WHERE ReservationID = '$reservation_id'";
$stmt = $ConnectingDB->query($sql_original_reservation);
while ($DataRows = $stmt->fetch()) {
    $carmodel = $DataRows["CarID"];
    $customers_id = $DataRows["CustomerID"];
}

// Check if car model was submitted in POST request
if (isset($_POST["car_model"])) {
    $carmodel = $_POST["car_model"]; // retrieve the value of the "car_model" input field from the POST data
    if (empty($carmodel)) {
        header("Location:editcar.php"); // redirect to editcar.php if the car model is empty
    }
}

// Select all reservations for the given car model
$sql_car = "SELECT * FROM reservations WHERE CarID = '$carmodel'";
$stmt = $ConnectingDB->query($sql_car);

// Loop through the reservations for the given car model to get the pickup and drop-off dates
while ($DataRows = $stmt->fetch()) {
    if ((isset($date1) && isset($date2)) && ($DataRows["PickUpDate"] != $date1) && ($DataRows["DropOffDate"] != $date2)) {
        $pick_up[] = $DataRows["PickUpDate"];
        $drop_off[] = $DataRows["DropOffDate"];
    }
}

// Select all admins
$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);

// Loop through the admins to get the staff ID for the given admin username
while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        $staff_id = $DataRows["StaffID"];
    }
}

// Select all cars
$sql_car_rental = "SELECT * FROM cars";
$stmt = $ConnectingDB->query($sql_car_rental);

// Loop through the cars to get the rental price for the given car model
while ($DataRows = $stmt->fetch()) {
    if ($carmodel == $DataRows["CarID"]) {
        $rental = $DataRows["RentalPrice"];
    }
}
if (isset($_POST["submit"])) { // check if the submit button was clicked
    if (!empty($_POST["pick_up_date"]) && !empty($_POST["drop_off_date"])) { // check if the pick-up date and drop-off date fields are not empty
        if (!empty($pick_up)) { // check if the pick_up array is not empty
            // loop through the pick_up and drop_off arrays to check for overlapping dates
            for ($i = 0; $i < sizeof($pick_up); $i++) {
                if ((($_POST["pick_up_date"] >= $pick_up[$i]) && ($_POST["pick_up_date"] <= $drop_off[$i])) || (($_POST["drop_off_date"] >= $pick_up[$i]) && ($_POST["drop_off_date"] <= $drop_off[$i]))) {
                    $Failed = true; // set the $Failed variable to true if there is a date overlap
                    break;
                }
            }
        }

        if ($Failed == false) { // check if there were no date overlaps
            $customer_id = $_POST["customer_id"]; // retrieve the customer ID from the POST data
            $timezone = new DateTimeZone('Asia/Kuala_Lumpur'); // create a new DateTimeZone object with the specified timezone
            $date = new DateTime('now', $timezone); // create a new DateTime object with the current date and the specified timezone
            $current_date = $date->format('Y-m-d'); // format the current date as YYYY-MM-DD
            $date1 = new DateTime($_POST["pick_up_date"]); // create a new DateTime object with the pick-up date
            $date2 = new DateTime($_POST["drop_off_date"]); // create a new DateTime object with the drop-off date
            $days = $date1->diff($date2)->days + 1; // calculate the number of rental days
            $total_rental = $days * $rental; // calculate the total rental price

            // create an SQL query to update the reservation with the new data
            $sql_update = "UPDATE reservations SET StaffID = :staffID, CustomerID = :customerID, CarID = :carID, ReservationDate = :reservationDate, PickUpDate = :pickUpDate, DropOffDate = :dropOffDate, RentalDays = :rentalDays, TotalPrice = :totalPrice WHERE ReservationID = :reservationID";
            $newData = array(':staffID' => $staff_id, ':customerID' => $customer_id, ':carID' => $carmodel, ':reservationDate' => $current_date, ':pickUpDate' => $date1->format('Y-m-d'), ':dropOffDate' => $date2->format('Y-m-d'), ':rentalDays' => $days, ':totalPrice' => $total_rental, ':reservationID' => $reservation_id);
            $stmt = $ConnectingDB->prepare($sql_update); // prepare the SQL query
            $Execute = $stmt->execute($newData); // execute the SQL query with the new data

            $Success = true; // set the $Success variable to true if the update was successful
        }
    } else {
        $Failed = true; // set the $Failed variable to true if either the pick-up date or the drop-off date fields are empty
    }
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

    <title> Edit Reservation </title>
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

        <form class="" action="editbooking.php?reservation_id=<?php echo $reservation_id ?>" method="post" enctype="multipart/form-data" onsubmit="return validateDates();">
            <div id="success" class="" role="alert" style="display: none;">
                Successfully edit booking !!

            </div>

            <div id="failed" class="" role="alert" style="display: none;">
                Fail to edit booking !!

            </div>

            <label>
                <span class="FieldInfo">Reservation ID: </span>
            </label>
            <input type="text" id="country" name="reservation_id" value="<?php echo $reservation_id ?>" readonly>

            <label for="car_model">Car Model: <?php echo $carmodel; ?></label>
            <input type="hidden" name="car_model" value="<?php echo $carmodel; ?>">

            <label>
                <span class="FieldInfo">Customer ID: </span>
            </label>
            <input class="form-control" name="customer_id" value="<?php echo $customers_id; ?>" readonly>

            <label for="pickupdate">Pick Up Date:</label>
            <input type="date" id="pickupdate" name="pick_up_date" value="" required>

            <label for="dropoffdate">Drop Off Date:</label>
            <input type="date" id="dropoffdate" name="drop_off_date" value="" required>

            <button class="" name="submit"><i class="fas fa-check"></i>submit</button>

        </form>

        <a href="edit.php?reservation_id=<?php echo $reservation_id ?>"><button class=""><i class="fas fa-arrow-left"></i>
                Back</button></a>
    </div>

    <script type="text/javascript">
        var success = "<?php echo $Success ?>";
        var failed = "<?php echo $Failed ?>";

        if (success == true) {
            document.getElementById("success").style.display = "block";
        }
        if (failed == true) {
            document.getElementById("failed").style.display = "block";
        }
    </script>
    <script src="include/date.js"></script>

</body>

</html>