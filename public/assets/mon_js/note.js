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

  // Affichage du spinner pendant le chargement
  $("#loadingSpinner").show();

  $.ajax({
    url: ROOT + "/get_note_etudiant",
    type: "POST",
    data: {
      idPromotion: promotion_id,
      idModule: module_id,
      idFiliere: filiere_id,
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
      } else {
        $("#alerte").html(
          "<h6 class='text-center text-bold-600 text-warning'>Aucune note disponible pour cette promotion dans ce module !</h6>"
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
function saveNoteEtudiant(noteDevoir, noteEvaluation, noteSession, idNote) {
  const ROOT = "http://localhost/G_universite/public/Notes";
  $.ajax({
    url: ROOT + "/save_note_etudiant",
    type: "POST",
    data: {
      idNote: idNote,
      devoir: noteDevoir,
      evaluation: noteEvaluation,
      session: noteSession,
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
  let moyenne = 0;
  // Selon la règle : si noteEvaluation est supérieure à noteSession, on utilise (devoir + evaluation)/2
  // Sinon, on utilise (devoir + session)/2
  if (noteEvaluation > noteSession) {
    moyenne = (noteDevoir + noteEvaluation) / 2;
  } else {
    moyenne = (noteDevoir + noteSession) / 2;
  }
  // On applique le coefficient pour obtenir la moyenne du module
  moyenneModule = moyenne;

  return moyenneModule;
}
