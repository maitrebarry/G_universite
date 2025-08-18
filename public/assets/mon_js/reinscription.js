// Fonction pour afficher/masquer les champs de session
const ROOT = "http://localhost/G_universite/public/Notes";
const ROOT2 = "http://localhost/G_universite/public/Reinsciptions";

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
      list: "liste_etudiant",
    },
    success: function (response) {
      const promotionContainer = $("#promotions");
      promotionContainer.empty();
      promotionContainer.append(
        `<option value="" >Selectionner une Classe</option>`
      );
      const newPromotionContainer = $("#newPromotions");
      newPromotionContainer.empty();
      newPromotionContainer.append(
        `<option value="" >Selectionner une Classe</option>`
      );

      const promotions = response;
      promotions.forEach((promotion) => {
        const option = `<option value='${promotion.id_promotion}' class='text-center' data-filiere='${promotion.id_filiere}' data-semestre='${promotion.id_parcours}'> 
        ${promotion.classe}</option>`;
        promotionContainer.append(option);
        newPromotionContainer.append(option);
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
    url: ROOT2 + "/get_etudiants",

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
