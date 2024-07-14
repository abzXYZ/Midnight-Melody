<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: account.php");
}
require("php/dbconnect.php");
include("php/checksettings.php");
require("php/getContentTile.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Melody</title>
    <link rel="stylesheet" href="style/anims.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/mixtapes.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link type="image/png" sizes="16x16" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-96.png">
    <style>
        :root{
            --current-color: var(--purple);
        }
    </style>
</head>
<body>
    <main>
        <a href="index.php"><h1 id="logo">MidnightMelody</h1></a>
        <?php include("php/nav-profile.php") ?>
        <?php include("php/nav-menu.php") ?>
        <div id="content" class="gradient-border">
            <h1>Add content</h1>
            <button class="primary" onclick="summon('mixtapeDialog')">New mixtape</button>
            <hr>
            <h1>Your mixtapes</h1>
            <div class="mixtapelist">
                <?php
                    $result = $conn->query("SELECT * FROM mixtapes WHERE ownerid = {$_SESSION['id']}");
                    if($result->num_rows > 0){
                        while($row = $result->fetch_object()){
                            $count = $conn->query("SELECT COUNT(trackid) as amount FROM mixtape_tracks WHERE mixtapeid = {$row->id}");
                            $amount = $count->fetch_object();
                            getMixtapeEntry($row->id,$row->name,$row->color,$amount->amount,$row->public,in_array("popup_player",$settings));
                        }
                    } else {
                        echo "No mixtapes found! Try creating one first.";
                    }
                ?>
            </div>
        </div>
    </main>
    <dialog id="mixtapeDialog">
        <form method="POST" action="php/newMixtape.php">
            <input type="hidden">
            <button type="reset" class="closebtn"></button>
            <label for="name">Mixtape name</label>
            <input type="text" name="name" maxlength="128" minlength="1" placeholder="Name" required>
            <label for="public">Visibility</label>
            <div class="checkbox+label">
                <input type="checkbox" name="public" /><p>Make the mixtape public</p>
            </div>
            <label for="color">Color</label>
            <select name="color" class="primary" required>
                <option value="red" selected>Red</option>
                <option value="green">Green</option>
                <option value="blue">Blue</option>
                <option value="yellow">Yellow</option>
                <option value="pink">Pink</option>
            </select>
            <button class="primary"  data-goal="add">Save changes</button>
        </form>
    </dialog>
    <script src="resources/scripts/dialogs.js"></script>
</body>
</html>