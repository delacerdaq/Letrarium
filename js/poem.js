document.addEventListener('DOMContentLoaded', function () {
    // const editForm = document.getElementById('edit-form');
    const deleteForm = document.getElementById('delete-form');

    if (deleteForm) {
        deleteForm.addEventListener('submit', function (event) {
            const confirmed = confirm("Tem certeza de que deseja excluir este poema?");
            if (!confirmed) {
                event.preventDefault();
            }
        });
    }
});
