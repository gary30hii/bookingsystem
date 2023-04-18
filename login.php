<?php
ini_set('display_errors', '1');

// Require the database configuration file and start a session
require_once("include/db.php");
session_start();

$Failed = false;
$UserError = false;

// Retrieve all admin data from the 'admins' table
global $ConnectingDB;
$sql = "SELECT StaffID, Username, Password, StaffName FROM admins";
$stmt = $ConnectingDB->query($sql);

// Loop through the result set and store the data in arrays
while ($DataRows = $stmt->fetch()) {
    $id[] = $DataRows["StaffID"];
    $UserName[] = $DataRows["Username"];
    $PassWord[] = $DataRows["Password"];
    $Name[] = $DataRows["StaffName"];
}

$User = null;

// Check if a URL is set in the session, otherwise set it to 'dashboard.php'
if (isset($_SESSION['url'])) {
    $url = $_SESSION['url'];
} else {
    $url = "dashboard.php";
}

// Handle login form submission
if (isset($_POST["publish"])) {

    // Check if username and password are not empty
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {

        // Retrieve the entered username and password
        $Password = $_POST["password"];
        $Username = $_POST["username"];

        // Check if the entered username exists in the database
        $chk = $ConnectingDB->prepare("SELECT Username FROM admins WHERE Username = :checkName");
        $chk->bindParam(':checkName', $Username);
        $chk->execute();

        if ($chk->rowCount() > 0) {
            $TruePassword = "";
            // Loop through the admin data arrays to find the matching password
            for ($i = 0; $i < sizeof($id); $i++) {
                if ($UserName[$i] == $Username) {
                    $TruePassword = $PassWord[$i];
                    // Set the user variable to the admin name if available, otherwise to the username
                    if ($Name[$i] != "") {
                        $User = $Name[$i];
                    } else {
                        $User = $Username;
                    }
                }
            }
            // If the entered password matches the true password, log the user in
            if ($Password == $TruePassword) {
                $Success = true;
                $_SESSION['admin'] = $_POST["username"];
                $_SESSION['firstlogin'] = true;
                unset($_SESSION["noadmin"]);
                header("Location:" . $url);
            } else {
                $Failed = true;
            }
        } else {
            $UserError = true;
            $message = 'User not exists';
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

    <link rel="stylesheet" href="css/loginpage.css">

    <title> Log in </title>
</head>

<body>

    <form class="" action="login.php" method="post" enctype="multipart/form-data">
        <div class="login-container">
            <div class="top">
                <h2>Login</h2>
            </div>

            <div id="failed" class="alert" role="alert" style="display: none;">
                <?php
                if (empty($_POST["username"]) && empty($_POST["password"])) {
                    echo "Please enter username and password correctly.";
                } else {
                    echo "Incorrect Password.";
                }
                ?>
            </div>

            <div id="user-error" class="alert" role="alert" style="display: none;">
                <?php echo $message ?>
                
            </div>

            <div class="form-group-1">
                <label for="floatingInput">Username</label>
                <input type="text" name="username" placeholder="">
            </div>
            <div class="form-group-2">
                <label for="floatingPassword">Password</label>
                <input type="password" name="password" placeholder="">
            </div>

            <div class="button-div">
                <button type="submit" class="" name="publish">Log In</button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        var failed = "<?php echo $Failed ?>";
        var user_error = "<?php echo $UserError ?>";

        if (failed == true) {
            document.getElementById("failed").style.display = "block";
        }
        if (user_error == true) {
            document.getElementById("user-error").style.display = "block";
        }
    </script>
</body>

</html>