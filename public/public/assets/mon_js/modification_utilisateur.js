$(document).ready(function() {
    // Lorsque le bouton "Editer" est cliqué
    $('.edit-btn').click(function(e) {
        e.preventDefault(); // Empêcher le lien de suivre le href="#" par défaut

        // Récupérer les attributs de données du lien cliqué
        var id_Utilisateur = $(this).data('id');
        var nom_Prenom = $(this).data('nom_prenom');
        var contact_Utilisateur = $(this).data('contact_utilisateur');
        var email_Utilisateurs = $(this).data('email_utilisateurs');
        var mot_Passe = $(this).data('mot_passe');
        var Role = $(this).data('role');

        // Remplir les champs du modal avec les données
        $('#inputid_Utilisateur').val(id_Utilisateur);
        $('#inputnom_Prenom').val(nom_Prenom);
        $('#inputcontact_Utilisateur').val(contact_Utilisateur);
        $('#inputemail_Utilisateurs').val(email_Utilisateurs);
        $('#inputmot_Passe').val(mot_Passe);
        $('#inputRole').val(Role);
     

        // Afficher le modal
        $('#default').modal('show');
    });
});

