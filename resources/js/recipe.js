const reportPopUp = document.querySelector('#report-pop-up');
const reportButton = document.querySelector('#report-button');
const inputsRadio = document.querySelectorAll('.radio');
const submitRadio = document.querySelector('#submit-radio');
const serverResponse = document.querySelector('#server-response');
const recipe = document.querySelector('article').id;


reportButton.addEventListener('click', function(){
    reportPopUp.classList.remove('hidden');
    menu.classList.add('hidden');
})

submitRadio.addEventListener('click', function(){
    let reason;
    inputsRadio.forEach(element => {
        console.log(element);
        if (element.checked){
            reason = element.value;
        }
    });

    if (reason){
        console.log(reason);
        // on prépare la requête xmlhttp
        const request = new XMLHttpRequest();
        request.onload = function(){
            $response = this.responseText;

            if ($response){
                serverResponse.innerHTML = $response;
            }
        }
        request.open('GET', '/report?recipe=' + recipe + '&reason=' + reason);
        request.send();
    }
})

