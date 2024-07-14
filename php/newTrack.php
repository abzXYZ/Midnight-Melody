<?php
session_start();
if(isset($_POST['name'],$_POST['authors'],$_POST['albumid'],$_POST['position'],$_SESSION['id'])){
    require("dbconnect.php");
    if(isset($_POST['id']) && $_POST['id'] != ""){
        $conn->query("UPDATE tracks SET `name` = '{$_POST['name']}', `authors` = '{$_POST['authors']}', `albumid` = '{$_POST['albumid']}', `position` = '{$_POST['position']}' WHERE `id` = {$_POST['id']}");
    } else if(isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE){
        if(mime_content_type($_FILES['file']['tmp_name']) == "audio/mpeg" && $_FILES['file']['size'] <= 50 * 1024 * 1024){
            do {
                $target_file = "./audio/" . $_POST['albumid'] . '-' . $hexString = bin2hex(random_bytes(5)) . ".mp3";
            } while(file_exists("." . $target_file));
            if(move_uploaded_file($_FILES['file']['tmp_name'], "." . $target_file)){
                $conn->query("INSERT INTO `tracks` (`name`,`file`,`ownerid`,`authors`,`albumid`,`position`) VALUES ('{$_POST['name']}', '$target_file', '{$_SESSION['id']}', '{$_POST['authors']}','{$_POST['albumid']}','{$_POST['position']}')");
            }
        }
    }
}
header("location: ../uploads.php");
?>