const deleteButtons = document.querySelectorAll('.delete-button');
const deletePopUp = document.querySelector('#delete-pop-up');

deleteButtons.forEach(deleteButton => {
    deleteButton.addEventListener('click', function(){
        deletePopUp.classList.remove('hidden');
        const submitButton = document.querySelector('#submit-button');
        submitButton.value = deleteButton.value;
    })
})