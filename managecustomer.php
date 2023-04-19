<?php
ini_set('display_errors', '1');

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

    <title> Customers List </title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="updatebooking.php"><i class="fa-solid fa-pen"></i> Update Booking</a>
                    </li>
                    <li class="nav-item active">
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
        <form action="managecustomer.php" method="get">
            <input type="text" name="name" placeholder="Enter customer name...">
            <button type="submit">Search</button>
        </form>

        <a href="newcustomer.php"><button class="btn btn-outline-light w-100">New Customer</button></a>

        <?php
        // Check if 'name' parameter is present in the URL using HTTP GET method
        if (isset($_GET['name'])) {
            $Name =  $_GET['name'];
        } else {
            $Name = "";
        }

        // Modify $Name variable to add '%' to the beginning and end, allowing for partial search
        $Name = "%" . $Name . "%";

        // Construct SQL query to search for customer names that match $Name using LIKE operator
        $sql = "SELECT * 
        FROM customers
        WHERE CustomerName LIKE '$Name'
        ";

        // Execute the SQL query and retrieve the results
        $cus_stmt = $ConnectingDB->query($sql);

        // Check if any matching records were found
        if ($cus_stmt->rowCount() > 0) {
        ?>
            <!-- Display the matching records in a table -->
            <div class="container content-div" style="overflow-x:auto;">
                <table class="table table-striped table-hover table-bordered table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Customer ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Customer Email</th>
                            <th scope="col">Customer Phone Number</th>
                            <th scope="col">Update</th>
                            <th scope="col">Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through the matching records and display them in the table
                        while ($DataRows = $cus_stmt->fetch()) {
                        ?>
                            <tr>
                                <td><?php echo $DataRows["CustomerID"] ?></td>
                                <td><?php echo $DataRows["CustomerName"] ?></td>
                                <td><?php echo $DataRows["CustomerEmail"] ?></td>
                                <td><?php echo $DataRows["CustomerPhoneNumber"] ?></td>
                                <td>
                                    <!-- Create a link to edit the current customer record -->
                                    <a href="editcustomer.php?customer_id=<?php echo $DataRows["CustomerID"] ?>"><button type="submit" id="update" class="btn btn-outline-light w-100"> Update </button></a>
                                </td>
                                <td>
                                    <!-- Create a link to delete the current customer record -->
                                    <a href="deletecustomer.php?customer_id=<?php echo $DataRows["CustomerID"] ?>"><button type="submit" id="cancel" class="btn btn-outline-light w-100"> Delete </button></a>
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
            // If no matching records were found, display an error message
            echo "<h1>No record found</h1>";
        }
        ?>
    </div>


</body>

</html>