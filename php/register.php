<?php
if(isset($_POST['login'],$_POST['email'],$_POST['pswd'],$_POST['repswd'])){
    if($_POST['pswd'] == $_POST['repswd']){
        require("dbconnect.php");
        $password = hash('whirlpool',htmlspecialchars(strip_tags($_POST['pswd']), ENT_QUOTES, 'UTF-8'));
        $login = htmlspecialchars(strip_tags($_POST['login']), ENT_QUOTES, 'UTF-8');
        $email = filter_var(strip_tags($_POST['email']), FILTER_SANITIZE_EMAIL);
        $result = $conn->query("SELECT id FROM users WHERE name = '$login' OR email = '$email'");
        if($result->num_rows == 0){
            if($conn->query("INSERT INTO `users` (`name`, `password`, `email`) VALUES ('$login', '$password', '$email');") == TRUE){
                header("location: ../account.php?success=1"); //Account created
            } else {
                header("location: ../account.php?error=2"); //Couldn't execute SQL query
            }
        } else {
            header("location: ../account.php?error=1"); //Account already exists error
        }
    } else {
        header("location: ../account.php?error=5"); //Passwords not the same
    }
}
?>