
 
 // Lorsque l'utilisateur sélectionne un fichier
  document.getElementById('excelFile').addEventListener('change', function() {
    const file = document.getElementById('excelFile').files[0];

    if (file) {
        // Afficher le modal si un fichier a été sélectionné
        const modal = new bootstrap.Modal(document.getElementById('menuConfig'));
        modal.show();
    }
});

// Quand l'utilisateur clique sur "Continuer" dans le modal
document.getElementById('continueBtn').addEventListener('click', function() {
    const file = document.getElementById('excelFile').files[0];

    if (!file) {
        Swal.fire({
            icon: 'error',
            title: 'Aucun fichier sélectionné',
            text: 'Veuillez sélectionner un fichier Excel.',
        });
        return;
    }

    // Vérifier l'extension du fichier
    const allowedExtensions = ['xlsx', 'xls'];
    const fileExtension = file.name.split('.').pop().toLowerCase();

    if (!allowedExtensions.includes(fileExtension)) {
        Swal.fire({
            icon: 'error',
            title: 'Format invalide',
            text: 'Seuls les fichiers Excel (.xlsx, .xls) avec un en-tête valide sont acceptés.',
        });
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const data = e.target.result;
        const workbook = XLSX.read(data, {
            type: 'binary'
        });
        const sheetName = workbook.SheetNames[0];
        const sheet = workbook.Sheets[sheetName];
        const rows = XLSX.utils.sheet_to_json(sheet, {
            header: 1
        });

        if (rows.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Fichier vide',
                text: 'Le fichier Excel est vide. Veuillez fournir un fichier valide.',
            });
            return;
        }

        //  Nouvelle liste des colonnes attendues
        let expectedHeaders = [
            "NOM", "PRENOM", "DATE DE NAISSANCE", "LIEU DE NAISSANCE",
            "SEXE", "MATRICULE", "DIPLÔME D'ENTREE", "STATUS"
        ];

        // Vérifier si l'en-tête du fichier correspond exactement
        let fileHeaders = rows[0].map(header => header ? header.trim() : '');

        if (JSON.stringify(fileHeaders) !== JSON.stringify(expectedHeaders)) {
            Swal.fire({
                icon: 'error',
                title: 'En-tête incorrect',
                text: "Le fichier ne correspond pas au format attendu. Vérifiez l'ordre et les noms des colonnes.",
            });
            return;
        }

        let allData = [];
        document.getElementById('dataTableBody').innerHTML = '';

        rows.forEach((row, index) => {
            if (index === 0) return; // Ignorer l'en-tête

            let dateNaissance = row[2] || '';
            if (dateNaissance) {
                dateNaissance = XLSX.SSF.format('dd-mm-yyyy', dateNaissance);
            }

            let etudiant = {
                nom_prenom_etudiant: (row[0] || '') + ' ' + (row[1] || ''),
                date_naissance_etudiant: dateNaissance,
                lieu_naissance_etudiant: row[3] || '',
                genre_etudiant: row[4] || '',
                matricule_etudiant: row[5] || '',
                diplome: row[6] || '',
                id_statut: row[7] || ''
            };

            if (Object.values(etudiant).some(value => value.trim() !== '')) {
                allData.push(etudiant);
            }

            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td contenteditable="true">${etudiant.nom_prenom_etudiant}</td>
            <td contenteditable="true">${etudiant.date_naissance_etudiant}</td>
            <td contenteditable="true">${etudiant.lieu_naissance_etudiant}</td>
            <td contenteditable="true">${etudiant.genre_etudiant}</td>
            <td contenteditable="true">${etudiant.matricule_etudiant}</td>
            <td contenteditable="true">${etudiant.diplome}</td>
        `;
            document.getElementById('dataTableBody').appendChild(tr);
        });

        console.log("Données finales à envoyer :", allData);

        if (allData.length > 0) {
            document.getElementById('hiddenData').value = JSON.stringify(allData);
            document.getElementById('validateBtn').style.display = 'block';
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Aucune donnée valide',
                text: 'Aucune donnée exploitable trouvée dans le fichier.',
            });
        }
        //  Fermer automatiquement le modal après tout traitement
        const modalElement = document.getElementById('menuConfig');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }
    };

    reader.readAsBinaryString(file);
});

// Lorsque l'utilisateur clique sur "Télécharger le modèle"
document.getElementById('downloadModel').addEventListener('click', function() {
    // Fermer le modal après un court délai pour éviter un conflit de clic
    setTimeout(function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('menuConfig'));
        modal.hide();
    }, 500);
});


// Envoi des données en AJAX avec jQuery
$('#validateBtn').on('click', function() {
    // Récupérer l'ID de la promotion sélectionnée
    const idPromotion = document.getElementById('id_promotion').value;

    // Vérifier si l'ID de la promotion est vide
    if (!idPromotion) {
        Swal.fire({
            icon: 'error',
            title: 'Promotion non sélectionnée',
            text: 'Veuillez sélectionner une promotion avant de continuer.',
        });
        return;
    }

    let allData = JSON.parse($('#hiddenData').val()); // Convertir JSON string en objet
    let updatedData = updateModifiedData(allData); // Mettre à jour les données

    console.log("Données récupérées avant envoi :", updatedData);

    if (!updatedData || updatedData.length === 0) {
        alert('Aucune donnée à insérer.');
        return;
    }

    // Ajouter l'ID de la promotion à chaque étudiant avant l'envoi
    updatedData.forEach(etudiant => {
        etudiant.id_promotion = idPromotion;
    });

    $.ajax({
        url: window.APP_ROUTE ? window.APP_ROUTE('EtudiantPargroupes') : 'EtudiantPargroupes',
        type: 'POST',
        data: {
            data: JSON.stringify(
                updatedData
            ) // Envoyer toutes les données mises à jour, y compris l'ID de promotion
        },
        dataType: 'json',
        success: function(response) {
            console.log(" Réponse du serveur :", response);
            alert(response.message);
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX:', error);
        }
    });
});


// Fonction qui met à jour toutes les données
function updateModifiedData(allData) {
    let updatedData = [];
    let rows = document.querySelectorAll("#dataTableBody tr");

    // Récupérer l'ID de la promotion sélectionnée
    const idPromotion = document.getElementById('id_promotion').value;

    rows.forEach((tr, index) => {
        let cells = tr.querySelectorAll("td");
        let etudiant = {
            ...allData[index],
            id_promotion: idPromotion // Ajouter l'ID de la promotion
        };

        // Récupérer les valeurs des cellules modifiées (les 5 premières colonnes visibles)
        let keys = ["nom_prenom_etudiant", "date_naissance_etudiant", "lieu_naissance_etudiant",
            "genre_etudiant", "matricule_etudiant"
        ];

        cells.forEach((cell, i) => {
            let modifiedValue = cell.innerText.trim();
            etudiant[keys[i]] = modifiedValue; // Mettre à jour les valeurs modifiées
        });

        updatedData.push(etudiant);
    });

    return updatedData;
}
