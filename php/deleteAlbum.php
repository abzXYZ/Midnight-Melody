<?php
session_start();
require("dbconnect.php");
if (isset($_POST['id'],$_SESSION['id']) && $_POST['id'] != "") {
    $conn->query("DELETE FROM `albums` WHERE `id` = '{$_POST['id']}' AND `ownerid` = '{$_SESSION['id']}'");
}
header("location: ../uploads.php");
?>