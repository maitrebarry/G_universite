<style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden; /* Évite le défilement */
        }

        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ajuste la vidéo pour qu'elle couvre tout l'écran */
            z-index: -1; /* Place la vidéo derrière le contenu */
        }

        .content {
            position: relative;
            z-index: 1; /* Place le contenu au-dessus de la vidéo */
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
    </style>

<?php $this->view("Partials/header") ?>
<body class="vertical-layout vertical-menu-modern semi-dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="semi-dark-layout">
    <!-- BEGIN: Content-->
    <video autoplay muted loop>
        <source src="<?=ROOT?>/assets/images/pages/404.mp4" type="video/mp4">
        Votre navigateur ne supporte pas les vidéos HTML5.
    </video>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- error 404 -->
                <section class="row flexbox-container">
                    <div class="col-xl-6 col-md-7 col-9">
                        <div class="card bg-transparent shadow-none">
                            <div class="card-content">
                                <div class="card-body text-center bg-transparent miscellaneous">
                                    <h1 class="error-title">Page Not Found :(</h1>
                                    <p class="pb-3">
                                        we couldn't find the page you are looking for</p>
                                   
                                    <a href="<?= ROOT ?>/index" class="btn btn-primary round glow mt-3">
                                        BACK TO HOME
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- error 404 end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="<?=ROOT?>/assets/vendors/js/vendors.min.js"></script>
    <script src="<?=ROOT?>/assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="<?=ROOT?>/assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="<?=ROOT?>/assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?=ROOT?>/assets/js/scripts/configs/vertical-menu-dark.js"></script>
    <script src="<?=ROOT?>/assets/js/core/app-menu.js"></script>
    <script src="<?=ROOT?>/assets/js/core/app.js"></script>
    <script src="<?=ROOT?>/assets/js/scripts/components.js"></script>
    <script src="<?=ROOT?>/assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>