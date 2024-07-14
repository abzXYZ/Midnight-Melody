<?php
session_start();
require("dbconnect.php");

if(isset($_POST['text'],$_POST['albumid'],$_SESSION['id'])){
    $conn->query("INSERT INTO reviews (`userid`,`albumid`,`text`) VALUES ('{$_SESSION['id']}','{$_POST['albumid']}','{$_POST['text']}')");
}

header("location: ../index.php");

?>