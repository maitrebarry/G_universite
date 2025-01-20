$(document).ready(function() {
    // Lorsque le bouton "Editer" est cliqué
    $('.edit-btn').click(function(e) {
        e.preventDefault(); // Empêcher le lien de suivre le href="#" par défaut

        // Récupérer les attributs de données du lien cliqué
        var idsemestre = $(this).data('id');
        var nomSemestre = $(this).data('nom_semestre');
        var sigleSemestre = $(this).data('sigle_semestre');

        // Remplir les champs du modal avec les données
        $('#inputIdmodule').val(idsemestre);
        $('#inputnomSemestre').val(nomSemestre);
        $('#inputsigleModule').val(sigleSemestre);
     
        // Afficher le modal
        $('#default').modal('show');
    });
});