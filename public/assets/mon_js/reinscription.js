// Fonction pour afficher/masquer les champs de session
var ROOT_NOTES = window.APP_ROUTE ? window.APP_ROUTE("Notes") : window.APP_ROOT + "/Notes";
var ROOT_REINSCRIPTIONS = window.APP_ROUTE ? window.APP_ROUTE("Reinsciptions") : window.APP_ROOT + "/Reinsciptions";
var ROOT_EDT = window.APP_ROUTE ? window.APP_ROUTE("Emploi_du_temps") : window.APP_ROOT + "/Emploi_du_temps";

async function infosFiliere(idFiliere, source = null) {
  try {
    response = await $.ajax({
      method: "POST",
      url: ROOT_EDT + "/filiere_info",
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
    url: ROOT_NOTES + "/get_classe_annee",
    dataType: "json",

    data: {
      anneeUniversitaire: anneeUniversitaire,
      action: "liste_note",
      list: "liste_etudiant",
    },
    success: function (response) {
      const promotionContainer = $("#promotions");
      promotionContainer.empty();
      promotionContainer.append(
        `<option value="" disabled selected>Sélectionner une classe</option>`
      );
      (response || []).forEach((promotion) => {
        promotionContainer.append(
          `<option value='${promotion.id_promotion}' data-filiere='${promotion.id_filiere}' data-semestre='${promotion.id_parcours}'>${promotion.classe}</option>`
        );
      });
    },
    error: function () {},
  });
}

function getEtudiants() {
  anneeUniversitaire = $("#anneeUniversitaire option:selected").val();
  idPromotion = $("#promotions option:selected").val();

  $.ajax({
    method: "POST",
    url: ROOT_REINSCRIPTIONS + "/get_etudiants",

    data: {
      annee_universitaire: anneeUniversitaire,
      id_promotion: idPromotion,
    },
    success: function (response) {
      console.log(response);
      const tableSection = $("#table_section");
      tableSection.empty();
      tableSection.html(response);
    },
    error: function () {},
  });
}
