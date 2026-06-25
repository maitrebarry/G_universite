<?php $this->view("Partials/header") ?>
<?php
$filiere = $infoFiliere['filiere'];
$ancienSemestres = $infoFiliere['semestres'];
$ues = $infoFiliere['ues'];
$ancienModules = $infoFiliere['modules'];
?>
<style>
    #guWizard .gu-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 18px; margin-bottom: 16px; }
    #guWizard .form-label { font-size: .85rem; font-weight: 500; color: #475569; margin-bottom: 4px; }
    #guWizard .char-count { font-size: 11px; color: #8a97ad; text-align: right; }
    #guWizard .error { font-size: 12px; color: #b91c1c; }
    #guWizard .container { margin-top: 0; }

    /* Sélecteur de mode */
    #guWizard .gu-modes { display: flex; gap: 8px; flex-wrap: wrap; }
    #guWizard .gu-mode { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border: 1px solid #e3e8f2; border-radius: 10px; color: #14346b; text-decoration: none; font-weight: 600; font-size: 13px; background: #fff; transition: all .12s ease; }
    #guWizard .gu-mode:hover { background: #eaf1fb; border-color: #1f4f9c; }
    #guWizard .gu-mode.active { background: #1f4f9c; color: #fff; border-color: #1f4f9c; }
    #guWizard .gu-mode.danger { color: #b91c1c; } #guWizard .gu-mode.danger.active { background: #b91c1c; color: #fff; border-color: #b91c1c; }

    /* Bloc semestre */
    #guWizard .section { background: #f6f8fc; border: 1px solid #dbe3f0; border-radius: 12px; padding: 14px 16px; margin-top: 14px; }
    #guWizard .section > h4 { font-weight: 700; font-size: 14px; text-align: center; background: #14346b; color: #fff; padding: 6px; border-radius: 8px; }
    #guWizard table { width: 100%; border-collapse: collapse; background: #fff; }
    #guWizard table thead th { background: #e7ecf5; color: #14346b; font-size: 12px; padding: 6px 8px; border: 1px solid #cfd8e8; text-align: left; }
    #guWizard table td { border: 1px solid #e3e8f2; padding: 8px; vertical-align: top; }
    #guWizard .nomUe { font-weight: 600; }
    #guWizard .module-list { list-style: none; padding-left: 0; margin: 8px 0 0; }
    #guWizard .module-item { border: 1px solid #dbe3f0; border-radius: 10px; padding: 12px; margin: 8px 0; background: #f9fbfe; }
    #guWizard .module-item label { display: block; font-size: 10.5px; color: #5a6b86; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 2px; text-align: center; }
    #guWizard .module-item .form-control { text-align: center; }
    #guWizard .btn-danger { background: #fdecec; color: #b91c1c; border: 1px solid #f0b4b4; }
    #guWizard .btn-danger:hover { background: #f8d7d7; }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Modifier une filière</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Filieres">Filières</a></li>
                                    <li class="breadcrumb-item active"><?= $action == 'add' ? 'Ajouter un élément' : ($action == 'del' ? 'Supprimer un élément' : 'Modifier') ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="guWizard">
                <div id="message"></div>

                <!-- Sélecteur de mode -->
                <div class="gu-card">
                    <div class="gu-modes">
                        <a class="gu-mode <?= $action == 'edit' ? 'active' : '' ?>" href="<?= ROOT ?>/Filieres/editer_filiere/<?= $filiere->id_filiere ?>"><i class="bx bx-edit-alt"></i> Modifier les infos</a>
                        <a class="gu-mode <?= $action == 'add' ? 'active' : '' ?>" href="<?= ROOT ?>/Filieres/ajouter_element_filiere/<?= $filiere->id_filiere ?>"><i class="bx bx-plus-circle"></i> Ajouter un élément</a>
                        <a class="gu-mode danger <?= $action == 'del' ? 'active' : '' ?>" href="<?= ROOT ?>/Filieres/supprimer_element_filiere/<?= $filiere->id_filiere ?>"><i class="bx bx-minus-circle"></i> Supprimer un élément</a>
                        <a class="gu-mode" href="<?= ROOT ?>/Filieres/apercu_Filiere/<?= $filiere->id_filiere ?>"><i class="bx bx-show"></i> Aperçu</a>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <?php if ($action == 'edit'): ?>Modifiez le nom de la filière, des UE et les détails des modules.
                        <?php elseif ($action == 'add'): ?>Ajoutez de nouveaux semestres, UE ou modules (les éléments existants sont en lecture seule).
                        <?php else: ?>Supprimez des UE ou des modules existants. Cette action est définitive.<?php endif ?>
                    </small>
                </div>

                <!-- Identité de la filière -->
                <div class="gu-card">
                    <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-bookmark-alt"></i></span> Identité de la filière</div>
                    <div class="row mt-1" style="row-gap:12px;">
                        <div class="col-12 col-md-7">
                            <label for="nomFiliere" class="form-label">Nom de la filière</label>
                            <input type="text" id="nomFiliere" class="form-control" placeholder="ex : Génie Informatique" maxlength="101" value="<?= strtoupper(htmlspecialchars($filiere->nom_filiere)) ?>" data-id="<?= $filiere->id_filiere ?>">
                            <div class="char-count" id="nomFiliereCount">0/100</div>
                            <div class="error" id="nomFiliereError"></div>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="sigleFiliere" class="form-label">Code de la filière</label>
                            <input type="text" id="sigleFiliere" class="form-control" placeholder="ex : GI" maxlength="50" value="<?= strtoupper(htmlspecialchars($filiere->sigle_filiere)) ?>">
                        </div>
                    </div>
                </div>

                <!-- Programme -->
                <div class="gu-card">
                    <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-layer"></i></span> Programme</div>
                    <div class="container">
                        <?php if ($action == "add"): ?>
                            <div class="section">
                                <div class="row align-items-end" style="row-gap:12px;">
                                    <div class="col-12 col-md-6">
                                        <label for="idSemestre" class="form-label">Ajouter un semestre</label>
                                        <select id="idSemestre" class="form-select" onchange="addSemestre()">
                                            <option value="">Choisir un semestre…</option>
                                            <?php foreach ($semestres as $semestre): ?>
                                                <option value="<?= $semestre->id_semestre ?>"><?= strtoupper(htmlspecialchars($semestre->nom_semestre)) ?> (<?= strtoupper(htmlspecialchars($semestre->sigle_semestre)) ?>)</option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="error w-100 d-flex justify-content-end" id="idSemestreError"></div>
                            </div>
                        <?php endif ?>

                        <!-- la section pour afficher les semestres -->
                        <div id="semestresTable">
                            <?php foreach ($ancienSemestres as $ancienSemestre): ?>
                                <div class='section' id="semestre_<?= $ancienSemestre->id_semestre ?>" data-id="<?= $ancienSemestre->id_parcours ?>">
                                    <h4 class='text-center'><?= strtoupper($ancienSemestre->nom_semestre) ?></h4>
                                    <table class='table table-bordered table-responsive' id="tableSemestre_<?= $ancienSemestre->id_semestre ?>">
                                        <thead>
                                            <tr>
                                                <th>Unité d'Enseignement (UE)</th>
                                                <th>Modules</th>
                                                <?php if ($action == "del" || $action == "add"): ?><th>Actions</th><?php endif ?>
                                            </tr>
                                        </thead>
                                        <tbody id="ueContainer_<?= $ancienSemestre->id_semestre ?>" data-id="<?= $ancienSemestre->id_parcours ?>">
                                            <?php foreach ($ues as $ue): ?>
                                                <?php if ($ue->id_parcours == $ancienSemestre->id_parcours): ?>
                                                    <tr class='ue-item'>
                                                        <td class="p-1" style="min-width: 140px;">
                                                            <div class="w-100 h-100 d-flex justify-content-around align-items-center">
                                                                <input type='text' class='form-control nomUe' placeholder="Nom de l'UE ( ex : UEMPH1 )" value="<?= htmlspecialchars($ue->nom_ue) ?>" data-id="<?= $ue->id_ue ?>">
                                                            </div>
                                                        </td>
                                                        <td style="min-width: 400px;">
                                                            <?php if ($action == 'add'): ?>
                                                                <div class="w-100 h-100 d-flex justify-content-around">
                                                                    <button type='button' class='btn btn-primary' onclick="addModule( <?= $ancienSemestre->id_semestre ?>, this )"><i class='bx bxs-plus-square'></i>&nbsp; Module</button>
                                                                </div>
                                                            <?php endif ?>
                                                            <ul class='module-list'>
                                                                <?php foreach ($ancienModules as $ancienModule): ?>
                                                                    <?php if ($ancienModule->id_ue == $ue->id_ue): ?>
                                                                        <li class='module-item row border'>
                                                                            <div class="col-12">
                                                                                <label class='text-center' for='modules'>Module</label>
                                                                                <?php
                                                                                    // PERF : ne rendre QUE l'option sélectionnée ; la liste complète des
                                                                                    // modules est injectée à la demande (au focus) depuis window.GU_MODULES.
                                                                                    $selMod = null;
                                                                                    foreach ($modules as $module) { if ($module->id_module == $ancienModule->id_module) { $selMod = $module; break; } }
                                                                                ?>
                                                                                <select id='module_<?= $ancienModule->id_ue_module ?>' class='form-control module module-lazy' data-id="<?= $ancienModule->id_ue_module ?>">
                                                                                    <?php if ($selMod): ?>
                                                                                        <option value="<?= $selMod->id_module ?>" selected><?= strtoupper(htmlspecialchars($selMod->nom_module)) ?> (<?= strtoupper(htmlspecialchars($selMod->sigle_module)) ?>)</option>
                                                                                    <?php else: ?>
                                                                                        <option value="">Choisir un module…</option>
                                                                                    <?php endif ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class='col-4 m-auto'><label>ECUE</label><input type='text' class='form-control code' value="<?= htmlspecialchars($ancienModule->code_module) ?>"></div>
                                                                            <div class='col-4 m-auto'><label>Crédit</label><input type='number' class='form-control coeficient' value="<?= $ancienModule->coeficient ?>"></div>
                                                                            <div class='col-4 m-auto'><label>VHT</label><input type='number' class='form-control' value="<?= $ancienModule->cm + $ancienModule->td + $ancienModule->tp + $ancienModule->tpe ?>" disabled></div>
                                                                            <div class='col-3'><label>CM</label><input type='number' class='form-control cm' value="<?= $ancienModule->cm ?>"></div>
                                                                            <div class='col-3'><label>TD</label><input type='number' class='form-control td' value="<?= $ancienModule->td ?>"></div>
                                                                            <div class='col-3'><label>TP</label><input type='number' class='form-control tp' value="<?= $ancienModule->tp ?>"></div>
                                                                            <div class='col-3'><label>TPE</label><input type='number' class='form-control tpe' value="<?= $ancienModule->tpe ?>"></div>
                                                                            <?php if ($action == "del"): ?>
                                                                                <div class="col-12 mt-1 d-flex justify-content-around">
                                                                                    <button type='button' class='btn btn-danger' onclick='delElementFiliere(this,<?= $ancienModule->id_ue_module ?>)' id="removeModule"><i class="bx bxs-minus-square"></i>&nbsp; Module</button>
                                                                                </div>
                                                                            <?php endif ?>
                                                                        </li>
                                                                    <?php endif ?>
                                                                <?php endforeach ?>
                                                            </ul>
                                                        </td>
                                                        <?php if ($action == "del"): ?>
                                                            <td class="p-0">
                                                                <div class="w-100 h-100 d-flex justify-content-around align-items-center">
                                                                    <button type='button' class='btn btn-danger' onclick="delElementFiliere(this,<?= $ue->id_ue ?>,'ue')" data-id="<?= $ue->id_ue ?>"><i class="bx bxs-minus-square"></i>&nbsp; UE</button>
                                                                </div>
                                                            </td>
                                                        <?php endif ?>
                                                    </tr>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                    <?php if ($action == "add"): ?>
                                        <button type="button" class="btn btn-primary mt-2" onclick="addUE(<?= $ancienSemestre->id_semestre ?>)"><i class="bx bxs-plus-square"></i>&nbsp; UE</button>
                                    <?php endif ?>
                                </div>
                            <?php endforeach ?>
                        </div>

                        <input type="hidden" id="action" value="<?= $action ?>">
                        <?php if ($action != 'del'): ?>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-primary" id="btnEnregistrer" onclick="ajouterFiliere('<?= ROOT . '/Filieres/' . $url ?>', action='<?= $url ?>')"><i class="bx bx-save"></i> <?= $action == 'add' ? 'Ajouter' : 'Enregistrer' ?></button>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
    <script>const ROOT = "<?= ROOT ?>";</script>
    <script src="<?= ROOT ?>/assets/mon_js/filiere.js"></script>
    <script>
        $(document).ready(function () {
            $('#nomFiliere').on('input', function () { $('#nomFiliereCount').text($(this).val().length + '/100'); }).trigger('input');
            const action = $('#action').val();
            if (action !== "edit") {
                // En modes "ajouter"/"supprimer" : les éléments existants sont en lecture seule (sauf le sélecteur de semestre).
                $("#guWizard .section .form-control").attr("disabled", true);
                $("#idSemestre").attr("disabled", false);
            }
        });
    </script>
    <script>
        // PERF : la liste complète des modules est envoyée UNE SEULE FOIS.
        // Chaque <select class="module-lazy"> n'a que son option sélectionnée et se
        // remplit à la demande (focus/clic) -> page d'édition allégée de plusieurs Mo.
        window.GU_MODULES = <?= json_encode(array_map(function ($m) {
            return ['id' => (int) $m->id_module, 'l' => mb_strtoupper($m->nom_module, 'UTF-8') . ' (' . mb_strtoupper($m->sigle_module, 'UTF-8') . ')'];
        }, $modules), JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) ?>;
        (function () {
            function fill(sel) {
                if (sel.dataset.filled || !window.GU_MODULES) return;
                var cur = sel.value, frag = document.createDocumentFragment();
                window.GU_MODULES.forEach(function (m) {
                    var o = document.createElement('option');
                    o.value = m.id; o.textContent = m.l;
                    if (String(m.id) === String(cur)) o.selected = true;
                    frag.appendChild(o);
                });
                sel.innerHTML = '';
                sel.appendChild(frag);
                sel.value = cur;
                sel.dataset.filled = '1';
            }
            ['focusin', 'mousedown', 'touchstart'].forEach(function (ev) {
                document.addEventListener(ev, function (e) {
                    var t = e.target;
                    if (t && t.classList && t.classList.contains('module-lazy')) fill(t);
                }, true);
            });
        })();
    </script>
</body>

</html>
