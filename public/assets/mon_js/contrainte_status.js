$(document).ready(function () {
    $("#modalEmargement .select2").select2({
      placeholder: function () {
        $(this).data("placeholder");
      },
    });
  
    const statutSelect = $("#statut");
    const heuresDuesInput = $("#heures_dues");
  
  
    // Fonction pour afficher les enseignants filtrés
    function afficherEnseignantsFiltrés(statut) {
      enseignantSelect.empty();
      enseignantSelect.append(`<option></option>`);
      enseignants.forEach((enseignant) => {
        if (
          (statut === "1" && enseignant.enseignant_statut === "CDI") ||
          (statut === "2" && enseignant.enseignant_statut === "VACT")
        ) {
          enseignantSelect.append(
            `<option value="${enseignant.enseignant_id}"
                          data-grade="${enseignant.enseignant_grade ?? ""}"
                          data-statut="${enseignant.enseignant_statut}">
                          ${enseignant.enseignant_nom} ${
              enseignant.enseignant_prenom
            }
                      </option>`
          );
        }
      });
      enseignantSelect.val(null).trigger("change");
    }
  
    // Gère l'affichage des enseignants en fonction du statut sélectionné
    statutSelect.on("change", function () {
      const statut = $(this).val();
      afficherEnseignantsFiltrés(statut);
    });
  
    // Initialiser la liste déroulante avec le statut par défaut
    afficherEnseignantsFiltrés(statutSelect.val());
  
    // Gère l'affichage du container de grade en fonction du statut
    statutSelect.on("change", function () {
      if (statutSelect.val() == "1") {
        // CDI
        gradeContainer.show();
      } else if (statutSelect.val() == "2") {
        // VCT
        gradeContainer.hide();
        heuresDuesInput.val(0);
      }
      updateHeuresSupp();
    });
  
    // Gestion de la sélection d'un enseignant
    enseignantSelect.on("change", function () {
      const selectedOption = $(this).find("option:selected");
      const grade = selectedOption.data("grade");
      const statutEnseignant = selectedOption.data("statut");
  
      // Mise à jour du champ grade automatiquement pour les enseignants CDI
      if (statutSelect.val() == "1" && statutEnseignant == "CDI") {
        gradeInput.val(grade);
        updateHeuresDues(grade);
        updateCumulHeuresProgrammees(selectedOption.val());
      } else {
        gradeInput.val("");
        heuresDuesInput.val("");
      }
      updateHeuresSupp();
    });
  
    nhProgrammeInput.on("input", function () {
      updateHeuresSupp();
    });
  
    function updateHeuresDues(status) {
      let heuresDues = 0;
      switch (status) {
        case "regle":
          heuresDues = 6000;
          break;
        case "cl":
          heuresDues = 0;
          break;
        case "proffesionnel prive":
          heuresDues = 112;
          break;
        case "proffesionnel collectivite":
          heuresDues = 82;
          break;
          case "proffesionnel etatique":
            heuresDues = 82;
            break;
        default:
          heuresDues = 0;
          break;
      }
      heuresDuesInput.val(heuresDues);
    }
  
    function updateCumulHeuresProgrammees(idEnseignant) {
      $.ajax({
        url: window.APP_ROUTE ? window.APP_ROUTE("Enseignants/cumul_heures_programmees") : "Enseignants/cumul_heures_programmees",
        method: "GET",
        data: { id_enseignant: idEnseignant },
        success: function (data) {
          const cumulHeuresProgrammees = data.cumul_heures_programmees;
          const nhProgramme = parseInt(nhProgrammeInput.val()) || 0;
          const totalHeuresProgrammees = cumulHeuresProgrammees + nhProgramme;
          calculateHeuresSuppForCDI(totalHeuresProgrammees);
        },
        error: function (error) {
          console.error(
            "Erreur lors de la récupération des heures programmées:",
            error
          );
        },
      });
    }
  
    function calculateHeuresSuppForCDI(totalHeuresProgrammees) {
      if (statutSelect.val() == "1" && heuresDuesInput.val()) {
        const heuresDues = parseInt(heuresDuesInput.val());
  
        if (totalHeuresProgrammees > heuresDues) {
          heuresSuppInput.val(totalHeuresProgrammees - heuresDues);
        } else {
          heuresSuppInput.val(0);
        }
      }
    }
  
    function updateHeuresSuppForVCT() {
      if (statutSelect.val() == "2") {
        const nhProgramme = parseInt(nhProgrammeInput.val()) || 0;
        heuresSuppInput.val(nhProgramme);
      }
    }
  
    function updateHeuresSupp() {
      if (statutSelect.val() == "1") {
        updateCumulHeuresProgrammees(enseignantSelect.val());
      } else if (statutSelect.val() == "2") {
        updateHeuresSuppForVCT();
      }
    }
  
    // Si le formulaire est soumis, traiter le formulaire
    $("#formEmargement").on("submit", function (event) {
      event.preventDefault();
      const formData = new FormData(this);
  
      $.ajax({
        url: window.APP_ROUTE ? window.APP_ROUTE("Enseignants/liste_emargement") : "Enseignants/liste_emargement",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          console.log("Succès:", data);
        },
        error: function (error) {
          console.error("Erreur:", error);
        },
      });
    });
  });
  
