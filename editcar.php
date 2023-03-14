<?php
ini_set('display_errors', '1');

require_once("include/db.php");
require_once("include/logout.php");

session_start();
$admin = $_SESSION['admin'];
global $ConnectingDB;

if (empty($admin)) {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $_SESSION["noadmin"] = true;
    header("Location:login.php");
}

$reservation_id = $_GET["reservation_id"]; // retrieve the value of the "car_model" input field from the POST data
if (empty($reservation_id)) {
    header("Location:dashboard.php");
}

if (array_key_exists('logout', $_POST)) {
    logout();
}

$sql_admin = "SELECT * FROM admins";
$stmt = $ConnectingDB->query($sql_admin);

while ($DataRows = $stmt->fetch()) {
    if ($admin == $DataRows["Username"]) {
        $staff_id = $DataRows["StaffID"];
    }
}

if (isset($_POST["publish"])) {
    if (empty($_POST["car_model"])) {
        header("Location: editcar.php");
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

    <title> Edit Reservation </title>
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
                        <a class="nav-link active" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blogposts.php">Check Availability</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">New Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Reservation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cancel Booking</a>
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

    <form class="" action="editbooking.php?reservation_id=<?php echo $reservation_id?>" method="post" enctype="multipart/form-data">

        <label>
            <span class="FieldInfo">Reservation ID: </span>
        </label>
        <input type="text" id="country" name="reservation_id" value="<?php echo $reservation_id?>" readonly>
        <label>
            <span class="FieldInfo">Car Model: </span>
        </label>
        <input class="form-control" list="car_model" name="car_model">
        <datalist id="car_model">
            <?php
            $sql_cars = "SELECT * FROM cars";
            $stmt = $ConnectingDB->query($sql_cars);

            while ($DataRows = $stmt->fetch()) {
                $car_id = $DataRows["CarID"];
                $car_model = $DataRows["CarModel"];
                $car_type = $DataRows["CarType"];
                $car_color = $DataRows["Color"]; ?>
                <option value=" <?php echo $car_id ?>"><?php echo $car_model ?> (<?php echo $car_color ?>) - <?php echo $car_type ?></option>
            <?php }
            ?>
        </datalist>

        <button formaction="dashboard.php" class="btn btn-outline-light w-100"><i class="fas fa-arrow-left"></i>
            Back To Dashboard</button>
        <button type="submit" class="btn btn-outline-light w-100" name="publish"><i class="fas fa-check"></i>Select</button>
    </form>

</body>

</html>