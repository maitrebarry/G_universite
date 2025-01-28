<?php foreach ($liste_etudiant as $etudiant): ?>
    <tr><td class="text-center">
    <input type="checkbox" name="paie[]" value="<?= $etudiant->id_etudiant ?>">
</td>

                <td><?=$etudiant->nom_prenom_etudiant?></td>
                <td><?=$etudiant->matricule_etudiant?></td>
                <td><?=$etudiant->id_statut?></td>
                <td><?=$etudiant->nom_filiere?></td>
                <td><?=$etudiant->diplome?></td>
                
                  
                <td>
                    <!-- Dropdown options for individual actions -->
                    <div class="dropdown">
                        <span class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item edit-btn"
                                href="<?= ROOT ?>/Etudiants/paiement_etudiant/<?=$etudiant->id_etudiant?>"><i
                                    class="bx bx-money mr-1"></i> Paiement</a>
                            <a class="dropdown-item" href=""><i class="bx bx-trash mr-1"></i> Supprimer</a>
                        </div>
                    </div>
                </td>
                
            </tr>
<?php endforeach ?>
