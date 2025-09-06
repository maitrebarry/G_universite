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

function loadEtudiants(url = ROOT + "/get_moyenne_etudiant") {
  let filiere_id = $("#promotions option:selected").data("filiere");
  let promotion_id = $("#promotions option:selected").val();
  let licence = $("#licences option:selected").data("semestre");
  let semestre_id = $("#promotions option:selected").data("semestre");
  let ue_id = $("#ues option:selected").data("id");
  let module_id = $("#modules option:selected").data("id");

  $.ajax({
    url: url,
    type: "POST",
    data: {
      idPromotion: promotion_id,
      idFiliere: filiere_id,
      licence: licence,
      idSemestre: semestre_id,
      idUe: ue_id,
      idModule: module_id,
    },
    success: function (response) {
      if (response.trim() !== "notfound") {
        // Injecter le HTML retourné dans la section de la table
        $("#table_section").html(response);
        $("#nomSemestre").text($("#semestres option:selected").text());
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
    console.log("hohohohoh");
    console.log(idFiliere);
  }
  var infoFiliere;
}

// recuperer les classes d'une annnée universitaire
function classesAnneeUniversitaire(anneeUniversitaire) {
  $.ajax({
    method: "POST",
    url: ROOT + "/get_classe_annee",
    dataType: "json",

    data: {
      anneeUniversitaire: anneeUniversitaire,
      action: "liste_note",
    },
    success: function (response) {
      console.log(response);

      const promotionContainer = $("#promotions");
      promotionContainer.empty();
      promotionContainer.append(
        `<option value="" >Selectionner une Classe</option>`
      );
      const promotions = response;
      promotions.forEach((promotion) => {
        const option = `<option value='${promotion.id_promotion}' class='text-center' data-filiere='${promotion.id_filiere}' data-semestre='${promotion.id_parcours}'> 
        ${promotion.classe}</option>`;
        promotionContainer.append(option);
      });
    },
    error: function () {},
  });
}

// recuperer les modules d'une promotion à travers l'id du semestre
function ueSemestre(idSemestre, infoFiliere) {
  const ueContainer = $("#ues");
  ueContainer.empty();

  ueContainer.append(`<option value="" selected>Tous les Ues</option>`);
  const ues = infoFiliere["ues"];

  ues.forEach((ue) => {
    if (ue.id_parcours == idSemestre) {
      const ueOption = `<option 
        class="mt-1 text-bold-600 text-capitalize" value"${ue.id_ue}" data-id="${ue.id_ue}">
        ${ue.nom_ue}
        </option>`;
      ueContainer.append(ueOption);
    }
  });
}

// recuperer les modules d'un ue à travers l'id du semestre
function moduleUe(idUe, infoFiliere) {
  const mouduleContainer = $("#modules");
  mouduleContainer.empty();

  mouduleContainer.append(
    `<option value="" selected>Tous les modules</option>`
  );
  const modules = infoFiliere["modules"];

  modules.forEach((module) => {
    if (module.id_ue == idUe) {
      const option = `<option value='${module.id_ue_module}' class='text-center'
      data-id='${module.id_ue_module}'>
            ${module.nom_module}(${module.code_module})
            </option>`;
      mouduleContainer.append(option);
    }
  });
}

// Imprimer pour faire une impression
function imprimer(nom, html, format = "a3", margin = 0) {
  $("#loader").removeClass("d-none");
  $("#loader").addClass("d-flex");
  if (html == null) {
    html = document.getElementById("edt");
  } else {
    nom = nom
      .split("-")
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join("-");
  }

  html2pdf()
    .from(html)
    .set({
      margin: margin,
      filename: nom,
      html2canvas: {
        scale: 2,
      },
      jsPDF: {
        unit: "mm",
        format: format,
        orientation: "landscape",
      },
    })
    .save()
    .then(() => {
      $("#loader").removeClass("d-flex");
      $("#loader").addClass("d-none");
    });
}

// Fonction pour imprimer les relevés de notes
function imprimerReleve() {
  url = ROOT + "/get_releves_notes";
  let promotionId = $("#promotions option:selected").val();
  let semestreId = $("#promotions option:selected").data("semestre");

  $.ajax({
    url: url,
    type: "POST",
    data: {
      idPromotion: promotionId,
      idSemestre: semestreId,
    },
    success: function (response) {
      imprimer(
        "releve-note-" + $("#promotions option:selected").text(),
        response,
        "a4",
        10
      );
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
