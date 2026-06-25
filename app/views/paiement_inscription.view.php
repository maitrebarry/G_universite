<?php $this->view("Partials/header") ?>

<style>
    #paieCard .stat { border: 1px solid #c7d2e6; border-radius: 10px; padding: 12px 16px; text-align: center; background: #f4f7fc; }
    #paieCard .stat .lab { font-size: 11px; color: #5a6b86; text-transform: uppercase; letter-spacing: .4px; }
    #paieCard .stat .val { font-size: 22px; font-weight: 700; color: #14346b; line-height: 1.2; }
    #paieCard .stat.due .val { color: #14346b; }
    #paieCard .stat.paid { background: #e9f7ef; border-color: #9ed9b5; } #paieCard .stat.paid .val { color: #15803d; }
    #paieCard .stat.left { background: #fdecec; border-color: #f0b4b4; } #paieCard .stat.left .val { color: #b91c1c; }
    #paieCard .stat.left.solde { background: #e9f7ef; border-color: #9ed9b5; } #paieCard .stat.left.solde .val { color: #15803d; }
    #paieCard .gu-progress { height: 12px; background: #e7ecf5; border-radius: 8px; overflow: hidden; }
    #paieCard .gu-progress > div { height: 100%; background: linear-gradient(90deg, #1f4f9c, #4f86d6); transition: width .4s ease; }
    #paieCard .stu-head { display: flex; align-items: center; gap: 14px; }
    #paieCard .stu-head .ava { width: 52px; height: 52px; border-radius: 12px; background: #1f4f9c; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 26px; }
    #paieCard table.histo { width: 100%; border-collapse: collapse; font-size: 13px; }
    #paieCard table.histo th, #paieCard table.histo td { border: 1px solid #9aa3b2; padding: 7px 10px; }
    #paieCard table.histo thead th { background: #e7ecf5; color: #14346b; text-align: left; }
    #paieCard table.histo tbody tr:nth-child(even) { background: #f6f8fc; }
    #paieCard .montant { font-weight: 700; color: #0f2a52; text-align: right; }
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
                                    <li class="breadcrumb-item active">Paiement</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($error)): ?>
                    <div class="card"><div class="card-body"><div class="gu-empty"><i class="bx bx-error-circle"></i><?= htmlspecialchars($error) ?></div></div></div>
                <?php else:
                    $totalFrais = (int) ($etudiant['total_frais'] ?? 0);
                    $paid = (int) ($totalPaid ?? 0);
                    $reste = $totalFrais - $paid;
                    $pct = $totalFrais > 0 ? min(100, round($paid * 100 / $totalFrais)) : 0;
                    $solde = $reste <= 0 && $totalFrais > 0;
                    $fmt = function ($v) { return number_format((int) $v, 0, ',', ' '); };
                ?>
                <div class="card" id="paieCard">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="gu-section-title lg"><span class="gu-ico-chip"><i class="bx bx-money"></i></span> Paiement des frais</div>

                            <!-- En-tête étudiant -->
                            <div class="stu-head mb-3">
                                <div class="ava"><i class="bx bx-user"></i></div>
                                <div>
                                    <div style="font-size:18px;font-weight:700;color:#14346b;">
                                        <?= mb_strtoupper(htmlspecialchars($etudiant['nom_prenom_etudiant'] ?? ''), 'UTF-8') ?>
                                        <?= !empty($etudiant['prenom']) ? ' ' . mb_convert_case(htmlspecialchars($etudiant['prenom']), MB_CASE_TITLE, 'UTF-8') : '' ?>
                                    </div>
                                    <div style="font-size:13px;color:#5a6b86;">Matricule : <?= htmlspecialchars($etudiant['matricule_etudiant'] ?? '—') ?></div>
                                </div>
                            </div>

                            <!-- Cartes synthèse -->
                            <div class="row mb-2" style="row-gap:12px;">
                                <div class="col-12 col-md-4"><div class="stat due"><div class="lab">Total dû</div><div class="val"><?= $fmt($totalFrais) ?> <small style="font-size:12px;color:#5a6b86;">FCFA</small></div></div></div>
                                <div class="col-12 col-md-4"><div class="stat paid"><div class="lab">Total payé</div><div class="val"><?= $fmt($paid) ?> <small style="font-size:12px;">FCFA</small></div></div></div>
                                <div class="col-12 col-md-4"><div class="stat left <?= $solde ? 'solde' : '' ?>"><div class="lab"><?= $solde ? 'Statut' : 'Reste à payer' ?></div><div class="val"><?= $solde ? '✓ Soldé' : $fmt(max(0, $reste)) . ' <small style="font-size:12px;">FCFA</small>' ?></div></div></div>
                            </div>

                            <!-- Progression -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between" style="font-size:12px;color:#5a6b86;margin-bottom:4px;">
                                    <span>Progression du paiement</span><span><?= $pct ?>%</span>
                                </div>
                                <div class="gu-progress"><div style="width:<?= $pct ?>%;"></div></div>
                            </div>

                            <!-- Formulaire de paiement -->
                            <?php if (!$solde): ?>
                            <form action="" method="POST" class="row align-items-end mb-4" style="row-gap:12px;" onsubmit="return validerPaiement();">
                                <div class="col-12 col-md-4">
                                    <label class="form-label" style="font-size:.85rem;color:#475569;">Montant à payer (FCFA)</label>
                                    <input type="number" class="form-control" id="montant_paye" name="montant_paye" min="1" <?= $reste > 0 ? 'max="' . $reste . '"' : '' ?> placeholder="Ex : <?= $fmt(max(0, $reste)) ?>" required oninput="majApercu()">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label" style="font-size:.85rem;color:#475569;">Restant après paiement</label>
                                    <input type="text" class="form-control" id="apres" value="<?= $fmt(max(0, $reste)) ?> FCFA" readonly style="background:#eef3fb;font-weight:600;">
                                </div>
                                <div class="col-12 col-md-4 d-flex" style="gap:8px;">
                                    <button type="submit" name="paie" value="1" class="btn btn-primary"><i class="bx bx-check"></i> Enregistrer le paiement</button>
                                    <a href="<?= ROOT ?>/Etudiants" class="btn btn-ghost">Retour</a>
                                </div>
                            </form>
                            <?php else: ?>
                            <div class="mb-4 p-3" style="background:#e9f7ef;border:1px solid #9ed9b5;border-radius:10px;color:#15803d;font-weight:600;">
                                <i class="bx bx-check-circle"></i> La scolarité de cet étudiant est entièrement réglée.
                                <a href="<?= ROOT ?>/Etudiants" class="btn btn-ghost btn-sm" style="float:right;">Retour à la liste</a>
                            </div>
                            <?php endif ?>

                            <!-- Historique -->
                            <div class="gu-section-title"><i class="bx bx-history"></i> Historique des paiements</div>
                            <?php if (empty($payments)): ?>
                                <div class="gu-empty"><i class="bx bx-receipt"></i>Aucun paiement enregistré pour le moment.</div>
                            <?php else: ?>
                                <table class="histo">
                                    <thead><tr><th style="width:50px;">N°</th><th>Date</th><th style="text-align:right;">Montant</th></tr></thead>
                                    <tbody>
                                        <?php $i = count($payments); foreach ($payments as $payment): ?>
                                            <tr>
                                                <td><?= $i-- ?></td>
                                                <td><?= date('d/m/Y à H:i', strtotime($payment['date'])) ?></td>
                                                <td class="montant"><?= $fmt($payment['montant_paye']) ?> FCFA</td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                    <tfoot>
                                        <tr><td colspan="2" style="text-align:right;font-weight:700;">Total payé</td><td class="montant"><?= $fmt($paid) ?> FCFA</td></tr>
                                    </tfoot>
                                </table>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php $this->view("Partials/foot") ?>
    <?php $this->view("Partials/footer") ?>
</body>

</html>
<?php if (!isset($error)): ?>
<script>
    var RESTE = <?= (int) max(0, ($etudiant['total_frais'] ?? 0) - ($totalPaid ?? 0)) ?>;
    function fmt(n) { return (n || 0).toLocaleString('fr-FR'); }
    function majApercu() {
        var v = parseInt(document.getElementById('montant_paye').value, 10) || 0;
        var apres = RESTE - v;
        document.getElementById('apres').value = fmt(apres < 0 ? 0 : apres) + ' FCFA';
    }
    function validerPaiement() {
        var v = parseInt(document.getElementById('montant_paye').value, 10) || 0;
        if (v <= 0) { Swal.fire("Montant invalide", "Saisissez un montant supérieur à 0.", "warning"); return false; }
        if (RESTE > 0 && v > RESTE) {
            return !!confirm("Le montant dépasse le reste à payer (" + fmt(RESTE) + " FCFA). Continuer ?");
        }
        return true;
    }
</script>
<?php endif ?>
