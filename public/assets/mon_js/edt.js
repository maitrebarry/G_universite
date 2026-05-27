// Contantes pour les heures de l'edt

window.ROOT_EDT = window.ROOT_EDT || (window.APP_ROUTE ? window.APP_ROUTE("Emploi_du_temps") : window.APP_ROOT + "/Emploi_du_temps");
window.ROOT_NOTES = window.ROOT_NOTES || (window.APP_ROUTE ? window.APP_ROUTE("Notes") : window.APP_ROOT + "/Notes");

const horaireEdt = {
  simple: {
    1: { heureDebut: "08:00", heureFin: "10:00" },
    2: { heureDebut: "10:15", heureFin: "12:15" },
    3: { heureDebut: "14:00", heureFin: "16:00" },
    4: { heureDebut: "16:00", heureFin: "18:00" },
  },
  complexe: {
    1: { heureDebut: "08:00", heureFin: "10:00" },
    2: { heureDebut: "10:15", heureFin: "13:15" },
    3: { heureDebut: "14:00", heureFin: "16:00" },
    4: { heureDebut: "16:00", heureFin: "19:00" },
  },
};
const typeEdt = ["cm", "td", "tp", "all"];

// calcul des heures total d'un module
function calculerHeuresModuleEdt() {
  const heureTotal = parseInt($("#vht").val(), 10);
  const heureCm = parseInt($(".cm").val(), 10);
  const heureTd = parseInt($(".td").val(), 10);
  const heureTp = parseInt($(".tp").val(), 10);
  const heureTpe = parseInt($(".tpe").val(), 10);
  const heuresModule = {
    heureCm: heureCm,
    heureTd: heureTd,
    heureTp: heureTp,
    heureTpe: heureTpe,
    heureTotal: heureTotal,
  };
  return heuresModule;
}

// generer les type de cours d'un edt
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

// generer un edt en fonction des nombres d'heures(cm, td, tp)
function genererEdtMixed(heuresModule, nbrColonne = 4) {
  let nbrHeureCm = heuresModule.heureCm;
  let nbrHeureTd = heuresModule.heureTd;
  let nbrHeureTp = heuresModule.heureTp;

  for (let colonne = 0; colonne <= nbrColonne; colonne++) {
    $("#table-extended-chechbox tbody tr").each(function (index) {
      const tache = $(this).find(".tache").eq(colonne);
      if (nbrHeureCm != null && nbrHeureCm > 1) {
        tache.val("cm");
        nbrHeureCm -= 2;
        if (nbrColonne == 5 && (colonne == 1 || colonne == 3)) {
          nbrHeureCm += 2;
          nbrHeureCm -= 3;
        }
      } else if (nbrHeureTd != null && nbrHeureTd > 1) {
        tache.val("td");
        nbrHeureTd -= 2;
        if (nbrColonne == 5 && (colonne == 1 || colonne == 3)) {
          nbrHeureCm += 2;
          nbrHeureCm -= 3;
        }
      } else if (nbrHeureTp != null && nbrHeureTp > 1) {
        tache.val("tp");
        nbrHeureTp -= 2;
        if (nbrColonne == 5 && (colonne == 1 || colonne == 3)) {
          nbrHeureCm += 2;
          nbrHeureCm -= 3;
        }
      }
    });
  }
}

// ajouter une ligne à un edt
function addHeure(horaireDebut, horaireFin, coursJour) {
  var newRow = document.createElement("tr");
  heureTotal = calculerHeuresModuleEdt().heureTotal;
  isExams =
    $("#table-extended-chechbox tbody").find("tr").length === 0 &&
    heureTotal <= 40
      ? "selected"
      : "";

  newRow.innerHTML = `                         
        <td style='min-width: 236px !important;'>
            <div class='row'>
                <div class='col-6'>
                    <input type='time' class='form-control horaireDebut'>
                </div>
                <div class='col-6'>
                    <input type='time' class='form-control horaireFin'>
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
                <option value='x' class='text-center' ${
                  coursJour.s == "x" && heureTotal > 40 ? "selected" : ""
                }>X</option>
                <option value='cm' class='text-center' ${
                  coursJour.s == "cm" && heureTotal > 40 ? "selected" : ""
                }>CM</option>
                <option value='td' class='text-center' ${
                  coursJour.s == "td" && heureTotal > 40 ? "selected" : ""
                }>TD</option>
                <option value='tp' class='text-center' ${
                  coursJour.s == "tp" && heureTotal > 40 ? "selected" : ""
                }>TP</option>
                <option value='tpe' class='text-center' ${
                  coursJour.s == "tpe" && heureTotal > 40 ? "selected" : ""
                }>TPE</option>
                <option value='examen' class='text-center' ${isExams} >EXAMEN</option>
            </select>
        </td>
                                                    
    `;
  document.querySelector("#table-extended-chechbox tbody").appendChild(newRow);
  row = document.querySelector(
    "#table-extended-chechbox tbody"
  ).lastElementChild;
  row.querySelector(".horaireDebut").value = horaireDebut;
  row.querySelector(".horaireFin").value = horaireFin;
}
// supprimer une ligne d'un edt
function removeHeure() {
  var tableBody = document.querySelector("#table-extended-chechbox tbody");
  var rows = tableBody.querySelectorAll("tr");
  if (rows.length > 1) {
    // Empêche la suppression de la première ligne
    tableBody.removeChild(rows[rows.length - 1]);
  }
}

