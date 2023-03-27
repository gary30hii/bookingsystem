<?php
ini_set('display_errors', '1');

require_once("include/db.php");
require_once("include/logout.php");

session_start();
$admin = $_SESSION['admin'];

if (empty($admin)) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $_SESSION["noadmin"] = true;
    header("Location:login.php");
}

if (array_key_exists('logout', $_POST)) {
    logout();
}

$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);

while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        $admin_name = $DataRows["StaffName"];
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
                    <li class="nav-item">
                        <a class="nav-link" href="newcustomerbooking.php">New Booking</a>
                    </li>
                    <li class="nav-item active">
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

    <form action="updatebooking.php" method="get">
        <input type="text" name="id" placeholder="Enter an ID...">
        <button type="submit">Search</button>
    </form>

    <?php
    if (isset($_GET['id'])) {
        $id =  $_GET['id'];
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    } else {
        $id = "";
    }

    $id = "%" . $id . "%";
    $sql = "SELECT * 
        FROM reservations 
        JOIN customers ON reservations.CustomerID = customers.CustomerID 
        JOIN cars ON reservations.CarID = cars.CarID 
        JOIN car_types ON cars.CarType = car_types.TypeID
        WHERE reservations.ReservationID LIKE '$id'
        ";
    $reservation_stmt = $ConnectingDB->query($sql);

    if ($reservation_stmt->rowCount() > 0) {
    ?>
        <div class="container content-div" style="overflow-x:auto;">
            <table class="table table-striped table-hover table-bordered table-dark">
                <thead>
                    <tr>
                        <th scope="col">Booking ID</th>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Email</th>
                        <th scope="col">Customer Phone Number</th>
                        <th scope="col">Car ID</th>
                        <th scope="col">Car Model</th>
                        <th scope="col">Car Type</th>
                        <th scope="col">Rental Price</th>
                        <th scope="col">Rental Days</th>
                        <th scope="col">Reservation Date</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Pick Up Date</th>
                        <th scope="col">Drop Off Date</th>
                        <th scope="col">Last Modified by (Staff ID)</th>
                        <th scope="col">Update</th>
                        <th scope="col">Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($DataRows = $reservation_stmt->fetch()) {
                    ?>
                        <tr>
                            <td><?php echo $DataRows["ReservationID"] ?></td>
                            <td><?php echo $DataRows["CustomerID"] ?></td>
                            <td><?php echo $DataRows["CustomerName"] ?></td>
                            <td><?php echo $DataRows["CustomerEmail"] ?></td>
                            <td><?php echo $DataRows["CustomerPhoneNumber"] ?></td>
                            <td><?php echo $DataRows["CarID"] ?></td>
                            <td><?php echo $DataRows["CarModel"] ?></td>
                            <td><?php echo $DataRows["CarTypes"] ?></td>
                            <td>RM<?php echo $DataRows["RentalPrice"] ?></td>
                            <td><?php echo $DataRows["RentalDays"] ?></td>
                            <td><?php echo $DataRows["ReservationDate"] ?></td>
                            <td>RM<?php echo $DataRows["TotalPrice"] ?></td>
                            <td><?php echo $DataRows["PickUpDate"] ?></td>
                            <td><?php echo $DataRows["DropOffDate"] ?></td>
                            <td><?php echo $DataRows["StaffID"] ?></td>
                            <td>
                                <a href="edit.php?reservation_id=<?php echo $DataRows["ReservationID"] ?>"><button type="submit" class="btn btn-outline-light w-100"> Update </button></a>
                            </td>
                            <td>
                                <a href="deletebooking.php?reservation_id=<?php echo $DataRows["ReservationID"] ?>"><button type="submit" class="btn btn-outline-light w-100"> Cancel </button></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    <?php
    } else {
        echo "<h1>No record found</h1>";
    }
    ?>

</body>

</html>