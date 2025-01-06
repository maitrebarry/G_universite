document.addEventListener("DOMContentLoaded", function () {
  const baseURL = document.getElementById("baseURL").value;

  // Fonction pour remplir les options du champ enseignant en fonction du statut
  function remplirEnseignants(statut, enseignantSelect, callback) {
    enseignantSelect.empty().append("<option></option>");

    // Requête AJAX pour obtenir les enseignants par statut
    $.ajax({
      url: baseURL + "/Enseignants/getEnseignantsParStatut",
      method: "GET",
      data: { statut: statut },
      success: function (response) {
        const data = JSON.parse(response);
        if (data.success) {
          data.enseignants.forEach((enseignant) => {
            enseignantSelect.append(
              `<option value="${enseignant.enseignant_id}"
                        data-grade="${enseignant.enseignant_grade ?? ""}"
                        data-statut="${enseignant.enseignant_statut}">
                        ${enseignant.enseignant_nom} ${
                enseignant.enseignant_prenom
              }
              </option>`
            );
          });
          if (callback) callback();
        } else {
          alert("Erreur: " + data.message);
        }
      },
      error: function (error) {
        alert("Erreur lors de la récupération des enseignants.");
      },
    });
  }

  // Initialisation et gestion des événements
  document
    .querySelectorAll(
      '[data-bs-toggle="modal"][data-bs-target="#modalEmargementUpdate"]'
    )
    .forEach(function (item) {
      item.addEventListener("click", function (event) {
        let id_emargement = this.getAttribute("data-id");
        let id_enseignant = this.getAttribute("data-enseignant");
        let id_filiere = this.getAttribute("data-filiere");
        let id_semestre = this.getAttribute("data-semestre");
        let nh_programme = this.getAttribute("data-nh_programme");
        let heures_supp = this.getAttribute("data-heuresSupp");
        let heures_dues = this.getAttribute("data-heuresDues");
        let statut = this.getAttribute("data-statut");
        let grade = this.getAttribute("data-grade");
        let date_debut = this.getAttribute("data-dateDebut");
        let date_fin = this.getAttribute("data-dateFin");

        if (
          id_emargement === null ||
          id_enseignant === null ||
          id_filiere === null ||
          id_semestre === null ||
          nh_programme === null ||
          heures_supp === null ||
          heures_dues === null ||
          statut === null ||
          date_debut === null ||
          date_fin === null
        ) {
          console.error("Un ou plusieurs attributs sont manquants");
          return;
        }

        document.getElementById("statutUpdate").value = statut;
        document.getElementById("filiereUpdate").value = id_filiere;
        document.getElementById("semestreUpdate").value = id_semestre;
        document.getElementById("nh_programmeUpdate").value = nh_programme;
        document.getElementById("heures_suppUpdate").value = heures_supp;
        document.getElementById("heures_duesUpdate").value = heures_dues;
        document.getElementById("date_debutUpdate").value = date_debut;
        document.getElementById("date_finUpdate").value = date_fin;

        // Remplir les enseignants en fonction du statut, puis sélectionner l'enseignant approprié
        remplirEnseignants(
          statut === "1" ? "CDI" : "VACT",
          $("#enseignant_selectUpdate"),
          function () {
            $("#enseignant_selectUpdate").val(id_enseignant).trigger("change");
          }
        );

        // Forcer le changement pour les éléments <select>
        document
          .getElementById("filiereUpdate")
          .dispatchEvent(new Event("change"));
        document
          .getElementById("semestreUpdate")
          .dispatchEvent(new Event("change"));

        if (statut === "1") {
          $("#cdiFieldsUpdate").show();
        } else {
          $("#cdiFieldsUpdate").hide();
        }

        document.getElementById("formEmargementUpdate").dataset.id =
          id_emargement;
      });
    });

  $("#statutUpdate").on("change", function () {
    const statut = $(this).val();
    const enseignantSelect = $("#enseignant_selectUpdate");

    // Remplir les options du champ enseignant en fonction du statut sélectionné
    remplirEnseignants(statut === "1" ? "CDI" : "VACT", enseignantSelect);

    if (statut === "1") {
      $("#cdiFieldsUpdate").show();
    } else {
      $("#cdiFieldsUpdate").hide();
    }
  });

  $("#enseignant_selectUpdate").on("change", function () {
    const selectedOption = $(this).find("option:selected");
    const grade = selectedOption.data("grade");

    // Mettre à jour le champ grade avec la valeur correspondante
    $("#gradeUpdate").val(grade ? grade : "");
  });

  $("#formEmargementUpdate").on("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    const id_emargement = this.dataset.id;

    const errors = [];
    if (!formData.get("id_enseignant")) {
      errors.push("Veuillez sélectionner un enseignant.");
    }
    if (!formData.get("id_filiere")) {
      errors.push("Veuillez sélectionner une filière.");
    }
    if (!formData.get("id_semestre")) {
      errors.push("Veuillez sélectionner un semestre.");
    }
    if (!formData.get("date_debut")) {
      errors.push("Veuillez sélectionner une date de début.");
    }
    if (!formData.get("date_fin")) {
      errors.push("Veuillez sélectionner une date de fin.");
    }
    if (!formData.get("statut")) {
      errors.push("Veuillez sélectionner un statut.");
    }

    if (errors.length > 0) {
      alert("Erreurs :\n" + errors.join("\n"));
      return;
    }

    $.ajax({
      url: baseURL + "/Enseignants/update_emargement/" + id_emargement,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
        const response = JSON.parse(data);
        if (response.success) {
          alert("Mise à jour réussie");
          location.reload();
        } else {
          alert("Erreur: " + response.message);
        }
      },
      error: function (error) {
        alert("Erreur lors de la mise à jour.");
      },
    });
  });
});
