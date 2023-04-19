<?php

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php"); 
require_once("include/main.php"); 

$sql_admin = "SELECT * FROM admins"; // Select all admins from the admins table
$stmt = $ConnectingDB->query($sql_admin); // Execute the query

while ($DataRows = $stmt->fetch()) { // Loop through the results
    if ($admin == $DataRows["Username"]) { // If the admin username matches
        $admin_name = $DataRows["StaffName"]; // Set the admin name
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

    <title> Dashboard </title>
</head>

<body>

    <nav class="">
        <div class="container">
            
            <div class="navbar-brand">
                <a href="dashboard.php" id="Booking_System">Booking System</a>
            </div>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
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

    <div class="topbar">
        <div class="topbar_1">
            <a href="#" class="title">Dashboard</a>

            <?php if (!empty($_SESSION['firstlogin'])) { ?>
                <div id="welcomediv" role="alert">
                    
                    <?php echo "<a id='welcome'>Welcome back, " . $admin_name . " !!</a>" ?>
                    
                </div>
            <?php } ?>
            
        </div>
    </div>

    <div class="topbar_2">
                    <div class="col">
                        <a href="newcustomerbooking.php"><button class="btn btn-outline-light w-100">New Booking <i class="fa-solid fa-plus"></i></button></a>
                    </div>
                    <div class="col">
                        <a href="updatebooking.php"><button class="btn btn-outline-light w-100">Update Booking <i class="fa-solid fa-pen"></i></button></a>
                    </div>
                    <div class="col">
                        <a href="newcustomer.php"><button class="btn btn-outline-light w-100">New Customer <i class="fa-solid fa-person"></i></button></a>
                    </div>
                    <div class="col">
                        <a href="managecustomer.php"><button class="btn btn-outline-light w-100">Update Customer <i class="fa-solid fa-pen"></i></button></a>
                    </div>
                    <div class="col">
                        <a href="newadmin.php"><button class="btn btn-outline-light w-100">New Admin <i class="fa-solid fa-user"></i></button></a>
                    </div>
                    <div class="col">
                        <a href="manageadmin.php"><button class="btn btn-outline-light w-100">Update Admin <i class="fa-solid fa-pen"></i></button></a>
                    </div>
                    <div class="col">
                        <a href="checkavailability.php"><button class="btn btn-outline-light w-100">Check Availability <i class="fa-solid fa-table-list"></i></button></a>
                    </div>
    </div>

</body>

</html>