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
$(document).ready(function() {
    // Charger les périodes
    $.get(ROOT + "/Enseignants/periodes", function(data) {
        let periodes = JSON.parse(data);
        $("#periode_debut").append('<option value="">Choisir une période</option>');
        periodes.forEach(function(p) {
            $("#periode_debut").append('<option value="'+p.date_debut+'" data-fin="'+p.date_fin+'">'+p.date_debut+' ('+p.status+')</option>');
        });
    });

    // Quand on choisit une période, remplir la date de fin, charger les enseignants et le tableau
    $("#periode_debut").change(function() {
        let debut = $(this).val();
        let fin = $("#periode_debut option:selected").data("fin");
        $("#periode_fin").val(fin ? fin : "");
        $("#prof").html('<option value="">Tous les enseignants</option>');
        if(debut && fin) {
            // Charger les enseignants de la période
            $.post(ROOT + "/Enseignants/enseignants_par_periode", {date_debut: debut, date_fin: fin}, function(data) {
                let profs = JSON.parse(data);
                profs.forEach(function(p) {
                    $("#prof").append('<option value="'+p.enseignant_id+'">'+p.enseignant_prenom+' '+p.enseignant_nom+'</option>');
                });
                // Charger le tableau pour tous les enseignants de la période
                chargerTableauEDT(debut, fin, "");
            });
        } else {
            $("#table-edt-individuels").html('<h6 class="text-center text-success">Sélectionnez une période pour voir les EDT individuels</h6>');
        }
    });

    // Quand on choisit un prof, filtrer le tableau
    $("#prof").change(function() {
        let debut = $("#periode_debut").val();
        let fin = $("#periode_debut option:selected").data("fin");
        let prof = $(this).val();
        if(debut && fin) {
            chargerTableauEDT(debut, fin, prof);
        }
    });

    // Fonction pour charger le tableau des enseignants
    function chargerTableauEDT(date_debut, date_fin, enseignant_id) {
        $.post(ROOT + "/Enseignants/table_EDT_individuels", {
            date_debut: date_debut,
            date_fin: date_fin,
            enseignant_id: enseignant_id
        }, function(html) {
            // On suppose que le contrôleur renvoie la liste des enseignants au format JSON
            // Si ce n'est pas le cas, adapte cette partie pour parser le HTML ou le JSON selon ton backend
            let data;
            try {
                data = JSON.parse(html);
            } catch(e) {
                // Si ce n'est pas du JSON, on affiche directement le HTML (mode fallback)
                $("#table-edt-individuels").html(html);
                return;
            }
            afficherTableEDTIndividuels(data.liste, date_debut, date_fin);
        });
    }

    // Fonction pour afficher le tableau de sélection et le bouton d'export
    function afficherTableEDTIndividuels(liste, date_debut, date_fin) {
        if (!liste || liste.length === 0) {
            $("#table-edt-individuels").html('<h6 class="text-center text-danger">Aucun enseignant trouvé pour cette période.</h6>');
            return;
        }
        let tbody = '';
        liste.forEach(function(ens) {
            tbody += `<tr>
                <td><input type="checkbox" name="enseignants[]" value="${ens.enseignant_id}"></td>
                <td>${ens.enseignant_nom}</td>
                <td>${ens.enseignant_prenom}</td>
                <td>${ens.nom_grade ?? ''}</td>
                <td>${ens.enseignant_statut ?? ''}</td>
                <td>${ens.total_heures ?? ''}</td>
            </tr>`;
        });
        let html = `
            <form id="form-impression-edt" method="post" action="${ROOT}/Enseignants/imprimerEDTIndividuels" target="_blank">
                <input type="hidden" name="date_debut" value="${date_debut}">
                <input type="hidden" name="date_fin" value="${date_fin}">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Grade</th>
                            <th>Statut</th>
                            <th>Total Heures</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tbody}
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary mt-2">Exporter PDF individuels (ZIP)</button>
            </form>
        `;
        $("#table-edt-individuels").html(html);

        // Sélectionner/désélectionner toutes les cases
        $('#select-all').on('change', function() {
            $('input[name="enseignants[]"]').prop('checked', this.checked);
        });
    }
});