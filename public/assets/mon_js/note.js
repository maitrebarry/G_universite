// Fonction pour afficher/masquer les champs de session
const ROOT = "http://localhost/G_universite/public/Notes";
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
function loadEtudiants(save = false) {
  if (save == false) {
    filiere_id = $("#promotions option:selected").data("filiere");
    promotion_id = $("#promotions option:selected").val();
    module_id = $("#modules").val();
    semestre_id = $("#promotions option:selected").data("semestre");
  } else {
    filiere_id = sessionStorage.getItem("filiere");
    promotion_id = sessionStorage.getItem("classe");
    module_id = sessionStorage.getItem("module");
    semestre_id = sessionStorage.getItem("semestre");
  }

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
            "Aucun étudiant trouvé pour cette classe !</h6>"
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

// recuperer les classes d'une annnée universitaire
async function classesAnneeUniversitaire(anneeUniversitaire) {
  $.ajax({
    method: "POST",
    url: ROOT + "/get_classe_annee",
    dataType: "json",

    data: {
      anneeUniversitaire: anneeUniversitaire,
    },
    success: function (response) {
      const promotionContainer = $("#promotions");
      promotionContainer.empty();
      promotionContainer.append(
        `<option value="" >Selectionner une Classe</option>`
      );

      idPromotionSelected = sessionStorage.getItem("classe");
      idParcoursSelected = sessionStorage.getItem("semestre");
      //sessionStorage.setItem("semestre", idParcoursSelected);
      const promotions = response;
      promotions.forEach((promotion) => {
        const option = `<option value='${
          promotion.id_promotion
        }' class='text-center' 
        data-filiere='${promotion.id_filiere}'
        data-semestre='${promotion.id_parcours}'
        ${
          promotion.id_promotion == idPromotionSelected &&
          promotion.id_parcours == idParcoursSelected
            ? "selected"
            : ""
        }
        > 
        ${promotion.classe}</option>`;
        promotionContainer.append(option);
      });
    },
    error: function () {},
  });
}

// recuperer les modules d'une promotion à travers l'id du semestre
function modulesSemestre(idSemestre, infoFiliere, idModule = "") {
  const mouduleContainer = $("#modules");
  mouduleContainer.empty();

  mouduleContainer.append(`<option value="" >Selectionner un Module</option>`);
  const ues = infoFiliere["ues"];
  const modules = infoFiliere["modules"];
  idModuleSelected = sessionStorage.getItem("module");
  ues.forEach((ue) => {
    if (ue.id_parcours == idSemestre) {
      // const ueOption = `<option disabled class="mt-1">${ue.nom_ue}</option>`;
      // mouduleContainer.append(ueOption);
      modules.forEach((module) => {
        if (module.id_ue == ue.id_ue) {
          const option = `<option value='${module.id_ue_module}' 
          class='text-center' ${
            module.id_ue_module == idModuleSelected ? "selected" : ""
          }>
            ${module.nom_module}(${module.code_module})
            </option>`;
          mouduleContainer.append(option);
        }
      });
    }
  });
}
