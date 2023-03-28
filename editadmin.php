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

$admin_id = $_GET["admin_id"];
if (empty($admin_id)) {
    header("Location:manageadmin.php");
}

$sql_admin = "SELECT * FROM admins WHERE StaffID = '$admin_id'";
$stmt = $ConnectingDB->query($sql_admin);

while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        $admin_name = $DataRows["StaffName"];
        $admin_position = $DataRows["Position"];
        $staff_id = $DataRows["StaffID"];
    }
    if (!($staff_id == $admin_id)) {
        $_SESSION["noaccess"] = true;
        header("Location:manageadmin.php");
    }
}

$Success = false;
$Failed = false;

if (isset($_POST["publish"])) {
    if (!empty($_POST["admin_name"]) && !empty($_POST["username"]) && !empty($_POST["admin_position"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"])) {

        if ($_POST["password"] == $_POST["confirm_password"]) {

            $sql_update = "UPDATE admins SET StaffName = :staffName, Username = :username, Position = :position, Password = :password WHERE StaffID = :staffID";
            $newData = array(':staffName' => $_POST["admin_name"], ':username' => $_POST["username"], ':position' => $_POST["admin_position"], ':password' => $_POST["password"], ':staffID' => $admin_id);
            $stmt = $ConnectingDB->prepare($sql_update);
            $Execute = $stmt->execute($newData);

            $Success = true;
        } else {
            $Failed = true;
        }
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
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="include/fontawesome-5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>

    <title> Edit Admin's detail</title>
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
                    Successfully edit admin's detail !!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <div id="failed" class="" role="alert" style="display: none;">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                        <use xlink:href="#exclamation-triangle-fill" />
                    </svg>
                    Fail to edit admin's detail !!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <label>
                    <span class="FieldInfo">Admin Name: </span>
                </label>
                <input type="text" name="admin_name" placeholder="Name">

                <label>
                    <span class="FieldInfo">Username: </span>
                </label>
                <input type="text" name="username" placeholder="Username">

                <label>
                    <span class="FieldInfo">Admin position: </span>
                </label>
                <input class="form-control" list="admin_position" name="admin_position">
                <datalist id="admin_position">
                    <option value="Boss">Boss</option>
                    <option value="Supervisor">Supervisor</option>
                    <option value="Staff">Staff</option>
                </datalist>

                <label>
                    <span class="FieldInfo">Password: </span>
                </label>
                <input type="password" name="password" placeholder="Password">

                <label>
                    <span class="FieldInfo">Confirm Password: </span>
                </label>
                <input type="password" name="confirm_password" placeholder="Password">

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