// generer un edt
function genererEdt(heuresModule, model = "edt-row", type = 3) {
  document.querySelector("#table-extended-chechbox tbody").innerHTML = "";
  heureTotal = heuresModule.heureTotal;
  horaire = heureTotal <= 40 ? horaireEdt["simple"] : horaireEdt["complexe"];
  if (model == "edt-row") {
    ligne = heureTotal / 8 >= 4 ? 4 : heureTotal / 8;

    for (let index = 1; index <= ligne; index++) {
      const horaireDebut = horaire[index].heureDebut;
      const horaireFin = horaire[index].heureFin;
      coursJour = genererCoursEdt(typeEdt[type]);
      addHeure(horaireDebut, horaireFin, coursJour);
    }
  } else if (model == "edt-column") {
    let coursJour = {
      l: typeEdt[type],
      m: typeEdt[type],
      mer: typeEdt[type],
      j: typeEdt[type],
      v: typeEdt[type],
      s: typeEdt[type],
    };

    for (let index = 1; index <= 4; index++) {
      const horaireDebut = horaire[index].heureDebut;
      const horaireFin = horaire[index].heureFin;
      nbrTotal = Math.ceil(heureTotal / 8);
      for (let clee in coursJour) {
        if (nbrTotal-- <= 0) {
          coursJour[clee] = "x";
        }
        if (heureTotal % 8 != 0 && index > heureTotal / 8 && nbrTotal == 0) {
          coursJour[clee] = "x";
        }
      }

      addHeure(horaireDebut, horaireFin, coursJour);
    }
  }
  if (typeEdt[type].toUpperCase() === "ALL") {
    nbrColonne = 4;
    if (heureTotal > 40) {
      nbrColonne = 5;
    }
    genererEdtMixed(heuresModule, nbrColonne);
  }
}

