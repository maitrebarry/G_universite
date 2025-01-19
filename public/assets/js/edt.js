// Contantes pour les heures de l'edt
const heureEdt = {
  "edt-row": {
    1: { heureDebut: "08:00", heureFin: "10:00" },
    2: { heureDebut: "10:15", heureFin: "13:15" },
    3: { heureDebut: "14:00", heureFin: "16:00" },
    4: { heureDebut: "16:00", heureFin: "18:00" },
  },
  "edt-column": {
    1: { heureDebut: "08:00", heureFin: "10:00" },
    2: { heureDebut: "10:15", heureFin: "12:15" },
    3: { heureDebut: "14:00", heureFin: "16:00" },
    4: { heureDebut: "16:00", heureFin: "18:00" },
  },
};
const typeEdt = ["cm", "td", "tp", "tpe"];

function addHeure(heureDebut, heureFin, coursJour) {
  var newRow = document.createElement("tr");
  isExams =
    $("#table-extended-chechbox tbody").find("tr").length === 0
      ? "selected"
      : "";

  newRow.innerHTML = `                         
        <td>
            <div class='row'>
                <div class='col-sm-6'>
                    <input type='time' class='form-control heureDebut'>
                </div>
                <div class='col-sm-6'>
                    <input type='time' class='form-control heureFin'>
                </div>
            </div>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center' ${
                  coursJour.l == "x" ? "selected" : ""
                }>X</option>
                <option value='cm' class='text-center' ${
                  coursJour.l == "cm" ? "selected" : ""
                }>CM</option>
                <option value='td' class='text-center' ${
                  coursJour.l == "td" ? "selected" : ""
                }>TD</option>
                <option value='tp' class='text-center' ${
                  coursJour.l == "tp" ? "selected" : ""
                }>TP</option>
                <option value='tpe' class='text-center' ${
                  coursJour.l == "tpe" ? "selected" : ""
                }>TPE</option>
                <option value='examen' class='text-center' ${
                  coursJour.l == "examen" ? "selected" : ""
                }>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center' ${
                  coursJour.m == "x" ? "selected" : ""
                }>X</option>
                <option value='cm' class='text-center' ${
                  coursJour.m == "cm" ? "selected" : ""
                }>CM</option>
                <option value='td' class='text-center' ${
                  coursJour.m == "td" ? "selected" : ""
                }>TD</option>
                <option value='tp' class='text-center' ${
                  coursJour.m == "tp" ? "selected" : ""
                }>TP</option>
                <option value='tpe' class='text-center' ${
                  coursJour.m == "tpe" ? "selected" : ""
                }>TPE</option>
                <option value='examen' class='text-center' ${
                  coursJour.m == "examen" ? "selected" : ""
                }>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center' ${
                  coursJour.mer == "x" ? "selected" : ""
                }>X</option>
                <option value='cm' class='text-center' ${
                  coursJour.mer == "cm" ? "selected" : ""
                }>CM</option>
                <option value='td' class='text-center' ${
                  coursJour.mer == "td" ? "selected" : ""
                }>TD</option>
                <option value='tp' class='text-center' ${
                  coursJour.mer == "tp" ? "selected" : ""
                }>TP</option>
                <option value='tpe' class='text-center' ${
                  coursJour.mer == "tpe" ? "selected" : ""
                }>TPE</option>
                <option value='examen' class='text-center' ${
                  coursJour.mer == "examen" ? "selected" : ""
                }>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center' ${
                  coursJour.j == "x" ? "selected" : ""
                }>X</option>
                <option value='cm' class='text-center' ${
                  coursJour.j == "cm" ? "selected" : ""
                }>CM</option>
                <option value='td' class='text-center' ${
                  coursJour.j == "td" ? "selected" : ""
                }>TD</option>
                <option value='tp' class='text-center' ${
                  coursJour.j == "tp" ? "selected" : ""
                }>TP</option>
                <option value='tpe' class='text-center' ${
                  coursJour.j == "tpe" ? "selected" : ""
                }>TPE</option>
                <option value='examen' class='text-center' ${
                  coursJour.j == "examen" ? "selected" : ""
                }>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center' ${
                  coursJour.v == "x" ? "selected" : ""
                }>X</option>
                <option value='cm' class='text-center' ${
                  coursJour.v == "cm" ? "selected" : ""
                }>CM</option>
                <option value='td' class='text-center' ${
                  coursJour.v == "td" ? "selected" : ""
                }>TD</option>
                <option value='tp' class='text-center' ${
                  coursJour.v == "tp" ? "selected" : ""
                }>TP</option>
                <option value='tpe' class='text-center' ${
                  coursJour.v == "tpe" ? "selected" : ""
                }>TPE</option>
                <option value='examen' class='text-center' ${
                  coursJour.v == "examen" ? "selected" : ""
                }>EXAMEN</option>
            </select>
        </td>
       
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center'>X</option>
                <option value='cm' class='text-center'>CM</option>
                <option value='td' class='text-center'>TD</option>
                <option value='tp' class='text-center'>TP</option>
                <option value='tpe' class='text-center'>TPE</option>
                <option value='examen' class='text-center' ${isExams} >EXAMEN</option>
            </select>
        </td>
                                                    
    `;
  document.querySelector("#table-extended-chechbox tbody").appendChild(newRow);
  row = document.querySelector(
    "#table-extended-chechbox tbody"
  ).lastElementChild;
  row.querySelector(".heureDebut").value = heureDebut;
  row.querySelector(".heureFin").value = heureFin;
}

