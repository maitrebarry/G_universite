function imprimerEdtIndi(nomEdt = "edtIndividuel") {
  $("#loader").removeClass("d-none").addClass("d-flex");

  let html = document.getElementById("edtIndi");

  // Masquer les éléments avec la classe "no-print"
  $(".no-print").hide();

  // Mise en forme du nom du fichier
  nomEdt = nomEdt
    .split("-")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join("-");

  html2pdf()
    .from(html)
    .set({
      margin: 0,
      filename: nomEdt + ".pdf",
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
      // Réafficher les éléments après la génération du PDF
      $(".no-print").show();
      $("#loader").removeClass("d-flex").addClass("d-none");
    });
}

function imprimerEdt(id, dateDebut, dateFin, nomEdt = "edtIndividuel") {
    $("#loader").removeClass("d-none").addClass("d-flex");

    // Debugging: afficher les paramètres envoyés
    console.log("Envoi de la requête AJAX avec les paramètres suivants:");
    console.log("ID:", id);
    console.log("Date début:", dateDebut);
    console.log("Date fin:", dateFin);
    console.log("Nom EDT:", nomEdt);

    // Envoi de la requête AJAX
    $.ajax({
        method: "POST",
        url: `${ROOT}/Enseignants/imprimerplusieursEDT_individuel`,
        data: {
            action: "print",
            ids: JSON.stringify([id]), // Convertir le tableau en chaîne JSON
            date_debut: dateDebut,
            date_fin: dateFin,
            search: "inachevé"
        },
        dataType: "json",
        success: function (response) {
            console.log("Réponse AJAX:", response); // Debugging: afficher la réponse

            if (response.html) {
                let tempDiv = document.createElement("div");
                tempDiv.innerHTML = response.html;
                document.body.appendChild(tempDiv);
                imprimerEdtIndi(nomEdt);
                document.body.removeChild(tempDiv);
            } else {
                console.error("Erreur: Aucun emploi du temps trouvé.");
            }
            $("#loader").removeClass("d-flex").addClass("d-none");
        },
        error: function (error) {
            console.error("Erreur AJAX:", error); // Debugging: afficher l'erreur AJAX
            $("#loader").removeClass("d-flex").addClass("d-none");
        }
    });
}

// function imprimerEdtIndi(nomEdt = "edtIndividuel") {
//     $("#loader").removeClass("d-none").addClass("d-flex");

//     let html = document.getElementById("edtIndi");

//     // Masquer les éléments avec la classe "no-print"
//     $(".no-print").hide();

//     // Mise en forme du nom du fichier
//     nomEdt = nomEdt
//         .split("-")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
//         .join("-");

//     // Debugging: afficher l'ID de l'élément à imprimer
//     console.log("Impression de l'EDT pour:", nomEdt);

//     html2pdf()
//         .from(html)
//         .set({
//             margin: 0,
//             filename: nomEdt + ".pdf",
//             html2canvas: {
//                 scale: 2,
//             },
//             jsPDF: {
//                 unit: "mm",
//                 format: "a4",
//                 orientation: "landscape",
//             },
//         })
//         .save()
//         .then(() => {
//             // Réafficher les éléments après la génération du PDF
//             $(".no-print").show();
//             $("#loader").removeClass("d-flex").addClass("d-none");
//         });
// }

