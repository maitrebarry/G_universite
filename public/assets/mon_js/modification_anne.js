$(document).ready(function() {
// Lorsque le bouton "Editer" est cliqué
$('.edit-btn').click(function(e) {
e.preventDefault(); // Empêcher le lien de suivre le href="#" par défaut

// Récupérer les attributs de données du lien cliqué
var id_anne = $(this).data('id');
var anne_scolaire = $(this).data('anne_scolaire');
var date_debut = $(this).data('date_debut');
var date_fin = $(this).data('date_fin');

// Remplir les champs du modal avec les données
$('#inputId_anne').val(id_anne);
$('#inputAnne_scolaire').val(anne_scolaire);
$('#inputDate_debut').val(date_debut);
$('#inputDate_fin').val(date_fin);


// Afficher le modal

});
});