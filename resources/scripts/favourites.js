$(document).ready(function() {
    $("#fav").on("click", function() {
        $.post("./php/changeFavTrack.php", { id: "'" + tracksArray[currentTrack]['id'] + "'"}, function(data) {
            if (data == "Success") {
                $("#fav").toggleClass("unlike");
                if(favTracksArray.includes(tracksArray[currentTrack].id)){
                    favTracksArray.splice(favTracksArray.indexOf(tracksArray[currentTrack].id), 1);
                } else {
                    favTracksArray.push(tracksArray[currentTrack].id);
                }
            } else {
                console.log(data)
            }
        });
    });
});