<?php
$this->view("Partials/header");
// Map des totaux déjà payés par étudiant
$payeMap = [];
foreach (($paiements ?? []) as $p) {
    $payeMap[$p['idEtudt']] = ($payeMap[$p['idEtudt']] ?? 0) + (int) $p['total_paye'];
}
$fmt = function ($v) { return number_format((int) $v, 0, ',', ' '); };
?>

<style>
    #grpCard #grpTable { border-collapse: collapse; width: 100%; font-size: 13px; }
    #grpCard #grpTable th, #grpCard #grpTable td { border: 1px solid #9aa3b2; padding: 7px 9px; }
    #grpCard #grpTable thead th { background: #e7ecf5; color: #14346b; text-align: left; vertical-align: middle; }
    #grpCard #grpTable tbody tr:nth-child(even) { background: #f6f8fc; }
    #grpCard #grpTable .num { text-align: right; }
    #grpCard #grpTable input.montant { width: 130px; text-align: right; }
    #grpCard .reste { font-weight: 700; }
    #grpCard .reste.ok { color: #15803d; } #grpCard .reste.ko { color: #b91c1c; }
    #grpCard .grp-bar { position: sticky; bottom: 0; background: #fff; border-top: 1px solid #e5e7eb; padding: 12px 4px; display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 8px; }
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
                            <h5 class="content-header-title float-left pr-1 mb-0">Étudiants</h5>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb p-0 mb-0">
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Homes"><i class="bx bx-home-alt"></i></a></li>
                                    <li class="breadcrumb-item"><a href="<?= ROOT ?>/Etudiants">Gestion Étudiants</a></li>
                                    <li class="breadcrumb-item active">Paiement groupé</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card" id="grpCard">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-money"></i></span> Paiement groupé</div>

                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
                                <span class="text-muted" style="font-size:.85rem;"><i class="bx bx-group"></i> <b><?= count($etudiants) ?></b> étudiant(s) sélectionné(s) — laissez vide pour ne pas faire payer.</span>
                                <button type="button" class="btn btn-soft-primary btn-sm" id="toutSolder"><i class="bx bx-wallet"></i> Tout solder</button>
                            </div>

                            <form action="<?= ROOT ?>/Etudiants/traiter_paiement_groupes" method="POST" id="grpForm">
                                <div class="gu-table-wrap" style="max-height:calc(100vh - 360px);">
                                    <table id="grpTable">
                                        <thead>
                                            <tr>
                                                <th style="width:42px;">N°</th>
                                                <th>Nom &amp; Prénom</th>
                                                <th>Matricule</th>
                                                <th class="num">Total dû</th>
                                                <th class="num">Déjà payé</th>
                                                <th class="num">Montant à payer</th>
                                                <th class="num">Reste après</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; foreach ($etudiants as $etudiant):
                                                $frais = (int) $etudiant->total_frais;
                                                $paye = (int) ($payeMap[$etudiant->id_etudiant] ?? 0);
                                                $reste = max(0, $frais - $paye);
                                            ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td style="font-weight:var(--fw-medium);"><?= mb_strtoupper(htmlspecialchars($etudiant->nom_prenom_etudiant ?? ''), 'UTF-8') ?><?= !empty($etudiant->prenom) ? ' ' . mb_convert_case(htmlspecialchars($etudiant->prenom), MB_CASE_TITLE, 'UTF-8') : '' ?></td>
                                                    <td><?= htmlspecialchars($etudiant->matricule_etudiant ?? '—') ?></td>
                                                    <td class="num"><?= $fmt($frais) ?></td>
                                                    <td class="num"><?= $fmt($paye) ?></td>
                                                    <td class="num">
                                                        <input type="number" name="paiement[<?= $etudiant->id_etudiant ?>]" class="form-control montant"
                                                            min="0" max="<?= $reste ?>" step="1" placeholder="0"
                                                            data-reste="<?= $reste ?>" oninput="calculerReste(this)">
                                                    </td>
                                                    <td class="num reste <?= $reste == 0 ? 'ok' : 'ko' ?>" data-reste="<?= $reste ?>"><?= $fmt($reste) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="grp-bar">
                                    <span style="font-size:.95rem;">Total à encaisser : <b id="totalEncaisser" style="color:#14346b;">0 FCFA</b></span>
                                    <div style="display:flex;gap:8px;">
                                        <a href="<?= ROOT ?>/Etudiants" class="btn btn-ghost">Retour</a>
                                        <button type="submit" class="btn btn-primary"><i class="bx bx-check-double"></i> Enregistrer les paiements</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
</body>

</html>
<script>
    function fmtFcfa(n) { return (n || 0).toLocaleString('fr-FR') + ' FCFA'; }

    function calculerReste(input) {
        var resteInit = parseInt(input.getAttribute('data-reste'), 10) || 0;
        var montant = parseInt(input.value, 10) || 0;
        if (montant < 0) { montant = 0; input.value = ''; }
        if (montant > resteInit) { montant = resteInit; input.value = resteInit; }
        var resteCell = input.closest('tr').querySelector('.reste');
        var resteApres = resteInit - montant;
        resteCell.textContent = fmtFcfa(resteApres);
        resteCell.className = 'num reste ' + (resteApres === 0 ? 'ok' : 'ko');
        majTotal();
    }

    function majTotal() {
        var total = 0;
        document.querySelectorAll('#grpTable .montant').forEach(function (i) { total += parseInt(i.value, 10) || 0; });
        document.getElementById('totalEncaisser').textContent = fmtFcfa(total);
    }

    document.getElementById('toutSolder').addEventListener('click', function () {
        document.querySelectorAll('#grpTable .montant').forEach(function (i) {
            i.value = parseInt(i.getAttribute('data-reste'), 10) || 0;
            calculerReste(i);
        });
    });

    document.getElementById('grpForm').addEventListener('submit', function (e) {
        var total = 0;
        document.querySelectorAll('#grpTable .montant').forEach(function (i) { total += parseInt(i.value, 10) || 0; });
        if (total <= 0) {
            e.preventDefault();
            Swal.fire("Aucun montant", "Saisissez au moins un montant à encaisser (ou utilisez « Tout solder »).", "warning");
        }
    });
</script>
