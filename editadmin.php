<?php
ini_set('display_errors', '1');

require_once("include/db.php");
require_once("include/logout.php");

session_start();
$admin = $_SESSION['admin'];
$sql_admin = "SELECT * FROM admins WHERE Username = '$admin'";
$stmt = $ConnectingDB->query($sql_admin);

while ($DataRows = $stmt->fetch()) {
    $admin_id = $DataRows["StaffID"];
    $admin_position = $DataRows["Position"];
}

if (empty($admin)) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $_SESSION["noadmin"] = true;
    header("Location:login.php");
}

if (array_key_exists('logout', $_POST)) {
    logout();
}

$staff_id = $_GET["admin_id"];
if (empty($admin_id)) {
    header("Location:manageadmin.php");
}

$sql_staff = "SELECT * FROM admins WHERE StaffID = '$staff_id'";
$stmt = $ConnectingDB->query($sql_staff);

while ($DataRows = $stmt->fetch()) {
    if ($admin_id == $staff_id || $admin_position == "Boss") {
        $staff_name = $DataRows["StaffName"];
        $staff_position = $DataRows["Position"];
        $staff_username = $DataRows["Username"];
    } else {
        $_SESSION["noaccess"] = true;
        header("Location:manageadmin.php");

    }
}

$Success = false;
$Failed = false;

if (isset($_POST["publish"])) {
    if (!empty($_POST["admin_name"]) && !empty($_POST["username"]) && !empty($_POST["admin_position"])) {

        $sql_update = "UPDATE admins SET StaffName = :staffName, Username = :username, Position = :position WHERE StaffID = :staffID";
        $newData = array(':staffName' => $_POST["admin_name"], ':username' => $_POST["username"], ':position' => $_POST["admin_position"], ':staffID' => $admin_id);
        $stmt = $ConnectingDB->prepare($sql_update);
        $Execute = $stmt->execute($newData);

        $Success = true;
    } else {
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

    <title> Edit Admin</title>
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

    <div class="container-fluid top-heading">
        <div class="container">
            Manage Admin
        </div>
    </div>

    <form class="" action="editadmin.php?admin_id=<?php echo $admin_id ?>" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="header-div">
                <p>Edit Admin </p>
            </div>
            <div class="">
                <div id="success" class="" role="alert" style="display: none;">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                        <use xlink:href="#check-circle-fill" />
                    </svg>
                    Successfully delete admin !!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div id="failed" class="" role="alert" style="display: none;">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                        <use xlink:href="#exclamation-triangle-fill" />
                    </svg>
                    Fail to delete admin !!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <label>
                    <span class="FieldInfo">Staff ID: </span>
                </label>
                <input type="text" id="country" name="Staff_id" value="<?php echo $staff_id ?>" readonly>

                <label>
                    <span class="FieldInfo">Admin Name: </span>
                </label>
                <input type="text" name="admin_name" placeholder="Name" value="<?php echo $staff_name ?>">

                <label>
                    <span class="FieldInfo">Username: </span>
                </label>
                <input type="text" name="username" placeholder="Username" value="<?php echo $staff_username ?>">

                <label>
                    <span class="FieldInfo">Admin position: </span>
                </label>
                <?php if ($admin_position == "Boss") { ?>
                    <input class="form-control" list="admin_position" name="admin_position" value="<?php echo $staff_position ?>">
                    <datalist id="admin_position">
                        <option value="Boss">Boss</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Staff">Staff</option>
                    </datalist>
                <?php } else { ?>
                    <input class="form-control" list="admin_position" name="admin_position" value="<?php echo $staff_position ?>" readonly>
                <?php } ?>

                <div class="">
                    <div class="">
                        <button formaction="dashboard.php" class="btn btn-outline-light w-100"><i class="fas fa-arrow-left"></i>
                            Back To Dashboard</button>
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-outline-light w-100" name="publish"><i class="fas fa-check"></i>Publish</button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <br>

    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="include/main.js"></script>
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