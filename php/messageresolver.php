<?php
$msg = "";
if(isset($_GET["error"])){
    switch ($_GET["error"]) {
        case 1:
            $msg = "Account with the same login or email address already exists.";
            break;
        case 2:
            $msg = "There was an error while creating a new account.";
            break;
        case 3:
            $msg = "Provided email or password were incorrect.";
            break;
        case 4:
            $msg = "There was an error while veryfing credentials.";
            break;
        case 5:
            $msg = "Provided passwords are not the same. Try again.";
            break;
        default:
            $msg = "Error.";
            break;
    }
    echo "<div class='messagebar error'>".$msg."</div>";
}
if(isset($_GET["success"])){
    switch ($_GET["success"]) {
        case 1:
            $msg = "Account successfully created. You can log-in now.";
            break;
        case 2:
            $msg = "Account successfully removed alongside all related data.";
            break;
        case 3:
            $msg = "Logged out successfully.";
            break;
        default:
            $msg = "Success.";
            break;
    }
    echo "<div class='messagebar success'>".$msg."</div>";
}
?>