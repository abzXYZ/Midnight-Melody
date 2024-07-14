<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: account.php");
}
require("php/arrayGenerator.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Melody</title>
    <link rel="stylesheet" href="style/anims.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/uploads.css">
    <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link type="image/png" sizes="16x16" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="resources/icons/icons8-werewolf-pulsar-gradient-96.png">
    <style>
        :root{
            --current-color: var(--red);
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
            $result = $conn->query("SELECT `admin`, `date` FROM users WHERE id = {$_SESSION['id']}");
            $accountinfo = $result->fetch_assoc();
            $time = date_diff(new DateTime($accountinfo['date']),new DateTime());
            if($accountinfo['admin'] == 1 || $time->y > 0 || $time->m >= 2){
            ?>
            <h1>Add content</h1>
            <button class="primary" id="newalbumbtn">Add new album</button>
            <button class="primary" id="newtrackbtn">Add new track</button>
            <hr>
            <form name="filter">
                <div class="fields">
                    <div class="queries">
                        <h2>Search for</h2>
                        <?php
                        echo "<input name='filter_name' type='text' maxlength='128' placeholder='Name'";
                        if(isset($_GET['filter_name']) && $_GET['filter_name'] != ""){
                            echo " value='{$_GET['filter_name']}'";
                        }
                        echo ">";
                        ?>
                        <button type="submit" class="primary">Apply filter</button>
                    </div>
                    <div class="filters">
                        <h2>Sort by</h2>
                        <select name="filter_sort" id="sort" class="primary">
                            <option value="name asc" <?php if(isset($_GET['filter_sort']) && $_GET['filter_sort'] == "name asc") echo "selected"; ?>>Name (a-z)</option>
                            <option value="name desc" <?php if(isset($_GET['filter_sort']) && $_GET['filter_sort'] == "name desc") echo "selected"; ?>>Name (z-a)</option>
                            <option value="date asc" <?php if(isset($_GET['filter_sort']) && $_GET['filter_sort'] == "date asc") echo "selected"; ?>>Upload date ⬆</option>
                            <option value="date desc" <?php if(isset($_GET['filter_sort']) && $_GET['filter_sort'] == "date desc") echo "selected"; ?>>Uplaod date ⬇</option>
                        </select>
                    </div>
                </div>
            </form>
            <hr>
            <div class="uploadslist">
                <form method="POST" action="./php/editTracks.php" id="executeonselected">
                    <input type="hidden" id="secretalbumid" name="secretalbumid" value="">
                    <section>
                        <h1>Your tracks</h1>
                        <select id="operation" name="operation" class="primary">
                            <option value="" disabled selected>Operation</option>
                            <option value="changealbum">Change album</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button class="primary" id="exec" disabled>Execute on selected</button>
                        <button class="primary" id="edittrack" onClick='updateDialogForm("trackdialog",findObjectByID(tracksArray,getCurrentCheckboxID()))' disabled>Edit info</button>
                        <?php
                        $filter = "";
                        if(isset($_GET['filter_name']) && $_GET['filter_name'] != ""){
                            $filter .= " AND t.`name` LIKE '%{$_GET['filter_name']}%'";
                        }
                        if(isset($_GET['filter_sort'])){
                            $filter .= " ORDER BY t" . $_GET['filter_sort'];
                        }
                        $tracksresult = $conn->query("SELECT t.`id` AS tid, t.`name` AS tname, a.`name` AS aname, t.`date` AS tdate FROM tracks AS t INNER JOIN albums AS a ON t.`albumid` = a.`id` WHERE t.`ownerid` = {$_SESSION['id']}" . $filter);
                        if($tracksresult->num_rows > 0){
                            while($row = $tracksresult->fetch_object()){
                                echo "<div class='track_entry'><input type='checkbox' name='tracks[]' value='{$row->tid}'><h3>{$row->tname}</h3><h3>{$row->aname}</h3><h3>{$row->tdate}</h3></div>";
                            }
                        } else {
                            echo "No tracks found.";
                        }
                        ?>
                    </section>
                </form>
                <div class="vertical-divider"></div>
                <section>
                    <h1>Your albums</h1>
                    <?php
                    $filter = "";
                    if(isset($_GET['filter_name']) && $_GET['filter_name'] != ""){
                        $filter .= " AND `name` LIKE '%{$_GET['filter_name']}%'";
                    }
                    if(isset($_GET['filter_sort'])){
                        $filter .= " ORDER BY " . $_GET['filter_sort'];
                    }
                    $albums = [];
                    $albumresult = $conn->query("SELECT * FROM albums WHERE ownerid = {$_SESSION['id']}" . $filter);
                    if($albumresult->num_rows > 0){
                        while($row = $albumresult->fetch_object()){
                            $albums[] = $row;
                            echo "<div class='album_entry'><h3>{$row->name}</h3><div class='cover' style='background-image:url({$row->image});'></div><h3>Created: {$row->date}</h3><button class='primary' onClick='updateDialogForm(\"albumdialog\",findObjectByID(albumsArray,{$row->id}))'>Edit info</button><button class='primary' onClick='deleteAlbum({$row->id})'>Delete</button></div>";
                        }
                    } else {
                        echo "No albums found.";
                    }
                    ?>
                </section>
            </div>
            <?php
            } else {
                echo "<h1>'User' rank required to upload content!</h1>";
                echo "<h2>Your account must be at least 2 months old before you can upload any content.</h2>";
            }
            ?>
        </div>
    </main>
    <dialog id="albumdialog">
        <form method="POST" enctype="multipart/form-data" action="php/newAlbum.php">
            <input type="hidden" value="" name="id">
            <button type="reset" class="closebtn"></button>
            <label for="name">Album title</label>
            <input type="text" name="name" maxlength="128" minlength="1" placeholder="Title" required>
            <label for="desc">Description</label>
            <textarea name="desc" maxlength="1024" placeholder="Description" required></textarea>
            <label for="file">Thumbnail file</label>
            <input type="file" name="file" accept="image/png, image/jpeg, image/webp" />
            <button class="primary"  data-goal="add">Save changes</button>
        </form>
    </dialog>
    <dialog id="trackdialog">
        <form method="POST" enctype="multipart/form-data" action="php/newTrack.php">
            <input type="hidden" value="" name="id">
            <button type="reset" class="closebtn"></button>
            <label for="name">Title</label>
            <input type="text" name="name" maxlength="128" minlength="1" placeholder="Title" required>
            <label for="authors">List of authors</label>
            <input type="text" name="authors" maxlength="160" placeholder="Authors" required>
            <label for="authors">Featured in album</label>
            <select name="albumid" class="primary" required>
                <?php
                    $result = $conn->query("SELECT `id`,`name` FROM albums WHERE ownerid = {$_SESSION['id']}");
                    if($result->num_rows > 0){
                        echo "<option value='' selected disabled>Your albums</option>";
                        while($row = $result->fetch_object()){
                            echo "<option value='{$row->id}'>{$row->name}</option>";
                        }
                    } else {
                        echo "<option value='' selected disabled>Add an album first!</option>";
                    }
                ?>
            </select>
            <label for="position">Position in album</label>
            <input type="number" min="1" max="999" step="1" name="position" required/>
            <label for="file">Audio file</label>
            <input type="file" name="file" accept="audio/mp3" id="audiofileinput" required />
            <button class="primary" data-target="newTrack" data-goal="add">Save changes</button>
        </form>
    </dialog>
    <dialog id="updatealbumdialog">
        <form>
            <input type='hidden'>
        </form>
        <button type="reset" class="closebtn"></button>
        <h2>Choose an album to assign all selected tracks to</h2>
        <label for="album">Album</label>
        <select name="album" class="primary" id="secretalbumselect">
                <?php
                    $result = $conn->query("SELECT `id`,`name` FROM albums WHERE ownerid = {$_SESSION['id']}");
                    if($result->num_rows > 0){
                        echo "<option value='' selected disabled>Your albums</option>";
                        while($row = $result->fetch_object()){
                            echo "<option value='{$row->id}'>{$row->name}</option>";
                        }
                    } else {
                        echo "<option value='' selected disabled>Add an album first!</option>";
                    }
                ?>
        </select>
        <button class="primary" id="confirmexecutionbtn">Go</button>
    </dialog>
    <form id="deletealbumform" action="./php/deleteAlbum.php" method="POST">
        <input type="hidden" name="id" value="">
    </form>
    <?php
    $tracks = [];
    $result = $conn->query("SELECT * FROM tracks WHERE ownerid = {$_SESSION['id']};");
    if($result->num_rows > 0){
        while($row = $result->fetch_object()){
            $tracks[] = $row;
        }
    }
    tracksArrayGenerator($tracks);
    albumsArrayGenerator($albums);
    ?>
    <script src="resources/scripts/arrayIDfinder.js"></script>
    <script src="resources/scripts/dialogs.js"></script>
    <script src="resources/scripts/tracklistoperator.js"></script>
    <script src="resources/scripts/upload.js"></script>
</body>
</html>