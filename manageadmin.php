<?php
ini_set('display_errors', '1');

// include necessary PHP files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/main.php");

// start a session and retrieve the currently logged-in admin's username
session_start();
$admin = $_SESSION['admin'];

// retrieve all admin records from the database
$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);

// loop through the admin records to find the currently logged-in admin's details
while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        // if the currently logged-in admin's username matches the current record, retrieve their details
        $admin_name = $DataRows["StaffName"];
        $admin_position = $DataRows["Position"];
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

    <title> Admin List </title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="managecustomer.php"><i class="fa-solid fa-list-check"></i> Manage Customer</a>
                    </li>
                    <li class="nav-item active">
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

        <form action="manageadmin.php" method="get">
            <input type="text" name="username" placeholder="Enter admin username...">
            <button type="submit">Search</button>
        </form>

        <!-- Display "New Admin" button if admin position is "Boss"  -->
        <?php if ($admin_position == "Boss") { ?>
            <a href="newadmin.php"><button class="btn btn-outline-light w-100"> New Admin </button></a>
        <?php } ?>

        <!-- Display a message if user doesn't have access to the previous page -->
        <?php if (isset($_SESSION['noaccess']) && $_SESSION['noaccess'] == true) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                You do not have access to the page...
                
                <?php unset($_SESSION["noaccess"]); ?>
            </div>
        <?php } ?>

        <?php

        // Get admin username from the search form
        if (isset($_GET['username'])) {
            $Name =  $_GET['username'];
        } else {
            $Name = "";
        }
        $Name = "%" . $Name . "%";

        // Construct a SQL query to search for admins based on username
        $sql = "SELECT * 
            FROM admins
            WHERE Username LIKE '$Name'
            ";
        $cus_stmt = $ConnectingDB->query($sql);

        // Display a table of admins if any found, otherwise show "No record found" message
        if ($cus_stmt->rowCount() > 0) {
        ?>
            <div class="container content-div" style="overflow-x:auto;">
                <table class="table table-striped table-hover table-bordered table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Staff ID</th>
                            <th scope="col">Staff Name</th>
                            <th scope="col">Staff Username</th>
                            <th scope="col">Staff Position</th>
                            <th scope="col">Update</th>
                            <th scope="col">Cancel</th>
                            <th scope="col">Change password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($DataRows = $cus_stmt->fetch()) {
                        ?>
                            <tr>
                                <td><?php echo $DataRows["StaffID"] ?></td>
                                <td><?php echo $DataRows["StaffName"] ?></td>
                                <td><?php echo $DataRows["Username"] ?></td>
                                <td><?php echo $DataRows["Position"] ?></td>
                                <td>
                                    <a href="editadmin.php?admin_id=<?php echo $DataRows["StaffID"] ?>"><button type="submit" id="update"> Update </button></a>
                                </td>
                                <td>
                                    <a href="deleteadmin.php?admin_id=<?php echo $DataRows["StaffID"] ?>"><button type="submit" id="cancel"> Delete </button></a>
                                </td>
                                <td>
                                    <a href="changepassword.php?admin_id=<?php echo $DataRows["StaffID"] ?>"><button type="submit" id="changePw"> Change Password </button></a>
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

    </div>

</body>

</html>