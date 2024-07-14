<?php
include("updateFavs.php");

function getTrackTile($album,$id,$thumbnail,$name,$artists,$popup){
    $classes = "track";
    if(isset($_SESSION['favtracks'])){
        if(in_array($id,$_SESSION['favtracks'])){
            $classes .= " fav";
        }
    }
    if($popup == true){
        $onclick = "onClick='window.open(\"listen.php?album={$album}&track={$id}\",\"Midnight Melody Player\",\"width=800,height=800\")'";
        $href = "#";
    } else {
        $onclick = "";
        $href = "listen.php?album={$album}&track={$id}";
    }
    echo "<a class='$classes' href='$href' $onclick><div class='card' style='background-image:url({$thumbnail})'></div><p class='label'>{$name}</p><p class='author'>By $artists</p></a>";
}

function getAlbumTile($id,$thumbnail,$name,$popup){
    $classes = "track";
    if(isset($_SESSION['favalbums'])){
        if(in_array($id,$_SESSION['favalbums'])){
            $classes .= " fav";
        }
    }
    if($popup == true){
        $onclick = "onClick='window.open(\"listen.php?album={$id}\",\"Midnight Melody Player\",\"width=800,height=800\")'";
        $href = "#";
    } else {
        $onclick = "";
        $href = "listen.php?album={$id}";
    }
    echo "<a class='$classes' href='$href' $onclick><div class='card' style='background-image:url({$thumbnail})'></div><p class='label'>{$name}</p></a>";
}

function getMixtapeEntry($id,$name,$color,$amount,$public,$popup){
    $visibility = [["Private","Open"],["Public","Share"]];
    if($popup == true){
        $onclick = "onClick='window.open(\"listen.php?mixtape=$id\",\"Midnight Melody Player\",\"width=800,height=800\")'";
        $href = "#";
    } else {
        $onclick = "";
        $href = "listen.php?mixtape={$id}";
    }
    $tapecolor = "";
    if($color != "red"){
        $tapecolor = "tape_" . $color;
    }
    echo "<div class='ml_entry $tapecolor'>
    <section>
        <button></button>
        <img class='ml_icon' src='resources/icons/mixtape red.png'>
        <h3>$name</h3>
    </section>
    <section>
        <h3>{$visibility[$public][0]}</h3>
    </section>
    <section>
        <h3>{$amount} tracks</h3>
    </section>
    <section>
        <a $onclick href='$href'>{$visibility[$public][1]}</a>
    </section>
</div>";
}
?>