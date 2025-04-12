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
  let filiere_id = $("#filiere").val();
  let promotion_id = $("#promotions").val();
  let licence = $("#licences option:selected").data("semestre");
  let semestre_id = $("#semestres").val();
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

// recuperer les semestres d'une filière à travers son id
function licencesPromotion(semestreCourant) {
  const licenceContainer = $("#licences");
  licenceContainer.empty();
  licenceContainer.append(
    `<option value="" selected data-id="">Toutes Licences</option>`
  );
  semestreCourant = parseInt(semestreCourant.slice(-1), 10);
  const nbrLicence = parseInt(semestreCourant / 2, 10);
  if (nbrLicence > 0) {
    for (let index = 1; index <= nbrLicence; index++) {
      licenceContainer.append(
        `<option value="${index}" data-id="${index}" data-semestre="">L ${index}</option>`
      );
    }
  } else {
    licenceContainer.empty();
    licenceContainer.append(
      `<option value="" selected disabled>Aucune Licence Trouvée</option>`
    );
  }
}

// recuperer les semestres d'une filière à travers son id
function semestresLicence(infoFiliere, licence) {
  const semestres = infoFiliere["semestres"];
  var semestreLicence = [];
  switch (licence) {
    case 1:
      semestres.forEach((semestre) => {
        if (
          parseInt(semestre.sigle_semestre.slice(-1), 10) == 1 ||
          parseInt(semestre.sigle_semestre.slice(-1), 10) == 2
        ) {
          semestreLicence += "|" + semestre.id_parcours;
        }
      });
      break;

    case 2:
      semestres.forEach((semestre) => {
        if (
          parseInt(semestre.sigle_semestre.slice(-1), 10) == 3 ||
          parseInt(semestre.sigle_semestre.slice(-1), 10) == 4
        ) {
          semestreLicence += "|" + semestre.id_parcours;
        }
      });
      break;

    case 3:
      semestres.forEach((semestre) => {
        if (
          parseInt(semestre.sigle_semestre.slice(-1), 10) == 5 ||
          parseInt(semestre.sigle_semestre.slice(-1), 10) == 6
        ) {
          semestreLicence += "|" + semestre.id_parcours;
        }
      });
      break;

    default:
      break;
  }

  $("#licences option:selected").attr(
    "data-semestre",
    semestreLicence.slice(1)
  );
}

// recuperer les semestres d'une filière à travers son id
function semestresPromotion(infoFiliere, semestreCourant) {
  const semestreContainer = $("#semestres");
  semestreContainer.empty();
  semestreContainer.append(
    `<option value="" selected>Tous les Semestres</option>`
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
