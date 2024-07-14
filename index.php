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
    <link rel="stylesheet" href="style/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link type="image/png" sizes="16x16" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-96.png">
    <style>
        :root{
            --current-color: var(--blue);
        }
    </style>
</head>
<body>
    <main>
        <a href="index.php"><h1 id="logo">MidnightMelody</h1></a>
        <?php include("php/nav-profile.php") ?>
        <?php include("php/nav-menu.php") ?>
        <div id="content" class="gradient-border">
            <form name="search">
                <div class="fields">
                    <div class="queries">
                        <h2>Search by</h2>
                        <input name="name" type="text" maxlength="128" placeholder="Name" value="<?php if(isset($_GET['name'])) echo $_GET['name'] ?>">
                        <input name="artist" type="text" maxlength="160" placeholder="Artist" value="<?php if(isset($_GET['artist'])) echo $_GET['artist'] ?>">
                    </div>
                    <div class="filters">
                        <div>
                            <h2>Look for</h2>
                            <div>
                                <input type="radio" id="radio_tracks" name="lookfor" value="tracks" checked />
                                <label for="track">Tracks</label>
                                <br>
                                <input type="radio" id="radio_albums" name="lookfor" value="albums" />
                                <label for="albums">Albums</label>
                            </div>
                        </div>
                        <div>
                            <h2>Sort by</h2>
                            <select name="search_sort" id="sort" class="primary">
                                <option value="name asc">Name (a-z)</option>
                                <option value="name desc">Name (z-a)</option>
                                <option value="date asc">Upload date ⬆</option>
                                <option value="date desc" selected>Upload date ⬇</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="primary">Search</button>
            </form>
            <hr>
            <?php
            require("php/dbconnect.php");
            require("php/filter.php");
            include("php/getContentTile.php");
            require("php/checksettings.php");

            function summonContent($sql, $conn, $type, $popup) {
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
                            getTrackTile($row->albumid,$row->id,$thumbnails[$row->albumid],$row->name,$artists,$popup);
                        } else {
                            echo "<a class='track' href='listen.php?album={$row->id}'><div class='card' style='background-image:url({$row->image})'></div><p class='label'>{$row->name}</p></a>";
                        }
                    }
                    echo "</div>";
                } else {
                    echo "<h3>Nothing found!</h3>";
                }
            }

            $popup = in_array("popup_player",$settings);

            if(isset($_GET['lookfor'])){
                if($_GET['lookfor'] == "tracks"){
                    echo "<h1>Tracks</h1>";
                    $sql = filterTracks();
                } else {
                    echo "<h1>Albums</h1>";
                    $sql = filterAlbums();
                }
                summonContent($sql,$conn,$_GET['lookfor'],$popup);
            } else {
                echo "<h1>Recent tracks</h1>";
                $sql = "SELECT * FROM tracks ORDER BY date DESC LIMIT 10";
                summonContent($sql,$conn,"tracks",$popup);
                echo "<h1>Recent albums</h1>";
                $sql = "SELECT * FROM albums ORDER BY date DESC LIMIT 10";
                summonContent($sql,$conn,"albums",$popup);
            }
            ?>
        </div>
    </main>
</body>
</html>