<?php
session_start();
if(!isset($_SESSION['id'])){
    header("location: account.php");
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
    <link rel="stylesheet" href="style/favs.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link type="image/png" sizes="16x16" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-96.png">
    <style>
        :root{
            --current-color: var(--pink);
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
            require("php/dbconnect.php");
            require("php/filter.php");

            function summonContent($sql, $conn, $type) {
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<div class='trackrow'>";
                    while ($row = $result->fetch_object()) {
                        if ($type == "tracks") {
                            $thumbnails = array();
                            $artists = $row->authors;
                            if (!isset($thumbnails[$row->albumid])) {
                                $imgresult = $conn->query("SELECT `image` FROM albums WHERE `id` = {$row->albumid};");
                                if ($imgresult->num_rows > 0) {
                                    $thumbnails[$row->albumid] = $imgresult->fetch_assoc()['image'];
                                } else {
                                    $thumbnails[$row->albumid] = "";
                                }
                            }
                            if (strlen($artists) > 44) {
                                $artists = substr($artists, 0, 41) . "...";
                            }
                            echo "<a class='track' href='listen.php?album={$row->albumid}&track={$row->id}'><div class='card' style='background-image:url({$thumbnails[$row->albumid]})'></div><p class='label'>{$row->name}</p><p class='author'>By $artists</p></a>";
                        } else {
                            echo "<a class='track' href='listen.php?album={$row->id}'><div class='card' style='background-image:url({$row->image})'></div><p class='label'>{$row->name}</p></a>";
                        }
                    }
                    echo "</div>";
                } else {
                    echo "<h3>Nothing found!</h3>";
                }
            }

            

            if(isset($_GET['target'])){
                if($_GET['target'] == "tracks"){
                    echo "<h1>Tracks</h1>";
                    $sql = "SELECT * FROM tracks WHERE `id` IN (SELECT `trackid` FROM favourite_tracks WHERE `userid` = '{$_SESSION['id']}')";
                } else {
                    echo "<h1>Albums</h1>";
                    $sql = "SELECT * FROM albums WHERE `id` IN (SELECT `albumid` FROM favourite_albums WHERE `userid` = '{$_SESSION['id']}')";
                }
                summonContent($sql,$conn,$_GET['target']);
            } else {
                echo "<h1>Favourite tracks</h1>";
                $sql = $sql = "SELECT * FROM tracks WHERE `id` IN (SELECT `trackid` FROM favourite_tracks WHERE `userid` = '{$_SESSION['id']}') LIMIT 10";
                summonContent($sql,$conn,"tracks");
                echo "<a class='primary' href='favourites.php?target=tracks'>See all tracks</a>";
                echo "<h1>Favourite albums</h1>";
                $sql = "SELECT * FROM albums WHERE `id` IN (SELECT `albumid` FROM favourite_albums WHERE `userid` = '{$_SESSION['id']}') LIMIT 10";
                summonContent($sql,$conn,"albums");
                echo "<a class='primary'  href='favourites.php?target=albums'>See all albums</a>";
            }
            ?>
    </main>
</body>
</html>