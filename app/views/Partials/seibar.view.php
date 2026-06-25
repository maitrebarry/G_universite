<?php
$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$role = $_SESSION['role'] ?? '';

// Source UNIQUE de la navigation : définit $menu_permissions + $menus
require __DIR__ . '/../../core/menu.php';
?>

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="<?= ROOT ?>/Homes">
                    <div class="brand-logo">
                        <img class="logo" src="<?= ROOT ?>/assets/images/pwa/icon-192.png?v=5" />
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
