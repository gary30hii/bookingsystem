<?php
ini_set('display_errors', '1');

require_once("include/db.php");
session_start();

$Failed = false;
$UserError = false;

global $ConnectingDB;
$sql = "SELECT StaffID, Username, Password, StaffName FROM admins";
$stmt = $ConnectingDB->query($sql);

while ($DataRows = $stmt->fetch()) {
    $id[] = $DataRows["StaffID"];
    $UserName[] = $DataRows["Username"];
    $PassWord[] = $DataRows["Password"];
    $Name[] = $DataRows["StaffName"];
}

$User = null;

if (isset($_SESSION['url'])) {
    $url = $_SESSION['url'];
} else {
    $url = "dashboard.php";
}

if (isset($_POST["publish"])) {

    if (!empty($_POST["username"]) && !empty($_POST["password"])) {

        $Password = $_POST["password"];
        $Username = $_POST["username"];

        $chk = $ConnectingDB->prepare("SELECT Username FROM admins WHERE Username = :checkName");
        $chk->bindParam(':checkName', $Username);
        $chk->execute();

        if ($chk->rowCount() > 0) {
            $TruePassword = "";
            for ($i = 0; $i < sizeof($id); $i++) {
                if ($UserName[$i] == $Username) {
                    $TruePassword = $PassWord[$i];
                    if ($Name[$i] != "") {
                        $User = $Name[$i];
                    } else {
                        $User = $Username;
                    }
                }
            }
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

    <title> Log in </title>
</head>

<body>



    <form class="" action="login.php" method="post" enctype="multipart/form-data">
        <div class="">
            <div class="">
                <p>Login</p>
            </div>

            <div id="failed" class="alert alert-warning alert-dismissible fade show" role="alert" style="display: none;">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <?php
                if (empty($_POST["username"]) && empty($_POST["password"])) {
                    echo "Please enter username and password correctly.";
                } else {
                    echo "Incorrect Password.";
                }
                ?>
            </div>

            <div id="user-error" class="alert alert-warning alert-dismissible fade show" role="alert" style="display: none;">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <?php echo $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="">
                <label for="floatingInput">Username</label>
                <input type="text" name="username" class="form-control" id="floatingInput" placeholder="">
            </div>
            <div class="">
                <label for="floatingPassword">Password</label>
                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="">
            </div>

            <div class="">
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