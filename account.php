<?php
session_start();
if(isset($_POST['logout'])){
    if($_POST['logout'] == "Log out"){
        session_unset();
        header("location: account.php?success=3");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Melody</title>
    <link rel="stylesheet" href="style/anims.css">
    <link rel="stylesheet" href="style/main.css">
    <?php
    if(!isset($_SESSION['id'])){
        echo '<link rel="stylesheet" href="style/signup.css">';
    } else {
        echo '<link rel="stylesheet" href="style/profile.css">';
    }
    ?>
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link type="image/png" sizes="16x16" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-96.png">
    <style>
        :root{
            --current-color: var(--logo-purple);
        }
    </style>
</head>
<body>
    <main>
    <a href="index.php"><h1 id="logo">MidnightMelody</h1></a>
        <?php
        if(isset($_SESSION['id'])){
            require("php/dbconnect.php");
            $result = $conn->query("SELECT email, `admin`, `date` FROM users WHERE id = {$_SESSION['id']}");
            $accountinfo = $result->fetch_assoc();
        ?>
        <?php include("php/nav-profile.php") ?>
        <?php include("php/nav-menu.php") ?>
        <div id="content" class="gradient-border">
            <h1>Account details</h1>
            <div class="profile">
                <?php 
                if(isset($_SESSION['pfp'])){
                    echo "<div style='background-image:url(" . $_SESSION['pfp'] . ");' id='pfp' class='pfp' alt='User profile picture'></div>";
                } else {
                    echo "<img src='resources/icons/cat96.png' id='pfp' alt='User profile picture' />";
                }
                echo "<h1>" . $_SESSION['login'] . "</h1>";
                ?>
            </div>
            <form method="POST">
                <input type="submit" class="primary" name="logout" value="Log out">
            </form>
            <h3>
                <?php
                echo "📆 Account creation time: " . $accountinfo['date'] . "<br>";
                echo "🏷️ Rank: ";
                switch ($accountinfo['admin']) {
                    case '0':
                        $time = date_diff(new DateTime($accountinfo['date']),new DateTime());
                        if($time->y > 0 || $time->m >= 2){
                            echo "User";
                        } else {
                            echo "Newbie (account younger than 2 months)";
                        }
                        break;
                    case '1':
                        echo "ADMIN";
                        break;
                }
                ?>
            </h3>
            <hr>
            <h2>Edit info</h2>
            <form action="php/updateaccount.php" method="POST" enctype="multipart/form-data">
                <?php
                echo "<label for='pfpurl' title='Valid formats: JPG, JPEG, PNG, WEBP'>Profile picture URL</label>";
                echo "<input type='url' name='pfpurl' pattern='https?://.*\.(png|jpg|jpeg|webp)' placeholder='Link to profile picture file' maxlength='512' value='";
                if(isset($_SESSION['pfp'])){
                    echo $_SESSION['pfp'];
                }
                echo "' />";
                echo "<label for='username'>Username</label>";
                echo "<input type='text' name='username' placeholder='New username' minlength='6' maxlength='24' value='" . $_SESSION['login'] . "' required />";
                echo "<label for='email'>E-mail</label>";
                echo "<input type='email' name='email' placeholder='New e-mail' maxlength='320' value='" . $accountinfo['email'] . "' required />";
                ?>
                <label for="pswd">Password</label>
                <input type="password" name="pswd" maxlength="64" minlength="5" placeholder="Password">
                <input type="password" name="repswd" maxlength="64" minlength="5" placeholder="Repeat password">
                <input type="submit" class="primary" value="Save changes">
                <button class="primary danger" name="pleaseremove">Delete account</button>
            </form>
        </div>
        <dialog id="deletedialog">
            
        </dialog>
        <?php
        } else {
        ?>
        <div id="content" class="signuppanel gradient-border">
            <?php 
            include("php/messageresolver.php");
            ?>
            <form action="php/login.php" method="POST">
                <h1>Log-in</h1>
                <label for="email">E-mail</label>
                <input type="email" maxlength="320" name="email" placeholder="E-mail" required>
                <label for="password">Password</label>
                <input type="password" maxlength="64" name="pswd" placeholder="Password" required>
                <input type="submit" class="primary" value="Continue">
            </form>
            <div class="vertical-divider"></div>
            <form action="php/register.php" method="POST">
                <h1>Register</h1>
                <label for="login">Username</label>
                <input type="text" maxlength="24" name="login" placeholder="Username" required>
                <label for="email">E-mail</label>
                <input type="email" maxlength="320" name="email" placeholder="E-mail" required>
                <label for="password">Password</label>
                <input type="password" maxlength="64" minlength="5" name="pswd" placeholder="Password" required>
                <input type="password" maxlength="64" minlength="5" name="repswd" placeholder="Repeat password" required>
                <input type="submit" class="primary" value="Create account">
            </form>
        </div>
        <?php
        }
        ?>
    </main>
</body>
</html>