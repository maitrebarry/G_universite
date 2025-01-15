// // Contantes pour les heures de l'edt
// const heureEdt = {
//   "edt-row": {
//     1: { heureDebut: "08:00", heureFin: "10:00" },
//     2: { heureDebut: "10:15", heureFin: "13:15" },
//     3: { heureDebut: "14:00", heureFin: "16:00" },
//     4: { heureDebut: "16:00", heureFin: "19:00" },
//   },
//   "edt-column": {
//     1: { heureDebut: "08:00", heureFin: "10:00" },
//     2: { heureDebut: "10:15", heureFin: "12:15" },
//     3: { heureDebut: "14:00", heureFin: "16:00" },
//     4: { heureDebut: "16:00", heureFin: "18:00" },
//   },
// };
// const typeEdt = ["cm", "td", "tp", "tpe"];
// // Fonction pour définir l'année scolaire par défaut
// function setDefaultAcademicYear() {
//   const currentYear = new Date().getFullYear(); // Année en cours
//   const lastYear = currentYear - 1; // Année suivante
//   const academicYear = `${lastYear}-${currentYear}`; // Format "2024-2025"

//   // Affecter la valeur par défaut à l'input
//   document.getElementById("anneeUniversitaire").value = academicYear;
// }

// // Fonction pour valider et formater l'input
// function formatAcademicYear(event) {
//   const input = event.target;
//   const value = input.value.replace(/\D/g, ""); // Supprimer tout sauf les chiffres
//   if (value.length === 4) {
//     const currentYear = parseInt(value, 10); // Convertir les 4 premiers caractères en nombre
//     const nextYear = currentYear + 1; // Calculer l'année suivante
//     input.value = `${currentYear}-${nextYear}`; // Appliquer le format "YYYY-YYYY"
//   }
// }

// const ROOT = "HTTP://localhost/G_universite/public/Emploi_du_temps";
// async function infosFiliere(idFiliere) {
//   try {
//     response = await $.ajax({
//       method: "POST",
//       url: ROOT + "/filiere_info",
//       dataType: "json",

//       data: {
//         action: "semestre",
//         idFiliere: idFiliere,
//       },
//     });
//     return response;
//   } catch (error) {
//     console.error(error);
//   }
//   var infoFiliere;
// }

// function promotionsFiliere(infoFiliere) {
//   const promotionContainer = $("#promotions");
//   promotionContainer.empty();
//   promotionContainer.append(
//     `<option value="" disabled selected>Selectionner une Promotion</option>`
//   );
//   const promotions = infoFiliere["promotions"];
//   promotions.forEach((promotion) => {
//     const option = `<option value='${
//       promotion.id_promotion
//     }' class='text-center  text-uppercase' data-id='${promotion.id_parcours}'>
//     ${promotion.sigle_filiere.toUpperCase()}-${promotion.sigle_semestre.toUpperCase()}( ${
//       promotion.annee_universitaire
//     } )</option>`;
//     promotionContainer.append(option);
//   });
// }

// function semestresFiliere(infoFiliere) {
//   const semestreContainer = $("#semestres");
//   semestreContainer.empty();
//   semestreContainer.append(
//     `<option value="" disabled selected>Selectionner un Semestre</option>`
//   );
//   const semestres = infoFiliere["semestres"];
//   semestres.forEach((semestre) => {
//     const option = `<option value='${semestre.id_parcours}' class='text-center  text-uppercase'>${semestre.nom_semestre}(${semestre.sigle_semestre})</option>`;
//     semestreContainer.append(option);
//   });
// }

// function modulesSemestre(idSemestre, infoFiliere) {
//   const mouduleContainer = $("#modules");
//   mouduleContainer.empty();

//   mouduleContainer.append(
//     `<option value="" disabled selected>Selectionner un Module</option>`
//   );
//   const ues = infoFiliere["ues"];
//   const modules = infoFiliere["modules"];

//   ues.forEach((ue) => {
//     if (ue.id_parcours == idSemestre) {
//       const ueOption = `<option disabled class="mt-1">${ue.nom_ue}</option>`;
//       mouduleContainer.append(ueOption);
//       modules.forEach((module) => {
//         if (module.id_ue == ue.id_ue) {
//           const option = `<option value='${module.id_ue_module}' class='text-center  text-uppercase'>${module.nom_module}(${module.code_module})</option>`;
//           mouduleContainer.append(option);
//         }
//       });
//     }
//   });
// }

// function infoModule(idModule, infoFiliere) {
//   if (idModule != null && idModule != "") {
//     modules = infoFiliere["modules"];
//     modules.forEach((module) => {
//       if (module.id_ue_module == idModule) {
//         $("#infoModule").removeClass("d-none");
//         $("#infoModule").attr("display", "block");
//         $(".cm").val(module.cm);
//         $(".td").val(module.td);
//         $(".tp").val(module.tp);
//         $(".tpe").val(module.tpe);
//         const heureTotal =
//           parseInt(module.cm, 10) +
//           parseInt(module.td, 10) +
//           parseInt(module.tp, 10) +
//           parseInt(module.tpe, 10);
//         genererEdt(heureTotal);
//       }
//     });
//   } else {
//     $("#infoModule").addClass("d-none");
//     document.querySelector("#table-extended-chechbox tbody").innerHTML = "";
//   }
// }
