function findObjectByID(arr,id){
    for(let i = 0; i < Object.keys(arr).length; i++){
        if(arr[i].id == id){
            return arr[i];
        }
    }
    console.log("findObjectByID ERROR: Trying to find object with an " + id + " in array " + arr);
    return null;
}

function findObjectByFile(arr,file){
    for(let i = 0; i < Object.keys(arr).length; i++){
        if(arr[i].file == file){
            return arr[i];
        }
    }
    console.log("findObjectByFile ERROR: Trying to find object with a filename " + file + " in array " + arr);
    return null;
}