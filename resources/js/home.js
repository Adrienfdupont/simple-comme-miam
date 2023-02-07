const ingredientsInput = document.querySelector('#ingredients');
const categoriesInput = document.querySelector('#categories');
const maxPrepTime = document.querySelector('#max-prep-time');
const maxBakeTime = document.querySelector('#max-bake-time');
const filterButton = document.querySelector('#filter-button');


// affiche suggestions pour ingrédients et catégories
ingredientsInput.addEventListener('keyup', function(){
    suggest(this);
})
categoriesInput.addEventListener('keyup', function(){
    suggest(this);
})


// montre à l'utilisateur les valeurs actuelles des inputs range
maxPrepTime.addEventListener('input', function(){
    showTime(this);
})

maxBakeTime.addEventListener('input', function(){
    showTime(this);
})


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
    if (!document.querySelector('#'+ tableName + suggestion.id)){
        const item = document.createElement('div');
        item.id = tableName + suggestion.id;
        item.classList.add('ml-2');
        listContainer.appendChild(item);
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = tableName + '[]';
        checkbox.checked = true;
        checkbox.value = suggestion.id;
        item.appendChild(checkbox);

        const label = document.createElement('label');
        label.innerHTML = suggestion.innerHTML;
        item.appendChild(label);

        checkbox.addEventListener('click', function(){deleteItem(this.parentElement)});
    }   
}

function deleteItem(item){
    item.remove();
}

// afficher les temps de préparation et cuisson saisis par l'utilisateur
function showTime(range){
    range.nextElementSibling.innerHTML = range.value + ' minutes';
}
