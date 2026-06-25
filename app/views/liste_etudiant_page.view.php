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
                            <h5 class="content-header-title float-left pr-1 mb-0">Étudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item active">Liste des étudiants</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="gu-table-card is-fitted" id="etuList">
                    <div class="gu-toolbar">
                        <span class="gu-title"><i class="bx bx-group"></i> Liste des étudiants <span class="gu-count" data-count>0</span></span>

                        <div class="gu-field gu-search ms-auto" style="min-width:210px;">
                            <i class="bx bx-search gu-ico"></i>
                            <input type="text" class="form-control has-ico" placeholder="Rechercher (nom, matricule…)" data-search>
                        </div>

                        <select class="form-select" style="width:auto;min-width:230px;" data-promotion data-smart title="Classe (promotion)">
                            <option value="">Toutes les classes</option>
                            <?php foreach ($promotions as $p): ?>
                                <option value="<?= htmlspecialchars($p->id_promotion) ?>"><?= htmlspecialchars(strtoupper($p->sigle_filiere) . ' · ' . ($p->sigle_semestre ?? $p->nom_semestre ?? '') . ' (' . $p->annee_universitaire . ')') ?></option>
                            <?php endforeach; ?>
                        </select>

                        <a href="<?= ROOT ?>/Etudiants/export_liste" target="_blank" class="btn btn-soft-primary"><i class="bx bx-download"></i> Exporter</a>
                        <a href="<?= ROOT ?>/Etudiants/incrit_etudiant" class="btn btn-primary"><i class="bx bx-plus"></i> Inscrire</a>
                    </div>

                    <div class="gu-table-wrap">
                        <table class="gu-table gu-card-mobile">
                            <thead>
                                <tr>
                                    <th style="width:42px;text-align:center;"><input class="form-check-input" type="checkbox" data-all></th>
                                    <th class="sortable" data-key="name">Nom &amp; prénom <i class="bx bx-sort-alt-2"></i></th>
                                    <th class="sortable" data-key="matricule">Matricule <i class="bx bx-sort-alt-2"></i></th>
                                    <th class="sortable" data-key="statut">Statut <i class="bx bx-sort-alt-2"></i></th>
                                    <th class="sortable" data-key="filiere">Filière <i class="bx bx-sort-alt-2"></i></th>
                                    <th class="sortable" data-key="diplome">Diplôme <i class="bx bx-sort-alt-2"></i></th>
                                    <th style="width:120px;text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody data-body></tbody>
                        </table>
                        <div class="gu-empty" data-empty hidden><i class="bx bx-user-x"></i>Aucun étudiant trouvé.</div>
                        <div class="gu-empty" data-loading><i class="bx bx-loader-alt bx-spin"></i>Chargement…</div>
                    </div>

                    <div class="gu-foot">
                        <div class="d-flex align-items-center flex-wrap" style="gap:10px 16px;">
                            <div class="gu-pagination" data-pager></div>
                            <div class="d-flex align-items-center" style="gap:6px;font-size:var(--fs-sm);color:var(--text-secondary);">
                                <span>Aller à</span>
                                <input type="number" min="1" class="form-control" data-jump style="width:66px;height:30px;padding:.2rem .5rem;text-align:center;">
                            </div>
                            <span data-info style="font-size:var(--fs-sm);">—</span>
                            <div class="d-flex align-items-center" style="gap:6px;font-size:var(--fs-sm);color:var(--text-secondary);">
                                <span>Lignes</span>
                                <select class="form-select form-select-sm" data-size style="width:auto;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-soft-primary btn-sm" data-pay><i class="bx bx-wallet"></i> Paiement groupé (<span data-selcount>0</span>)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>

    <script src="<?= ROOT ?>/assets/mon_js/gu-smart-select.js"></script>
    <script>
    (function () {
        var ROOT = window.APP_ROOT || '';
        var root = document.getElementById('etuList');
        var body = root.querySelector('[data-body]');
        var count = root.querySelector('[data-count]');
        var empty = root.querySelector('[data-empty]');
        var loading = root.querySelector('[data-loading]');
        var info = root.querySelector('[data-info]');
        var pager = root.querySelector('[data-pager]');
        var sizeEl = root.querySelector('[data-size]');
        var jumpEl = root.querySelector('[data-jump]');
        var allCb = root.querySelector('[data-all]');
        var selCount = root.querySelector('[data-selcount]');
        var searchEl = root.querySelector('[data-search]');
        var promoEl = root.querySelector('[data-promotion]');

        var st = { q: '', promotion: '', sort: 'name', dir: 'asc', page: 1, size: 10, total: 0, pages: 1 };
        var selected = new Set();
        var timer = null;

        function esc(s) { return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
        function initials(n) { return (n || '').split(/\s+/).filter(Boolean).map(function (w) { return w[0]; }).join('').slice(0, 2).toUpperCase() || 'E'; }

        function rowHtml(e) {
            var id = e.id_etudiant;
            var nom = ((e.nom_prenom_etudiant || '') + ' ' + (e.prenom || '')).trim();
            var statut = '<span class="badge ' + (e.id_statut === 'REGULIER' ? 'badge-soft-success' : 'badge-soft-info') + '">' + esc(e.statut_label || e.id_statut || '—') + '</span>';
            var ck = selected.has(String(id)) ? 'checked' : '';
            return '<tr data-id="' + id + '" class="' + (ck ? 'is-selected' : '') + '">' +
                '<td class="text-center"><input class="form-check-input" type="checkbox" data-row ' + ck + '></td>' +
                '<td><div class="d-flex align-items-center" style="gap:10px;"><span class="gu-avatar">' + esc(initials(nom)) + '</span>' +
                    '<span style="font-weight:500;">' + esc(nom) + '</span></div></td>' +
                '<td>' + esc(e.matricule_etudiant) + '</td>' +
                '<td>' + statut + '</td>' +
                '<td>' + esc(e.nom_filiere) + '</td>' +
                '<td>' + esc(e.diplome) + '</td>' +
                '<td style="text-align:right;"><div class="gu-row-actions" style="justify-content:flex-end;">' +
                    '<a class="btn btn-ghost btn-icon btn-sm" title="Aperçu" href="' + ROOT + '/Etudiants/apercu_etudiant/' + id + '"><i class="bx bx-show"></i></a>' +
                    '<a class="btn btn-ghost btn-icon btn-sm" title="Modifier" href="' + ROOT + '/Etudiants/modifier/' + id + '"><i class="bx bx-edit"></i></a>' +
                    '<a class="btn btn-ghost btn-icon btn-sm" title="Paiement" href="' + ROOT + '/Etudiants/paiement_etudiant/' + id + '"><i class="bx bx-wallet"></i></a>' +
                '</div></td></tr>';
        }

        function load() {
            loading.hidden = false; empty.hidden = true;
            var data = new URLSearchParams({ q: st.q, promotion: st.promotion, sort: st.sort, dir: st.dir, page: st.page, size: st.size });
            fetch(ROOT + '/Etudiants/liste_data', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: data.toString()
            }).then(function (r) { return r.json(); }).then(function (res) {
                loading.hidden = true;
                st.total = res.total; st.pages = res.pages; st.page = res.page;
                count.textContent = res.total;
                body.innerHTML = (res.rows || []).map(rowHtml).join('');
                empty.hidden = (res.rows && res.rows.length > 0);
                var from = res.total ? ((res.page - 1) * res.size + 1) : 0;
                var to = Math.min(res.page * res.size, res.total);
                info.textContent = res.total ? (from + '–' + to + ' sur ' + res.total + ' étudiants') : 'Aucun étudiant';
                renderPager();
                syncAll();
            }).catch(function () {
                loading.hidden = true; empty.hidden = false;
            });
        }

        function pageWindow(cur, total) {
            var a = [];
            if (total <= 7) { for (var i = 1; i <= total; i++) a.push(i); return a; }
            a.push(1);
            var s = Math.max(2, cur - 1), e = Math.min(total - 1, cur + 1);
            if (s > 2) a.push('…');
            for (var j = s; j <= e; j++) a.push(j);
            if (e < total - 1) a.push('…');
            a.push(total);
            return a;
        }
        function renderPager() {
            var p = st.page, n = st.pages;
            var h = '<button ' + (p <= 1 ? 'disabled' : '') + ' data-p="first" title="Première page"><i class="bx bx-chevrons-left"></i></button>'
                  + '<button ' + (p <= 1 ? 'disabled' : '') + ' data-p="prev" title="Précédente"><i class="bx bx-chevron-left"></i></button>';
            pageWindow(p, n).forEach(function (x) {
                h += (x === '…')
                    ? '<button disabled style="border:0;background:transparent;min-width:1.2rem;cursor:default;">…</button>'
                    : '<button class="' + (x === p ? 'active' : '') + '" data-p="' + x + '">' + x + '</button>';
            });
            h += '<button ' + (p >= n ? 'disabled' : '') + ' data-p="next" title="Suivante"><i class="bx bx-chevron-right"></i></button>'
               + '<button ' + (p >= n ? 'disabled' : '') + ' data-p="last" title="Dernière page"><i class="bx bx-chevrons-right"></i></button>';
            pager.innerHTML = h;
            if (jumpEl) { jumpEl.max = n; jumpEl.placeholder = n > 1 ? ('1–' + n) : '1'; }
        }

        function syncAll() {
            var vis = Array.prototype.slice.call(body.querySelectorAll('[data-row]'));
            var on = vis.filter(function (cb) { return cb.checked; });
            allCb.checked = vis.length > 0 && on.length === vis.length;
            allCb.indeterminate = on.length > 0 && on.length < vis.length;
            selCount.textContent = selected.size;
        }

        searchEl.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { st.q = searchEl.value.trim(); st.page = 1; load(); }, 300);
        });
        promoEl.addEventListener('change', function () { st.promotion = promoEl.value; st.page = 1; load(); });

        // Nombre de lignes par page (mémorisé)
        var savedSize = parseInt(localStorage.getItem('gu_etu_size'), 10);
        if ([10, 25, 50, 100].indexOf(savedSize) > -1) { st.size = savedSize; sizeEl.value = String(savedSize); }
        sizeEl.addEventListener('change', function () {
            st.size = parseInt(sizeEl.value, 10) || 10;
            localStorage.setItem('gu_etu_size', st.size);
            st.page = 1; load();
        });

        // « Aller à la page »
        function doJump() {
            var n = parseInt(jumpEl.value, 10);
            if (n >= 1 && n <= st.pages && n !== st.page) { st.page = n; load(); }
            jumpEl.value = '';
        }
        jumpEl.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); doJump(); } });
        jumpEl.addEventListener('blur', doJump);

        // Navigation clavier (flèches gauche/droite) hors champs de saisie
        document.addEventListener('keydown', function (e) {
            var t = (e.target && e.target.tagName) || '';
            if (t === 'INPUT' || t === 'TEXTAREA' || t === 'SELECT') return;
            if (e.key === 'ArrowLeft' && st.page > 1) { st.page--; load(); }
            else if (e.key === 'ArrowRight' && st.page < st.pages) { st.page++; load(); }
        });

        root.querySelectorAll('th.sortable').forEach(function (th) {
            th.addEventListener('click', function () {
                var k = th.getAttribute('data-key');
                if (st.sort === k) st.dir = (st.dir === 'asc' ? 'desc' : 'asc'); else { st.sort = k; st.dir = 'asc'; }
                root.querySelectorAll('th.sortable').forEach(function (t) {
                    t.classList.remove('sorted-asc', 'sorted-desc');
                    t.querySelector('i').className = 'bx bx-sort-alt-2';
                });
                th.classList.add(st.dir === 'asc' ? 'sorted-asc' : 'sorted-desc');
                th.querySelector('i').className = st.dir === 'asc' ? 'bx bx-chevron-up' : 'bx bx-chevron-down';
                load();
            });
        });

        pager.addEventListener('click', function (e) {
            var b = e.target.closest('button'); if (!b || b.disabled) return;
            var p = b.getAttribute('data-p'); if (p === null) return;
            if (p === 'first') st.page = 1;
            else if (p === 'prev') st.page = Math.max(1, st.page - 1);
            else if (p === 'next') st.page = Math.min(st.pages, st.page + 1);
            else if (p === 'last') st.page = st.pages;
            else { var n = parseInt(p, 10); if (!n || n === st.page) return; st.page = n; }
            load();
        });

        body.addEventListener('change', function (e) {
            if (!e.target.matches('[data-row]')) return;
            var tr = e.target.closest('tr'); var id = tr.getAttribute('data-id');
            if (e.target.checked) selected.add(id); else selected.delete(id);
            tr.classList.toggle('is-selected', e.target.checked);
            syncAll();
        });
        allCb.addEventListener('change', function () {
            body.querySelectorAll('[data-row]').forEach(function (cb) {
                var tr = cb.closest('tr'); var id = tr.getAttribute('data-id');
                cb.checked = allCb.checked;
                if (allCb.checked) selected.add(id); else selected.delete(id);
                tr.classList.toggle('is-selected', allCb.checked);
            });
            syncAll();
        });

        root.querySelector('[data-pay]').addEventListener('click', function () {
            if (!selected.size) { alert('Sélectionnez au moins un étudiant.'); return; }
            var f = document.createElement('form');
            f.method = 'POST'; f.action = ROOT + '/Etudiants/paiement_groupe';
            selected.forEach(function (id) {
                var i = document.createElement('input');
                i.type = 'hidden'; i.name = 'paie[]'; i.value = id; f.appendChild(i);
            });
            document.body.appendChild(f); f.submit();
        });

        load();
        if (window.guSmartSelectAll) guSmartSelectAll();
    })();
    </script>

</body>

</html>
