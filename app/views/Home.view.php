<style>
    .card-animated-border-start {
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
    }
</style>
<?php $this->view("Partials/header") ?>

<body class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- inclusion du partie header -->
    <?php $this->view("Partials/navbar") ?>
    <!-- inclusion du partie header fin-->

    <!-- inclusion du partie seibar-->
    <?php $this->view("Partials/seibar") ?>
    <!-- inclusion du partie seibar fin-->

    <!--  Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row ">
                        <?php $this->view("set_flash") ?>
                        <!-- Greetings Content Starts -->
                        <div class="row col-12  mt-2">
                            <div class="col-4">
                                <div class="card card-animated-border-top1 ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-rgba-primary m-0 p-25 mr-75 mr-xl-2">
                                                <div class="avatar-content">
                                                    <i class="bx bx-user text-primary font-medium-2"></i>
                                                </div>
                                            </div>
                                            <div class="">

                                                <p class="text-muted">Nbre departements</p>
                                                <h4 class="mb-0 text-tiffany">248</h4>

                                            </div>
                                            <div class="ms-auto  widget-icon bg-tiffany text-white" style=" width: 60px; height: 60px;">

                                                <i class="fa-solid  fa-user " style="font-size: 30px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="mb-1">Nbre filière par departements</p>
                                                <h4 class="mb-0 text-success">$1,245</h4>
                                            </div>
                                            <div class="ms-auto widget-icon bg-success text-white">
                                                <i class="bi bi-currency-dollar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="mb-1">Bounce Rate</p>
                                                <h4 class="mb-0 text-pink">24.25%</h4>
                                            </div>
                                            <div class="ms-auto widget-icon bg-pink text-white">
                                                <i class="bi bi-bar-chart-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div><!--end row-->
                        <div class="row col-12  mt-2">
                            <div class="col-4">
                                <div class="card card-animated-border-top1 ">
                                    <div class="card-body">

                                        <div class="d-flex align-items-center">
                                            <div class="">

                                                <p class="mb-1">Elève inscrit</p>
                                                <h4 class="mb-0 text-tiffany">248</h4>

                                            </div>
                                            <div class="ms-auto  widget-icon bg-tiffany text-white" style=" width: 60px; height: 60px;">

                                                <i class="fa-solid  fa-user " style="font-size: 30px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="mb-1">Total Revenue</p>
                                                <h4 class="mb-0 text-success">$1,245</h4>
                                            </div>
                                            <div class="ms-auto widget-icon bg-success text-white">
                                                <i class="bi bi-currency-dollar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="mb-1">Bounce Rate</p>
                                                <h4 class="mb-0 text-pink">24.25%</h4>
                                            </div>
                                            <div class="ms-auto widget-icon bg-pink text-white">
                                                <i class="bi bi-bar-chart-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div><!--end row-->
                        <div class="row col-12  mt-2">
                            <div class="col-4">
                                <div class="card card-animated-border-top1 ">
                                    <div class="card-body">

                                        <div class="d-flex align-items-center">
                                            <div class="">

                                                <p class="mb-1">Elève inscrit</p>
                                                <h4 class="mb-0 text-tiffany">248</h4>

                                            </div>
                                            <div class="ms-auto  widget-icon bg-tiffany text-white" style=" width: 60px; height: 60px;">

                                                <i class="fa-solid  fa-user " style="font-size: 30px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="mb-1">Total Revenue</p>
                                                <h4 class="mb-0 text-success">$1,245</h4>
                                            </div>
                                            <div class="ms-auto widget-icon bg-success text-white">
                                                <i class="bi bi-currency-dollar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-animated-border-top1">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <p class="mb-1">Bounce Rate</p>
                                                <h4 class="mb-0 text-pink">24.25%</h4>
                                            </div>
                                            <div class="ms-auto widget-icon bg-pink text-white">
                                                <i class="bi bi-bar-chart-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div><!--end row-->

                    </div>

                </section>
                <!-- Dashboard Ecommerce ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- demo chat-->

    <!-- fin: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- inclusion du partie foot-->
    <?php $this->view("Partials/foot") ?>
    <!-- inclusion du partie foot fin-->
    <!-- inclusion du partie footer-->
    <?php $this->view("Partials/footer") ?>
    <!-- inclusion du partie footer fin-->
</body>
<!-- END: Body-->

</html>