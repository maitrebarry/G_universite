<?php
if (!isset($_SESSION['id_utilisateur'])) {
    // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    $this->redirect("Logins");
}
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
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
    <title>Institut universaire de formation de Ségou</title>
    <link rel="apple-touch-icon" href="<?= ROOT ?>/assets/images/OIP.jpeg">
    <link rel="shortcut icon" type="image/x-icon" href="<?= ROOT ?>/assets/images/OIP.jpeg">
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
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/plugins/forms/wizard.css"> -->
    <!-- END: Page CSS-->
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/bsStepper.min1.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?= ROOT ?>/assets/css/custom-bsStepper.css"> -->
    <!-- END: Custom CSS-->


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
</style>