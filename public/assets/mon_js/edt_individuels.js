// $(document).ready(function() {
//     // Charger les périodes
//     $.get(ROOT + "/Enseignants/periodes", function(data) {
//         let periodes = JSON.parse(data);
//         $("#periode_debut").append('<option value="">Choisir une période</option>');
//         periodes.forEach(function(p) {
//             $("#periode_debut").append('<option value="'+p.date_debut+'" data-fin="'+p.date_fin+'">'+p.date_debut+' ('+p.status+')</option>');
//         });
//     });

//     // Quand on choisit une période, remplir la date de fin, charger les enseignants et le tableau
//     $("#periode_debut").change(function() {
//         let debut = $(this).val();
//         let fin = $("#periode_debut option:selected").data("fin");
//         $("#periode_fin").val(fin ? fin : "");
//         $("#prof").html('<option value="">Tous les enseignants</option>');
//         if(debut && fin) {
//             // Charger les enseignants de la période
//             $.post(ROOT + "/Enseignants/enseignants_par_periode", {date_debut: debut, date_fin: fin}, function(data) {
//                 let profs = JSON.parse(data);
//                 profs.forEach(function(p) {
//                     $("#prof").append('<option value="'+p.enseignant_id+'">'+p.enseignant_prenom+' '+p.enseignant_nom+'</option>');
//                 });
//             });
//             // Charger le tableau pour tous les enseignants de la période
//             chargerTableauEDT(debut, fin, "");
//         } else {
//             $("#table-edt-individuels").html('<h6 class="text-center text-success">Sélectionnez une période pour voir les EDT individuels</h6>');
//         }
//     });

//     // Quand on choisit un prof, filtrer le tableau
//     $("#prof").change(function() {
//         let debut = $("#periode_debut").val();
//         let fin = $("#periode_fin").val();
//         let prof = $(this).val();
//         if(debut && fin) {
//             chargerTableauEDT(debut, fin, prof);
//         }
//     });

//     // Fonction pour charger le tableau via AJAX
//     function chargerTableauEDT(debut, fin, prof) {
//         $("#table-edt-individuels").html('<div class="text-center">Chargement...</div>');
//         $.post(ROOT + "/Enseignants/table_EDT_individuels", 
//             {date_debut: debut, date_fin: fin, enseignant_id: prof}, 
//             function(html) {
//                 $("#table-edt-individuels").html(html);
//             }
//         );
//     }
// });
$(document).ready(function () {
  // Charger les périodes
  $.get(ROOT + "/Enseignants/periodes", function (data) {
    let periodes = JSON.parse(data);
    $("#periode_debut").append('<option value="">Choisir une période</option>');
    periodes.forEach(function (p) {
      $("#periode_debut").append(
        '<option value="' +
          p.id_periode +
          '" data-debut="' +
          p.date_debut +
          '" data-fin="' +
          p.date_fin +
          '">' +
          p.date_debut +
          " (" +
          p.status +
          ")</option>"
      );
    });
  });

  // Quand on choisit une période, remplir la date de fin, charger les enseignants et le tableau
  $("#periode_debut").change(function () {
    let periode_id = $(this).val();
    let debut = $("#periode_debut option:selected").data("debut");
    let fin = $("#periode_debut option:selected").data("fin");

    $("#periode_fin").val(fin ? fin : "");
    $("#prof").html('<option value="">Tous les enseignants</option>');

    if (periode_id && debut && fin) {
      // Charger les enseignants de la période
      $.post(
        ROOT + "/Enseignants/enseignants_par_periode",
        { periode_id: periode_id, date_debut: debut, date_fin: fin },
        function (data) {
          let profs = JSON.parse(data);
          profs.forEach(function (p) {
            $("#prof").append(
              '<option value="' +
                p.enseignant_id +
                '">' +
                p.enseignant_prenom +
                " " +
                p.enseignant_nom +
                "</option>"
            );
          });

          // Charger le tableau pour tous les enseignants de la période
          chargerTableauEDT(debut, fin, periode_id, "");
        }
      );
    } else {
      $("#table-edt-individuels").html(
        '<h6 class="text-center text-success">Sélectionnez une période pour voir les EDT individuels</h6>'
      );
    }
  });

  // Quand on choisit un prof, filtrer le tableau
  $("#prof").change(function () {
    let periode_id = $("#periode_debut").val();
    let debut = $("#periode_debut option:selected").data("debut");
    let fin = $("#periode_debut option:selected").data("fin");
    let prof = $(this).val();

    if (periode_id && debut && fin) {
      chargerTableauEDT(debut, fin, periode_id, prof);
    }
  });

  // Fonction pour charger le tableau des enseignants
  function chargerTableauEDT(date_debut, date_fin, periode_id, enseignant_id) {
    $.post(
      ROOT + "/Enseignants/table_EDT_individuels",
      {
        periode_id: periode_id,
        date_debut: date_debut,
        date_fin: date_fin,
        enseignant_id: enseignant_id,
      },
      function (html) {
        // console.log("periode_id envoyé:", periode_id);
        // console.log(
        //   "date_debut envoyé:",
        //   date_debut,
        //   "date_fin envoyé:",
        //   date_fin
        // );

        $("#table-edt-individuels").html(html);
      }
    );
  }
});