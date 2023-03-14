<?php
    function logout(){
        unset($_SESSION["admin"]);
        unset($_SESSION["noadmin"]);
        unset($_SESSION["url"]);
        header("Location:login.php");
    }
?>