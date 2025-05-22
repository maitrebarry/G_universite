<?php
// Récupère le nom de la page actuelle
$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>
<style>
    .nav-item.active>.nav-link {
        background-color: #007bff;
        /* Couleur de fond */
        color: #fff;
        /* Couleur du texte */
        font-weight: bold;
    }

    .menu-content .active>a {
        color: #007bff;
        /* Couleur pour le sous-menu actif */
        font-weight: bold;
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
                    <i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary"
                        data-ticon="bx-disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <!-- Tableau de bord -->
            <li class="nav-item <?= ($current_page == 'index') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= ROOT ?>/Homes">
                    <i class="bx bx-home-alt"></i>
                    <span class="menu-title">Tableau Bord</span>
                </a>
            </li>

            <!-- Filières -->
            <li class="nav-item <?= ($current_page == 'Filieres') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= ROOT ?>/Filieres">
                    <i class="bx bx-bookmark-alt"></i> <!-- Changer en fonction du thème de l'icône -->
                    <span class="menu-title">Filières</span>
                </a>
            </li>

            <!-- Enseignants -->
            <li class="nav-item <?= ($current_page == 'Enseignants') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= ROOT ?>/Enseignants">

                    <i class="bx bx-user"></i>
                    <span class="menu-title">Enseignants</span>
                </a>
            </li>

            <!-- Notes -->
            <li class="nav-item <?= (strpos($_SERVER['REQUEST_URI'], 'Notes') !== false) ?  : '' ?>">
                <a class="nav-link" href="<?= ROOT ?>/Notes">
                    <i class="bx bx-book"></i> <!-- Icône représentant des notes -->
                    <span class="menu-title">Notes</span>
                </a>
                <ul class="menu-content">
                    <li class="<?= ($current_page == 'Notes') ? 'active' : '' ?>">
                        <a href="<?= ROOT ?>/Notes">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item"> Saisie des Notes</span>
                        </a>
                    </li>
                    <li class="<?= ($current_page == 'liste_note') ? 'active' : '' ?>">
                        <a href="<?= ROOT ?>/Notes/liste_note">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item">Résultat Notes</span>
                        </a>
                    </li>
                
                </ul>
            </li>

            <!-- Emploi du temps -->
            <li class="nav-item <?= ($current_page == 'Emploi_du_temps') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= ROOT ?>/Emploi_du_temps">
                    <i class="bx bx-calendar"></i> <!-- Calendrier pour l'emploi du temps -->
                    <span class="menu-title">EDT</span>
                </a>
            </li>

            <!-- Étudiants (Sous-menus) -->
            <li class="nav-item <?= (strpos($_SERVER['REQUEST_URI'], 'Etudiants') !== false) ?: '' ?>">
                <a class="nav-link" href="#">
                    <i class="bx bx-group"></i> <!-- Icône représentant les étudiants -->
                    <span class="menu-title">Étudiants</span>
                </a>
                <ul class="menu-content">
                    <li class="<?= ($current_page == 'Etudiants') ? 'active' : '' ?>">
                        <a href="<?= ROOT ?>/Etudiants">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item">Liste Etudiants</span>
                        </a>
                    </li>
                    <li class="<?= ($current_page == 'EtudiantPargroupes') ? 'active' : '' ?>">
                        <a href="<?= ROOT ?>/EtudiantPargroupes">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item">Impoter une Liste</span>
                        </a>
                    </li>
                    <li class="<?= ($current_page == 'Reinsciptions') ? 'active' : '' ?>">
                        <a href="<?= ROOT ?>/Reinsciptions">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item">Réinscription</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Configuration -->
            <li class="nav-item <?= ($current_page == 'Modules/listeModule') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= ROOT ?>/Modules/listeModule">
                    <i class="bx bx-cog"></i>
                    <span class="menu-title">Configuration</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentPage = window.location.pathname.split("/").pop(); // Récupère le nom de la page
        const menuItems = document.querySelectorAll(".main-menu .nav-item");

        menuItems.forEach(item => {
            const link = item.querySelector("a");
            if (link) {
                const linkPage = link.getAttribute("href").split("/").pop();
                if (linkPage === currentPage) {
                    item.classList.add("active");
                }
            }
        });
    });
</script>