<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Unisolve Reports</title>

    <meta name="description" content="UNISOLVE">
    <meta name="author" content="Medha Tech Solutions">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <!-- <link rel="shortcut icon" href="<?=base_url();?>assets/img/favicon.png"> -->
    <!--<link rel="icon" type="image/png" sizes="192x192" href="<?=base_url();?>assets/media/favicons/favicon-192x192.png">-->
    <!--<link rel="apple-touch-icon" sizes="180x180" href="<?=base_url();?>assets/media/favicons/apple-touch-icon-180x180.png">-->
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and OneUI framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="<?=base_url();?>assets/css/oneui.css">
    <!-- <link rel="stylesheet" id="css-theme" href="<?=base_url();?>assets/css/themes/flat.css"> -->
    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/amethyst.min.css"> -->
    <!-- END Stylesheets -->

    <script src="<?=base_url();?>assets/js/core/jquery.min.js"></script>
    <script src="<?=base_url();?>assets/js/oneui.core.min.js"></script>
    <script src="<?=base_url();?>assets/js/oneui.app.min.js"></script>
    <script src="<?=base_url();?>assets/js/custom.js"></script>
</head>

<body>
    <!-- Page Container -->
    <div id="page-container"
        class="enable-page-overlay page-header-fixed main-content-narrow">

        <!-- Header -->
        <header id="page-header">

        <?php if(isset($instance)){ ?>
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <!-- <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout"
                        data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button> -->
                    <!-- END Toggle Sidebar -->

                    <!-- Toggle Mini Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout">
                        <?php
                            $instancesLive = array(" " => "") + $this->globals->instancesLive();
                        ?>
                        <a href=<?=$instancesLive[$instance]; ?> target="_blank"><i class="fa fa-fw fa-globe"></i></a>
                    </button>
                    <!-- END Toggle Mini Sidebar -->

                    <!-- Open Search Section (visible on smaller screens) -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <!-- <button type="button" class="btn btn-sm btn-dual d-md-none" data-toggle="layout"
                        data-action="header_search_on">
                        <i class="fa fa-fw fa-search"></i>
                    </button> -->
                    <!-- END Open Search Section -->

                    <!-- Search Form (visible on larger screens) -->
                    <!-- <?=form_open('reports/search','name="form" novalidate class="d-none d-md-inline-block form-horizontal" method="post"');?>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-alt" placeholder="Search by DISE Code" id="search"
                            name="search">
                        <div class="input-group-append">
                            <span class="input-group-text bg-body border-0">
                                <i class="fa fa-fw fa-search"></i>
                            </span>
                        </div>
                     </div>                                                  
                    <?=form_close();?> 
                    <!-- END Search Form -->
                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <?php
                        echo anchor('reports','ALL INSTANCES','class="btn btn-danger btn-sm"');
                    ?>

                    <!-- User Dropdown -->
                    <!-- <div class=zzzzzzzzz"dropdown d-inline-block ml-2">
                        <button type="button" class="btn btn-sm btn-dual d-flex align-items-center"
                            id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="rounded-circle" src="<?=base_url();?>assets/img/avatar.jpg" alt="Header Avatar"
                                style="width: 21px;">
                            <span class="d-none d-sm-inline-block ml-2">TN UNISOLVE</span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0"
                            aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-primary-dark rounded-top">
                                <img class="img-avatar img-avatar48 img-avatar-thumb"
                                    src="<?=base_url();?>assets/img/avatar.jpg" alt="">
                                <p class="mt-2 mb-0 text-white font-w500">TAMIL NADU</p>
                                <p class="mb-0 text-white-50 font-size-sm">UNISOLVE</p>
                            </div>
                            <div class="p-2">
                                <?php
                                        echo anchor('admin/logout','<span class="font-size-sm font-w500">Logout</span>','class="dropdown-item d-flex align-items-center justify-content-between"');
                                    ?>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_profile.html">
                                   <span class="font-size-sm font-w500">Profile</span>
                                   <span class="badge badge-pill badge-primary ml-2">1</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                   <span class="font-size-sm font-w500">Settings</span>
                                </a>
                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="op_auth_signin.html">
                                   <span class="font-size-sm font-w500">Log Out</span>
                                </a>
                            </div>
                        </div>
                    </div> -->
                    <!-- END User Dropdown -->

                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->
        <?php } ?>

        </header>
        <!-- END Header -->