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

$sql = "SELECT * FROM reservations";
$result = $ConnectingDB->query($sql);

$events = array();

while($DataRows = $result->fetch())
{
  $events[] = array
  (
    'CarID' => $DataRows["CarID"],
    'PickUpDate' => $DataRows["PickUpDate"],
    'DropOffDate'=> $DataRows["DropOffDate"],
  );
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
    <link href="checkavailability.css" rel="stylesheet" type="text/css">
    <script src="checkavailability.js" defer></script>
  </head>

  <body>
    
    <nav class="navtop">
      <div>
        <h1>Car Reservation</h1>
        <h2><a href="dashboard.php"><button>Return to Dashboard</button></a><h2>
      </div>
      
    </nav>
        
     <div class="wrapper">
      <header>
       
        <p class="current-date"></p>
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