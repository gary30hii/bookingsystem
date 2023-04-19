<?php

ini_set('display_errors', '1');

require_once("include/db.php");
require_once("include/logout.php");

session_start();
$admin = $_SESSION['admin'];
global $ConnectingDB;

if (empty($admin)) 
{
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
  $_SESSION["noadmin"] = true;
  header("Location:login.php");
}

if (array_key_exists('logout', $_POST))
{
  logout();
}

$events = array();

// Retrieve selected car and reservations
if (isset($_POST['carselect'])) 
{
  $selected_car = $_POST['car_model'];
  $sql = "SELECT CustomerName, PickUpDate, DropOffDate FROM customers INNER JOIN reservations ON customers.CustomerID = reservations.CustomerID WHERE reservations.CarID = '$selected_car'";
  $result = $ConnectingDB->query($sql);

  // Fetch reservations data and store in events array
  while ($DataRows = $result->fetch()) {
    $events[] = array(
      'CustomerName' => (String)$DataRows["CustomerName"],
      'PickUpDate' => $DataRows["PickUpDate"],
      'DropOffDate' => $DataRows["DropOffDate"],
    );
  }
}

?>

<script>
    var reservations = <?php echo json_encode($events, JSON_HEX_TAG); ?>; 
</script>

<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="checkavailability.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link href="css/checkavailability.css" rel="stylesheet" type="text/css">
    <script src="include/checkavailability.js" defer></script>
  </head>

  <body>
    
    <nav class="navtop">
      <div>
        <h1>Car Reservation</h1>
        
        <form method="post" enctype="multipart/form-data">
        <span>Car Model: </span>
        <input list="car_model" name="car_model">
        <datalist id="car_model">
            <?php
            $sql_cars = "SELECT * FROM cars";
            $stmt = $ConnectingDB->query($sql_cars);

            //fetch all car models and display to user
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
        </form>
        
        <h2><a href="dashboard.php"><button>Return to Dashboard</button></a></h2>
      </div>
    </nav>
       
     <div class="wrapper">
      <header>
      
        <p class="current-date"></p>

        <div class = "car-name">
        <?php
          $car_name = array("Rolls Royce Phantom","Bentley Continental Flying Spur","Mercedes Benz CLS 350","Jaguar S Type","	
          Ferrari F430 Scuderia","Lamborghini Murcielago LP640","Porsche Boxster","Lexus SC430", "Jaguar MK 2","Rolls Royce Silver Spirit Limousine",
          "MG TD");

          if (isset($_POST['carselect'])) 
          {
            $selected_car = $_POST['car_model'];
            if (is_numeric($selected_car)) {
              $selected_car = (int)$selected_car - 1;
              if (array_key_exists($selected_car, $car_name))
              {
                $carname = $car_name[$selected_car];
                echo "<p class='carname'>" . $carname . "</p>"; 
              }
            }
          }
        ?> 
        </div>

        <div class="icons">
          <span id="prev" class="material-symbols-rounded">chevron_left</span>
          <span id="next" class="material-symbols-rounded">chevron_right</span>
        </div>
      </header>
     
      <div class="calendar">
        <ul class="weeks">
          <li>Sun</li>
          <li>Mon</li>
          <li>Tue</li>
          <li>Wed</li>
          <li>Thu</li>
          <li>Fri</li>
          <li>Sat</li>
        </ul>
        <ul class="days"></ul>
      </div>
    </div>
    
  </body>
</html>