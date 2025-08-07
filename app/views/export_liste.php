<?php
// On force le téléchargement d'un fichier Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=liste.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Exemple d'entête professionnel
$nom_universite = "INSTITUT UNIVERSITAIRE DE FORMATION PROFESSIONNEL(IUFP)";
$adresse = "MINISTERE DE L'ENSEIGNEMENT SUPERIEUR ET DE LA RECHERCHE SCIENTIFIQUE";
$contact = "Tél: 01 23 45 67 89 | Email: contact@universiteg.edu";

// Colonnes du tableau
$colonnes = [
    'Prenom',
    'Nom',
    'Matricule',
    'Filiere',
    "Emargement"
];

echo "<table border='1' style='border-collapse:collapse; font-family:Arial, sans-serif;'>";
echo "<tr>";
echo "<td colspan='".count($colonnes)."' style='text-align:center; font-size:18px; font-weight:bold; background:#f2f2f2;'>$nom_universite</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan='".count($colonnes)."' style='text-align:center; font-size:12px;'>$adresse<br>$contact</td>";
echo "</tr>";
echo "<tr><td colspan='".count($colonnes)."' style='height:20px;'></td></tr>";
echo "<tr>";
foreach ($colonnes as $colonne) {
    echo "<th style='background:#d9edf7; font-size:14px;'>" . htmlspecialchars($colonne) . "</th>";
}
echo "</tr>";

// Affichage des étudiants
if (!empty($liste_etudiants)) {
    foreach ($liste_etudiants as $etudiant) {
        echo "<tr>";
        echo "<td style='padding:5px;'>" . htmlspecialchars($etudiant->prenom) . "</td>";
        echo "<td style='padding:5px;'>" . htmlspecialchars($etudiant->nom_prenom_etudiant) . "</td>";
        echo "<td style='padding:5px;'>" . htmlspecialchars($etudiant->matricule_etudiant) . "</td>";
        echo "<td style='padding:5px;'>" . htmlspecialchars($etudiant->sigle_filiere) . "</td>";
        echo "<td style='padding:5px;'></td>"; // Colonne Emargement vide
        echo "</tr>";
    }
}
echo "</table>";
?>
