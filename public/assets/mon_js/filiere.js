let semestreCount = 0;
const ROOT = "HTTP://localhost/G_universite/public/Filieres";
// Fonction pour ajouter un semestre dès qu'il est sélectionné
function addSemestre() {
  const semestre = document.getElementById("idSemestre");
  const semestreId = semestre.value;
  const semestreName = semestre.options[semestre.selectedIndex].text;

  // Vérifier si le semestre est déjà associé
  if (document.getElementById(`semestre_${semestreId}`)) {
    alert(`Semestre déjà associé à cette filière.`);
    return;
  }

  semestreCount++;
  const semestresDiv = document.getElementById("semestresTable");

  // Créer le semestre sélectionné dans le tableau
  $.ajax({
    method: "POST",
    url: ROOT + "/post_ajouter_Filiere",
    data: {
      action: "semestre",
      semestreId: semestreId,
      semestreName: semestreName,
    },
    success: function (response) {
      semestresDiv.insertAdjacentHTML("beforeend", response);
    },
    error: function (error) {
      console.log(error.status);
    },
  });
}

// Ajouter une Unité d'Enseignement (UE) à un semestre sélectionné
function addUE(semestreId) {
  const ueContainer = document.getElementById(`ueContainer_${semestreId}`);

  $.ajax({
    method: "POST",
    url: ROOT + "/post_ajouter_Filiere",
    data: {
      action: "ue",
      semestreId: semestreId,
    },
    success: function (response) {
      ueContainer.insertAdjacentHTML("beforeend", response);
    },
    error: function (error) {
      console.log(error.status);
    },
  });
}

// Ajouter un module à une UE
function addModule(semestreId, button) {
  const moduleContainer = button.parentElement.nextElementSibling;

  // Créer un menu de sélection de module existant

  $.ajax({
    method: "POST",
    url: ROOT + "/post_ajouter_filiere",
    data: {
      action: "module",
      semestreId: semestreId,
    },
    success: function (response) {
      moduleContainer.insertAdjacentHTML("beforeend", response);
      li = moduleContainer.lastElementChild;
      li.querySelector(".coeficient").addEventListener("keyup", function () {
        calculerHeureModule(li, this.value);
      });
    },
    error: function (error) {
      console.log(error.status);
    },
  });
}

// Supprimer une UE
function removeUE(button) {
  const ueRow = button.closest(".ue-item");
  ueRow.remove();
}

// Supprimer un module
function removeModule(button) {
  const moduleItem = button.closest(".module-item");
  moduleItem.remove();
}

// Calcul des heures d'un module
function calculerHeureModule(li, credit) {
  const vht = credit * 20;
  const cm = vht;
  const td = 0;
  const tp = 0;
  const tpe = 0;
  li.querySelector(".cm").value = cm;
  li.querySelector(".td").value = td;
  li.querySelector(".tp").value = tp;
  li.querySelector(".tpe").value = tpe;
  li.querySelector(".vht").value = vht;
}

