const username = document.querySelector('#username');
const password = document.querySelector('#password');
const button = document.querySelector('#button');


username.addEventListener('input', function(){checkInput(this)});
password.addEventListener('input', function(){checkInput(this)});


function checkInput(input){
    if (input.value.length > 0){
        input.classList.add('border-green-500');
    }else{
        input.classList.remove('border-green-500');
    }
    showButton();
}

function showButton(){
    if (username.value.length > 0 && password.value.length > 0){
        button.disabled = false;
        button.classList.remove('opacity-50');
    } else {
        button.disabled = true;
        button.classList.add('opacity-50');
    }
}