function removeHeure() {
  var tableBody = document.querySelector("#table-extended-chechbox tbody");
  var rows = tableBody.querySelectorAll("tr");
  if (rows.length > 1) {
    // Empêche la suppression de la première ligne
    tableBody.removeChild(rows[rows.length - 1]);
  }
}

function genererCoursEdt(type) {
  let coursJour;
  if (type.toUpperCase() !== "all") {
    coursJour = {
      l: type,
      m: type,
      mer: type,
      j: type,
      v: type,
      s: type,
    };
  }
  return coursJour;
}

function genererEdt(heureTotal, model = "edt-row", type = 0) {
  document.querySelector("#table-extended-chechbox tbody").innerHTML = "";
  if (model == "edt-row") {
    if (typeEdt[type].toUpperCase() !== "all") {
      const ligne = heureTotal / 8;
      heure = heureEdt[model];
      for (let index = 1; index < ligne; index++) {
        const heureDebut = heure[index].heureDebut;
        const heureFin = heure[index].heureFin;
        coursJour = genererCoursEdt(typeEdt[type]);
        addHeure(heureDebut, heureFin, coursJour);
      }
    }
  } else if (model == "edt-column") {
    if (typeEdt[type].toUpperCase() !== "all") {
    }
  }
}

// Fonction pour définir l'année scolaire par défaut
function setDefaultAcademicYear() {
  const currentYear = new Date().getFullYear(); // Année en cours
  const lastYear = currentYear - 1; // Année suivante
  const academicYear = `${lastYear}-${currentYear}`; // Format "2024-2025"

  // Affecter la valeur par défaut à l'input
  document.getElementById("anneeUniversitaire").value = academicYear;
}

// Fonction pour valider et formater l'input
function formatAcademicYear(event) {
  const input = event.target;
  const value = input.value.replace(/\D/g, ""); // Supprimer tout sauf les chiffres
  if (value.length === 4) {
    const currentYear = parseInt(value, 10); // Convertir les 4 premiers caractères en nombre
    const nextYear = currentYear + 1; // Calculer l'année suivante
    input.value = `${currentYear}-${nextYear}`; // Appliquer le format "YYYY-YYYY"
  }
}

const ROOT = "HTTP://localhost/G_universite/public/Emploi_du_temps";
async function infosFiliere(idFiliere) {
  try {
    response = await $.ajax({
      method: "POST",
      url: ROOT + "/filiere_info",
      dataType: "json",

      data: {
        action: "semestre",
        idFiliere: idFiliere,
      },
    });
    return response;
  } catch (error) {
    console.error(error);
  }
  var infoFiliere;
}

function promotionsFiliere(infoFiliere) {
  const promotionContainer = $("#promotions");
  promotionContainer.empty();
  promotionContainer.append(
    `<option value="" disabled selected>Selectionner une Promotion</option>`
  );
  const promotions = infoFiliere["promotions"];
  promotions.forEach((promotion) => {
    const option = `<option value='${
      promotion.id_promotion
    }' class='text-center' data-id='${promotion.id_parcours}'>
    ${promotion.sigle_filiere.toUpperCase()}-${promotion.sigle_semestre.toUpperCase()}( ${
      promotion.annee_universitaire
    } )</option>`;
    promotionContainer.append(option);
  });
}

