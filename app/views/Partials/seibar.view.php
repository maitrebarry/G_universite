<?php
$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$role = $_SESSION['role'] ?? '';

// ✅ Définition des permissions par rôle
$menu_permissions = [
    'SupAdmin' => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants', 'configuration'],
    'DG'       => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants'],
    'DGA'      => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants'],
    'Chef DR'  => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants', 'configuration'], // ✅ ajouté
    'Enseignant' => ['dashboard', 'notes_simple', 'etudiant_simple'], 
    'Sécretaire principale' => ['dashboard', 'etudiants_sp'],
    'Scolarite' => ['dashboard','etudiants', 'notes']
];


// ✅ Menus disponibles
$menus = [
    'dashboard' => [
        'title' => 'Tableau Bord',
        'icon'  => 'bx bx-home-alt',
        'url'   => ROOT . '/Homes'
    ],
    'filieres' => [
        'title' => 'Filières',
        'icon'  => 'bx bx-bookmark-alt',
        'url'   => ROOT . '/Filieres'
    ],
    'enseignants' => [
        'title' => 'Enseignants',
        'icon'  => 'bx bx-user',
        'url'   => ROOT . '/Enseignants'
    ],
    'notes' => [
        'title' => 'Notes',
        'icon'  => 'bx bx-book',
        'submenu' => [
            ['url' => ROOT . '/Notes', 'title' => 'Saisie des Notes'],
            ['url' => ROOT . '/Notes/liste_note', 'title' => 'Résultat Annuel']
        ]
    ],
    'emploi' => [
        'title' => 'Emploi du temps',
        'icon'  => 'bx bx-calendar',
        'submenu' => [
            ['url' => ROOT . '/Emploi_du_temps', 'title' => 'EDT'],
            ['url' => ROOT . '/Enseignants/listeEDT_individuels_par_periode', 'title' => 'EDT Individuel']
        ]
    ],
    'etudiants' => [
        'title' => 'Étudiants',
        'icon'  => 'bx bx-group',
        'submenu' => [
            ['url' => ROOT . '/Etudiants', 'title' => 'Liste Étudiants'],
            ['url' => ROOT . '/EtudiantPargroupes', 'title' => 'Importer une Liste'],
            ['url' => ROOT . '/Reinsciptions', 'title' => 'Réinscription']
        ]
    ],
    'configuration' => [
        'title' => 'Configuration',
        'icon'  => 'bx bx-cog',
        'url'   => ROOT . '/Modules/listeModule'
    ],

    // ✅ Menus simplifiés pour Enseignant
    'notes_simple' => [
        'title' => 'Saisie des Notes',
        'icon'  => 'bx bx-book',
        'url'   => ROOT . '/Notes'
    ],
    'etudiant_simple' => [
        'title' => 'Liste Étudiants',
        'icon'  => 'bx bx-user-pin',
        'url'   => ROOT . '/Etudiants'
    ],

    // ✅ Menu spécial SP
    'etudiants_sp' => [
        'title' => 'Étudiants',
        'icon'  => 'bx bx-group',
        'submenu' => [
            ['url' => ROOT . '/Etudiants', 'title' => 'Liste Étudiants'],
            ['url' => ROOT . '/EtudiantPargroupes', 'title' => 'Importer une Liste'],
            ['url' => ROOT . '/Reinsciptions', 'title' => 'Réinscription']
        ]
    ]
];
?>

<style>
.nav-item.active > .nav-link {
    color: #007bff;
    font-weight: bold;
}
.menu-content .active > a {
    color: #007bff;
    font-weight: bold;
}
.menu-content {
    display: none;
}
.nav-item.open .menu-content {
    display: block;
}
</style>

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="<?= ROOT ?>/Homes">
                    <div class="brand-logo">
                        <img class="logo" src="<?= ROOT ?>/assets/images/OIP.jpeg" />
                    </div>
                    <h2 class="brand-text mb-0">IUFP</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="bx bx-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
                    <i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="bx-disc"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <?php if ($role && isset($menu_permissions[$role])): ?>
            <?php foreach ($menu_permissions[$role] as $menu_key): ?>
                <?php if (isset($menus[$menu_key])): ?>
                    <?php $menu = $menus[$menu_key]; ?>

                    <?php
                        $isActive = false;
                        $isOpen = false;
                        if (isset($menu['submenu'])) {
                            foreach ($menu['submenu'] as $sub) {
                                if ($current_page == basename($sub['url'])) {
                                    $isActive = false; 
                                    $isOpen = true;
                                    break;
                                }
                            }
                        } else {
                            $isActive = ($current_page == basename($menu['url']));
                        }
                    ?>

                    <li class="nav-item <?= $isOpen ? 'open' : '' ?> <?= $isActive ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= isset($menu['url']) ? $menu['url'] : '#' ?>">
                            <i class="<?= $menu['icon'] ?>"></i>
                            <span class="menu-title"><?= $menu['title'] ?></span>
                        </a>

                        <?php if (isset($menu['submenu'])): ?>
                        <ul class="menu-content">
                            <?php foreach ($menu['submenu'] as $sub): ?>
                                <li class="<?= ($current_page == basename($sub['url'])) ? 'active' : '' ?>">
                                    <a href="<?= $sub['url'] ?>"><i class="bx bx-right-arrow-alt"></i><?= $sub['title'] ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        </ul>
    </div>
</div>
