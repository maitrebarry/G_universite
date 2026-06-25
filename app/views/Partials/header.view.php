<?php
if (!isset($_SESSION['id_utilisateur'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    $this->redirect("Logins");
}
?>
<!DOCTYPE html>
<html class="loading" lang="fr" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description"
        content="Frest admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Frest admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Kunafoni IUFP — Université de Ségou</title>
    <link rel="icon" type="image/png" sizes="48x48" href="<?= ROOT ?>/assets/images/pwa/favicon-48.png?v=5">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= ROOT ?>/assets/images/pwa/favicon-32.png?v=5">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= ROOT ?>/assets/images/pwa/favicon-16.png?v=5">
    <link rel="shortcut icon" href="<?= ROOT ?>/assets/images/pwa/favicon-32.png?v=5">
    <link rel="icon" href="<?= ROOT ?>/favicon.ico?v=5" sizes="any">

    <!-- BEGIN: PWA -->
    <link rel="manifest" href="<?= ROOT ?>/manifest.webmanifest">
    <meta name="theme-color" content="#14346b">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Kunafoni IUFP">
    <link rel="apple-touch-icon" href="<?= ROOT ?>/assets/images/pwa/apple-touch-icon.png?v=5">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= ROOT ?>/assets/images/pwa/icon-192.png?v=5">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= ROOT ?>/assets/images/pwa/icon-512.png?v=5">
    <!-- END: PWA -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/tables/datatable/datatables.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/print.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/core/menu/menu-types/vertical-menu.css">

    <!-- <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/plugins/forms/wizard.css"> -->
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Page CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/g-universite-chatbot.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/bsStepper.min1.css">
    <!-- END: Custom CSS-->

    <!-- BEGIN: Design system G_universite (override — doit rester en dernier) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/theme.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/gu-components.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/gu-shell.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/gu-mobile.css">
    <!-- END: Design system -->

    <script>
        window.APP_ROOT = <?= json_encode(ROOT) ?>;
        window.ROOT = window.APP_ROOT;
        window.APP_ROUTE = function (path) {
            return window.APP_ROOT + "/" + String(path || "").replace(/^\/+/, "");
        };
        window.ROOT_NOTES = window.APP_ROUTE("Notes");
        window.ROOT_EDT = window.APP_ROUTE("Emploi_du_temps");
    </script>

</head>
<style>
    .card-animated-border-top1 {

        border-left-width: 3px; /* Utilisez border-left pour simuler une bordure de départ */
    border-left-style: solid;
    border-image-slice: 1;
    border-image-source: linear-gradient(to bottom, #ff416c, #ff4b2b);
    animation: border-shift 5s linear infinite;
    
}
.card-animated-border-top {
    border-top: 2px solid;
    border-image-slice: 1;
    border-image-source: linear-gradient(to right, #ff416c, #ff4b2b);
    animation: border-shift 3s linear infinite;
}

    

    .card-animated-border-top {
        border-top: 2px solid;
        border-image-slice: 1;
        border-image-source: linear-gradient(to right, #ff416c, #ff4b2b);
        animation: border-shift 3s linear infinite;
    }

    @keyframes border-shift {
        0% {
            border-image-source: linear-gradient(to right, #ff416c, #ff4b2b);
        }

        50% {
            border-image-source: linear-gradient(to right, #4facfe, #00f2fe);
        }

        100% {
            border-image-source: linear-gradient(to right, #ff416c, #ff4b2b);
        }

        0% {
            border-image-source: linear-gradient(to bottom, #ff416c, #ff4b2b);
            /* Rouge-Orange */
        }

        25% {
            border-image-source: linear-gradient(to bottom, #4facfe, #00f2fe);
            /* Bleu-Cyan */
        }

        50% {
            border-image-source: linear-gradient(to bottom, #f9d423, #ff4e50);
            /* Jaune-Rouge */
        }

        75% {
            border-image-source: linear-gradient(to bottom, #30cfd0, #330867);
            /* Vert-Voilet */
        }

        100% {
            border-image-source: linear-gradient(to bottom, #ff416c, #ff4b2b);
            /* Retour au Rouge-Orange */
        }
    }

    .card-animated-border-top2 {
        border-top: 2px solid;
        border-image-slice: 1;
        border-image-source: linear-gradient(to right, #ff416c, #ff4b2b);
        animation: border-shift 3s linear infinite;
    }

    /* Fixed footer centered at bottom */
    .footer.footer-fixed {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        text-align: center;
        z-index: 1050;
        background: #ffffff;
        padding: 8px 12px;
        box-shadow: 0 -1px 6px rgba(0,0,0,0.08);
    }

    .footer.footer-fixed .footer-content {
        margin: 0;
        display: inline-block;
        text-align: center;
    }

    /* Ensure float utilities don't break centering */
    .footer.footer-fixed .float-left,
    .footer.footer-fixed .float-right {
        float: none !important;
        display: inline-block;
    }

    /* Position the scroll-top button to the right inside the fixed footer */
    .footer.footer-fixed .scroll-top {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
    }
</style>
