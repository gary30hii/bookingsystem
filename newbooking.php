<?php
ini_set('display_errors', '1');

require_once("include/db.php");
require_once("include/logout.php");

session_start();
$admin = $_SESSION['admin'];

if (isset($_POST["car_model"])) {
    $carmodel = $_POST["car_model"]; // retrieve the value of the "car_model" input field from the POST data
    if (empty($carmodel)) {
        header("Location:selectcar.php");
    }
}

if (empty($admin)) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $_SESSION["noadmin"] = true;
    header("Location:login.php");
}

if (array_key_exists('logout', $_POST)) {
    logout();
}

global $ConnectingDB;
$sql = "SELECT * FROM reservations WHERE CarID = '$carmodel'";
$stmt = $ConnectingDB->query($sql);

while ($DataRows = $stmt->fetch()) {
    $pick_up[] = $DataRows["PickUpDate"];
    $drop_off[] = $DataRows["DropOffDate"];
}

$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);

while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        $staff_id = $DataRows["StaffID"];
    }
}

$sql_car_rental = "SELECT * FROM cars";
$stmt = $ConnectingDB->query($sql_car_rental);

while ($DataRows = $stmt->fetch()) {
    if ($carmodel == $DataRows["CarID"]) {
        $rental = $DataRows["RentalPrice"];
    }
}

$Success = false;
$Failed = false;

if (isset($_POST["submit"])) {
    if (!empty($_POST["pick_up_date"]) && !empty($_POST["drop_off_date"])) {
        if (!empty($pick_up)) {
            for ($i = 0; $i < sizeof($pick_up); $i++) {
                if ((($_POST["pick_up_date"] >= $pick_up[$i]) && ($_POST["pick_up_date"] <= $drop_off[$i])) || (($_POST["drop_off_date"] >= $pick_up[$i]) && ($_POST["drop_off_date"] <= $drop_off[$i]))) {
                    $Failed = true;
                    break;
                }
            }
        }
        if ($Failed == false) {
            $customer_id = $_POST["customer_id"];
            $timezone = new DateTimeZone('Asia/Kuala_Lumpur'); // Replace with your timezone
            $date = new DateTime('now', $timezone);
            $current_date = $date->format('Y-m-d');
            $date1 = new DateTime($_POST["pick_up_date"]);
            $date2 = new DateTime($_POST["drop_off_date"]);
            $days = $date1->diff($date2)->days + 1;
            $total_rental = $days * $rental;

            $sql = "INSERT INTO reservations (StaffID, CustomerID, CarID, ReservationDate, PickUpDate, DropOffDate, RentalDays, TotalPrice)";
            $sql .= "VALUES(:staffID, :customerID, :carID, :reservationDate, :pickUpDate, :dropOffDate, :rentalDays, :totalPrice)";
            $stmt = $ConnectingDB->prepare($sql);
            $stmt->bindValue(':staffID', $staff_id);
            $stmt->bindValue(':customerID', $customer_id);
            $stmt->bindValue(':carID', $carmodel);
            $stmt->bindValue(':reservationDate', $current_date);
            $stmt->bindValue(':pickUpDate', $date1->format('Y-m-d'));
            $stmt->bindValue(':dropOffDate', $date2->format('Y-m-d'));
            $stmt->bindValue(':rentalDays', $days);
            $stmt->bindValue(':totalPrice', $total_rental);
            $Execute = $stmt->execute();

            // Get the ID of the newly inserted row
            $reservation_id = $ConnectingDB->lastInsertId();
            $Success = true;
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
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="include/fontawesome-5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>

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

</html>

<form class="" action="newbooking.php" method="post" enctype="multipart/form-data">
    <div id="success" class="" role="alert" style="display: none;">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        Successfully add new booking !!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div id="failed" class="" role="alert" style="display: none;">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
            <use xlink:href="#exclamation-triangle-fill" />
        </svg>
        Fail to add new booking !!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <label for="car_model">Car Model: <?php echo $carmodel; ?></label>
    <input type="hidden" name="car_model" value="<?php echo $carmodel; ?>">

    <label>
        <span class="FieldInfo">Customer ID: </span>
    </label>
    <input class="form-control" list="customerid" name="customer_id">
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
    <input type="date" id="pickupdate" name="pick_up_date">

    <label for="dropoffdate">Drop Off Date:</label>
    <input type="date" id="dropoffdate" name="drop_off_date">

    <button formaction="dashboard.php" class=""><i class="fas fa-arrow-left"></i>
        Back to Dashboard</button>
    <button class="" name="submit"><i class="fas fa-check"></i>submit</button>

</form>

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
</body>

</html>