<?php
session_start();
if(isset($_POST['email'],$_POST['pswd'])){
    require("dbconnect.php");
    $password = hash('whirlpool',htmlspecialchars(strip_tags($_POST['pswd']), ENT_QUOTES, 'UTF-8'));
    $email = filter_var(strip_tags($_POST['email']), FILTER_SANITIZE_EMAIL);
    $result = $conn->query("SELECT id, `name`, pfp FROM users WHERE password = '$password' AND email = '$email'");
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['login'] = $row['name'];
        $_SESSION['pfp'] = $row['pfp'];
        $id = $_SESSION['id'];
        include("updateFavs.php");
        header("location: ../index.php");
    } else {
        header("location: ../account.php?error=4"); //Wrong credentials
    }
}
?>