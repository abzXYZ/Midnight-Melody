<?php
session_start();
require("php/dbconnect.php");
include("php/checksettings.php");
require("php/arrayGenerator.php");

$error = true;
if(isset($_GET['album'])){
    $result = $conn->query("SELECT * FROM `albums` WHERE `id` = '{$_GET['album']}'");
    if($result->num_rows == 1){
        $error = false;
        $assoc = $result->fetch_assoc();
        $name = $assoc['name'];
        $desc = $assoc['description'];
        $img = $assoc['image'];
        $id = $_GET['album'];
    }
} else if(isset($_GET['mixtape'],$_SESSION['id'],$_SESSION['login'])) {
    $result = $conn->query("SELECT * FROM `mixtapes` WHERE `id` = '{$_GET['mixtape']}'");
    if($result->num_rows == 1){
        $assoc = $result->fetch_assoc();
        if($assoc['ownerid'] == $_SESSION['id'] || $assoc['public'] == 1){
            $error = false;
            $name = $assoc['name'];
            $namequery = $conn->query("SELECT `name` FROM `users` WHERE `id` = '{$assoc['ownerid']}'");
            $desc = "A mixtape by someone quite mysterious indeed";
            if($namequery->num_rows > 0){
                $nameassoc = $namequery->fetch_assoc();
                $desc = "A mixtape by " . $nameassoc['name'];
            }
            $img = "resources/icons/cassettes.jpg";
            $id = $assoc['id'];
        }
    }
}
if($error){
    header("location: index.php");
}

if(isset($_GET['mixtape']) && !isset($_GET['album'])){
    $result = $conn->query("SELECT * FROM `tracks` WHERE `id` IN (SELECT `trackid` FROM `mixtape_tracks` WHERE `mixtapeid` = {$_GET['mixtape']});");
} else {
    $result = $conn->query("SELECT * FROM `tracks` WHERE `albumid` = '$id' ORDER BY `position` ASC");
}

$tracks = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_object()) {
        $tracks[] = $row;
    }
}

function tracklistGenerator($tracks){
    if(sizeof($tracks)>0){
        foreach ($tracks as $track) {
            echo "<li data-id='{$track->id}'>{$track->name}</li>";
        }   
    } else {
        echo "<h3>Found nothing!</h3>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name ?> - Midnight Melody</title>
    <link rel="stylesheet" href="style/anims.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/player.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link type="image/png" sizes="16x16" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-96.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        :root{
            --current-color: var(--logo-purple);
            <?php echo "--thumbnail: url($img);"; ?>
        }
    </style>
</head>
<body>
    <main>
    <a href="index.php"><h1 id="logo">MidnightMelody</h1></a>
        <?php include("php/nav-profile.php") ?>
        <?php include("php/nav-menu.php") ?>
        <div id="content" class="gradient-border">
            <audio id="music" src="" <?php if(isset($settings) && in_array("autoplay_tracks",$settings)) echo "onended='next()'"; ?>></audio>
            <?php
            if(sizeof($tracks) > 0){
            ?>
            <div id="audioplayer">
                <div id="trackinfo">
                    <h2 id="tracktitle">Track title</h2>
                    <p id="trackauthors">Authors</p>
                </div>
                <div id="trackcontrols">
                    <div id="top">
                        <div>
                            <?php
                            $disabled = "";
                            if(!isset($_SESSION['id'])){
                                $disabled = "disabled";
                            }
                            echo "<button id='fav' class='primary' $disabled></button>
                            <button id='mixtape' class='primary' onClick='summon(\"mixtapedialog\")' $disabled></button>";
                            ?>
                        </div>
                        <div id="controls">
                            <button id="prev"></button>
                            <button id="control"></button>
                            <button id="next"></button>
                        </div>
                        <div id="options">
                            <img src="resources/icons/icons8-voice-48.png" />
                            <input type="range" min="0" max="100" step="1" value="50" id="audiovolume">
                        </div>
                    </div>
                    <input type="range" value="0" min="0" max="100" step="1" id="trackprogress">
                </div>
            </div>
            <?php
            }
            ?>
            <div id="playerinfo">
            <?php
            echo "<section>";
            echo "<h1>$name</h1>";
            echo "<ol id='tracklist'>";
            tracklistGenerator($tracks);
            echo "</ol>";
            echo "</section>";
            echo "<section>";
            echo "<div class='thumbnail' style='background-image:var(--thumbnail)'></div>";
            echo "<p id='desc'>$desc</p>";
            echo "</section>";
            ?>
            </div>
            <?php
            if(isset($_GET['album'])){
            ?>
            <hr>
            <section id="reviews">
                <h1>Reviews</h1>
                <?php
                if(isset($_SESSION['id'])){
                ?>
                <form id="newreview" action="php/addReview.php" method="POST">
                    <input type="hidden" name="albumid" value="<?php echo $_GET['album'] ?>">
                    <h2>Add review</h2><textarea name="text"></textarea>
                    <button type="submit" class="primary">Submit review</button>
                </form>
                <?php
                } 
                $result = $conn->query("SELECT u.`name` AS name, r.`date` AS rdate, r.`text` AS rtext FROM `reviews` AS r INNER JOIN `users` AS u ON r.`userid` = u.`id` WHERE `albumid` = {$_GET['album']}");
                if($result->num_rows > 0){
                    while($row = $result->fetch_object()){
                        echo "<div class='review'><h2>{$row->name}</h2><h3>{$row->rdate}</h3><p>{$row->rtext}</p></div>";
                    }
                } else {
                    echo "No reviews yet";
                }
                ?>
            </section>
            <?php } ?>
        </div>
    </main>
    <dialog id="mixtapedialog">
        <form>
            <input type='hidden'>
        </form>
        <button type="reset" class="closebtn"></button>
        <h2>Choose a mixtape to add the current track to</h2>
        <select name="mixtape" class="primary" id="mixtapelist">
                <?php
                if(isset($_SESSION['id'])){
                    $result = $conn->query("SELECT `id`,`name` FROM mixtapes WHERE ownerid = {$_SESSION['id']}");
                    if($result->num_rows > 0){
                        echo "<option value='' selected disabled>Your mixtapes</option>";
                        while($row = $result->fetch_object()){
                            echo "<option value='{$row->id}'>{$row->name}</option>";
                        }
                    } else {
                        echo "<option value='' selected disabled>Add an album first!</option>";
                    }
                }
                ?>
        </select>
        <button class="primary" id="addtomixtape">Add</button>
    </dialog>
    <?php
    favTracksArrayGenerator();
    tracksArrayGenerator($tracks);
    if(sizeof($tracks) > 0){
        echo "<script id='playerscript' data-startwith='";
        if(isset($_GET['track'])){
            echo $_GET['track'];
        } else {
            echo "0";
        }
        echo "' data-infinity='";
        if(in_array("infinite_playlist",$settings)) echo "true";
        echo"' src='resources/scripts/player.js'></script>";
    }
    ?>
    <script src="./resources/scripts/dialogs.js"></script>
    <script src="./resources/scripts/mixtapes.js"></script>
    <script src="./resources/scripts/arrayIDfinder.js"></script>
    <script src="./resources/scripts/favourites.js"></script>
</body>
</html>