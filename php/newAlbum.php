<?php
session_start();
if(isset($_POST['name'],$_POST['desc'],$_SESSION['id'])){
    require("dbconnect.php");
    if(isset($_POST['id']) && $_POST['id'] != ""){
        $sql = "UPDATE `albums` SET `name` = '{$_POST['name']}', `description` = '{$_POST['desc']}'";
        if(isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE){
            $result = $conn->query("SELECT image FROM albums WHERE id = {$_POST['id']}");
            $success = false;
            if($result->num_rows == 1) {
                $row = $result->fetch_object();
                if($row->image != ""){
                    if (file_exists("." . $row->image)) {
                        if (unlink("." . $row->image)) {
                            $success = true;
                        }
                    }
                } else {
                    $success = true;
                }
            }
            if($success){
                $accepted_types = array("image/png","image/jpg","image/jpeg","image/webp");
                if(in_array(mime_content_type($_FILES['file']['tmp_name']),$accepted_types) && $_FILES['file']['size'] <= 10 * 1024 * 1024){
                    do {
                        $target_file = "./audio/thumbnails/" . $hexString = bin2hex(random_bytes(5)) . substr($_FILES['file']['name'], -4);
                    } while(file_exists("." . $target_file));
                    if(move_uploaded_file($_FILES['file']['tmp_name'], "." . $target_file)){
                        $sql .= ", `image` = '" . $target_file . "'";
                    }
                }
            }   
        }
        $sql .= " WHERE `id` = {$_POST['id']}";
    } else {
        $sql = "INSERT INTO `albums` (`name`,`description`,`ownerid`,`image`) VALUES ('{$_POST['name']}','{$_POST['desc']}','{$_SESSION['id']}','";
        if(isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE){
            $accepted_types = array("image/png","image/jpg","image/jpeg","image/webp");
            if(in_array(mime_content_type($_FILES['file']['tmp_name']),$accepted_types) && $_FILES['file']['size'] <= 10 * 1024 * 1024){
                do {
                    $target_file = "./audio/thumbnails/" . $hexString = bin2hex(random_bytes(5)) . substr($_FILES['file']['name'], -4);
                } while(file_exists("." . $target_file));
                if(move_uploaded_file($_FILES['file']['tmp_name'], "." . $target_file)){
                    $sql .= $target_file;
                }
            }   
        }
        $sql .= "');";
    }
    $conn->query($sql);
}
header("location: ../uploads.php");
?>