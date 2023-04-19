<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/main.php");

// start a session to keep track of the user's login status
session_start();

// retrieve the username of the current admin from the session
$admin = $_SESSION['admin'];

// query the database to retrieve the details of the current admin
$sql_admin = "SELECT * FROM admins WHERE Username = '$admin'";
$stmt = $ConnectingDB->query($sql_admin);

// extract the staff ID and position of the current admin from the database results
while ($DataRows = $stmt->fetch()) {
    $admin_id = $DataRows["StaffID"];
    $admin_position = $DataRows["Position"];
}

// retrieve the staff ID of the admin to be managed from the URL parameters
$staff_id = $_GET["admin_id"];

// if the staff ID is not provided, redirect the user to the manageadmin page
if (empty($_GET["admin_id"])) {
    header("Location:manageadmin.php");
}

// query the database to retrieve the details of the admin to be managed
$sql_staff = "SELECT * FROM admins WHERE StaffID = '$staff_id'";
$stmt = $ConnectingDB->query($sql_staff);

// extract the details of the admin to be managed from the database results
while ($DataRows = $stmt->fetch()) {

    // check if the current admin has permission to manage the admin based on their position
    if ($admin_id == $staff_id || $admin_position == "Boss") {
        $staff_name = $DataRows["StaffName"];
        $staff_position = $DataRows["Position"];
        $staff_username = $DataRows["Username"];
    } else {
        // if the current admin does not have permission to manage the admin, redirect them to the manageadmin page
        $_SESSION["noaccess"] = true;
        header("Location:manageadmin.php");
    }
}

// initialize variables to track the success or failure of the update operation
$Success = false;
$Failed = false;

// check if the form was submitted
if (isset($_POST["publish"])) {

    // check if all required fields have been filled in
    if (!empty($_POST["admin_name"]) && !empty($_POST["username"]) && !empty($_POST["admin_position"])) {

        // prepare an SQL statement to update the admin's details in the database
        $sql_update = "UPDATE admins SET StaffName = :staffName, Username = :username, Position = :position WHERE StaffID = :staffID";
        $newData = array(':staffName' => $_POST["admin_name"], ':username' => $_POST["username"], ':position' => $_POST["admin_position"], ':staffID' => $staff_id);
        $stmt = $ConnectingDB->prepare($sql_update);

        // execute the SQL statement with the new data
        $Execute = $stmt->execute($newData);

        // set the Success variable to true to indicate that the update was successful
        $Success = true;

    } else {
        // set the Failed variable to true to indicate that the update failed due to missing fields
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

    <title> Edit Admin</title>
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


    <div class="topbar">
        <div class="topbar_1">
            <p class="title">
                Manage Admin
            </p>
        </div>
    </div>

    <div class="main">

        <form class="" action="editadmin.php?admin_id=<?php echo $staff_id ?>" method="post" enctype="multipart/form-data">
            <div class="container">
                <div class="header">
                    <p>Edit Admin </p>
                </div>
                <div class="">
                    <div id="success" class="" role="alert" style="display: none;">
                        Successfully edit admin infomation
                        
                    </div>

                    <div id="failed" class="" role="alert" style="display: none;">
                        Fail to delete admin !!
                        
                    </div>

                    <label>
                        <span class="FieldInfo">Staff ID: </span>
                    </label>
                    <input type="text" id="country" name="Staff_id" value="<?php echo $staff_id ?>" readonly required>

                    <label>
                        <span class="FieldInfo">Admin Name: </span>
                    </label>
                    <input type="text" name="admin_name" placeholder="Name" value="<?php echo $staff_name ?>" required>

                    <label>
                        <span class="FieldInfo">Username: </span>
                    </label>
                    <input type="text" name="username" placeholder="Username" value="<?php echo $staff_username ?>" required>

                    <label>
                        <span class="FieldInfo">Admin position: </span>
                    </label>
                    <?php if ($admin_position == "Boss") { ?>
                        <input class="form-control" list="admin_position" name="admin_position" value="<?php echo $staff_position ?>" required>
                        <datalist id="admin_position">
                            <option value="Boss">Boss</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Staff">Staff</option>
                        </datalist>
                    <?php } else { ?>
                        <input class="form-control" list="admin_position" name="admin_position" value="<?php echo $staff_position ?>" readonly>
                    <?php } ?>

                    <div class="">
                        <button type="submit" class="btn btn-outline-light w-100" name="publish"><i class="fas fa-check"></i>Publish</button>
                    </div>

                </div>
            </div>
        </form>

        <a href="manageadmin.php"><button class=""><i class="fas fa-arrow-left"></i>
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
    
</body>

</html>