<?php
session_start();
require("dbconnect.php");
if(isset($_POST['pleaseremove'],$_SESSION['id'])){
    $conn->query("DELETE FROM `users` WHERE `id` = {$_SESSION['id']}");
    session_destroy();
    header("location: ../account.php?success=2");
} else if (isset($_POST['username'],$_POST['email'],$_SESSION['id'])){
    $login = htmlspecialchars(strip_tags($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(strip_tags($_POST['email']), FILTER_SANITIZE_EMAIL);
    $result = $conn->query("SELECT id FROM users WHERE name = '$login' OR email = '$email'");
    if($result->num_rows == 0 || $result->num_rows == 1 && $result->fetch_assoc()['id'] == $_SESSION['id']){
        $sql = "UPDATE users SET name='{$login}', email='{$email}'";
        if(isset($_POST['pfpurl'])){
            $sql .= ", pfp='{$_POST['pfpurl']}'";
            if($_POST['pfpurl'] == ""){
                unset($_SESSION['pfp']);
            } else {
                $_SESSION['pfp'] = $_POST['pfpurl'];
            }
        }
        if(isset($_POST['pswd'],$_POST['repswd'])){
            if($_POST['pswd'] != "" && $_POST['pswd'] == $_POST['repswd']){
                $password = hash('whirlpool',htmlspecialchars(strip_tags($_POST['pswd']), ENT_QUOTES, 'UTF-8'));
                $sql .= ", password='{$password}'";
            }
        }
        $sql .= " WHERE id={$_SESSION['id']};";
        $conn->query($sql);
    }
    header("location: ../account.php");
}
?>