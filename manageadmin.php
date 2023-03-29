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
                    <li class="nav-item">
                        <a class="nav-link" href="updatebooking.php">Edit Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="updatebooking.php">Cancel Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="managecustomer.php">Manage Customer</a>
                    </li>
                    <li class="nav-item active">
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

    <form action="manageadmin.php" method="get">
        <input type="text" name="username" placeholder="Enter admin username...">
        <button type="submit">Search</button>
    </form>

    <?php if ($admin_position == "Boss") { ?>
        <a href="newadmin.php"><button class="btn btn-outline-light w-100"> New Admin </button></a>
    <?php } ?>

    <?php if (isset($_SESSION['noaccess']) && $_SESSION['noaccess'] == true) { ?>
        <div id="success" class="alert alert-success alert-dismissible fade show" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                <use xlink:href="#check-circle-fill" />
            </svg>
            You do not have access to the page...
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>

    <?php

    if (isset($_GET['username'])) {
        $Name =  $_GET['username'];
    } else {
        $Name = "";
    }
    $Name = "%" . $Name . "%";
    $sql = "SELECT * 
        FROM admins
        WHERE Username LIKE '$Name'
        ";
    $cus_stmt = $ConnectingDB->query($sql);

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
                                <a href="editadmin.php?admin_id=<?php echo $DataRows["StaffID"] ?>"><button type="submit" class="btn btn-outline-light w-100"> Update </button></a>
                            </td>
                            <td>
                                <a href="deleteadmin.php?admin_id=<?php echo $DataRows["StaffID"] ?>"><button type="submit" class="btn btn-outline-light w-100"> Delete </button></a>
                            </td>
                            <td>
                                <a href="changepassword.php?admin_id=<?php echo $DataRows["StaffID"] ?>"><button type="submit" class="btn btn-outline-light w-100"> Change Password </button></a>
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