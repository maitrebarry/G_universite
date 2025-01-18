<?php foreach ($liste_etudiant as $etudiant): ?>
<tr>
    <td><?=$etudiant->nom_prenom_etudiant?></td>
    <td><?=$etudiant->matricule_etudiant?></td>
    <td><?=$etudiant->id_statut?></td>
    <td><?=$etudiant->diplome?></td>
    <td>
    <td class="text-center py-1">
        <div class="dropdown">
            <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item edit-btn"
                    href="<?= ROOT ?>/Etudiants/paiement_etudiant/<?=$etudiant->id_etudiant?>"><i
                        class="bx bx-edit-alt mr-1"></i> Paiement</a>
                <a class="dropdown-item" href=""><i class="bx bx-trash mr-1"></i> delete</a>
            </div>
        </div>
    </td>
    </td>

</tr>
<?php endforeach ?>