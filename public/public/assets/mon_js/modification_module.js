$(document).ready(function() {
    // Lorsque le bouton "Editer" est cliqué
    $('.edit-btn').click(function(e) {
        e.preventDefault(); // Empêcher le lien de suivre le href="#" par défaut

        // Récupérer les attributs de données du lien cliqué
        var idmodule = $(this).data('id');
        var nomModule = $(this).data('nom_module');
        var sigleModule = $(this).data('sigle_module');

        // Remplir les champs du modal avec les données
        $('#inputIdmodule').val(idmodule);
        $('#inputnomModule').val(nomModule);
        $('#inputsigleModule').val(sigleModule);
     

        // Afficher le modal
        $('#default').modal('show');
    });
});