// recuperer toute les informations d'une filière à travers son id (promotions et modules )
async function infosFiliere(idFiliere, source = null) {
  try {
    response = await $.ajax({
      method: "POST",
      url: window.ROOT_EDT + "/filiere_info",
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
// function classesAnneeUniversitaire(anneeUniversitaire) {
//   $.ajax({
//     method: "POST",
//     url: "http://localhost:8080/G_universite/public/Notes/get_classe_annee",
//     dataType: "json",

//     data: {
//       anneeUniversitaire: anneeUniversitaire,
//     },
//     success: function (response) {
//       const promotionContainer = $("#promotions");
//       promotionContainer.empty();
//       promotionContainer.append(
//         `<option value="" >Selectionner une Classe</option>`
//       );
//       const promotions = response;
//       promotions.forEach((promotion) => {
//         const option = `<option value='${promotion.id_promotion}' class='text-center' data-filiere='${promotion.id_filiere}'data-semestre='${promotion.id_parcours}'> 
//         ${promotion.classe}</option>`;
//         promotionContainer.append(option);
//       });
//     },
//     error: function () {},
//   });
// }
function classesAnneeUniversitaire(anneeUniversitaire) {
  $.ajax({
    method: "POST",
    url: window.ROOT_NOTES + "/get_classe_annee",
    dataType: "json",
    data: {
      anneeUniversitaire: anneeUniversitaire,
    },
    success: function (response) {
      const promotionContainer = $("#promotions");
      promotionContainer.empty();
      promotionContainer.append(
        `<option value="">Selectionner une Classe</option>`
      );
      const promotions = response || [];

      promotions.forEach((promotion) => {
        const option = `
          <option value='${promotion.id_promotion}' 
                  class='text-center' 
                  data-filiere='${promotion.id_filiere}' 
                  data-semestre='${promotion.id_parcours}'> 
            ${promotion.classe}
          </option>`;
        promotionContainer.append(option);
      });
    },
    error: function (xhr, status, error) {
      console.error("Erreur lors du chargement des classes :", error);
    },
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

// recueperer les information d'un module à travers son id (cm,td,tp,tpe,credit)
function infoModule(idModule, infoFiliere, makeEdt = true) {
  if (idModule != null && idModule != "") {
    modules = infoFiliere["modules"];
    modules.forEach((module) => {
      if (module.id_ue_module == idModule) {
        $("#infoModule").removeClass("d-none");
        $("#infoModule").addClass("d-flex justify-content-end");
        $("#infoModule").attr("display", "block");
        $(".cm").val(module.cm);
        $(".td").val(module.td);
        $(".tp").val(module.tp);
        $(".tpe").val(module.tpe);

        heureCm = parseInt(module.cm, 10);
        heureTd = parseInt(module.td, 10);
        heureTp = parseInt(module.tp, 10);
        heureTpe = parseInt(module.tpe, 10);

        const heureTotal =
          parseInt(module.cm, 10) +
          parseInt(module.td, 10) +
          parseInt(module.tp, 10);
        $(".vht").val(heureTotal);

        const heuresModule = {
          heureCm: heureCm,
          heureTd: heureTd,
          heureTp: heureTp,
          heureTpe: heureTpe,
          heureTotal: heureTotal,
        };
        if (heureTotal > 40) {
        }

        const model = $("#model-row").hasClass("border-primary")
          ? $("#model-row").data("model")
          : $("#model-column").data("model");
        const type = parseInt($('input[name="type"]:checked').val(), 10);
        if (makeEdt == true) {
          genererEdt(heuresModule, model, type);
        }
      }
    });
  } else {
    $("#infoModule").addClass("d-none");
  }
}

// Sauvegarder les informations d'un emploi de temps
function ajouterEdt(url = window.ROOT_EDT + "/ajouter_EDT", action = "ajouter_EDT") {
  //les différents données à recuperer
  let edt = {};
  let horaires = [];

  // Debut de la recuperation des données

  // la recuperation des données de base de l'emploi
  const idFiliere = $("#promotions option:selected").data("filiere");
  const idPromotion = $("#promotions option:selected").val();
  const idSemestre = $("#promotions option:selected").data("semestre");
  const idModule = $("#modules").val();
  var enseignants = [];
  $("#corpsEnseignant tr").each(function (index) {
    row = $(this);
    let salle = $("#groupeSelect").is(":checked")
      ? row.find(".salle").val()
      : $("#salles").val();
    enseignants.push({
      enseignant: row.find(".id").attr("id"),
      groupe: row.find("#groupe").val(),
      nombreHeure: row.find(".nombreHeure").val(),
      typeCours: row.find(".typeCours").val(),
      salle: salle,
    });
  });
  console.log(enseignants);

  const dateDebut = $("#dateDebut").val();
  const heureTotal = parseInt($("#vht").val(), 10);
  const idEdt = $("#valider").data("id");
  edt = {
    idFiliere: idFiliere,
    idPromotion: idPromotion,
    idSemestre: idSemestre,
    idModule: idModule,
    dateDebut: dateDebut,
    heureTotal: heureTotal,
    idEdt: idEdt,
  };

  // La recuperation des horaires et des taches
  $("#corpsEdt tr").each(function () {
    const heureDebut = $(this).find(".horaireDebut").val();
    const heureFin = $(this).find(".horaireFin").val();
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
      enseignants: enseignants,
    },
    success: function (response) {
      console.log(response);

      // location.reload();
      document.getElementById("message").innerHTML = response;
      if (response.includes("primary") && action == "ajouter_EDT") {
        document.querySelector("#table-extended-chechbox tbody").innerHTML = "";
        let promotionNom = $("#promotions option:selected")
          .text()
          .trim()
          .replaceAll("(", "-");
        promotionNom = promotionNom.replaceAll(")", "");

        const enseignantNom = $("#enseignants option:selected")
          .text()
          .trim()
          .split(" ", 1);
        const nomEdt = "Edt-" + promotionNom + "-" + enseignantNom;

        $("#print").click(function (event) {
          event.preventDefault();
          window.scrollTo({
            top: 0,
            behavior: "smooth",
          });
          var element = $(this);
          setTimeout(function () {
            imprimerEdt(element.data("id"), nomEdt);
          }, 500);
          $(".champ").val("");
          $("#modules").val("");
        });
        $("#corpsEnseignant tr").html("");
      }
      if (response.includes("primary") && action == "editer_edt") {
        window.location.href = window.ROOT_EDT + "/";
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

// Trier la liste des edts
function trierListeEdt(idFiliere, idPromotion, idSemestre) {
  // Debut de l'envoi des données avec Ajax
  $.ajax({
    method: "POST",
    url: window.ROOT_EDT + "/trier_liste_edt",
    data: {
      action: "trier_edt",
      idFiliere: idFiliere,
      idPromotion: idPromotion,
      idSemestre: idSemestre,
    },
    success: function (response) {
      $("#listeEdts").html(response);
      $(".imprimerEdt").click(function (event) {
        event.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
        var element = $(this);
        setTimeout(function () {
          imprimerEdt(element.data("id"), element.data("nom"));
        }, 500);
      });

      // tous coucher ou decocher
      $("#edts tbody tr").each(function () {
        if ($(this).find(".isSelected").data("statut") == 0) {
          $(this)
            .find(".isSelected")
            .click(function () {
              $("#allSelect").prop("checked", true);
              isAll = true;

              $("#edts tbody tr").each(function () {
                if (
                  $(this).find(".isSelected").data("statut") == 0 &&
                  !$(this).find(".isSelected").prop("checked")
                ) {
                  isAll = false;
                }
              });

              if (!isAll) {
                $("#allSelect").prop("checked", false);
              }
            });
        }
      });

      $("#allSelect").change(function (event) {
        if ($(this).prop("checked")) {
          $("#edts tbody tr").each(function () {
            if ($(this).find(".isSelected").data("statut") == 0) {
              $(this).find(".isSelected").prop("checked", true);
            }
          });
        } else {
          $("#edts tbody tr").each(function () {
            $(this).find(".isSelected").prop("checked", false);
          });
        }
      });
    },

    error: function (error) {},
  });
  // Fin de l'envoi des données avec Ajax
}
//la fonction pour imprimer un editer
function imprimerEdt(idEdt, nomEdt = "edt") {
  // Debut de l'envoi des données avec Ajax
  $.ajax({
    method: "POST",
    url: window.ROOT_EDT + "/apercu_EDT/" + idEdt,
    data: {
      action: "print",
    },
    success: function (response) {
      imprimer(nomEdt, response);
    },
    error: function (error) {
      console.log(error.status);
    },
  });
  // Fin de l'envoi des données avec Ajax
}
// Imprimer pour faire une impression
function imprimer(nomEdt, html = null) {
  $("#loader").removeClass("d-none");
  $("#loader").addClass("d-flex");
  if (html == null) {
    html = document.getElementById("edt");
  } else {
    nomEdt = nomEdt
      .split("-")
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join("-");
  }

  html2pdf()
    .from(html)
    .set({
      margin: 0,
      filename: nomEdt,
      html2canvas: {
        scale: 2,
      },
      jsPDF: {
        unit: "mm",
        format: "a4",
        orientation: "landscape",
      },
    })
    .save()
    .then(() => {
      $("#loader").removeClass("d-flex");
      $("#loader").addClass("d-none");
    });
  // printJS({
  //   printable: "edt",
  //   type: "html",
  //   documentTitle: nomEdt,
  //   targetStyles: ["*"],
  //   style: `@page{size:landscape}
  //   body * {
  //       visibility: hidden;
  //   }
  //   #edt,
  //   #edt * {
  //       visibility: visible;
  //   }
  //   #edt {
  //       position: absolute;
  //       left: 0;
  //       top: 0;
  //   }
  //         `,
  // });
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

// recuperer un ancien enseignant et une acienne sale d'un ancien edt
function getDefaultEnseignantAndSalleModule(idFiliere, idModule) {
  $.ajax({
    method: "POST",
    url: window.ROOT_EDT + "/get_ancien_edt",
    dataType: "json",

    data: {
      idFiliere: idFiliere,
      idModule: idModule,
    },
    success: function (response) {
      if (response != null) {
        ancienEnseignant = response.id_enseignant;
        ancienneSalle = response.id_salle;

        $("#enseignants").val(ancienEnseignant);
        $("#salles").val(ancienneSalle);
        $("#enseignants").addClass("select2");
      }
    },
    error: function (error) {},
  });
}

// les methodes pour la gestion des enseignants
