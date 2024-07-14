let forms = document.getElementsByTagName("form");

Array.from(forms).forEach(element => {
    let formdata = new FormData(element);
    let senders = element.getElementsByTagName("button.sender");
    if(senders.length > 0){
        senders[0].addEventListener("click", function(){
            fetch("../php/" + sender.dataset.target + ".php", {
                method: 'POST',
                body: formdata
            })
            .then(response => response.text())
            .then(data => {
                console.log('POST successful: ', data);
            })
            .catch(error => {
                console.error('POST ERROR:', error);
                alert("An error occured!");
            });
        });
    }
});