<?php
session_start();

if(isset($_SESSION['id'])){
    require("dbconnect.php");
    $result = $conn->query("SELECT * FROM `favourite_tracks` WHERE `userid` = '{$_SESSION['id']}';");
    $_SESSION['favtracks'] = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            array_push($_SESSION['favtracks'], $row->trackid);
        }
    }

    $result = $conn->query("SELECT * FROM `favourite_albums` WHERE `userid` = '{$_SESSION['id']}';");
    $_SESSION['favalbums'] = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            array_push($_SESSION['favalbums'], $row->albumid);
        }
    }
}
?>