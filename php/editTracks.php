<?php
require("dbconnect.php");
if (isset($_POST['tracks']) && is_array($_POST['tracks'])) {
    $list = "(";
    $i = 0;
    foreach ($_POST['tracks'] as $track_id) {
        $i++;
        $list .= "'" . $track_id . "'";
        if($i != sizeof($_POST['tracks'])){
            $list .= ",";
        }
    }
    $list .= ")";
    echo "$list\n";
    if($list != "()" && isset($_POST['operation'])){
        switch ($_POST['operation']) {
            case 'delete':
                $result = $conn->query("SELECT `file` FROM `tracks` WHERE `id` IN " . $list);
                while($row = $result->fetch_object()){
                    if(file_exists("." . $row->file)) {
                        unlink("." . $row->file);
                    }
                }
                $conn->query("DELETE FROM `tracks` WHERE `id` IN " . $list);
                break;
            case 'changealbum':
                if(isset($_POST['secretalbumid'])){
                    $conn->query("UPDATE `tracks` SET `albumid` = '{$_POST['secretalbumid']}' WHERE `id` IN " . $list);
                }
                break;
            default:
                break;
        }
    }

}
header("location: ../uploads.php");
?>