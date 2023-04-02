<?php

ini_set('display_errors', '1');

// Include necessary files
require_once("include/db.php");
require_once("include/logout.php");
require_once("include/checkadmin.php"); 

if (array_key_exists('logout', $_POST))
{
  logout();
}

?>

<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="checkavailability.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link href="checkavailability.css" rel="stylesheet" type="text/css">
  </head>

  <body>

  <nav class="navtop">
  <div>
    <h1>Car Reservation</h1>
    <h2><a href="dashboard.php"><button>Return to Dashboard</button></a><h2>
  </div>
  </nav>
  
    <form class="" action="calendar.php" method="post" enctype="multipart/form-data">
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

        <button type="submit" name="carselect">Select</button>
    
  </body>
</html>
    
