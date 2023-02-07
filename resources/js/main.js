// sélectionne toutes les boites de suggestions du site
const allSuggestions = document.querySelectorAll('.suggestions');

// interactions avec le menu
const openMenu = document.querySelector('#open-menu');
const menu = document.querySelector('#menu');
const closeMenu = document.querySelector('#close-menu');

// recherche de recettes
const recipeInputs = document.querySelectorAll('#recipe-input, #md-recipe-input');

// interactions pop-up
const closePopUp = document.querySelectorAll('.close-pop-up');
const searchPopUp = document.querySelector('#search-pop-up');


// au clavier dans un input de recherche de recette
// on affiche des suggestions
recipeInputs.forEach(recipeInput =>{
    recipeInput.addEventListener('keyup', function(){
        suggestRecipes(this);
    })
})


// ouvrir le menu (<md)
openMenu.addEventListener('click', function(){
    menu.classList.remove('hidden');
})

// fermer le menu (<md)
closeMenu.addEventListener('click', function(){
    menu.classList.add('hidden');
})

// au clique de "Rechercher" dans le menu
// on affiche une pop-up de recherche (<md)
search.addEventListener('click', function(){
    searchPopUp.classList.remove('hidden');
    menu.classList.add('hidden');
})

// ferme une pop-up
closePopUp.forEach(element => {
    element.addEventListener('click', function(){
        popUp = this.parentNode.parentNode.parentNode;
        popUp.classList.add('hidden');
    })
});


function suggestRecipes(input){

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
                suggestion.addEventListener('click', (e)=>window.location.href = '/recipe?title=' + items[item][0]);
                suggestion.innerHTML = items[item][1];
                suggestion.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
              }
          }
        }
        // on exécute la requête
        request.open('GET', '/home/recipes/suggestions?q=' + input.value);
        request.send();

        document.addEventListener('click', function(){
            clean();
            input.value = '';
        })
    }
}


// sur demande cache toutes les boites de suggestions
function clean(){
    allSuggestions.forEach(suggestions => {
        suggestions.innerHTML = '';
    });
}
