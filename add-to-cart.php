<?php
if (isset($_GET["id"])) {
    session_start();
    $_SESSION["books"][] = (int)$_GET["id"];
}
header("location:sell.php");