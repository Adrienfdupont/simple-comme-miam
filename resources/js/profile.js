const upload = document.querySelector('#upload');
const uploadedImage = document.querySelector('#uploaded-image');
const editButtons = document.querySelectorAll('.edit-button');
const editPopUp = document.querySelector('#edit-pop-up');
const label = document.querySelector('#label');
const password = document.querySelector('#password');
const submitButton = document.querySelector('#submit-button');
const input = document.querySelector('#input');
let inputValue = false;
const serverResponse = document.querySelector('#server-response');
const UserImageForm = document.querySelector('#user-image-form');
const imageForm = document.forms['image-form'];
const deleteButton = document.querySelector('#delete-button');
const deletePopUp = document.querySelector('#delete-pop-up');

// on définit les expressions régulières à vérifier pour le mdp
const min = new RegExp(/[a-z]/);
const maj = new RegExp(/[A-Z]/);
const num = new RegExp(/[0-9]/);
const spec = new RegExp(/[\'^£€$%&*()}{@#~?><>,;.|=_+¬-]/);

uploadedImage.addEventListener('click', function(){
    upload.click();
})

// changer photo de profil
upload.addEventListener('change', () => {

    if (upload.files && upload.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
        uploadedImage.src = e.target.result;
        }
    reader.readAsDataURL(upload.files[0]);
    }
    imageForm.submit();
})

// afficher les pop-ups de modifications
editButtons.forEach(editButton => {
    editButton.addEventListener('click', function(){showPopUp(this)});
})

// afficher la pop-up de suppression de compte
deleteButton.addEventListener('click', function(){
    deletePopUp.classList.remove('hidden');
})

// afficher ou non le bouton submit quand on tape le mdp
password.addEventListener('input', function (){showButton()});

// afficher la pop de modification
function showPopUp(editButton){

    editPopUp.classList.remove('hidden');
    menu.classList.add('hidden');
    
    // dans le cas où on modifie l'email
    if (editButton.value === 'username'){
        label.innerHTML = 'Votre nouveau nom d\'utilisateur';
        input.name = 'username';
        input.addEventListener('input', function(){checkUsername(this)});
    }
    else if (editButton.value === 'email'){
        label.innerHTML = 'Votre nouvel e-mail';
        input.name = 'email';
        input.addEventListener('input', function(){checkEmail(this)});
        // dans le cas où on modifie le mot de passe
    }
    else if (editButton.value === 'password') {
        label.innerHTML = 'Votre nouveau mot de passe';
        input.type = 'password';
        input.name = 'new-password';
        input.addEventListener('input', function(){checkPassword(this)});
    }
}


// afficher ou non le bouton submit
function showButton(){

    if (password.value.length > 0 && inputValue == true){
        submitButton.classList.remove('opacity-50');
        submitButton.disabled = false;
    } else {
        submitButton.classList.add('opacity-50');
        submitButton.disabled = true;
    }
}

// dans le cas où l'input change l'email
function checkEmail(input){

    if (input.value.length > 0){
       
        // si l'adresse est valide
        if (input.value.includes('@')){
            input.previousElementSibling.innerHTML = '';
            input.classList.add('border-green-500');
            inputValue = true;

        // si l'adresse est invalide
        } else {
            
            input.previousElementSibling.innerHTML = 'L\'adresse e-mail doit être valide.';
            input.classList.remove('border-green-500');
            inputValue = false;

        }
    } else {
        input.previousElementSibling.innerHTML  = '';
        input.classList.remove('border-green-500');
        inputValue = false;
    }
    showButton();
}

// dans le cas où on change le mdp
function checkPassword(input){

    if (input.value.length > 0){

        // si le mot de passe est assez fort
        if (input.value.length >= 8 && input.value.match(min) &&
        input.value.match(maj) && input.value.match(num) && input.value.match(spec)){
            input.previousElementSibling.innerHTML = '';
            input.classList.add('border-green-500');
            inputValue = true;

        // si le mot de passe n'est pas assez fort
        } else{
            input.previousElementSibling.innerHTML = 'Le mot de passe doit contenir au moins 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.';
            input.classList.remove('border-green-500');
            inputValue = false;
        }
    } else {
        input.previousElementSibling.innerHTML = '';
        input.classList.remove('border-green-500');
        inputValue = false;
    }
    showButton()
}

// dans le cas où on change le nom d'utilisateur
function checkUsername(input){
    console.log('checkUsername');

    // requête xmlhttp pour savoir si le nom existe déjà
    const request = new XMLHttpRequest();
    request.onload = function(){
    const response = this.responseText;

        if (input.value.length){
            if (response == 0){
                input.classList.add('border-green-500');
                input.previousElementSibling.innerHTML = '';
                inputValue = true;
                
            } else {
                input.classList.remove('border-green-500');
                input.previousElementSibling.innerHTML = 'Nom indisponible';
                inputValue = false;
            }
        } else {
            input.classList.remove('border-green-500');
            input.previousElementSibling.innerHTML = '';
            inputValue = false;
        }
        showButton()
    } 
    request.open('GET', '/register/detect-user?name=' + input.value);
    request.send();
}