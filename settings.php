<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Melody</title>
    <link rel="stylesheet" href="style/anims.css">
    <link rel="stylesheet" href="style/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link type="image/png" sizes="16x16" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-96.png">
    <style>
        :root{
            --current-color: var(--green);
        }
    </style>
</head>
<body>
    <main>
    <a href="index.php"><h1 id="logo">MidnightMelody</h1></a>
        <?php include("php/nav-profile.php") ?>
        <?php include("php/nav-menu.php") ?>
        <div id="content" class="gradient-border">
            <?php
            require("php/checksettings.php");
            ?>
            <h1>Client settings</h1>
            <h2>
            <input type="checkbox" name="settings[]" value="autoplay_tracks" <?php if($settingsfound && in_array("autoplay_tracks",$settings)) echo "checked" ?> >
            <label for="popup-player">Continuously play tracks in albums</label>
            </h2>
            <h2>
            <input type="checkbox" name="settings[]" value="popup_player" <?php if($settingsfound && in_array("popup_player",$settings)) echo "checked" ?> >
            <label for="popup-player">Open the audio player in a separate popup window</label>
            </h2>
            <h2>
            <input type="checkbox" name="settings[]" value="infinite_playlist" <?php if($settingsfound && in_array("infinite_playlist",$settings)) echo "checked" ?> >
            <label for="popup-player">Auto-repeat album/mixtape when they end</label>
            </h2>
            <script src="resources/scripts/settings.js"></script>
        </div>
    </main>
</body>
</html>