<?php

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/main.php"); 

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/78d9acbca6.js" crossorigin="anonymous"></script>

    <title> Reservation list </title>
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
        <form action="updatebooking.php" method="get">
            <input type="text" name="id" placeholder="Enter an ID...">
            <button type="submit">Search</button>
        </form>

        <?php
        // Check if the "id" parameter is set in the URL
        if (isset($_GET['id'])) {
            // Get the "id" parameter and filter it to only allow numbers
            $id =  $_GET['id'];
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        } else {
            // If the "id" parameter is not set, set $id to an empty string
            $id = "";
        }

        // Add wildcard characters to the beginning and end of the ID to allow partial matches
        $id = "%" . $id . "%";

        // Query the database to get reservations that match the ID
        $sql = "SELECT * 
        FROM reservations 
        JOIN customers ON reservations.CustomerID = customers.CustomerID 
        JOIN cars ON reservations.CarID = cars.CarID 
        JOIN car_types ON cars.CarType = car_types.TypeID
        WHERE reservations.ReservationID LIKE '$id'
        ";
        $reservation_stmt = $ConnectingDB->query($sql);

        // If there are rows returned from the query, display them in a table
        if ($reservation_stmt->rowCount() > 0) {
        ?>
            <!-- Display a table of reservation records -->
            <div class="container content-div" style="overflow-x:auto;">
                <table class="table table-striped table-hover table-bordered table-dark">
                    <!-- Table headers -->
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
                    <!-- Table body -->
                    <tbody>
                        <?php
                        // Loop through each reservation record and display the information in a table row
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
                                <!-- Link to update reservation record -->
                                <td>
                                    <a href="edit.php?reservation_id=<?php echo $DataRows["ReservationID"] ?>"><button type="submit" id="update"> Update </button></a>
                                </td>
                                <!-- Link to cancel reservation record -->
                                <td>
                                    <a href="deletebooking.php?reservation_id=<?php echo $DataRows["ReservationID"] ?>"><button type="submit" id="cancel"> Cancel </button></a>
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
            // Display a message if no reservation records were found
            echo "<h1>No record found</h1>";
        }
        ?>
    </div>

</body>

</html>