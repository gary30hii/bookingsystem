<?php

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/main.php");


// Retrieve the car model from the POST data if it exists
if (isset($_POST["car_model"])) {
    $carmodel = $_POST["car_model"];
    // If the car model is empty, redirect the user to the select car page
    if (empty($carmodel)) {
        header("Location:selectcar.php");
    }
}

// Get the reservations for the selected car
global $ConnectingDB;
$sql = "SELECT * FROM reservations WHERE CarID = '$carmodel'";
$stmt = $ConnectingDB->query($sql);
$pick_up = array();
$drop_off = array();
while ($DataRows = $stmt->fetch()) {
    $pick_up[] = $DataRows["PickUpDate"];
    $drop_off[] = $DataRows["DropOffDate"];
}

// Get the staff ID for the logged in admin
$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);
$staff_id = "";
while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        $staff_id = $DataRows["StaffID"];
    }
}

// Get the rental price for the selected car
$sql_car_rental = "SELECT * FROM cars";
$stmt = $ConnectingDB->query($sql_car_rental);
$rental = 0;
while ($DataRows = $stmt->fetch()) {
    if ($carmodel == $DataRows["CarID"]) {
        $rental = $DataRows["RentalPrice"];
    }
}

// Set initial values for $Success and $Failed
$Success = false;
$Failed = false;

if (isset($_POST["submit"])) { // Check if the form has been submitted
    if (!empty($_POST["pick_up_date"]) && !empty($_POST["drop_off_date"])) { // Check if pick-up and drop-off dates are not empty
        if (!empty($pick_up)) { // If $pick_up array is not empty, loop through it
            for ($i = 0; $i < sizeof($pick_up); $i++) {
                if ((($_POST["pick_up_date"] >= $pick_up[$i]) && ($_POST["pick_up_date"] <= $drop_off[$i])) || (($_POST["drop_off_date"] >= $pick_up[$i]) && ($_POST["drop_off_date"] <= $drop_off[$i]))) { // Check if the pick-up and drop-off dates overlap with existing reservations
                    $Failed = true; // Set $Failed to true if there is an overlap
                    break; // Exit the loop if there is an overlap
                }
            }
        }
        if ($Failed == false) { // If there is no overlap, continue with the reservation process
            $customer_id = $_POST["customer_id"]; // Get customer ID from the form
            $timezone = new DateTimeZone('Asia/Kuala_Lumpur'); // Set timezone to Asia/Kuala_Lumpur
            $date = new DateTime('now', $timezone); // Get the current date and time in the specified timezone
            $current_date = $date->format('Y-m-d'); // Format the current date as YYYY-MM-DD
            $date1 = new DateTime($_POST["pick_up_date"]); // Get the pick-up date from the form
            $date2 = new DateTime($_POST["drop_off_date"]); // Get the drop-off date from the form
            $days = $date1->diff($date2)->days + 1; // Calculate the number of rental days
            $total_rental = $days * $rental; // Calculate the total rental price

            $sql = "INSERT INTO reservations (StaffID, CustomerID, CarID, ReservationDate, PickUpDate, DropOffDate, RentalDays, TotalPrice)"; // SQL query to insert reservation data into the database
            $sql .= "VALUES(:staffID, :customerID, :carID, :reservationDate, :pickUpDate, :dropOffDate, :rentalDays, :totalPrice)"; // Add values to the query using placeholders
            $stmt = $ConnectingDB->prepare($sql); // Prepare the SQL statement
            $stmt->bindValue(':staffID', $staff_id); // Bind values to the placeholders
            $stmt->bindValue(':customerID', $customer_id);
            $stmt->bindValue(':carID', $carmodel);
            $stmt->bindValue(':reservationDate', $current_date);
            $stmt->bindValue(':pickUpDate', $date1->format('Y-m-d'));
            $stmt->bindValue(':dropOffDate', $date2->format('Y-m-d'));
            $stmt->bindValue(':rentalDays', $days);
            $stmt->bindValue(':totalPrice', $total_rental);
            $Execute = $stmt->execute(); // Execute the SQL statement

            // Get the ID of the newly inserted row
            $reservation_id = $ConnectingDB->lastInsertId();
            $Success = true; // Set $Success to true if the reservation process is successful

        }
    } else {
        $Failed = true;
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

    <title> New Reservation </title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="newcustomerbooking.php"><i class="fa-solid fa-plus"></i> New Booking</a>
                    </li>
                    <li class="nav-item">
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

        <form class="" action="newbooking.php" method="post" enctype="multipart/form-data" onsubmit="return validateDates();">
            <div id="success" class="" role="alert" style="display: none;">
                Successfully add new booking !! Your reservation id: <?php echo $reservation_id ?>
                
            </div>

            <div id="failed" class="" role="alert" style="display: none;">
                Fail to add new booking !!
                
            </div>

            <label for="car_model">Car Model: <?php echo $carmodel; ?></label>
            <input type="hidden" name="car_model" value="<?php echo $carmodel; ?>">

            <label>
                <span class="FieldInfo">Customer ID: </span>
            </label>
            <input class="form-control" list="customerid" name="customer_id" required>
            <datalist id="customerid">
                <?php
                $sql_customers = "SELECT * FROM customers";
                $stmt = $ConnectingDB->query($sql_customers);

                while ($DataRows = $stmt->fetch()) {
                    $customers_id = $DataRows["CustomerID"];
                    $customers_name = $DataRows["CustomerName"]; ?>
                    <option value=" <?php echo $customers_id ?>"><?php echo $customers_name ?></option>
                <?php }
                ?>
            </datalist>

            <label for="pickupdate">Pick Up Date:</label>
            <input type="date" id="pickupdate" name="pick_up_date" required>

            <label for="dropoffdate">Drop Off Date:</label>
            <input type="date" id="dropoffdate" name="drop_off_date" required>

            <button class="" name="submit"><i class="fas fa-check"></i>submit</button>

        </form>
        <a href="selectcar.php"><button class=""><i class="fas fa-arrow-left"></i>
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