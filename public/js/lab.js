// addlab.js

document.addEventListener("DOMContentLoaded", function() {
    const addLabButton = document.getElementById('add-lab-button');
    const hasLab = addLabButton.dataset.hasLab === 'true';

    addLabButton.addEventListener('click', function(event) {
        if (hasLab) {
            event.preventDefault();
            const labModal = new bootstrap.Modal(document.getElementById('labModal'));
            labModal.show();
        }
    });
    // Ajoutez également cette logique pour le bouton de suppression du laboratoire
    const deleteLabButtons = document.querySelectorAll('.delete-lab-button');
    deleteLabButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });
    });

    const deleteAnalyseButtons = document.querySelectorAll('.delete-analyse-button');
    deleteAnalyseButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteAnalyseModal = new bootstrap.Modal(document.getElementById('deleteAnalyseModal'));
            deleteAnalyseModal.show();
        });
    });
});
