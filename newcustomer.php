<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/main.php");

$Success = false;
$Failed = false;

// Check if form has been submitted using HTTP POST method
if (isset($_POST["publish"])) {

    // Check if required fields are not empty
    if (!empty($_POST["customer_name"]) && !empty($_POST["customer_email"]) && !empty($_POST["customer_phone_no"])) {

        // Assign form data to variables
        $customer_name = $_POST["customer_name"];
        $customer_email = $_POST["customer_email"];
        $customer_phone_no = $_POST["customer_phone_no"];

        // Construct SQL query to insert data into database using prepared statements
        $sql = "INSERT INTO customers (CustomerName, CustomerEmail, CustomerPhoneNumber)";
        $sql .= "VALUES(:customerName, :customerEmail, :customerPhoneNumber)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(':customerName', $customer_name);
        $stmt->bindValue(':customerEmail', $customer_email);
        $stmt->bindValue(':customerPhoneNumber', $customer_phone_no);

        // Execute prepared statement to insert data into database
        $Execute = $stmt->execute();

        // Set success variable to true if data is successfully inserted
        $Success = true;
    } else {
        // Set failed variable to true if any required fields are empty
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

    <title> Add New Customer</title>
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
                    <li class="nav-item active">
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
            <p class="title">Manage Customer</p>
        </div>
    </div>


    <div class="main">
        <form class="" action="newcustomer.php" method="post" enctype="multipart/form-data">
            <div class="container">
                <div class="header">
                    <p>Add New Customer </p>
                </div>
                <div class="">
                    <div id="success" class="" role="alert" style="display: none;">
                        Successfully add new customer !!
                        
                    </div>

                    <div id="failed" class="" role="alert" style="display: none;">
                        Fail to add new customer !!
                        
                    </div>

                    <label>
                        <span class="FieldInfo">Customer Name: </span>
                    </label>
                    <input type="text" name="customer_name" placeholder="Name">

                    <label>
                        <span class="FieldInfo">Customer Email: </span>
                    </label>
                    <input type="email" id="email-input" name="customer_email" placeholder="Email">

                    <label>
                        <span class="FieldInfo">Customer Phone: (10-11 digit)</span>
                    </label>
                    <input type="tel" id="phone-input" name="customer_phone_no" placeholder="10-11 digit Phone Number" minlength="10" maxlength="11"  pattern="[0-9]+" required>

                    <button type="submit" class="btn btn-outline-light w-100" name="publish"><i class="fas fa-check"></i>Publish</button>

                </div>
            </div>
        </form>
    
        <div id="newreservation" style="display:none">
            <a href="selectcar.php"><button class=""><i class="fas fa-arrow-left"></i>
                    New reservation</button></a>
        </div>
        <a href="managecustomer.php"><button class=""><i class="fas fa-arrow-left"></i>
                Back to customers list</button></a>

    </div>

    <script type="text/javascript">
        var success = "<?php echo $Success ?>";
        var failed = "<?php echo $Failed ?>";

        if (success == true) {
            document.getElementById("success").style.display = "block";
            document.getElementById("newreservation").style.display = "block";
        }
        if (failed == true) {
            document.getElementById("failed").style.display = "block";
        }
    </script>

</body>

</html>