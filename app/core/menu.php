<?php
/**
 * Source UNIQUE de la navigation (rôle -> menus).
 * Utilisée par la barre latérale (app/views/Partials/seibar.view.php).
 * Le garde d'authentification (app/core/Controller.php) peut consommer
 * ce même fichier plus tard pour aligner accès et navigation.
 *
 * Rôles connus : SupAdmin, DG, DGA, Chef DR, Enseignant, Sécretaire principale, Scolarite
 */

$menu_permissions = [
    'SupAdmin'              => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants', 'configuration'],
    'DG'                    => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants'],
    'DGA'                   => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants'],
    'Chef DR'               => ['dashboard', 'filieres', 'enseignants', 'notes', 'emploi', 'etudiants', 'configuration'],
    'Enseignant'            => ['dashboard', 'notes_simple', 'etudiant_simple'],
    'Sécretaire principale' => ['dashboard', 'etudiants_sp'],
    'Scolarite'             => ['dashboard', 'etudiants', 'notes'],
];

$menus = [
    'dashboard' => [
        'title' => 'Tableau de bord',
        'icon'  => 'bx bx-home-alt',
        'url'   => ROOT . '/Homes',
    ],
    'filieres' => [
        'title' => 'Filières',
        'icon'  => 'bx bx-bookmark-alt',
        'url'   => ROOT . '/Filieres',
    ],
    'enseignants' => [
        'title' => 'Enseignants',
        'icon'  => 'bx bx-user',
        'url'   => ROOT . '/Enseignants',
    ],
    'notes' => [
        'title'   => 'Notes',
        'icon'    => 'bx bx-book',
        'submenu' => [
            ['url' => ROOT . '/Notes', 'title' => 'Saisie des notes'],
            ['url' => ROOT . '/Notes/liste_note', 'title' => 'Résultat annuel'],
            ['url' => ROOT . '/Notes/releves', 'title' => 'Relevés de notes'],
        ],
    ],
    'emploi' => [
        'title'   => 'Emploi du temps',
        'icon'    => 'bx bx-calendar',
        'submenu' => [
            ['url' => ROOT . '/Emploi_du_temps', 'title' => 'EDT'],
            ['url' => ROOT . '/Enseignants/listeEDT_individuels_par_periode', 'title' => 'EDT individuel'],
        ],
    ],
    'etudiants' => [
        'title'   => 'Étudiants',
        'icon'    => 'bx bx-group',
        'submenu' => [
            ['url' => ROOT . '/Etudiants', 'title' => 'Liste étudiants'],
            ['url' => ROOT . '/EtudiantPargroupes', 'title' => 'Importer une liste'],
            ['url' => ROOT . '/Reinsciptions', 'title' => 'Réinscription'],
        ],
    ],
    'configuration' => [
        'title' => 'Configuration',
        'icon'  => 'bx bx-cog',
        'url'   => ROOT . '/Modules/listeModule',
    ],

    // Menus simplifiés pour Enseignant
    'notes_simple' => [
        'title' => 'Saisie des notes',
        'icon'  => 'bx bx-book',
        'url'   => ROOT . '/Notes',
    ],
    'etudiant_simple' => [
        'title' => 'Liste étudiants',
        'icon'  => 'bx bx-user-pin',
        'url'   => ROOT . '/Etudiants',
    ],

    // Menu spécial Secrétaire principale
    'etudiants_sp' => [
        'title'   => 'Étudiants',
        'icon'    => 'bx bx-group',
        'submenu' => [
            ['url' => ROOT . '/Etudiants', 'title' => 'Liste étudiants'],
            ['url' => ROOT . '/EtudiantPargroupes', 'title' => 'Importer une liste'],
            ['url' => ROOT . '/Reinsciptions', 'title' => 'Réinscription'],
        ],
    ],
];
