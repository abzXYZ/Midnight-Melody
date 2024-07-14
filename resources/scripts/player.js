const progressbar = document.getElementById("trackprogress");
const volumebar = document.getElementById("audiovolume");
const playbackcontrol = document.getElementById("control");
const nextbutton = document.getElementById("next");
const prevbutton = document.getElementById("prev");
const favbutton = document.getElementById("fav");
const mixtapebutton = document.getElementById("mixtape");
const title = document.getElementById("tracktitle");
const authors = document.getElementById("trackauthors");
const audio = document.getElementById("music");
const thisscript = document.getElementById("playerscript");

let pause = true;
let mouseoninput = false;
let currentTrack = 0;
const length = Object.keys(tracksArray).length;
const infinity = thisscript.dataset.infinity;

function loadTrackInfo(){
    progressbar.value = 0;
    title.innerText = tracksArray[currentTrack].name;
    authors.innerText = tracksArray[currentTrack].authors;
    audio.src = tracksArray[currentTrack].file;
    audio.volume =  volumebar.value / 100;
    if(typeof favTracksArray !== 'undefined' && favTracksArray.includes(tracksArray[currentTrack].id)){
        favbutton.classList = "primary unlike";
    } else {
        favbutton.classList = "primary";
    }
}

function next(force = false){
    if(length > currentTrack+1){
        currentTrack++;
        loadTrackInfo();
        pause = false;
        play();
    } else if(length != 0 && force || infinity) {
        currentTrack = -1;
        next();
    }
}

function prev(){
    if(currentTrack-1 >= 0){
        currentTrack--;
        loadTrackInfo();
        pause = false;
        play();
    } else {
        currentTrack = length;
        prev();
    }
}

function play(){
    playbackcontrol.classList.add("playing");
    audio.play();
}

playbackcontrol.addEventListener("click",function(){
    if(pause == true){
        pause = false;
        play();
    } else {
        pause = true;
        playbackcontrol.classList.remove("playing");
        audio.pause();
    }
});
nextbutton.addEventListener("click", () => {
    next(true);
});
prevbutton.onclick = prev;
audio.addEventListener('timeupdate', () => {
    if(!mouseoninput){
        progressbar.value = audio.currentTime;
    }
});
progressbar.addEventListener("mousedown", () => {
    mouseoninput = true;
});
progressbar.addEventListener("mouseup", () => {
    mouseoninput = false;
});
progressbar.addEventListener("change", () => {
    audio.currentTime = progressbar.value;
});
volumebar.addEventListener('input', () => {
    audio.volume =  volumebar.value / 100;
});
audio.addEventListener('loadeddata', () => {
    progressbar.max = parseInt(audio.duration);
});
favbutton.addEventListener('click', () => {
    if(!favbutton.disabled){
        
    }
});

if(thisscript.dataset.startwith > 0){
    currentTrack = Object.values(tracksArray).findIndex(element => element.id === parseInt(thisscript.dataset.startwith));
}
loadTrackInfo();