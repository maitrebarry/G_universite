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
async function classesAnneeUniversitaire(anneeUniversitaire) {
  $.ajax({
    method: "POST",
    url: ROOT + "/get_classe_annee",
    dataType: "json",

    data: {
      anneeUniversitaire: anneeUniversitaire,
    },
    success: function (response) {
      const promotionContainer = $("#promotions");
      const newPromotionContainer = $("#newPromotions");
      newPromotionContainer.empty();
      newPromotionContainer.append(
        `<option value="" >Selectionner une Classe</option>`
      );
      promotionContainer.empty();
      promotionContainer.append(
        `<option value="" >Selectionner une Classe</option>`
      );

      idPromotionSelected = sessionStorage.getItem("classe");
      idParcoursSelected = sessionStorage.getItem("semestre");
      //sessionStorage.setItem("semestre", idParcoursSelected);
      const promotions = response;
      promotions.forEach((promotion) => {
        const option = `<option value='${
          promotion.id_promotion
        }' class='text-center' 
        data-filiere='${promotion.id_filiere}'
        data-semestre='${promotion.id_parcours}'
        ${
          promotion.id_promotion == idPromotionSelected &&
          promotion.id_parcours == idParcoursSelected
            ? "selected"
            : ""
        }
        > 
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

  idSemestre = $("#promotions option:selected").data("semestre");
  idFiliere = $("#promotions option:selected").data("filiere");
  console.log(
    "annee : " +
      anneeUniversitaire +
      " semestre : " +
      idSemestre +
      " fliere : " +
      idFiliere
  );

  $.ajax({
    method: "POST",
    url: ROOT2 + "/get_etudiants",

    data: {
      annee_universitaire: anneeUniversitaire,
      id_semestre: idSemestre,
      id_filiere: idFiliere,
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
