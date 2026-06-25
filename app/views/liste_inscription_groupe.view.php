<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns navbar-sticky footer-static"
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <?php $this->view("Partials/navbar") ?>
    <?php $this->view("Partials/seibar") ?>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2 mt-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h5 class="content-header-title float-left pr-1 mb-0">Importer une liste</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Etudiants">Étudiants</a></li>
                                    <li class="breadcrumb-item active">Importer</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card" id="wiz"><div class="card-body">

                    <div class="gu-steps">
                        <div class="st active" data-step="1"><span class="n">1</span> Fichier</div>
                        <div class="st" data-step="2"><span class="n">2</span> Correspondance</div>
                        <div class="st" data-step="3"><span class="n">3</span> Promotion</div>
                        <div class="st" data-step="4"><span class="n">4</span> Import</div>
                    </div>

                    <!-- Étape 1 : fichier -->
                    <div class="gu-step-panel active" data-panel="1">
                        <label class="gu-dropzone d-block" id="dz">
                            <i class="bx bx-cloud-upload"></i>
                            <div style="font-weight:500;margin-top:6px;">Glissez votre fichier ici ou <span style="color:var(--brand-600);">parcourir</span></div>
                            <div class="form-text">Formats acceptés : .xlsx, .xls, .csv</div>
                            <input type="file" id="file" accept=".xlsx,.xls,.csv" hidden>
                        </label>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <button type="button" class="btn btn-soft-primary btn-sm" id="tpl"><i class="bx bx-download"></i> Télécharger le modèle</button>
                            <span class="form-text" id="fileInfo"></span>
                        </div>
                    </div>

                    <!-- Étape 2 : correspondance + aperçu -->
                    <div class="gu-step-panel" data-panel="2">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-transfer"></i></span> Correspondance des colonnes</div>
                                <div id="mapping"></div>
                            </div>
                            <div class="col-lg-7">
                                <div class="gu-section-title"><span class="gu-ico-chip success"><i class="bx bx-table"></i></span> Aperçu &amp; validation</div>
                                <div class="d-flex flex-wrap gap-2 mb-2" id="summary"></div>
                                <div id="conform" class="mb-2"></div>
                                <div class="gu-table-wrap" style="max-height:340px;">
                                    <table class="gu-table"><thead id="pvHead"></thead><tbody id="pvBody"></tbody></table>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-ghost btn-prev"><i class="bx bx-arrow-back"></i> Retour</button>
                            <button type="button" class="btn btn-primary btn-next">Suivant <i class="bx bx-right-arrow-alt"></i></button>
                        </div>
                    </div>

                    <!-- Étape 3 : promotion -->
                    <div class="gu-step-panel" data-panel="3">
                        <div class="gu-section-title"><span class="gu-ico-chip warning"><i class="bx bx-bookmark-alt"></i></span> Promotion de destination</div>
                        <div style="max-width:480px;">
                            <label class="form-label">Les étudiants importés seront rattachés à :</label>
                            <select id="promoSelect" class="form-select">
                                <option value="">— Choisir la promotion —</option>
                                <?php foreach ($listeFilieres as $p): ?>
                                    <option value="<?= htmlspecialchars($p->id_promotion) ?>"><?= htmlspecialchars(($p->sigle_filiere ?? '') . ' · ' . ($p->nom_semestre ?? '') . ' (' . ($p->annee_universitaire ?? '') . ')') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-ghost btn-prev"><i class="bx bx-arrow-back"></i> Retour</button>
                            <button type="button" class="btn btn-primary btn-next">Suivant <i class="bx bx-right-arrow-alt"></i></button>
                        </div>
                    </div>

                    <!-- Étape 4 : import -->
                    <div class="gu-step-panel" data-panel="4">
                        <div class="gu-section-title"><span class="gu-ico-chip"><i class="bx bx-cloud-upload"></i></span> Lancer l'import</div>
                        <div id="recap" class="mb-2"></div>
                        <div class="gu-progress mb-1"><span id="bar"></span></div>
                        <div class="form-text mb-3" id="progTxt">Prêt.</div>
                        <div id="report"></div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-ghost btn-prev"><i class="bx bx-arrow-back"></i> Retour</button>
                            <button type="button" class="btn btn-gradient" id="run"><i class="bx bx-play"></i> Lancer l'import</button>
                        </div>
                    </div>

                </div></div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <style>
        .gu-steps { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
        .gu-steps .st { display: flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: var(--radius-md);
            background: var(--surface-muted); color: var(--text-secondary); font-size: var(--fs-sm); font-weight: var(--fw-medium); cursor: pointer; }
        .gu-steps .st .n { width: 22px; height: 22px; border-radius: 50%; background: var(--border-strong); color: #fff;
            display: inline-flex; align-items: center; justify-content: center; font-size: 12px; }
        .gu-steps .st.active { background: var(--info-bg); color: var(--info-text); }
        .gu-steps .st.active .n { background: var(--brand-600); }
        .gu-steps .st.done .n { background: var(--success); }
        .gu-step-panel { display: none; } .gu-step-panel.active { display: block; }
        .gu-progress { height: 8px; border-radius: var(--radius-pill); background: var(--surface-muted); overflow: hidden; }
        .gu-progress > span { display: block; height: 100%; background: var(--brand-600); width: 0; transition: width .2s; }
        .map-row { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
        .map-row .xl { flex: 1; min-width: 0; font-size: var(--fs-sm); padding: 6px 10px; background: var(--surface-muted); border-radius: var(--radius-md); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .map-row .form-select { flex: 1; }
        td.bad { background: var(--danger-bg); }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
    (function () {
        var ROOT = window.APP_ROOT || '';
        var CHAMPS = <?= json_encode($champsBdd) ?>;
        var EXISTING = <?= json_encode($existing) ?>;
        var REQUIS = ['nom_prenom_etudiant'];
        var existNums = new Set((EXISTING.numetudiants || []).map(String));

        var headers = [], rows = [], mapping = [], step = 1;
        var wiz = document.getElementById('wiz');
        function $(s) { return wiz.querySelector(s); }
        function norm(s) { return String(s == null ? '' : s).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9]/g, ''); }

        document.getElementById('tpl').addEventListener('click', function () {
            var labels = Object.keys(CHAMPS).map(function (f) { return CHAMPS[f]; });
            var ws = XLSX.utils.aoa_to_sheet([labels]);
            var wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Etudiants');
            XLSX.writeFile(wb, 'modele_import_etudiants.xlsx');
        });

        var dz = document.getElementById('dz'), fileInput = document.getElementById('file');
        ['dragenter', 'dragover'].forEach(function (e) { dz.addEventListener(e, function (ev) { ev.preventDefault(); dz.classList.add('dragover'); }); });
        ['dragleave', 'drop'].forEach(function (e) { dz.addEventListener(e, function (ev) { ev.preventDefault(); dz.classList.remove('dragover'); }); });
        dz.addEventListener('drop', function (ev) { if (ev.dataTransfer.files[0]) handleFile(ev.dataTransfer.files[0]); });
        fileInput.addEventListener('change', function () { if (fileInput.files[0]) handleFile(fileInput.files[0]); });

        function badFile(msg) {
            headers = []; rows = []; mapping = [];
            fileInput.value = ''; document.getElementById('fileInfo').textContent = '';
            notify({ icon: 'error', title: 'Fichier non conforme',
                html: msg + '<br><small style="opacity:.8;">Formats acceptés : .xlsx, .xls, .csv. Utilisez « Télécharger le modèle » pour partir d\'une base correcte.</small>' });
        }
        function handleFile(f) {
            if (!/\.(xlsx|xls|csv)$/i.test(f.name || '')) { badFile('Format non pris en charge : <b>' + (f.name || '?') + '</b>.'); return; }
            document.getElementById('fileInfo').textContent = f.name;
            var reader = new FileReader();
            reader.onerror = function () { badFile('Impossible de lire le fichier (lecture interrompue).'); };
            reader.onload = function (e) {
                var data;
                try {
                    var wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array', cellDates: true });
                    if (!wb.SheetNames.length) throw new Error('no sheet');
                    data = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]], { header: 1, defval: '' });
                } catch (err) {
                    badFile('Le fichier est illisible ou corrompu (ce n\'est pas un classeur Excel/CSV valide, ou il est protégé par mot de passe).');
                    return;
                }
                headers = (data[0] || []).map(function (h) { return String(h).trim(); });
                rows = data.slice(1).filter(function (r) { return r.some(function (c) { return String(c).trim() !== ''; }); });
                if (!headers.filter(function (h) { return h !== ''; }).length) { badFile('Aucune colonne détectée : la 1<sup>re</sup> ligne du fichier doit contenir les en-têtes des colonnes.'); return; }
                if (!rows.length) { badFile('Aucune donnée à importer : le fichier ne contient que des en-têtes (ou est vide).'); return; }
                autoMap(); renderMapping(); refresh(); goStep(2);
            };
            reader.readAsArrayBuffer(f);
        }

        var lookup = {};
        Object.keys(CHAMPS).forEach(function (f) { lookup[norm(f)] = f; lookup[norm(CHAMPS[f])] = f; });
        [['nom', 'nom_prenom_etudiant'], ['nometprenom', 'nom_prenom_etudiant'], ['sexe', 'genre_etudiant'], ['genre', 'genre_etudiant'],
         ['telephone', 'contact_etudiant'], ['tel', 'contact_etudiant'], ['datedenaissance', 'date_naissance_etudiant'],
         ['lieudenaissance', 'lieu_naissance_etudiant'], ['cenou', 'numetudiant'], ['matriculecenou', 'numetudiant'],
         ['statut', 'id_statut'], ['nationalite', 'nationnalite']].forEach(function (a) { if (!lookup[a[0]]) lookup[a[0]] = a[1]; });

        function autoMap() { mapping = headers.map(function (h) { return lookup[norm(h)] || ''; }); }

        function renderMapping() {
            var base = '<option value="">— Ignorer —</option>' + Object.keys(CHAMPS).map(function (f) { return '<option value="' + f + '">' + CHAMPS[f] + '</option>'; }).join('');
            $('#mapping').innerHTML = headers.map(function (h, i) {
                var sel = base.replace('value="' + mapping[i] + '"', 'value="' + mapping[i] + '" selected');
                return '<div class="map-row"><span class="xl" title="' + (h || '') + '"><i class="bx bx-spreadsheet"></i> ' + (h || 'Colonne ' + (i + 1)) + '</span>' +
                    '<i class="bx bx-right-arrow-alt" style="color:var(--text-tertiary);"></i>' +
                    '<select class="form-select" data-col="' + i + '">' + sel + '</select></div>';
            }).join('');
            $('#mapping').querySelectorAll('select').forEach(function (s) {
                s.addEventListener('change', function () { mapping[+s.getAttribute('data-col')] = s.value; refresh(); });
            });
        }

        function fmt(v) { if (v instanceof Date && !isNaN(v)) { return v.getFullYear() + '-' + String(v.getMonth() + 1).padStart(2, '0') + '-' + String(v.getDate()).padStart(2, '0'); } return v == null ? '' : String(v).trim(); }
        function mappedRow(r) { var o = {}; headers.forEach(function (h, i) { if (mapping[i]) o[mapping[i]] = fmt(r[i]); }); return o; }

        var existPersons = new Set((EXISTING.personnes || []).map(String));
        function personKey(nom, prenom) {
            return (String(nom || '') + ' ' + String(prenom || '')).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9]/g, '');
        }
        var STATUT_LABELS = { REGULIER: 'Régulier', CANDIDAT_LIBRE: 'Candidat libre', FORMATION_CONTINUE: 'Formation continue', PRO_PUBLIC: 'Professionnel public', PRO_PRIVE: 'Professionnel privé' };
        function statutNorm(raw) {
            var s = String(raw == null ? '' : raw).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').replace(/[^a-z]/g, '');
            if (!s) return null;
            if (['reg', 'regulier', 'regle'].indexOf(s) > -1) return 'REGULIER';
            if (['cl', 'candidatlibre'].indexOf(s) > -1) return 'CANDIDAT_LIBRE';
            if (['fc', 'formationcontinue'].indexOf(s) > -1) return 'FORMATION_CONTINUE';
            if (s.indexOf('priv') > -1 || s === 'proprive') return 'PRO_PRIVE';
            if (s.indexOf('pub') > -1 || s.indexOf('collect') > -1 || s.indexOf('colect') > -1 || s.indexOf('etat') > -1) return 'PRO_PUBLIC';
            if (s.indexOf('prof') === 0 || s.indexOf('pro') === 0) return 'PRO_PUBLIC';
            return null;
        }
        function statutLabel(raw) { var c = statutNorm(raw); return c ? STATUT_LABELS[c] : (String(raw || '').trim() || '—'); }
        function genMatricule(nom, prenom, genre, index) {
            var an = (new Date()).getFullYear();
            var ln = (String(nom || '').trim().charAt(0) || '').toUpperCase();
            var lp = (String(prenom || '').trim().charAt(0) || '').toUpperCase();
            return '' + an + ln + lp + String(genre || '').toUpperCase() + String(index + 1).padStart(3, '0');
        }

        function analyse() {
            var seenNum = new Set(), seenPers = new Set(), out = [];
            rows.forEach(function (r) {
                var o = mappedRow(r), st = 'ok', nameDup = false;
                var nom = String(o.nom_prenom_etudiant || '').trim();
                var num = String(o.numetudiant || '').trim();
                var pk = personKey(o.nom_prenom_etudiant, o.prenom);
                if (!nom) {
                    st = 'invalid';
                } else if (num && (existNums.has(num) || seenNum.has(num))) {
                    st = 'dup'; // doublon ferme (N° Cenou) -> exclu
                } else if (pk && (existPersons.has(pk) || seenPers.has(pk))) {
                    nameDup = true; // doublon possible (nom+prénom) -> signalé mais importé
                }
                if (num) seenNum.add(num);
                if (pk) seenPers.add(pk);
                var stat = String(o.id_statut || '').trim();
                out.push({ o: o, st: st, nameDup: nameDup, statutUnknown: (stat !== '' && !statutNorm(stat)) });
            });
            return out;
        }

        function refresh() {
            var a = analyse();
            var ok = a.filter(function (x) { return x.st === 'ok'; });
            var dup = a.filter(function (x) { return x.st === 'dup'; }).length;
            var bad = a.filter(function (x) { return x.st === 'invalid'; }).length;
            var nameDups = ok.filter(function (x) { return x.nameDup; }).length;
            var statInc = a.filter(function (x) { return x.statutUnknown && x.st !== 'invalid'; }).length;
            $('#summary').innerHTML =
                '<span class="badge badge-soft-info">' + a.length + ' lignes</span>' +
                '<span class="badge badge-soft-success">' + ok.length + ' à importer</span>' +
                (nameDups ? '<span class="badge badge-soft-warning" title="Même nom déjà présent — vérifiez">' + nameDups + ' doublon(s) possible(s)</span>' : '') +
                (dup ? '<span class="badge badge-soft-warning">' + dup + ' doublon(s) N° exclu(s)</span>' : '') +
                (bad ? '<span class="badge badge-soft-danger">' + bad + ' invalide(s)</span>' : '') +
                (statInc ? '<span class="badge badge-soft-danger" title="Statut non reconnu -> tarif par défaut">' + statInc + ' statut(s) non reconnu(s)</span>' : '');
            var fields = Object.keys(CHAMPS).filter(function (f) { return mapping.indexOf(f) > -1 && f !== 'matricule_etudiant'; });
            $('#pvHead').innerHTML = '<tr><th>#</th>' + fields.map(function (f) { return '<th>' + (f === 'id_statut' ? 'Statut' : CHAMPS[f]) + '</th>'; }).join('') + '<th>Matricule (auto)</th><th>État</th></tr>';
            $('#pvBody').innerHTML = a.slice(0, 8).map(function (x, i) {
                var tds = fields.map(function (f) {
                    var raw = x.o[f] == null ? '' : String(x.o[f]);
                    var disp = raw, cls = '';
                    if (f === 'id_statut' && raw) { disp = statutLabel(raw); if (x.statutUnknown) cls = 'bad'; }
                    if (REQUIS.indexOf(f) > -1 && !raw.trim()) cls = 'bad';
                    return '<td class="' + cls + '">' + String(disp).replace(/</g, '&lt;') + '</td>';
                }).join('');
                var mat = x.st === 'invalid' ? '—' : genMatricule(x.o.nom_prenom_etudiant, x.o.prenom, x.o.genre_etudiant, i);
                var b = x.st === 'invalid' ? '<span class="badge badge-soft-danger">Invalide</span>'
                      : x.st === 'dup' ? '<span class="badge badge-soft-warning">Doublon N°</span>'
                      : x.nameDup ? '<span class="badge badge-soft-warning" title="Même nom déjà présent">Doublon possible</span>'
                      : '<span class="badge badge-soft-success">OK</span>';
                return '<tr><td>' + (i + 1) + '</td>' + tds + '<td style="font-family:var(--font-mono);font-size:var(--fs-xs);">' + String(mat).replace(/</g, '&lt;') + '</td><td>' + b + '</td></tr>';
            }).join('');
            // Verdict de conformité + récupération des lignes fautives
            var nomMapped = mapping.indexOf('nom_prenom_etudiant') > -1;
            var issues = bad + dup + nameDups + statInc;
            var cf;
            if (!nomMapped) {
                cf = '<div class="alert alert-soft-danger d-flex align-items-center gap-2"><i class="bx bx-error-circle"></i> Colonne <b>« Nom &amp; prénom »</b> non associée — choisissez-la dans la correspondance à gauche : c\'est la seule colonne obligatoire pour importer.</div>';
            } else if (issues) {
                cf = '<div class="alert alert-soft-warning d-flex align-items-center justify-content-between gap-2" style="flex-wrap:wrap;">'
                   + '<span><i class="bx bx-list-check"></i> <b>' + issues + '</b> ligne(s) à corriger ou vérifier. Les ' + ok.length + ' ligne(s) valide(s) seront importées ; téléchargez le détail pour corriger le reste.</span>'
                   + '<button type="button" class="btn btn-soft-primary btn-sm" id="dlReject"><i class="bx bx-download"></i> Lignes à corriger (.xlsx)</button></div>';
            } else {
                cf = '<div class="alert alert-soft-success d-flex align-items-center gap-2"><i class="bx bx-check-circle"></i> Fichier conforme — <b>' + ok.length + '</b> ligne(s) prête(s) à importer.</div>';
            }
            $('#conform').innerHTML = cf;
            var dl = $('#dlReject'); if (dl) dl.addEventListener('click', exportRejected);

            window._okRows = ok.map(function (x) { return x.o; });
            window._stats = { ok: ok.length, dup: dup, bad: bad, nameDups: nameDups, statInc: statInc, total: a.length };
        }

        // Motif de rejet/vérification d'une ligne analysée
        function motifFor(x) {
            var m = [];
            if (x.st === 'invalid') m.push('Nom manquant');
            else if (x.st === 'dup') m.push('Doublon (N° Cenou déjà enregistré)');
            if (x.nameDup) m.push('Doublon possible (même nom et prénom déjà présents)');
            if (x.statutUnknown) m.push('Statut non reconnu (tarif par défaut appliqué)');
            return m.join(' ; ');
        }
        // Export Excel des lignes fautives : colonnes d'origine + Motif + Importée ?
        function exportRejected() {
            var a = analyse();
            var aoa = [headers.concat(['Motif', 'Importée ?'])];
            a.forEach(function (x, i) {
                var motif = motifFor(x);
                if (!motif) return; // ligne parfaitement conforme : non incluse
                var cells = [];
                for (var c = 0; c < headers.length; c++) { var v = (rows[i] || [])[c]; cells.push(v == null ? '' : v); }
                aoa.push(cells.concat([motif, x.st === 'ok' ? 'Oui (signalée)' : 'Non']));
            });
            if (aoa.length <= 1) { notify({ icon: 'success', title: 'Rien à corriger', text: 'Toutes les lignes sont conformes.' }); return; }
            var ws = XLSX.utils.aoa_to_sheet(aoa);
            var wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'A_corriger');
            XLSX.writeFile(wb, 'lignes_a_corriger.xlsx');
        }

        function goStep(n) {
            step = n;
            wiz.querySelectorAll('.gu-step-panel').forEach(function (p) { p.classList.toggle('active', +p.getAttribute('data-panel') === n); });
            wiz.querySelectorAll('.gu-steps .st').forEach(function (s) { var d = +s.getAttribute('data-step'); s.classList.toggle('active', d === n); s.classList.toggle('done', d < n); });
            if (n === 4) {
                var sel = $('#promoSelect'), promo = sel.options[sel.selectedIndex];
                $('#recap').innerHTML = '<span class="badge badge-soft-success">' + (window._okRows || []).length + ' étudiants</span> seront importés vers <b>' + (promo ? promo.text : '—') + '</b>.';
            }
        }
        wiz.querySelectorAll('.btn-prev').forEach(function (b) { b.addEventListener('click', function () { goStep(Math.max(1, step - 1)); }); });
        wiz.querySelectorAll('.btn-next').forEach(function (b) { b.addEventListener('click', function () {
            if (step === 2 && mapping.indexOf('nom_prenom_etudiant') === -1) {
                notify({ icon: 'warning', title: 'Correspondance incomplète', text: 'Associez la colonne « Nom & prénom » avant de continuer : c\'est la seule colonne obligatoire.' });
                return;
            }
            if (step === 3 && !$('#promoSelect').value) { notify({ icon: 'warning', title: 'Promotion requise', text: 'Choisissez la promotion de destination.' }); return; }
            goStep(Math.min(4, step + 1));
        }); });
        wiz.querySelectorAll('.gu-steps .st').forEach(function (s) { s.addEventListener('click', function () { var d = +s.getAttribute('data-step'); if (d < step) goStep(d); }); });

        function notify(opts) {
            if (window.Swal) { if (!opts.confirmButtonColor) opts.confirmButtonColor = '#1f4f9c'; return Swal.fire(opts); }
            alert((opts.title ? opts.title + '\n\n' : '') + (opts.text || (opts.html || '').replace(/<[^>]+>/g, '')));
        }

        document.getElementById('run').addEventListener('click', function () {
            var okRows = window._okRows || [];
            var s = window._stats || { dup: 0, bad: 0, total: 0 };
            var excluded = (rows.length || 0) - okRows.length;
            var promo = $('#promoSelect').value;

            if (!promo) { notify({ icon: 'warning', title: 'Promotion requise', text: 'Choisissez la promotion de destination (étape 3).' }); return; }
            if (!okRows.length) {
                var why = (s.total === 0) ? 'Aucun fichier chargé, ou fichier vide.'
                    : (s.bad && !s.dup) ? 'Aucune ligne valide : la colonne « Nom » n\'est probablement pas associée. Vérifiez la correspondance à l\'étape 2.'
                    : (s.dup && !s.bad) ? 'Toutes les lignes (' + s.dup + ') sont déjà enregistrées (doublons).'
                    : 'Aucune ligne importable : ' + (s.dup || 0) + ' doublon(s) et ' + (s.bad || 0) + ' invalide(s). Vérifiez la correspondance à l\'étape 2.';
                notify({ icon: 'info', title: 'Rien à importer', text: why });
                return;
            }

            var promoTxt = $('#promoSelect').options[$('#promoSelect').selectedIndex].text;
            var runBtn = this; runBtn.classList.add('is-loading'); runBtn.disabled = true;
            $('#report').innerHTML = '';
            var batch = 50, done = 0, imported = 0, skipped = 0, errors = [];

            function fail(msg) {
                runBtn.classList.remove('is-loading'); runBtn.disabled = false;
                $('#progTxt').textContent = 'Échec.';
                notify({ icon: 'error', title: 'Import interrompu', text: msg });
            }
            function sendNext() {
                if (done >= okRows.length) { finish(); return; }
                var slice = okRows.slice(done, done + batch);
                fetch(ROOT + '/EtudiantPargroupes/importJson', {
                    method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_promotion: promo, start: done, rows: slice })
                }).then(function (r) { return r.text(); }).then(function (txt) {
                    var res;
                    try { res = JSON.parse(txt); } catch (e) {
                        fail('Réponse inattendue du serveur (session expirée ?). Reconnectez-vous puis réessayez.'); return;
                    }
                    if (res.error) { fail(res.error); return; }
                    imported += res.imported || 0; skipped += res.skipped || 0;
                    if (res.errors) errors = errors.concat(res.errors);
                    done += slice.length;
                    var pct = Math.round(done / okRows.length * 100);
                    $('#bar').style.width = pct + '%';
                    $('#progTxt').textContent = 'Import en cours… ' + done + ' / ' + okRows.length + ' (' + pct + '%)';
                    sendNext();
                }).catch(function () { fail('Erreur réseau pendant l\'import. Vérifiez votre connexion et réessayez.'); });
            }
            function finish() {
                runBtn.classList.remove('is-loading'); runBtn.disabled = false;
                $('#bar').style.width = '100%';
                $('#progTxt').textContent = 'Terminé.';
                var errHtml = errors.length ? '<div class="mt-2"><div class="gu-section-title" style="font-size:var(--fs-base);"><span class="gu-ico-chip warning"><i class="bx bx-error"></i></span> Lignes en erreur</div><div class="gu-table-wrap" style="max-height:200px;"><table class="gu-table"><thead><tr><th>Ligne</th><th>Raison</th></tr></thead><tbody>' + errors.map(function (e) { return '<tr><td>' + e.ligne + '</td><td>' + String(e.raison).replace(/</g, '&lt;') + '</td></tr>'; }).join('') + '</tbody></table></div></div>' : '';
                $('#report').innerHTML =
                    '<div class="alert alert-soft-success d-flex align-items-center gap-2"><i class="bx bx-check-circle"></i> <b>' + imported + '</b> étudiant(s) importé(s) avec succès vers <b>' + promoTxt + '</b>.</div>' +
                    (excluded ? '<div class="alert alert-soft-info d-flex align-items-center gap-2"><i class="bx bx-info-circle"></i> ' + excluded + ' ligne(s) exclue(s) avant envoi (doublons / invalides).</div>' : '') +
                    (skipped ? '<div class="alert alert-soft-warning d-flex align-items-center gap-2"><i class="bx bx-error"></i> ' + skipped + ' ignoré(s) à l\'insertion.</div>' : '') +
                    errHtml +
                    '<a href="' + ROOT + '/Etudiants" class="btn btn-primary mt-2"><i class="bx bx-list-ul"></i> Voir la liste des étudiants</a>';
                $('#report').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                notify({
                    icon: imported > 0 ? 'success' : 'info',
                    title: imported > 0 ? 'Import terminé' : 'Import terminé (0 ajouté)',
                    html: '<b>' + imported + '</b> étudiant(s) importé(s) vers <b>' + promoTxt + '</b>.'
                        + (excluded ? '<br>' + excluded + ' exclu(s) (doublons / invalides).' : '')
                        + (skipped ? '<br>' + skipped + ' ignoré(s) à l\'insertion.' : ''),
                    confirmButtonText: 'OK'
                });
            }
            sendNext();
        });
    })();
    </script>

</body>

</html>
