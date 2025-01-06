// Fonction pour définir l'année scolaire par défaut
function setDefaultAcademicYear() {
  const currentYear = new Date().getFullYear(); // Année en cours
  const nextYear = currentYear + 1; // Année suivante
  const academicYear = `${currentYear}-${nextYear}`; // Format "2024-2025"

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
      }
    });
  } else {
    $("#infoModule").addClass("d-none");
  }
}

function addHeure() {
  var newRow = document.createElement("tr");
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
                <option value='x' class='text-center'>X</option>
                <option value='cm' class='text-center' selected>CM</option>
                <option value='td' class='text-center'>TD</option>
                <option value='tp' class='text-center'>TP</option>
                <option value='tpe' class='text-center'>TPE</option>
                <option value='examen' class='text-center'>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center'>X</option>
                <option value='cm' class='text-center' selected>CM</option>
                <option value='td' class='text-center'>TD</option>
                <option value='tp' class='text-center'>TP</option>
                <option value='tpe' class='text-center'>TPE</option>
                <option value='examen' class='text-center'>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center'>X</option>
                <option value='cm' class='text-center' selected>CM</option>
                <option value='td' class='text-center'>TD</option>
                <option value='tp' class='text-center'>TP</option>
                <option value='tpe' class='text-center'>TPE</option>
                <option value='examen' class='text-center'>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center'>X</option>
                <option value='cm' class='text-center' selected>CM</option>
                <option value='td' class='text-center'>TD</option>
                <option value='tp' class='text-center'>TP</option>
                <option value='tpe' class='text-center'>TPE</option>
                <option value='examen' class='text-center'>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center'>X</option>
                <option value='cm' class='text-center' selected>CM</option>
                <option value='td' class='text-center'>TD</option>
                <option value='tp' class='text-center'>TP</option>
                <option value='tpe' class='text-center'>TPE</option>
                <option value='examen' class='text-center'>EXAMEN</option>
            </select>
        </td>
        <td>
            <select class='select2 form-control tache'>
                <option value='x' class='text-center'>X</option>
                <option value='cm' class='text-center'>CM</option>
                <option value='td' class='text-center'>TD</option>
                <option value='tp' class='text-center'>TP</option>
                <option value='tpe' class='text-center'>TPE</option>
                <option value='examen' class='text-center' selected>EXAMEN</option>
            </select>
        </td>
                                                    
    `;
  document.querySelector("#table-extended-chechbox tbody").appendChild(newRow);
}

function removeHeure() {
  var tableBody = document.querySelector("#table-extended-chechbox tbody");
  var rows = tableBody.querySelectorAll("tr");
  if (rows.length > 1) {
    // Empêche la suppression de la première ligne
    tableBody.removeChild(rows[rows.length - 1]);
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
  const idParcours = $("#semestres").val();
  const idModule = $("#modules").val();
  const idEnseignant = $("#enseignants").val();
  const idSalle = $("#salles").val();
  const anneeUniversitaire = $("#anneeUniversitaire").val();
  const dateDebut = $("#dateDebut").val();
  edt = {
    idFiliere: idFiliere,
    idParcours: idParcours,
    idModule: idModule,
    idEnseignant: idEnseignant,
    idSalle: idSalle,
    anneeUniversitaire: anneeUniversitaire,
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
