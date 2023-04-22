<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/main.php");
session_start();

// Construct the SQL query to select all rows from the "admins" table
$sql_admin = "SELECT * FROM admins";

// Execute the SQL query and store the result in the $stmt variable
$stmt = $ConnectingDB->query($sql_admin);

// Iterate through each row of the result set
while ($DataRows = $stmt->fetch()) {
    // Check if the current row's "Username" column matches the $admin variable
    if ($_SESSION['admin'] == $DataRows["Username"]) {
        // If there is a match, store the admin's name and position in variables
        $admin_name = $DataRows["StaffName"];
        $admin_position = $DataRows["Position"];
    }

    // Check if the admin's position is not "Boss"
    if ($admin_position != "Boss") {
        // If the admin's position is not "Boss", set a session variable to true and redirect the user
        $_SESSION["noaccess"] = true;
        header("Location: manageadmin.php");
    }
}


$Success = false; // Set initial value for success flag
$Failed = false; // Set initial value for failed flag

if (isset($_POST["publish"])) { // Check if form has been submitted
    if (!empty($_POST["admin_name"]) && !empty($_POST["username"]) && !empty($_POST["admin_position"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"])) { // Check if all required fields are not empty

        if ($_POST["password"] == $_POST["confirm_password"]) { // Check if password and confirm password fields match
            $sql = "INSERT INTO admins (StaffName, Username, Position, Password)"; // Define SQL query for inserting data into database
            $sql .= "VALUES(:staffName, :username, :position, :password)"; // Add values for SQL query
            $stmt = $ConnectingDB->prepare($sql); // Prepare SQL statement
            $stmt->bindValue(':staffName', $_POST["admin_name"]); // Bind parameter values to the prepared statement
            $stmt->bindValue(':username', $_POST["username"]);
            $stmt->bindValue(':position', $_POST["admin_position"]);
            $stmt->bindValue(':password', $_POST["password"]);

            $Execute = $stmt->execute(); // Execute the prepared statement

            $Success = true; // Set success flag to true
        } else {
            $Failed = true; // Set failed flag to true
        }
    } else {
        $Failed = true; // Set failed flag to true
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

    <title> Add New Admin</title>
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

        <form class="" action="newadmin.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="container">
                <div class="header">
                    <p>Add New Admin </p>
                </div>
                <div class="">
                    <div id="success" class="" role="alert" style="display: none;">
                        Successfully add new admin !!
                        
                    </div>

                    <div id="failed" class="" role="alert" style="display: none;">
                        Fail to add new admin !!
                        
                    </div>

                    <label>
                        <span class="FieldInfo">Admin Name: </span>
                    </label>
                    <input type="text" name="admin_name" placeholder="Name" required>

                    <label>
                        <span class="FieldInfo">Username: </span>
                    </label>
                    <input type="text" name="username" placeholder="Username" required>

                    <label>
                        <span class="FieldInfo">Admin position: </span>
                    </label>
                    <input class="form-control" list="admin_position" name="admin_position" required>
                    <datalist id="admin_position">
                        <option value="Boss">Boss</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Staff">Staff</option>
                    </datalist>

                    <label>
                        <span class="FieldInfo">Password: </span>
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