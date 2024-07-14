const trackEntries = document.getElementsByClassName("track_entry");
const checkboxes = [];
Array.from(trackEntries).forEach(element => {
     checkboxes.push(element.getElementsByTagName("input")[0]);
});
const editButton = document.getElementById("edittrack");
const execButton = document.getElementById("exec");
const newTrackButton = document.getElementById("newtrackbtn");
const newAlbumButton = document.getElementById("newalbumbtn");
const goButton = document.getElementById("confirmexecutionbtn");
const executeForm = document.getElementById("executeonselected");
const deleteAlbumForm = document.getElementById("deletealbumform");

function countChecked(){
    let i = 0;
    Array.from(checkboxes).forEach(checkbox => {
        if(checkbox.checked){
            i++;
        }
    });
    return i;
}

function updateButtons(){
    let i = countChecked();
    if(i > 0){
        execButton.disabled = false;
        if(i == 1){
            editButton.disabled = false;
        } else {
            editButton.disabled = true;
        }
    } else {
        execButton.disabled = true;
        editButton.disabled = true;
    }
}

function getCurrentCheckboxID(){
    if(countChecked() == 1){
        document.getElementById("audiofileinput").required = false;
        return document.querySelector('input[name="tracks[]"]:checked').value;
    } else {
        return null;
    }
}

function deleteAlbum(id){
    deleteAlbumForm.firstElementChild.value = id;
    deleteAlbumForm.submit();
}

newTrackButton.addEventListener("click", () => {
    document.getElementById("audiofileinput").required = true;
    summon('trackdialog');
});

newAlbumButton.addEventListener("click", () => {
    summon('albumdialog');
});

execButton.addEventListener("click", function(e){
    if(document.getElementById("operation").value != "delete"){
        e.preventDefault();
        summon('updatealbumdialog');
    } else {
        executeForm.submit();
    }
});

goButton.addEventListener("click", () => {
    document.getElementById("secretalbumid").value = document.getElementById("secretalbumselect").value;
    executeForm.submit();
});

Array.from(checkboxes).forEach(element => {
    element.addEventListener("input",() => {
        updateButtons();
    });
});