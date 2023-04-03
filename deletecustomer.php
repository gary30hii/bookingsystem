<?php
ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php");
require_once("include/main.php");
require_once("include/checkcustomerid.php");

$Success = false;
$Failed = false;

// query the database to retrieve the customer data using the customer ID
$sql_customers = "SELECT * FROM customers WHERE CustomerID = '$customer_id'";
$stmt = $ConnectingDB->query($sql_customers);

// loop through the results and assign the values to variables
while ($DataRows = $stmt->fetch()) {
    $customer_name = $DataRows["CustomerName"];
    $customer_email =  $DataRows["CustomerEmail"];
    $customer_no =  $DataRows["CustomerPhoneNumber"];
}

// check if the delete button was clicked
if (isset($_POST["submit"])) {
    // delete the customer record from the database
    $sql_delete = "DELETE FROM customers WHERE CustomerID = '$customer_id'";
    $Execute = $ConnectingDB->query($sql_delete);
    // set the success flag to true
    $Success = true;
}


?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> Delete Customer </title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="managecustomer.php">Manage Customer</a>
                    </li>
                    <li class="nav-item">
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

    <form class="" action="deletecustomer.php?customer_id=<?php echo $customer_id ?>" method="post" enctype="multipart/form-data">
        <div id="success" class="" role="alert" style="display: none;">
            Successfully delete customer!!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div id="failed" class="" role="alert" style="display: none;">
            Fail to delete customer!!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <label>
            <span class="FieldInfo">Customer ID: </span>
        </label>

        <input type="text" id="country" name="customer_id" value="<?php echo $customer_id ?>" readonly>
        <label>
            <span class=" FieldInfo">Customer Name: </span>
        </label>
        <input type="text" name="customer_name" placeholder="Name" value="<?php echo $customer_name ?>" readonly>

        <label>
            <span class=" FieldInfo">Customer Email: </span>
        </label>
        <input type="text" name="customer_email" placeholder="Email" value="<?php echo $customer_email ?>" readonly>

        <label>
            <span class="FieldInfo">Customer Phone: </span>
        </label>
        <input type="text" name="customer_phone_no" placeholder="Phone Number" value="<?php echo $customer_no ?>" readonly>

        <button class="" name="submit"><i class="fas fa-check"></i>submit</button>

    </form>

    <a href="managecustomer.php"><button class=""><i class="fas fa-arrow-left"></i>
            Back</button></a>

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