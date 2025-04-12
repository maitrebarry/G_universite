// Fonction pour afficher/masquer les champs de session
function toggleSessionFields(show, hideNotes) {
  const sessionInfo = document.getElementById("session_info");
  const tableSection = document.getElementById("table_section");
  const noteSessionFields = document.querySelectorAll(".note_session");

  sessionInfo.classList.toggle("hidden", !show);
  tableSection.classList.toggle("hidden", !show);
  noteSessionFields.forEach((field) => {
    field.classList.toggle("hidden", hideNotes);
  });
}

/**************************************************** */
// Fonction de chargement des étudiants et de leurs notes via AJAX
function loadEtudiants() {
  const ROOT = "http://localhost/G_universite/public/Notes";
  let filiere_id = $("#filiere").val();
  let promotion_id = $("#promotions").val();
  let module_id = $("#modules").val();
  let semestre_id = $("#semestres").val();

  // Affichage du spinner pendant le chargement
  $("#loadingSpinner").show();

  $.ajax({
    url: ROOT + "/get_note_etudiant",
    type: "POST",
    data: {
      idPromotion: promotion_id,
      idModule: module_id,
      idFiliere: filiere_id,
      idSemestre: semestre_id,
    },
    success: function (response) {
      $("#loadingSpinner").hide();
      console.log(response);

      if (response.trim() !== "notfound") {
        // Injecter le HTML retourné dans la section de la table
        $("#table_section").html(response);

        // Pour chaque ligne du tableau, attacher un gestionnaire pour recalculer et sauvegarder
        $("#notesTable tbody tr").each(function () {
          const row = $(this);
          const idNote = row.data("id");

          // Fonction de recalcul de la moyenne et sauvegarde des notes
          function calculAndSave() {
            // Récupération et conversion des valeurs des inputs et du coefficient
            let noteDevoir = parseFloat(row.find(".noteDevoir").val());
            let noteEvaluation = parseFloat(row.find(".noteEvaluation").val());
            let noteSession = parseFloat(row.find(".noteSession").val());
            let coeficient = parseFloat($(".coeficient").text());

            //console.log("Devoir :", noteDevoir, "Evaluation :", noteEvaluation, "Coefficient :", coeficient);

            // Calcul de la moyenne
            let moyenne = calculeMoyenModuleSessionNormale(
              noteDevoir,
              noteEvaluation,
              noteSession,
              coeficient
            );
            // Mise à jour du champ input de la moyenne (readonly)
            row.find(".moyenne").val(moyenne.toFixed(2));
            const moyenneInput = row.find(".moyenne");
            if (moyenne < 10) {
              moyenneInput.removeClass("bg-rgba-warning");
              moyenneInput.removeClass("bg-rgba-success");
              moyenneInput.addClass("bg-rgba-danger");
            } else if (moyenne < 15) {
              moyenneInput.removeClass("bg-rgba-danger");
              moyenneInput.removeClass("bg-rgba-success");
              moyenneInput.addClass("bg-rgba-warning");
            } else if (moyenne <= 20) {
              moyenneInput.removeClass("bg-rgba-danger");
              moyenneInput.removeClass("bg-rgba-warning");
              moyenneInput.addClass("bg-rgba-success");
            } else {
              moyenneInput.removeClass("bg-rgba-danger");
              moyenneInput.removeClass("bg-rgba-warning");
              moyenneInput.removeClass("bg-rgba-success");
            }

            // Sauvegarder les modifications en envoyant les notes au serveur
            saveNoteEtudiant(
              row.find(".noteDevoir").val(),
              row.find(".noteEvaluation").val(),
              row.find(".noteSession").val(),
              row.find(".moyenneModule").val(),
              idNote
            );
          }

          // Attachement d'un seul gestionnaire keyup sur tous les inputs de la ligne ayant la classe "note"
          calculAndSave();
          row.find(".note").on("keyup", function (event) {
            if ($(this).val() < 0) {
              $(this).val(0);
            }
            if ($(this).val() > 20) {
              $(this).val(20);
            }
            calculAndSave();
          });
        });

        // Gestion de l'affichage des boutons de session selon l'état
        const sessionNormaleBtn = $("#session_normale_btn");
        if (sessionNormaleBtn.hasClass("active")) {
          toggleSessionFields(true, false);
        } else {
          toggleSessionFields(true, true);
        }
      } else if (response.trim().include("empty")) {
        $("#table_section").html(
          "<h6 class='text-center text-bold-600 text-warning'>" +
            "Aucun étudiant trouvé pour cette promotion !</h6>"
        );
      } else {
        $("#table_section").html(
          "<h6 class='text-center text-bold-600 text-warning'>" +
            "Veuilez Bien verifier vos données !</h6>"
        );
      }
    },
    error: function (xhr, status, error) {
      $("#loadingSpinner").hide();
      console.error("Erreur AJAX : ", status, error);
      alert(
        "Une erreur s'est produite lors du chargement des données. Veuillez réessayer."
      );
    },
  });
}

