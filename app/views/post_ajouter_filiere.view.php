<?php if ($action == 'semestre'): ?>
    <!-- le formulaire pour ajouter un semestre -->
    <div class='section' id="semestre_<?php echo $semestre['semestreId'] ?>">
        <h4 class='text-center'>
            <?php echo $semestre['semestreName'] ?>
        </h4>
        <table class='table table-bordered table-responsive' id="tableSemestre_<?php echo $semestre['semestreId'] ?>">
            <thead>
                <tr>
                    <th>Unité d'Enseignement (UE)</th>
                    <th>Modules</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ueContainer_<?php echo $semestre['semestreId'] ?>">
                <!-- UE et modules seront ajoutés ici -->
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" onclick="addUE(<?php echo $semestre['semestreId'] ?>)">
            <i class="bx bxs-plus-square"></i>&nbsp; UE
        </button>
    </div>
<?php endif ?>

<?php if ($action == 'ue'): ?>

    <!-- le formulaire pour ajouter un ue -->
    <tr class='ue-item'>
        <td class="p-1" style="min-width: 150px;">
            <div class="w-100 h-100 d-flex justify-content-around align-items-center">
                <input type='text' class='form-control nomUe' placeholder="Nom de l'UE ( ex : UEMPH1 )">
            </div>
        </td>
        <td style="min-width: 400px;">
            <div class="w-100 h-100 d-flex justify-content-around ">
                <button type='button' class='btn btn-primary'
                    onclick="addModule( <?php echo $semestre['semestreId'] ?>, this )"><i
                        class=' bx bxs-plus-square '></i>&nbsp;
                    Module</button>
            </div>
            <ul class='module-list'></ul>
        </td>
        <td class="p-0 ">
            <div class="w-100 h-100 d-flex justify-content-around align-items-center">
                <button type='button' class='btn btn-danger' onclick='removeUE(this)'>
                    <i class="bx bxs-minus-square"></i>&nbsp; UE
                </button>
            </div>
        </td>
    </tr>
<?php endif ?>

<?php if ($action == 'module'): ?>

    <!-- le formulaire pour ajouter un module -->
    <li class='module-item row border'>
        <!-- Module  -->
        <div class="col-12  ">
            <label class='text-center' for='modules'>Module</label>
            <select id='modulessqfsqd' class='select2 form-control module'>
                <option value="" disabled selected>Selectionner un Module</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?php echo $module->id_module ?>">
                        <?php echo strtoupper($module->nom_module) ?>(
                        <?php echo strtoupper($module->sigle_module) ?> )
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- CODE -->
        <div class='col-5 m-auto mb-1'>
            <label>ECUE</label>
            <input type='text' class='form-control code'>
        </div>
        <!-- COEFICIENT -->
        <div class='col-5 m-auto mb-1'>
            <label>Crédit</label>
            <input type='number' class='form-control coeficient'>
        </div>

        <!-- CM -->
        <div class='col-3'>
            <label>CM</label>
            <input type='number' class='form-control cm'>
        </div class='col-3'>
        <!-- TD -->
        <div class='col-3'>
            <label>TD</label>
            <input type='number' class='form-control td'>
        </div>
        <!-- TP -->
        <div class='col-3'>
            <label>TP</label>
            <input type='number' class='form-control tp'>
        </div>
        <!-- TPE -->
        <div class='col-3'>
            <label>TPE</label>
            <input type='number' class='form-control tpe'>
        </div>
        <!-- TOTAL -->
        <div class='col-12 row mt-1'>
            <div class="col-4 m-auto ">
                <label>VHT</label>
                <input type='number' class='form-control disabled vht'>
            </div>
        </div>
        <!-- SUPPRIMER -->
        <div class="col-12 mt-1 d-flex justify-content-around">
            <button type='button' class='btn btn-danger' onclick='removeModule(this)'>
                <i class="bx bxs-minus-square"></i>&nbsp;
                Module
            </button>
        </div>
    </li>
<?php endif ?>