<div class="header-navbar-shadow"></div>
    <nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
        <div class="navbar-wrapper">
            <div class="navbar-container content">                 
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
                        </ul>                       
                    </div>             
                    <ul class="nav navbar-nav float-right">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon bx bx-fullscreen"></i></a></li>
                        <!-- Icône de recherche supprimée -->
                       <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                <i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i>
                                <span class="badge badge-pill badge-danger badge-up">
                                    <?= count($notifications) ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header px-1 py-75 d-flex justify-content-between">
                                        <span class="notification-title text-center"><?= count($notifications) ?> Notifications</span>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list">
                                    <?php if (!empty($notifications)): ?>
                                        <?php foreach ($notifications as $notif): ?>
                                            <a class="d-flex justify-content-between" href="#">
                                                <div class="media d-flex align-items-center">
                                                    <div class="media-left pr-0">
                                                        <div class="avatar bg-primary bg-lighten-5 mr-1 m-0 p-25">
                                                            <span class="avatar-content text-primary font-medium-2">
                                                                <i class="bx bx-book"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading">
                                                            <?= htmlspecialchars($notif->nom_module) ?>
                                                        </h6>
                                                        <small class="notification-text">
                                                            Salle : <?= htmlspecialchars($notif->nom_salle) ?> |
                                                        Début : <?= date('d/m/Y', strtotime($notif->date_debut)) ?>
                                                            <?php if (date('H:i', strtotime($notif->date_debut)) != '00:00'): ?>
                                                                à <?= date('H:i', strtotime($notif->date_debut)) ?>
                                                            <?php endif; ?>

                                                        </small>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center p-1">Aucune notification</div>
                                    <?php endif; ?>
                                </li>
                                <li class="dropdown-menu-footer">
                                    <a class="dropdown-item p-50 text-primary justify-content-center" href="#">
                                        Voir tout
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none"><span class="user-name"><?= $_SESSION['nom_prenom']; ?></span><span class="user-status text-muted"><?= $_SESSION['role']; ?>
                                </span></div><span><?php $gp = trim($_SESSION['nom_prenom'] ?? ''); $ini = ''; foreach (preg_split('/\s+/', $gp) as $w) { if ($w !== '') $ini .= mb_strtoupper(mb_substr($w, 0, 1)); } echo '<span class="gu-avatar-nav">' . htmlspecialchars(mb_substr($ini, 0, 2) ?: 'U') . '</span>'; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right pb-0">
                                <a class="dropdown-item" href="<?= ROOT ?>/Utilisateurs/profil"><i class="bx bx-user mr-50"></i> Profil</a>
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item" href="<?= ROOT ?>/Deconnections/deconnexion"><i class="bx bx-power-off mr-50"></i> Déconnexion</a>
                            </div>
  
                    </ul>
                </div>
            </div>
        </div>
    </nav>