/**************************************************** */
// Fonction d'envoi des modifications des notes au serveur via AJAX
function saveNoteEtudiant(
  noteDevoir,
  noteEvaluation,
  noteSession,
  moyenneModule,
  idNote
) {
  const ROOT = "http://localhost/G_universite/public/Notes";
  $.ajax({
    url: ROOT + "/save_note_etudiant",
    type: "POST",
    data: {
      idNote: idNote,
      devoir: noteDevoir,
      evaluation: noteEvaluation,
      session: noteSession,
      moyenne: moyenneModule,
      action: "noterecuee",
    },
    success: function (response) {
      $("#message").html(response);
    },
    error: function (xhr, status, error) {
      console.error("Erreur AJAX : ", status, error);
    },
  });
}

/**************************************************** */
// Fonction de calcul de la moyenne selon la formule appliquée en session normale
// La formule utilisée ici dépend de la comparaison entre noteEvaluation et noteSession
function calculeMoyenModuleSessionNormale(
  noteDevoir,
  noteEvaluation,
  noteSession,
  coef
) {
  let moyenneModule = 0;
  // Selon la règle : si noteEvaluation est supérieure à noteSession, on utilise (devoir + evaluation)/2
  // Sinon, on utilise (devoir + session)/2
  moyenneModule = (noteDevoir + 2 * noteEvaluation) / 3;
  if (noteSession > moyenneModule) {
    moyenneModule = noteSession;
  }
  // On applique le coefficient pour obtenir la moyenne du module
  return moyenneModule;
}

async function infosFiliere(idFiliere, source = null) {
  try {
    response = await $.ajax({
      method: "POST",
      url: "http://localhost/G_universite/public/Emploi_du_temps/filiere_info",
      dataType: "json",

      data: {
        source: source,
        idFiliere: idFiliere,
      },
    });
    return response;
  } catch (error) {
    console.error(error);
  }
  var infoFiliere;
}

// recuperer les promotions d'une filière à travers son id
function promotionsFiliere(infoFiliere, idPromotion = "") {
  const promotionContainer = $("#promotions");
  promotionContainer.empty();
  promotionContainer.append(
    `<option value="" disabled>Selectionner une Promotion</option>`
  );
  const promotions = infoFiliere["promotions"];
  promotions.forEach((promotion) => {
    const option = `<option value='${
      promotion.id_promotion
    }' class='text-center' data-id='${
      promotion.id_parcours
    }'data-semestre='${promotion.sigle_semestre.toUpperCase()}' ${
      promotion.id_promotion == idPromotion ? "selected" : ""
    }>
    ${promotion.sigle_filiere.toUpperCase()}-${promotion.sigle_semestre.toUpperCase()}( ${
      promotion.annee_universitaire
    } )</option>`;
    promotionContainer.append(option);
  });
}
////////////////////////////////////////////////////////ppppppppppp

// recuperer les semestres d'une filière à travers son id
function semestresPromotion(infoFiliere, semestreCourant) {
  const semestreContainer = $("#semestres");
  semestreContainer.empty();
  semestreContainer.append(
    `<option value="" disabled selected>Selectionner un Semestre</option>`
  );
  const semestres = infoFiliere["semestres"];
  semestreCourant = parseInt(semestreCourant.slice(-1), 10);
  semestres.forEach((semestre) => {
    if (parseInt(semestre.sigle_semestre.slice(-1), 10) <= semestreCourant) {
      const option = `<option value='${
        semestre.id_parcours
      }' class='text-center' data-id='${
        semestre.id_parcours
      }'>${semestre.sigle_semestre.toUpperCase()}</option>`;
      semestreContainer.append(option);
    }
  });
}

// recuperer les modules d'une promotion à travers l'id du semestre
function modulesSemestre(idSemestre, infoFiliere, idModule = "") {
  const mouduleContainer = $("#modules");
  mouduleContainer.empty();

  mouduleContainer.append(
    `<option value="" disabled selected>Selectionner un Module</option>`
  );
  const ues = infoFiliere["ues"];
  const modules = infoFiliere["modules"];

  ues.forEach((ue) => {
    if (ue.id_parcours == idSemestre) {
      const ueOption = `<option disabled class="mt-1">${ue.nom_ue}</option>`;
      mouduleContainer.append(ueOption);
      modules.forEach((module) => {
        if (module.id_ue == ue.id_ue) {
          const option = `<option value='${
            module.id_ue_module
          }' class='text-center' ${
            module.id_ue_module == idModule ? "selected" : ""
          }>
            ${module.nom_module}(${module.code_module})
            </option>`;
          mouduleContainer.append(option);
        }
      });
    }
  });
}
