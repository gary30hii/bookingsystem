<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/main.php");

session_start(); // starts a session or resumes the current one based on a session identifier passed via a GET or POST request

// retrieves the value of the 'admin' session variable
$admin = $_SESSION['admin'];

// SQL query to select all data from the 'admins' table where the 'Username' column matches the 'admin' session variable
$sql_admin = "SELECT * FROM admins WHERE Username = '$admin'";

// performs the query on the database using the database connection object
$stmt = $ConnectingDB->query($sql_admin);

// loops through the result set returned from the query
while ($DataRows = $stmt->fetch()) {
    // assigns the value of the 'StaffID' column of the current row to the '$admin_id' variable
    $admin_id = $DataRows["StaffID"];
    // assigns the value of the 'Position' column of the current row to the '$admin_position' variable
    $admin_position = $DataRows["Position"];
}

// retrieves the value of the 'admin_id' parameter from the URL using the GET method and assigns it to the '$staff_id' variable
$staff_id = $_GET["admin_id"];

// checks if the '$admin_id' variable is empty
if (empty($admin_id)) {
    // redirects the user to the 'manageadmin.php' page
    header("Location:manageadmin.php");
}

// SQL query to select all data from the 'admins' table where the 'StaffID' column matches the '$staff_id' variable
$sql_staff = "SELECT * FROM admins WHERE StaffID = '$staff_id'";

// performs the query on the database using the database connection object
$stmt = $ConnectingDB->query($sql_staff);

// loops through the result set returned from the query
while ($DataRows = $stmt->fetch()) {
    // checks if the value of the '$admin_position' variable is 'Boss'
    if ($admin_position == "Boss") {
        // assigns the value of the 'StaffName' column of the current row to the '$staff_name' variable
        $staff_name = $DataRows["StaffName"];
        // assigns the value of the 'Position' column of the current row to the '$staff_position' variable
        $staff_position = $DataRows["Position"];
        // assigns the value of the 'Username' column of the current row to the '$staff_username' variable
        $staff_username = $DataRows["Username"];
    } else {
        // sets the 'noaccess' session variable to 'true'
        $_SESSION["noaccess"] = true;
        // redirects the user to the 'manageadmin.php' page
        header("Location:manageadmin.php");
    }
}

$Success = false; 
$Failed = false; 

if (isset($_POST["publish"])) { // check if the 'publish' button was clicked
    $sql_delete = "DELETE FROM admins WHERE StaffID = '$staff_id'"; // SQL query to delete data from the 'admins' table where the 'StaffID' column matches the '$staff_id' variable
    $Execute = $ConnectingDB->query($sql_delete); // execute the query on the database using the database connection object
    $Success = true; // set $Success variable to true indicating that the delete operation was successful
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

    <title> Delete Admin </title>
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
            <p class="title">Manage Admin</p>
        </div>
    </div>

    <div class="main">

        <form class="" action="deleteadmin.php?admin_id=<?php echo $staff_id ?>" method="post" enctype="multipart/form-data">
            <div class="container">
                <div class="">
                    <div id="success" class="" role="alert" style="display: none;">
                        Successfully delete admin!!
                        
                    </div>

                    <div id="failed" class="" role="alert" style="display: none;">
                        Fail to delete admin!!
                        
                    </div>

                    <label>
                        <span class="FieldInfo">Staff ID: </span>
                    </label>
                    <input type="text" id="country" name="Staff_id" value="<?php echo $staff_id ?>" readonly>

                    <label>
                        <span class="FieldInfo">Admin Name: </span>
                    </label>
                    <input type="text" name="admin_name" placeholder="Name" value="<?php echo $staff_name ?>" readonly>

                    <label>
                        <span class="FieldInfo">Username: </span>
                    </label>
                    <input type="text" name="username" placeholder="Username" value="<?php echo $staff_username ?>" readonly>

                    <label>
                        <span class="FieldInfo">Admin position: </span>
                    </label>

                    <input class="form-control" list="admin_position" name="admin_position" value="<?php echo $staff_position ?>" readonly>

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