<!-- <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css"> -->

<table class="table zero-configuration">
    <thead class="text-center">
        <tr>
            <th class="text-center">
                Tout<br>
                <input type="checkbox" id="select-all" title="Sélectionner tout" style="margin-top: 5px;">
            </th>
            <th>Nom & Prénom</th>
            <th>Matricule</th>
            <th>Status</th>
            <th>Filière</th>
            <th>Diplôme</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="table_etudiant" class="text-center">
        <?php foreach ($liste_etudiant as $etudiant): ?>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="paie[]" value="<?= $etudiant->id_etudiant ?>">
            </td>

            <td><?= $etudiant->nom_prenom_etudiant ?> <?= $etudiant->prenom ?></td>
            <td><?= $etudiant->matricule_etudiant ?></td>
            <td><?= $etudiant->id_statut ?></td>
            <td><?= $etudiant->nom_filiere ?></td>
            <td><?= $etudiant->diplome ?></td>


            <td>
                <!-- Dropdown options for individual actions -->
                <div class="dropdown">
                    <span
                        class="bx bx-dots-horizontal-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item edit-btn"
                            href="<?= ROOT ?>/Etudiants/paiement_etudiant/<?= $etudiant->id_etudiant ?>"><i
                                class="bx bx-money mr-1"></i> Paiement</a>
                        <a class="dropdown-item" href=""><i class="bx bx-trash mr-1"></i> Supprimer</a>
                    </div>
                </div>
            </td>

        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
<script src="<?= ROOT ?>/assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="<?= ROOT ?>/assets/js/scripts/datatables/datatable.js"></script>