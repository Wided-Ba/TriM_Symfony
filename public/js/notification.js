document.addEventListener('DOMContentLoaded', function() {
    // Récupérer l'élément de notification
    var notification = document.getElementById('notification');

    // Afficher la notification
    notification.style.display = 'block';

    // Cacher la notification après 2 secondes
    setTimeout(function() {
        notification.style.display = 'none';
    }, 3500);
});