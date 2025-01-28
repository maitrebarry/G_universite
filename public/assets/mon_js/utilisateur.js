$(document).ready(function () {
    // Lorsque le bouton "Modifier" est cliqué
    $('.edit-btn').click(function (e) {
        e.preventDefault(); // Empêcher le comportement par défaut

        // Récupérer les données à partir des attributs data-*
        var idUtilisateur = $(this).data('id');
        var nomPrenom = $(this).data('nom_prenom');
        var contactUtilisateur = $(this).data('contact_utilisateur');
        var emailUtilisateur = $(this).data('email_utilisateurs');
        var roleUtilisateur = $(this).data('role'); // Récupérer le rôle

        // Remplir les champs du modal avec les données
        $('#inputid_Utilisateur').val(idUtilisateur);
        $('#inputnom_Prenom').val(nomPrenom);
        $('#inputcontact_Utilisateur').val(contactUtilisateur);
        $('#inputemail_Utilisateurs').val(emailUtilisateur);
        $('#inputRole').val(roleUtilisateur); // Remplir le rôle dans le modal

        // Afficher le modal
        $('#large1').modal('show');
    });
});


