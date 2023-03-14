<?php
// ini_set('display_errors', '1');

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

global $ConnectingDB;
$sql = "SELECT * FROM reservations, customers, cars, car_types where reservations.CustomerID = customers.CustomerID AND reservations.CarID = cars.CarID AND cars.CarType = car_types.TypeID";
$stmt = $ConnectingDB->query($sql);

while ($DataRows = $stmt->fetch()) {
    $reservation_id[] = $DataRows["ReservationID"];
    $staff_id[] = $DataRows["StaffID"];
    $customer_id[] = $DataRows["CustomerID"];
    $car_id[] = $DataRows["CarID"];
    $reservation_date[] = $DataRows["ReservationDate"];
    $rental_days[] = $DataRows["RentalDays"];
    $total_price[] = $DataRows["TotalPrice"];
    $pick_up_date[] = $DataRows["PickUpDate"];
    $drop_off_date[] = $DataRows["DropOffDate"];
    $customer_name[] = $DataRows["CustomerName"];
    $customer_email[] = $DataRows["CustomerEmail"];
    $customer_phone_number[] = $DataRows["CustomerPhoneNumber"];
    $car_model[] = $DataRows["CarModel"];
    $car_type[] = $DataRows["CarTypes"];
    $car_rental[] = $DataRows["RentalPrice"];
}

$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);

while ($DataRows = $stmt->fetch()) {
    if($admin == $DataRows["Username"]){
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
                        <a class="nav-link active" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blogposts.php">Check Availability</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">New Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Reservation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cancel Booking</a>
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
                <div class="row row-cols-lg-4 ">
                    <div class="col">
                        <a href="newcustomerbooking.php"><button class="btn btn-outline-light w-100"><i class="fas fa-edit"></i>New Booking</button></a>
                    </div>
                    <div class="col">
                        <a href="newcustomer.php"><button class="btn btn-outline-light w-100"><i class="fa fa-list-alt"></i>New Customer</button></a>
                    </div>
                    <div class="col">
                        <a href="addnewadmin.php"><button class="btn btn-outline-light w-100"><i class="fa fa-user-plus"></i>New Admin</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container content-div" style="overflow-x:auto;">
        <table class="table table-striped table-hover table-bordered table-dark">
            <thead>
                <tr>
                    <th scope="col">#</th>
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
                for ($i = 0; $i < sizeof($reservation_id); $i++) {
                ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td><?php echo $reservation_id[$i] ?></td>
                        <td><?php echo $customer_id[$i] ?></td>
                        <td><?php echo $customer_name[$i] ?></td>
                        <td><?php echo $customer_email[$i] ?></td>
                        <td><?php echo $customer_phone_number[$i] ?></td>
                        <td><?php echo $car_id[$i] ?></td>
                        <td><?php echo $car_model[$i] ?></td>
                        <td><?php echo $car_type[$i] ?></td>
                        <td>RM<?php echo $car_rental[$i] ?></td>
                        <td><?php echo $rental_days[$i] ?></td>
                        <td><?php echo $reservation_date[$i] ?></td>
                        <td>RM<?php echo $total_price[$i] ?></td>
                        <td><?php echo $pick_up_date[$i] ?></td>
                        <td><?php echo $drop_off_date[$i] ?></td>
                        <td><?php echo $staff_id[$i] ?></td>
                        <td>
                            <a href="edit.php?reservation_id=<?php echo $reservation_id[$i]; ?>"><button type="submit" class="btn btn-outline-light w-100"> Update </button></a>
                        </td>
                        <td>
                            <a href="deletebooking.php?reservation_id=<?php echo $reservation_id[$i]; ?>"><button type="submit" class="btn btn-outline-light w-100"> Cancel </button></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>