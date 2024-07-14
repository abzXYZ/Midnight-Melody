<?php
session_start();
require("dbconnect.php");
$sql = "SELECT `trackid` FROM `favourite_tracks` WHERE `trackid` = {$_POST['id']} AND `userid` = {$_SESSION['id']}";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $id = $result->fetch_object()->trackid;
    $sql = "DELETE FROM `favourite_tracks` WHERE `trackid` = '$id'";
} else {
    $sql = "INSERT INTO `favourite_tracks` (`trackid`, `userid`) VALUES ({$_POST['id']}, {$_SESSION['id']})";
}

require("updateFavs.php");

if ($conn->query($sql) !== TRUE) {
    echo "Error: " . $sql . "<br>" . $conn->error;
} else {
    echo "Success";
}
?>