function semestresFiliere(infoFiliere) {
  const semestreContainer = $("#semestres");
  semestreContainer.empty();
  semestreContainer.append(
    `<option value="" disabled selected>Selectionner un Semestre</option>`
  );
  const semestres = infoFiliere["semestres"];
  semestres.forEach((semestre) => {
    const option = `<option value='${semestre.id_parcours}' class='text-center'>${semestre.nom_semestre}(${semestre.sigle_semestre})</option>`;
    semestreContainer.append(option);
  });
}

function modulesSemestre(idSemestre, infoFiliere) {
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
          const option = `<option value='${module.id_ue_module}' class='text-center'>${module.nom_module}(${module.code_module})</option>`;
          mouduleContainer.append(option);
        }
      });
    }
  });
}

function infoModule(idModule, infoFiliere) {
  if (idModule != null && idModule != "") {
    modules = infoFiliere["modules"];
    modules.forEach((module) => {
      if (module.id_ue_module == idModule) {
        $("#infoModule").removeClass("d-none");
        $("#infoModule").attr("display", "block");
        $(".cm").val(module.cm);
        $(".td").val(module.td);
        $(".tp").val(module.tp);
        $(".tpe").val(module.tpe);

        const heureTotal =
          parseInt(module.cm, 10) +
          parseInt(module.td, 10) +
          parseInt(module.tp, 10) +
          parseInt(module.tpe, 10);
        $(".vht").val(heureTotal);

        const model = $("#model-row").hasClass("border-primary")
          ? $("#model-row").data("model")
          : $("#model-column").data("model");
        const type = parseInt($('input[name="type"]:checked').val(), 10);
        genererEdt(heureTotal, model, type);
      }
    });
  } else {
    $("#infoModule").addClass("d-none");
    document.querySelector("#table-extended-chechbox tbody").innerHTML = "";
  }
}

// Sauvegarder les informations d'un emploi de temps
function ajouterEdt(url = ROOT + "/ajouter_EDT", action = "ajouter_EDT") {
  //les différents données à recuperer
  let edt = {};
  let horaires = [];

  // Debut de la recuperation des données

  // la recuperation des données de base de l'emploi
  const idFiliere = $("#filiere").val();
  const idPromotion = $("#promotions").val();
  const idModule = $("#modules").val();
  const idEnseignant = $("#enseignants").val();
  const idSalle = $("#salles").val();
  const dateDebut = $("#dateDebut").val();
  edt = {
    idFiliere: idFiliere,
    idPromotion: idPromotion,
    idModule: idModule,
    idEnseignant: idEnseignant,
    idSalle: idSalle,
    dateDebut: dateDebut,
  };

  // La recuperation des horaires et des taches
  $("#table-extended-chechbox tbody tr").each(function () {
    const heureDebut = $(this).find(".heureDebut").val();
    const heureFin = $(this).find(".heureFin").val();
    const taches = [];
    $(this)
      .find("td")
      .each(function (index) {
        if (index != 0) {
          const typeTache = $(this).find(".tache").val();
          const idJour = $("#table-extended-chechbox thead th")
            .eq(index)
            .data("id");
          taches.push({
            typeTache: typeTache,
            idJour: idJour,
          });
        }
      });

    horaires.push({
      heureDebut: heureDebut,
      heureFin: heureFin,
      taches: taches,
    });
  });

  // Fin de la recuperation des données

  // Debut de l'envoi des données avec Ajax
  $.ajax({
    method: "POST",
    url: url,
    data: {
      action: action,
      edt: edt,
      horaires: horaires,
    },
    success: function (response) {
      console.log(response);

      // location.reload();
      document.getElementById("message").innerHTML = response;
      if (response.includes("success") && action === "ajouter_EDT") {
      }
      // Réinitialiser après sauvegarde

      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    },
    error: function (error) {
      console.log(error.status);
    },
  });
  // Fin de l'envoi des données avec Ajax
}
