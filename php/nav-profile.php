<a id="profile" href="account.php">
    <?php
    if(isset($_SESSION['login'])){
        echo "<p>" . $_SESSION['login'] . "</p>";
    } else {
        echo "<p>Sign in</p>";
    }
    if(isset($_SESSION['pfp'])){
        echo "<div style='background-image:url(" . $_SESSION['pfp'] . ");' class='pfp defaultpfp' alt='User profile picture'></div>";
    } else {
        echo "<img src='./resources/icons/cat.png' class='defaultpfp' alt='User profile picture' />";
    }
    ?>
</a>