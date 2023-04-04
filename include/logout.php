<?php
    function logout(){
        unset($_SESSION["admin"]);
        unset($_SESSION["noadmin"]);
        unset($_SESSION["url"]);
        unset($_SESSION["noaccess"]);
        header("Location:login.php");
    }

?>
