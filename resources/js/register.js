const upload = document.querySelector('#upload');
const uploadedImage = document.querySelector('#uploaded-image');
const email = document.querySelector('#email');
const username = document.querySelector('#username');
const password = document.querySelector('#password');
const confirmedPassword = document.querySelector('#confirmed-password');
const registerButton = document.querySelector('#register-button');
let emailIsValid = false;
let usernameIsValid = false;
let passwordIsValid = false;
let confirmedPasswordIsValid = false;


// on définit les expressions régulières à vérifier pour le mdp
const min = new RegExp(/[a-z]/);
const maj = new RegExp(/[A-Z]/);
const num = new RegExp(/[0-9]/);
const spec = new RegExp(/[\'^£€$%&*()}{@#~?><>,;.|=_+¬-]/);


uploadedImage.addEventListener('click', function(){
    upload.click();
})

upload.addEventListener('change', () => {
    console.log('test');
    if (upload.files && upload.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
        uploadedImage.src = e.target.result;
        }
    reader.readAsDataURL(upload.files[0]);
    }
})


email.addEventListener('input', function(){

    if (this.value.length > 0){

        // si l'adresse est valide
        if (this.value.includes('@')){
            this.previousElementSibling.classList.add('hidden');
            this.classList.add('border-green-500');
            emailIsValid = true;

        // si l'adresse est invalide
        } else {
            this.previousElementSibling.classList.remove('hidden');
            this.classList.remove('border-green-500');
            emailIsValid = false;

        }
    } else {
        this.previousElementSibling.classList.add('hidden');
        this.classList.remove('border-green-500');
        emailIsValid = false;
    }
    showButton()
})

username.addEventListener('input', function(){
    const input = this;

    // requête xmlhttp pour savoir si le nom existe déjà
    const request = new XMLHttpRequest();
    request.onload = function(){
    const response = this.responseText;

        if (input.value.length){
            if (response == 0){
                input.classList.add('border-green-500');
                input.previousElementSibling.classList.add('hidden');
                usernameIsValid = true;
                
            } else {
                input.classList.remove('border-green-500');
                input.previousElementSibling.classList.remove('hidden');
                usernameIsValid = false;
            }
        } else {
            input.classList.remove('border-green-500');
            input.previousElementSibling.classList.add('hidden');
            usernameIsValid = false;
        }
        showButton()
    } 
    request.open('GET', '/register/detect-user?name=' + this.value);
    request.send();
})

password.addEventListener('input', function(){
    if (this.value.length > 0){

        // si le mot de passe est assez fort
        if (this.value.length >= 8 && this.value.match(min) &&
        this.value.match(maj) && this.value.match(num) && this.value.match(spec)){
            this.previousElementSibling.classList.add('hidden');
            this.classList.add('border-green-500');
            passwordIsValid = true;

        // si le mot de passe n'est pas assez fort
        } else{
            this.previousElementSibling.classList.remove('hidden');
            this.classList.remove('border-green-500');
            passwordIsValid = false;
        }
    } else {
        this.previousElementSibling.classList.add('hidden');
        this.classList.remove('border-green-500');
        passwordIsValid = false;
    }
    showButton()
})

confirmedPassword.addEventListener('input', function(){
    if (this.value.length > 0){

        // si les mots de passe correspondent
        if (this.value === password.value){
            this.previousElementSibling.classList.add('hidden');
            this.classList.add('border-green-500');
            confirmedPasswordIsValid = true;

        // si les mots de passe ne correspondant pas
        } else {
            this.previousElementSibling.classList.remove('hidden');
            this.classList.remove('border-green-500');
            confirmedPasswordIsValid = false;
        }
    } else {
        this.previousElementSibling.classList.add('hidden');
        this.classList.remove('border-green-500');
        confirmedPasswordIsValid = false;
    }
    showButton()
})

function showButton(){
    if (email.value.length > 0 && username.value.length > 0 &&
        password.value.length > 0 && confirmedPassword.value.length > 0 &&
        emailIsValid == usernameIsValid == passwordIsValid ==
        confirmedPasswordIsValid == true){
            
        registerButton.classList.remove('opacity-50');
        registerButton.disabled = false;
    } else {
        registerButton.classList.add('opacity-50');
        registerButton.disabled = true;
    }
}