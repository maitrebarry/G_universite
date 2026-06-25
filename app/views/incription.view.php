<?php $this->view("Partials/header") ?>

<style>
    #inscWrap .gu-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 14px; padding: 22px; }
    #inscWrap .form-label, #inscWrap label { font-size: .85rem; font-weight: 500; color: #475569; margin-bottom: 4px; }
    #inscWrap .form-group { margin-bottom: 14px; }

    /* Stepper custom */
    .gu-steps { display: flex; align-items: center; margin: 4px 0 24px; }
    .gu-stp { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .gu-stp .num { width: 36px; height: 36px; border-radius: 50%; background: #e7ecf5; color: #5a6b86; display: flex; align-items: center; justify-content: center; font-weight: 800; flex: 0 0 auto; transition: all .2s ease; }
    .gu-stp .lbl { font-size: 13px; color: #5a6b86; font-weight: 600; }
    .gu-stp.active .num { background: #1f4f9c; color: #fff; box-shadow: 0 0 0 4px rgba(31,79,156,.15); }
    .gu-stp.active .lbl { color: #14346b; }
    .gu-stp.done .num { background: #15803d; color: #fff; }
    .gu-steps .gu-line { flex: 1; height: 3px; background: #e7ecf5; margin: 0 12px; border-radius: 3px; }
    .gu-steps .gu-line.done { background: #15803d; }
    .gu-panel-nav { display: flex; justify-content: space-between; margin-top: 22px; padding-top: 16px; border-top: 1px solid #eef2f9; }

    .frais-total { background: #eaf1fb !important; border: 1px solid #9fc0ec !important; font-weight: bold; color: #14346b !important; }
    .frais-paye { background: #e9f7ef !important; border: 1px solid #9ed9b5 !important; font-weight: bold; color: #15803d !important; }
    .frais-restant { background: #fdecec !important; border: 1px solid #f0b4b4 !important; font-weight: bold; color: #b91c1c !important; }
</style>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <?php $this->view("set_flash") ?>
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Inscription d'un étudiant</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item">Gestion Étudiants</li>
                                    <li class="breadcrumb-item active">Inscription</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body" id="inscWrap">
                <div class="gu-card">
                    <!-- Étapes -->
                    <div class="gu-steps">
                        <div class="gu-stp active" data-step="1"><span class="num">1</span><span class="lbl">Informations</span></div>
                        <div class="gu-line" data-line="1"></div>
                        <div class="gu-stp" data-step="2"><span class="num">2</span><span class="lbl">Parents &amp; diplôme</span></div>
                        <div class="gu-line" data-line="2"></div>
                        <div class="gu-stp" data-step="3"><span class="num">3</span><span class="lbl">Scolarité &amp; frais</span></div>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data">
                        <!-- Étape 1 -->
                        <div class="gu-panel" data-panel="1">
                            <div class="gu-section-title"><i class="bx bx-user"></i> Informations de l'étudiant</div>
                            <div class="row mt-1">
                                <div class="col-md-3 form-group"><label>Nom <span class="text-danger">*</span></label><input type="text" class="form-control required" id="nomPrenom" placeholder="Nom" name="nom_prenom_etudiant"></div>
                                <div class="col-md-3 form-group"><label>Prénom <span class="text-danger">*</span></label><input type="text" class="form-control required" id="prenom" placeholder="Prénom" name="prenom"></div>
                                <div class="col-md-3 form-group"><label>Genre <span class="text-danger">*</span></label>
                                    <select class="form-select" id="genre" name="genre_etudiant"><option value="" disabled selected>Sexe</option><option value="F">Féminin</option><option value="M">Masculin</option></select>
                                </div>
                                <div class="col-md-3 form-group"><label>Date de naissance <span class="text-danger">*</span></label><input type="date" class="form-control" name="date_naissance_etudiant"></div>
                                <div class="col-md-4 form-group"><label>Lieu de naissance <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Lieu de naissance" name="lieu_naissance_etudiant"></div>
                                <div class="col-md-4 form-group"><label>Cercle de naissance <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Cercle" name="cercleNais"></div>
                                <div class="col-md-4 form-group"><label>Commune de naissance <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Commune" name="commNais"></div>
                                <div class="col-md-4 form-group"><label>Nationalité <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Nationalité" name="nationnalite"></div>
                                <div class="col-md-4 form-group"><label>Téléphone <span class="text-danger">*</span></label><input type="number" class="form-control" placeholder="Téléphone" name="contact_etudiant"></div>
                                <div class="col-md-4 form-group"><label>Email</label><input type="email" class="form-control" placeholder="Email" name="email_etudiant"></div>
                            </div>
                            <div class="gu-panel-nav">
                                <span></span>
                                <button type="button" class="btn btn-primary btn-nxt">Suivant <i class="bx bx-right-arrow-alt"></i></button>
                            </div>
                        </div>

                        <!-- Étape 2 -->
                        <div class="gu-panel" data-panel="2" hidden>
                            <div class="gu-section-title"><i class="bx bx-group"></i> Parents</div>
                            <div class="row mt-1">
                                <div class="col-md-4 form-group"><label>Prénom du père <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Prénom du père" name="prenompere"></div>
                                <div class="col-md-4 form-group"><label>Nom et prénom de la mère <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Nom et prénom de la mère" name="prenomnommere"></div>
                                <div class="col-md-4 form-group"><label>Résidence des parents <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Lieu de résidence" name="lieuresidenceparents"></div>
                            </div>
                            <div class="gu-section-title mt-2"><i class="bx bx-award"></i> Diplôme</div>
                            <div class="row mt-1">
                                <div class="col-md-4 form-group"><label>Diplôme <span class="text-danger">*</span></label>
                                    <select class="form-select" name="diplome"><option value="" disabled selected>Choisir</option><option value="Bac">Bac</option><option value="BT">BT</option><option value="IFM">IFM</option></select>
                                </div>
                                <div class="col-md-4 form-group"><label>Numéro de place <span class="text-danger">*</span></label><input type="number" class="form-control" placeholder="N° de place" name="numplace"></div>
                                <div class="col-md-4 form-group"><label>Série <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Série" name="serie"></div>
                                <div class="col-md-4 form-group"><label>Année du diplôme <span class="text-danger">*</span></label><input type="number" class="form-control" id="anneeDiplome" placeholder="Année" name="anneediplome"></div>
                                <div class="col-md-4 form-group"><label>Pays <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Pays" name="pays"></div>
                                <div class="col-md-4 form-group"><label>Académie <span class="text-danger">*</span></label><input type="text" class="form-control" placeholder="Académie" name="academie"></div>
                            </div>
                            <div class="gu-panel-nav">
                                <button type="button" class="btn btn-ghost btn-prev"><i class="bx bx-left-arrow-alt"></i> Précédent</button>
                                <button type="button" class="btn btn-primary btn-nxt">Suivant <i class="bx bx-right-arrow-alt"></i></button>
                            </div>
                        </div>

                        <!-- Étape 3 -->
                        <div class="gu-panel" data-panel="3" hidden>
                            <div class="gu-section-title"><i class="bx bx-buildings"></i> Scolarité</div>
                            <div class="row mt-1">
                                <div class="col-md-3 form-group"><label>N° étudiant (matricule)</label><input type="text" class="form-control" name="matricule_etudiant" id="matricule" placeholder="Auto-généré" readonly></div>
                                <div class="col-md-3 form-group"><label>Matricule CENOU</label><input type="text" class="form-control" name="numetudiant" placeholder="Matricule CENOU"></div>
                                <div class="col-md-3 form-group"><label>Promotion <span class="text-danger">*</span></label>
                                    <select class="form-select" name="id_promotion" id="id_promotion">
                                        <option value="">Choisir la promotion</option>
                                        <?php foreach ($listePromotion as $Promotion): ?>
                                            <option value="<?= htmlspecialchars($Promotion->id_promotion); ?>"><?= htmlspecialchars($Promotion->sigle_filiere . "-" . $Promotion->sigle_semestre . " (" . $Promotion->annee_universitaire . ")"); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group"><label>Photo de profil</label><input type="file" name="profilname" class="form-control"></div>
                            </div>

                            <div class="gu-section-title mt-2"><i class="bx bx-wallet"></i> Frais</div>
                            <div class="row mt-1">
                                <div class="col-md-3 form-group"><label>Statut <span class="text-danger">*</span></label>
                                    <select class="form-select" name="id_statut" id="statut"><option value="" disabled selected>Choisir le statut</option><option value="Regle">Régulier</option><option value="Cl">CL</option><option value="Proffesionnel">Professionnel</option></select>
                                </div>
                                <div class="col-md-3 form-group" id="type-prof-container" style="display:none;"><label>Type de professionnel <span class="text-danger">*</span></label>
                                    <select class="form-select" name="type_professionnel" id="type-professionnel"><option value="" disabled selected>Choisir le type</option><option value="Etatique">Professionnel Étatique</option><option value="Simple">Professionnel Collectivité</option><option value="Prive">Professionnel Privé</option></select>
                                </div>
                                <div class="col-md-3 form-group" id="prix-container" style="display:none;"><label>Frais d'inscription</label><input type="number" class="form-control" id="frais-inscription" name="frais_inscription" placeholder="Frais d'inscription" readonly></div>
                                <div class="col-md-3 form-group" id="formation-container" style="display:none;"><label>Frais de formation</label><input type="number" class="form-control" id="montant" placeholder="Frais de formation" readonly></div>
                            </div>
                            <div class="row" id="total-container" style="display:none;">
                                <div class="col-md-4 form-group"><label>Total des frais</label><input type="number" class="form-control frais-total" id="total-frais" name="total_frais" placeholder="Total" readonly></div>
                                <div class="col-md-4 form-group"><label>Somme payée</label><input type="number" class="form-control frais-paye" id="montant_paye" name="montant_paye" placeholder="Montant payé" min="0"></div>
                                <div class="col-md-4 form-group"><label>Somme restante</label><input type="number" class="form-control frais-restant" id="montant-restant" name="montant_restant" readonly></div>
                            </div>

                            <div class="gu-panel-nav">
                                <button type="button" class="btn btn-ghost btn-prev"><i class="bx bx-left-arrow-alt"></i> Précédent</button>
                                <button class="btn btn-success" type="submit" name="ddddddd"><i class="bx bx-check-double"></i> Enregistrer l'inscription</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <script>
        // ===== Stepper custom =====
        (function () {
            var cur = 1, max = 3;
            function go(n) {
                if (n < 1 || n > max) return;
                document.querySelectorAll('.gu-panel').forEach(function (p) { p.hidden = (+p.dataset.panel !== n); });
                document.querySelectorAll('.gu-stp').forEach(function (s) { var d = +s.dataset.step; s.classList.toggle('active', d === n); s.classList.toggle('done', d < n); });
                document.querySelectorAll('.gu-line').forEach(function (l) { l.classList.toggle('done', +l.dataset.line < n); });
                cur = n; window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            document.querySelectorAll('.btn-nxt').forEach(function (b) { b.addEventListener('click', function () { go(cur + 1); }); });
            document.querySelectorAll('.btn-prev').forEach(function (b) { b.addEventListener('click', function () { go(cur - 1); }); });
            document.querySelectorAll('.gu-stp').forEach(function (s) { s.addEventListener('click', function () { go(+s.dataset.step); }); });
        })();
    </script>

    <script>
        // ===== Calcul des frais (préservé) =====
        document.addEventListener('DOMContentLoaded', function () {
            const fraisParStatut = { "Regle": 6000, "Cl": 6000, "Proffesionnel": 6000 };
            const fraisParTypeProfessionnel = { "Etatique": 6000, "Simple": 6000, "Prive": 6000 };
            const fraisFormationParStatut = { "Regle": 0, "Cl": 75000, "Proffesionnel": 100000 };
            const fraisFormationParTypeProfessionnel = { "Etatique": 150000, "Simple": 150000, "Prive": 200000 };

            const statutSelect = document.getElementById('statut');
            const typeProfessionnelSelect = document.getElementById('type-professionnel');
            const fraisInscriptionInput = document.getElementById('frais-inscription');
            const montantInput = document.getElementById('montant');
            const montantPayeInput = document.getElementById('montant_paye');
            const totalFraisInput = document.getElementById('total-frais');
            const montantRestantInput = document.getElementById('montant-restant');
            const typeProfContainer = document.getElementById('type-prof-container');
            const prixContainer = document.getElementById('prix-container');
            const formationContainer = document.getElementById('formation-container');
            const totalContainer = document.getElementById('total-container');

            statutSelect.addEventListener('change', function () {
                const s = this.value;
                if (s === "Proffesionnel") {
                    typeProfContainer.style.display = 'block'; prixContainer.style.display = 'none'; formationContainer.style.display = 'none';
                    fraisInscriptionInput.value = ''; montantInput.value = '';
                } else {
                    typeProfContainer.style.display = 'none'; prixContainer.style.display = 'block'; formationContainer.style.display = 'block';
                    fraisInscriptionInput.value = fraisParStatut[s] || 0; montantInput.value = fraisFormationParStatut[s] || 0;
                    const fi = parseFloat(fraisInscriptionInput.value) || 0, ff = parseFloat(montantInput.value) || 0;
                    montantPayeInput.value = ((2 / 3) * ff + fi).toFixed(2);
                }
                mettreAJourTotal();
            });
            typeProfessionnelSelect.addEventListener('change', function () {
                const t = this.value;
                fraisInscriptionInput.value = fraisParTypeProfessionnel[t] || 0; montantInput.value = fraisFormationParTypeProfessionnel[t] || 0;
                prixContainer.style.display = 'block'; formationContainer.style.display = 'block';
                const fi = parseFloat(fraisInscriptionInput.value) || 0, ff = parseFloat(montantInput.value) || 0;
                montantPayeInput.value = ((2 / 3) * ff + fi).toFixed(2);
                mettreAJourTotal();
            });
            montantPayeInput.addEventListener('input', mettreAJourTotal);
            function mettreAJourTotal() {
                const fi = parseFloat(fraisInscriptionInput.value) || 0, ff = parseFloat(montantInput.value) || 0, mp = parseFloat(montantPayeInput.value) || 0;
                const total = fi + ff;
                totalFraisInput.value = total;
                montantRestantInput.value = Math.max(total - mp, 0);
                totalContainer.style.display = total > 0 ? 'flex' : 'none';
            }
        });

        // ===== Génération du matricule (préservé) =====
        document.addEventListener("DOMContentLoaded", function () {
            function genererMatricule() {
                let anneeDiplome = document.getElementById("anneeDiplome").value;
                let nomPrenom = document.getElementById("nomPrenom").value.trim().split(" ");
                let genre = document.getElementById("genre").value;
                if (anneeDiplome && nomPrenom.length > 0 && genre) {
                    let l1 = nomPrenom[0] ? nomPrenom[0][0].toUpperCase() : "";
                    let l2 = document.getElementById("prenom").value ? document.getElementById("prenom").value[0].toUpperCase() : "";
                    let num = Math.floor(1000 + Math.random() * 9000);
                    document.getElementById("matricule").value = `${anneeDiplome}${l1}${l2}${genre}${num}`;
                } else { document.getElementById("matricule").value = ""; }
            }
            ["anneeDiplome", "nomPrenom", "prenom"].forEach(id => document.getElementById(id).addEventListener("input", genererMatricule));
            document.getElementById("genre").addEventListener("change", genererMatricule);
        });
    </script>
</body>

</html>