// Sauvegarder la filière et afficher l'information
function ajouterFiliere(
  url = ROOT + "/ajouter_Filiere",
  action = "ajouter_filiere"
) {
  //les différents données à recuperer
  let filiere = {};
  let semestres = [];
  let ues = [];
  let modules = [];

  // Debut de la recuperation des données

  // la recuperation des données de base de la filiere
  const nomFiliere = document.getElementById("nomFiliere").value;
  const sigleFiliere = document.getElementById("sigleFiliere").value;
  const idFiliere = $("#nomFiliere").data("id");
  const idDepartement = $("#idDepartement").val();
  filiere = {
    nomFiliere: nomFiliere,
    sigleFiliere: sigleFiliere,
    idFiliere: idFiliere,
    idDepartement: idDepartement,
  };

  // Semestres Ues Modules
  const conteneurSemestre = $("#semestresTable").children();
  conteneurSemestre.each(function () {
    // les semestres
    let semestreId = $(this).attr("id");
    semestreId = semestreId.replace("semestre_", "");
    idParcours = $(this).data("id");
    semestres.push({
      idSemestre: semestreId,
      idParcours: idParcours,
    });

    // les Ues et les modules en fonction des semestres
    let tableSemestreId = "tableSemestre_" + semestreId;
    let conteneurUeId = "ueContainer_" + semestreId;
    $("#" + tableSemestreId + " #" + conteneurUeId + " tr").each(function () {
      // les UEs
      let nomUe = $(this).find(".nomUe").val();
      let idUe = $(this).find(".nomUe").data("id");
      ues.push({
        idSemestre: semestreId,
        nomUe: nomUe,
        idUe: idUe,
      });

      $(this)
        .find("ul li")
        .each(function () {
          // les modules
          let moduleId = $(this).find(".module").val();
          let moduleCm = $(this).find(".cm").val();
          let moduleTd = $(this).find(".td").val();
          let moduleTp = $(this).find(".tp").val();
          let moduleTpe = $(this).find(".tpe").val();
          let moduleCode = $(this).find(".code").val();
          let moduleCoeficient = $(this).find(".coeficient").val();
          let moduleCredit = $(this).find(".credit").val();
          modules.push({
            idSemestre: semestreId,
            nomUe: nomUe,
            idModule: moduleId,
            moduleCm: moduleCm,
            moduleTd: moduleTd,
            moduleTp: moduleTp,
            moduleTpe: moduleTpe,
            moduleCode: moduleCode,
            moduleCoeficient: moduleCoeficient,
            idUeModule: $(this).find(".module").data("id"),
          });
        });
    });
  });
  // Fin de la recuperation des données

  // Debut de l'envoi des données avec Ajax
  $.ajax({
    method: "POST",
    url: url,
    data: {
      action: action,
      filiere: filiere,
      semestres: semestres,
      ues: ues,
      modules: modules,
    },
    success: function (response) {
      console.log(response);

      // location.reload();
      document.getElementById("message").innerHTML = response;
      if (response.includes("success")) {
        if (action === "ajouter_filiere") {
          document.getElementById("nomFiliere").value = "";
          document.getElementById("sigleFiliere").value = "";
          document.getElementById("semestresTable").innerHTML = "";
        } else if (action == "ajouter_element_filiere") {
          window.location.reload();
        }
      }
      // Réinitialiser après sauvegarde

      // document.getElementById("idSemestre").value = "";

      document.getElementById("message").innerHTML = response;
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

// Supprimer un élement d'une filière
function delElementFiliere(button, id = null, action = "ue_module") {
  Swal.fire({
    title: "Êtes vous sûr?",
    text: "L'élement sera complètement supprimer",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonClass: "btn btn-primary",
    cancelButtonClass: "btn btn-danger ml-1",
    buttonsStyling: false,
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        method: "POST",
        url: ROOT + "/supprimer_element_filiere",
        data: {
          action: action,
          id: id,
        },
        success: function (response) {
          console.log(response);

          switch (response) {
            case "success":
              if (action == "ue_module") {
                removeModule(button);
              } else if (action == "ue") {
                removeUE(button);
              }
              Swal.fire({
                type: "success",
                title: "Succès",
                text: "Element Supprimé avec succès",
                timer: 2000,
                confirmButtonClass: "btn btn-primary",
                buttonsStyling: false,
              });
              break;

            case "notExist":
              Swal.fire({
                title: "Attention",
                text: "Cet element est introuvable",
                timer: 2000,
                type: "warning",
                confirmButtonClass: "btn btn-primary",
                buttonsStyling: false,
              });
              break;

            case "error":
              Swal.fire({
                type: "error",
                title: "Erreur",
                timer: 2000,
                text: "Impossible de supprimer cet élement!",
                confirmButtonClass: "btn btn-primary",
                buttonsStyling: false,
              });
              break;
            default:
              break;
          }
          // window.scrollTo({
          //   top: 0,
          //   behavior: "smooth",
          // });
        },
        error: function (error) {
          console.log(error.status);
        },
      });
    }
  });

  // Fin de l'envoi des données avec Ajax
}

// Fonction de validation globale
function validateForm() {
  let isValid = true;

  // Vérification des champs obligatoires
  $(".form-control").each(function () {
    const value = $(this).val().trim();
    if (!value) {
      isValid = false;
      $(this).next(".error").text("Ce champ est obligatoire.");
    } else {
      $(this).next(".error").text("");
    }
  });

  // Vérification du champ sigleFiliere (max 15 caractères)
  const sigleFiliere = $("#sigleFiliere").val().trim();
  if (sigleFiliere.length > 15) {
    isValid = false;
    $("#sigleFiliere").val(sigleFiliere.substring(0, 15));
    $("#sigleFiliereError").text("Le code ne peut pas dépasser 15 caractères.");
  } else if (/\d/.test(sigleFiliere)) {
    // Validation pour interdire les chiffres
    isValid = false;
    $("#sigleFiliereError").text("Le code ne doit pas contenir de chiffres.");
  } else {
    $("#sigleFiliereError").text("");
  }

  // Vérification du champ nomFiliere (max 50 caractères)
  const nomFiliere = $("#nomFiliere").val().trim();
  if (nomFiliere.length > 100) {
    isValid = false;
    $("#nomFiliere").val(nomFiliere.substring(0, 100));
    $("#nomFiliereError").text("Le nom ne peut pas dépasser 100 caractères.");
  } else if (/\d/.test(nomFiliere)) {
    // Validation pour interdire les chiffres
    isValid = false;
    $("#nomFiliereError").text("Le nom ne doit pas contenir de chiffres.");
  } else {
    $("#nomFiliereError").text("");
  }

  // Vérification du champ idSemestre
  if ($("#idSemestre").val() === "") {
    isValid = false;
    $("#idSemestreError").text("Veuillez sélectionner un semestre.");
  } else {
    $("#idSemestreError").text("");
  }

  // Activation ou désactivation du bouton
  $("#btnEnregistrer").prop("disabled", !isValid);
}
