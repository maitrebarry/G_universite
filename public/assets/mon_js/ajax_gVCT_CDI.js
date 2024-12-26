$(document).ready(function () {
  // Initialisation au chargement de la page
  toggleStatutElements();

  // Événement sur le changement de statut
  $("#statut").change(function () {
    toggleStatutElements();
  });

  // Fonction pour basculer les champs selon le statut
  function toggleStatutElements() {
    const statut = $("#statut").val();

    // Conteneurs des champs
    const gradeContainer = $("#grade-container");
    const matriculeContainer = $("#matricule-container");
    const cvContainer = $("input[name='cv']").closest(".col-md-6");

    if (statut === "VACT") {
      gradeContainer.hide();
      matriculeContainer.hide();
      cvContainer.show();

      // Effacer les valeurs des champs masqués
      $("#grade").val("");
      $("#matricule").val("");
    } else if (statut === "CDI") {
      gradeContainer.show();
      matriculeContainer.show();
      cvContainer.hide();

      // Effacer la valeur du CV
      $("input[name='cv']").val("");
    }
  }
});
