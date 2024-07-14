<?php
session_start();
require("dbconnect.php");
$sql = "SELECT `trackid` FROM `mixtape_tracks` WHERE `trackid` = {$_POST['trackid']} AND `mixtapeid` = {$_POST['mixtapeid']}";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $id = $result->fetch_object()->trackid;
    $sql = "DELETE FROM `mixtape_tracks` WHERE `trackid` = '$id'";
} else {
    $sql = "INSERT INTO `mixtape_tracks` (`trackid`, `mixtapeid`) VALUES ({$_POST['trackid']}, {$_POST['mixtapeid']})";
}

if ($conn->query($sql) !== TRUE) {
    echo "Error: " . $sql . "<br>" . $conn->error;
} else {
    echo "Success";
}
?>