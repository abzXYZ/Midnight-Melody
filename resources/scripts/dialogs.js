const dialogs = document.getElementsByTagName("dialog");

function resetHidden(form){
    form.querySelector("input[type='hidden']").value = "";
}

Array.from(dialogs).forEach(element => {
    let btn = element.querySelector('.closebtn');
    btn.addEventListener("click", function(){
        element.close();
    });
});

function summon(id){
    resetHidden(document.getElementById(id).getElementsByTagName("form")[0]);
    document.getElementById(id).showModal();
}

function updateDialogForm(id, object){
    summon(id);
    setFormData(id,object);
}

function setFormData(ID, object){
    let form = document.getElementById(ID).getElementsByTagName("form")[0];
    for(let key in object){
        let field = form.querySelector("input[name='" + key + "']");
        if(field){
            if(field.type != "file"){
                field.value = object[key];
            }
        } else {
            field = form.querySelector("select[name='" + key + "']");
            if(field){
                field.value = object[key];
            } else {
                field = form.querySelector("textarea[name='" + key + "']");
                if(field){
                    field.value = object[key];
                }
            }
        }
    }
}