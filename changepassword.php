<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/main.php");

// This code block retrieves the admin ID and position of the currently logged-in admin from the session
$admin = $_SESSION['admin'];
$sql_admin = "SELECT * FROM admins WHERE Username = '$admin'";
$stmt = $ConnectingDB->query($sql_admin);

while ($DataRows = $stmt->fetch()) {
    $admin_id = $DataRows["StaffID"];
    $admin_position = $DataRows["Position"];
}

// The staff ID is obtained from the GET parameter 'admin_id'
$staff_id = $_GET["admin_id"];

// If the admin ID is empty, redirect to the manageadmin.php page
if (empty($admin_id)) {
    header("Location:manageadmin.php");
}

// This code block retrieves the staff details from the database based on the staff ID obtained from the GET parameter 'admin_id'
$sql_staff = "SELECT * FROM admins WHERE StaffID = '$staff_id'";
$stmt = $ConnectingDB->query($sql_staff);

while ($DataRows = $stmt->fetch()) {
    // If the staff ID matches the admin ID, retrieve the staff password and username
    if ($admin_id == $staff_id) {
        $staff_password = $DataRows["Password"];
        $staff_username = $DataRows["Username"];
    } else {
        // If the staff ID does not match the admin ID, set a session variable to indicate that the user does not have access and redirect to the manageadmin.php page
        $_SESSION["noaccess"] = true;
        header("Location:manageadmin.php");
    }
}

// Initialize variables for success and failure messages
$Success = false;
$Failed = false;

// Check if the form was submitted
if (isset($_POST["publish"])) {
    // Check if all the required fields are filled
    if (!empty($_POST["old_password"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"])) {

        // Check if the new password matches the confirm password and the old password matches the stored password
        if ($_POST["password"] == $_POST["confirm_password"] && $_POST["old_password"] == $staff_password) {

            // Update the admin's password in the database
            $sql_update = "UPDATE admins SET Password = :password WHERE StaffID = :staffID";
            $newData = array(':password' => $_POST["password"], ':staffID' => $staff_id);
            $stmt = $ConnectingDB->prepare($sql_update);
            $Execute = $stmt->execute($newData);

            // Set the success flag to true
            $Success = true;
        } else {
            // If the new password and confirm password do not match or the old password does not match the stored password, set the failure flag to true
            $Failed = true;
        }
    } else {
        // If any of the required fields are empty, set the failure flag to true
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

    <title> Change password </title>
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
                    <li class="nav-item active">
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
            <p class="title">Manage Admin</p>
        </div>
    </div>

    <div class="main">

        <form class="" action="changepassword.php?admin_id=<?php echo $admin_id ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="container">
                <div class="header">
                    <p>Edit Admin </p>
                </div>
                <div class="">
                    <div id="success" class="" role="alert" style="display: none;">
                        Successfully edit password !!  
                    </div>

                    <div id="failed" class="" role="alert" style="display: none;">
                        Fail to edit password, make sure you entered a correct old password.
                    </div>

                    <label>
                        <span class="FieldInfo">Staff ID: </span>
                    </label>
                    <input type="text" id="country" name="Staff_id" value="<?php echo $staff_id ?>" readonly>

                    <label>
                        <span class="FieldInfo">Staff Username: </span>
                    </label>
                    <input type="text" id="country" name="Username" value="<?php echo $staff_username ?>" readonly>

                    <label>
                        <span class="FieldInfo">Old Password: </span>
                    </label>
                    <input type="password" name="old_password" placeholder="Password">

                    <label>
                        <span class="FieldInfo">New Password: </span>
                    </label>
                    <input type="password" id="password" name="password" placeholder="Password">

                    <label>
                        <span class="FieldInfo">Confirm Password: </span>
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Password">

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
    <script src="include/newpassword.js"></script>

</body>

</html>