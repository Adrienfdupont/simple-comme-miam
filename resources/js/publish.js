const categoryInput = document.querySelector('#categories');
const ingredientInput = document.querySelector('#ingredients');
const quantityInput = document.querySelector('#quantity');
const unitInput = document.querySelector('#unit');
const upload = document.querySelector('#upload');
const uploadedImage = document.querySelector('#uploaded-image');

uploadedImage.addEventListener('click', function(){
    upload.click();
})

upload.addEventListener('change', () => {
    if (upload.files && upload.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
        uploadedImage.src = e.target.result;
        }
    reader.readAsDataURL(upload.files[0]);
    }
})

ingredientInput.addEventListener('input', function(){suggest(this)})

categoryInput.addEventListener('input', function(){suggest(this)})



function suggest(input){
    clean();
    // on vérifie que l'input n'est pas vide
    if (input.value != ''){
  
        // on prépare la requête
        const request = new XMLHttpRequest();
        request.onload = function(){
          const items = JSON.parse(this.responseText);
    
          // on vérifie que le serveur renvoie qq chose
          if (items){
              for (const item in items){
                const suggestions = input.nextElementSibling;
                let suggestion = document.createElement('div');
                suggestions.appendChild(suggestion);
                suggestion.id = items[item][0];
                suggestion.innerHTML = items[item][1];
                suggestion.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
                
                // au clique on ajoute l'élément dans
                // la liste qui sera envoyée au serveur
                suggestion.addEventListener('click', (e)=>addToList(e.target));
              }
          }
        }
        // on exécute la requête
        const tableName = input.id;
        request.open('GET', '/home/' + tableName + '/suggestions?q=' + input.value);
        request.send();

        // si on clique en dehors des suggestions elles disparaissent
        document.addEventListener('click', function(){
            clean();
            // on vide le champs
            input.value = '';
        })
    }
}

// faire apparaitre une suggestion cliquée dans une div
function addToList(suggestion){

    const tableName = suggestion.parentNode.previousElementSibling.id;
    const listContainer = suggestion.parentNode.parentNode.nextElementSibling;

    // on crée l'objet s'il n'est pas déjà dans la liste
    if (!document.querySelector('#' + tableName + suggestion.id)){
        const item = document.createElement('div');
        item.id = tableName + suggestion.id;
        item.classList.add('ml-2');
        listContainer.appendChild(item);
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = tableName + '[]';
        checkbox.checked = true;
        item.appendChild(checkbox);

        // s'il s'agit d'un ingrédient, on passe en valeur à la
        // checkbox l'id, la qtité et l'unité
        if (tableName === 'ingredients'){
            checkbox.value = [quantityInput.value, unitInput.value, suggestion.id];
            quantityInput.value = '';
        }else{
            checkbox.value = suggestion.id;
        }

        const label = document.createElement('label');
        label.innerHTML = suggestion.innerHTML;
        item.appendChild(label);

        checkbox.addEventListener('click', function(){deleteItem(this.parentElement)});
    }   
}

function deleteItem(item){
    item.remove();
}