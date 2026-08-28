<?php $act = $active ?? ''; ?>
<style>
    #confLayout { display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap; }
    #confLayout .conf-side { width: 230px; flex: 0 0 230px; }
    #confLayout .conf-main { flex: 1; min-width: 320px; }
    @media (max-width: 900px) { #confLayout .conf-side { width: 100%; flex-basis: 100%; } }
    .gu-confnav { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 8px; }
    .gu-cnav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 9px; color: #475569; text-decoration: none; font-weight: 600; font-size: 13.5px; margin-bottom: 2px; transition: all .12s ease; }
    .gu-cnav-item i { font-size: 18px; }
    .gu-cnav-item:hover { background: #eef2f9; color: #14346b; }
    .gu-cnav-item.active { background: #1f4f9c; color: #fff; }

    .conf-card { background: #fff; border: 1px solid #e7ecf5; border-radius: 12px; padding: 18px; }

    /* Modale config (custom, indépendante de Bootstrap) */
    .gu-conf-modal { position: fixed; inset: 0; z-index: 1080; display: none; align-items: center; justify-content: center; }
    .gu-conf-modal.open { display: flex; }
    .gu-conf-modal .gm-back { position: absolute; inset: 0; background: rgba(15, 23, 42, .55); }
    .gu-conf-modal .gm-card { position: relative; background: #fff; border-radius: 12px; width: min(520px, 94vw); max-height: 90vh; box-shadow: 0 20px 60px rgba(0, 0, 0, .3); overflow: hidden; display: flex; flex-direction: column; }
    .gu-conf-modal .gm-head { padding: 14px 18px; background: #14346b; color: #fff; display: flex; align-items: center; justify-content: space-between; flex: 0 0 auto; }
    .gu-conf-modal .gm-head h5 { margin: 0; font-weight: 700; font-size: 16px; }
    .gu-conf-modal .gm-body { padding: 18px; overflow-y: auto; flex: 1 1 auto; min-height: 0; }
    .gu-conf-modal .gm-foot { padding: 12px 18px; border-top: 1px solid #eef2f9; display: flex; justify-content: flex-end; gap: 8px; flex: 0 0 auto; }
    .gu-conf-modal .form-label { font-size: .85rem; color: #475569; font-weight: 500; }
    #confLayout .conf-act { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e3e8f2; color: #1f4f9c; background: #fff; cursor: pointer; font-size: 16px; text-decoration: none; margin: 0 1px; }
    #confLayout .conf-act:hover { background: #eaf1fb; border-color: #1f4f9c; }
    #confLayout .conf-act.danger { color: #b91c1c; } #confLayout .conf-act.danger:hover { background: #fdecec; border-color: #f0b4b4; }
</style>

<div class="conf-side">
    <div class="gu-confnav">
        <a class="gu-cnav-item <?= $act === 'modules' ? 'active' : '' ?>" href="<?= ROOT ?>/Modules/listeModule"><i class="bx bx-book-content"></i> Modules</a>
        <a class="gu-cnav-item <?= $act === 'semestres' ? 'active' : '' ?>" href="<?= ROOT ?>/Semestres/Liste"><i class="bx bx-calendar-event"></i> Semestres</a>
        <a class="gu-cnav-item <?= $act === 'periodes' ? 'active' : '' ?>" href="<?= ROOT ?>/Periodes/Liste"><i class="bx bx-calendar"></i> Périodes</a>
        <a class="gu-cnav-item <?= $act === 'salles' ? 'active' : '' ?>" href="<?= ROOT ?>/Salles/Liste"><i class="bx bx-door-open"></i> Salles</a>
        <a class="gu-cnav-item <?= $act === 'departements' ? 'active' : '' ?>" href="<?= ROOT ?>/Departements/listeDepartements"><i class="bx bx-building"></i> Départements</a>
        <a class="gu-cnav-item <?= $act === 'utilisateurs' ? 'active' : '' ?>" href="<?= ROOT ?>/Utilisateurs/liste_utilisateur"><i class="bx bx-user"></i> Utilisateurs</a>
    </div>
</div>

<script>
    function guConfOpen(id) { document.getElementById(id).classList.add('open'); }
    function guConfClose(id) { document.getElementById(id).classList.remove('open'); }
    document.addEventListener('click', function (e) {
        var c = e.target.closest('[data-conf-close]');
        if (c) guConfClose(c.getAttribute('data-conf-close'));
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') document.querySelectorAll('.gu-conf-modal.open').forEach(function (m) { m.classList.remove('open'); });
    });
    // Suppression avec confirmation
    document.addEventListener('click', function (e) {
        var b = e.target.closest('.btn-conf-del');
        if (!b) return;
        e.preventDefault();
        var url = b.getAttribute('href'), nom = b.getAttribute('data-nom') || 'cet élément';
        Swal.fire({ title: 'Supprimer ?', html: '<b>' + nom + '</b> sera supprimé définitivement.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Supprimer', cancelButtonText: 'Annuler', confirmButtonColor: '#b91c1c' })
            .then(function (r) { if (r && (r.isConfirmed || (r.value && !r.dismiss))) window.location.href = url; });
    });
</script>
