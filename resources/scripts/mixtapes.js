$(document).ready(function() {
    $("#addtomixtape").on("click", function() {
        $.post("./php/changeMixtapeTrack.php", { trackid: "'" + tracksArray[currentTrack]['id'] + "'", mixtapeid: "'" + $("#mixtapelist").val() + "'"}, function(data) {
            if (data == "Success") {
                console.log("Track successfully added to the mixtape");
                document.getElementById("mixtapedialog").close();
            } else {
                console.log(data)
                alert("There was an error while adding track to the desired mixtape!");
            }
        });
    });
});