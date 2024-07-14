<?php
session_start();
if(isset($_POST['name'],$_POST['color'],$_SESSION['id'])){
    $public = 0;
    if(isset($_POST['public'])){
        $public = 1;
    }
    require("dbconnect.php");
    $sql = "INSERT INTO `mixtapes` (`name`, `ownerid`, `color`, `public`) VALUES ('{$_POST['name']}', '{$_SESSION['id']}', '{$_POST['color']}', '$public');";
    $conn->query($sql);
}
header("location: ../mixtapes.php");
?>