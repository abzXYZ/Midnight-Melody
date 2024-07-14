<?php
function tracksArrayGenerator($tracks){
    echo "<script>const tracksArray = {};";
    $i = 0;
    foreach ($tracks as $track) {
        echo "tracksArray[$i] = {";
        echo "id: {$track->id},";
        echo "name: '{$track->name}',";
        echo "authors: '{$track->authors}',";
        echo "file: '{$track->file}',";
        echo "albumid: '{$track->albumid}',";
        echo "position: '{$track->position}'";
        echo "};";
        $i++;
    }
    echo "</script>";
}

function albumsArrayGenerator($albums){
    echo "<script>let albumsArray = {};";
    $i = 0;
    foreach ($albums as $album) {
        echo "albumsArray[$i] = {";
        echo "id: {$album->id},";
        echo "name: '{$album->name}',";
        echo "desc: '{$album->description}',";
        echo "image: '{$album->image}',";
        echo "ownerid: '{$album->ownerid}',";
        echo "date: '{$album->date}'";
        echo "};";
        $i++;
    }
    echo "</script>";
}

function favTracksArrayGenerator(){
    if(isset($_SESSION['favtracks'])){
        include("updateFavs.php");
        echo "<script>const favTracksArray = [];";
        foreach ($_SESSION['favtracks'] as $track) {
            echo "favTracksArray.push($track);";
        }
        echo "</script>";
    }
}

function favAlbumsArrayGenerator(){
    include("updateFavs.php");
    echo "<script>let favAlbumsArray = [];";
    foreach ($_SESSION['favalbums'] as $album) {
        echo "favAlbumsArray.push($album);";
    }
    echo "</script>";
}
?>