$(document).ready(function() {
    // Lorsque le bouton "Editer" est cliqué
    $('.edit-btn').click(function(e) {
        // e.preventDefault(); // Empêcher le lien de suivre le href="#" par défaut

        // Récupérer les attributs de données du lien cliqué
        var idsalle = $(this).data('id_salle');
        var nomSAlle = $(this).data('nom_salle');
        var sigleSalle = $(this).data('capacite_salle');

        // Remplir les champs du modal avec les données
        $('#inputIdSalle').val(idsalle);
        $('#inputnomSalle').val(nomSAlle);
        $('#inputcapaciteSalle').val(sigleSalle);
     

        // Afficher le modal
        // $('#default').modal('show');
    });
});