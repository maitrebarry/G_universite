let semestreCount = 0;

// Fonction pour ajouter un semestre dès qu'il est sélectionné
function addSemestre() {
  const semestre = document.getElementById("idSemestre");
  const semestreId = semestre.value;
  const semestreName = semestre.options[semestre.selectedIndex].text;

  // Vérifier si le semestre est déjà associé
  if (document.getElementById(`semestre_${semestreId}`)) {
    alert(`Semestre ${$semestreId} déjà associé à cette filière.`);
    return;
  }

  semestreCount++;
  const semestresDiv = document.getElementById("semestresTable");

  // Créer le semestre sélectionné dans le tableau
  $.ajax({
    method: "POST",
    url: "HTTP://localhost/G_universite/public/Filieres/post_ajouter_Filiere",
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
    url: "HTTP://localhost/G_universite/public/Filieres/post_ajouter_Filiere",
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
    url: "HTTP://localhost/G_universite/public/Filieres/post_ajouter_Filiere",
    data: {
      action: "module",
      semestreId: semestreId,
    },
    success: function (response) {
      moduleContainer.insertAdjacentHTML("beforeend", response);
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

// Sauvegarder la filière et afficher l'information
function ajouterFiliere() {
  //les différents données à recuperer
  let filiere = {};
  let semestres = [];
  let ues = [];
  let modules = [];

  // Debut de la recuperation des données

  // la recuperation des données de base de la filiere
  const nomFiliere = document.getElementById("nomFiliere").value;
  const sigleFiliere = document.getElementById("sigleFiliere").value;
  filiere = {
    nomFiliere: nomFiliere,
    sigleFiliere: sigleFiliere,
  };

  // Semestres Ues Modules
  const conteneurSemestre = $("#semestresTable").children();
  conteneurSemestre.each(function () {
    // les semestres
    let semestreId = $(this).attr("id");
    semestreId = semestreId.replace("semestre_", "");
    semestres.push({
      idSemestre: semestreId,
    });

    // les Ues et les modules en fonction des semestres
    let tableSemestreId = "tableSemestre_" + semestreId;
    let conteneurUeId = "ueContainer_" + semestreId;
    $("#" + tableSemestreId + " #" + conteneurUeId + " tr").each(function () {
      // les UEs
      let nomUe = $(this).find(".nomUe").val();
      ues.push({
        idSemestre: semestreId,
        nomUe: nomUe,
      });

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
      });
    });
  });
  // Fin de la recuperation des données

  // Debut de l'envoi des données avec Ajax
  $.ajax({
    method: "POST",
    url: "HTTP://localhost/G_universite/public/Filieres/ajouter_Filiere",
    data: {
      action: "ajouter_filiere",
      filiere: filiere,
      semestres: semestres,
      ues: ues,
      modules: modules,
    },
    success: function (response) {
      console.log(response);

      document.getElementById("message").innerHTML = response;
      // Réinitialiser après sauvegarde
      document.getElementById("nomFiliere").value = "";
      document.getElementById("codeFiliere").value = "";
      document.getElementById("idSemestre").value = "";
      document.getElementById("semestresTable").innerHTML = "";
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

// les fonction pour calculer les statistiques
