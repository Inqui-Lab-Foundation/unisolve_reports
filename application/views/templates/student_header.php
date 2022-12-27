<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>NGI Payments</title>

    <meta name="description" content="BMSCW Campus">
    <meta name="author" content="Medha Tech Solutions">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="<?=base_url();?>assets/img/favicon.png">
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

    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
        <nav id="sidebar" aria-label="Main Navigation">
            <!-- Side Header -->
            <div class="content-header bg-white-5">
                <!-- Logo -->
                <a class="font-w600 text-dual" href="<?=base_url();?>">
                    <span class="smini-visible">
                        <i class="fa fa-circle-notch text-primary"></i>
                    </span>
                    <span class="smini-hide font-size-h5 tracking-wider">
                        NGI <span class="font-w400">Payments</span>
                    </span>
                </a>
                <!-- END Logo -->

            </div>
            <!-- END Side Header -->

            <!-- Sidebar Scrolling -->
            <div class="js-sidebar-scroll">
                <!-- Side Navigation -->
                <div class="content-side">
                    <ul class="nav-main">

                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "dashboard")? 'active' :''; ?>
                            <?php echo anchor('student/dashboard','<i class="nav-main-link-icon fa fa-tachometer-alt"></i><span class="nav-main-link-name">Dashboard</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>

                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "fees")? 'active' :''; ?>
                            <?php echo anchor('student/fees','<i class="nav-main-link-icon fa fa-wallet"></i><span class="nav-main-link-name">Fees</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>

                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "receipts")? 'active' :''; ?>
                            <?php echo anchor('student/receipts','<i class="nav-main-link-icon fa fa-receipt"></i><span class="nav-main-link-name">Receipts</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>

                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "my_profile")? 'active' :''; ?>
                            <?php echo anchor('student/my_profile','<i class="nav-main-link-icon fa fa-user-secret"></i><span class="nav-main-link-name">My Profile</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>

                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "changePassword")? 'active' :''; ?>
                            <?php echo anchor('student/changePassword','<i class="nav-main-link-icon fa fa-fingerprint"></i><span class="nav-main-link-name">Change Password</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>

                        <li>
                            <?php echo anchor('student/logout','<i class="nav-main-link-icon fa fa-sign-out-alt"></i><span class="nav-main-link-name">Logout</span>','class="nav-main-link"');?>
                        </li>

                    </ul>
                </div>
                <!-- END Side Navigation -->
            </div>
            <!-- END Sidebar Scrolling -->
        </nav>
        <!-- END Sidebar -->

        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout"
                        data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->

                    <!-- Toggle Mini Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout"
                        data-action="sidebar_mini_toggle">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                    </button>
                    <!-- END Toggle Mini Sidebar -->


                </div>
                <!-- END Left Section -->

                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <!-- User Dropdown -->
                    <div class="dropdown d-inline-block ml-2">
                        <button type="button" class="btn btn-sm btn-dual d-flex align-items-center"
                            id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="rounded-circle" src="<?=base_url();?>assets/img/avatar.jpg" alt="Header Avatar"
                                style="width: 21px;">
                            <span class="d-none d-sm-inline-block ml-2"><?=$student_name;?></span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0"
                            aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-primary-dark rounded-top">
                                <img class="img-avatar img-avatar48 img-avatar-thumb"
                                    src="<?=base_url();?>assets/img/avatar.jpg" alt="">
                                <p class="mt-2 mb-0 text-white font-w500"><?=$student_name;?></p>
                                <p class="mb-0 text-white-50 font-size-sm"><?=$reg_no;?></p>
                            </div>
                            <div class="p-2">
                                <!--<div role="separator" class="dropdown-divider"></div>-->
                                <?php
                                        echo anchor("student/logout",'<span class="font-size-sm font-w500">Log Out</span>', 'class="dropdown-item d-flex align-items-center justify-content-between"');
                                    ?>
                            </div>
                        </div>
                    </div>
                    <!-- END User Dropdown -->

                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <div id="page-header-search" class="overlay-header bg-white">
                <div class="content-header">
                    <form class="w-100" action="be_pages_generic_search.html" method="POST">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                <button type="button" class="btn btn-alt-danger" data-toggle="layout"
                                    data-action="header_search_off">
                                    <i class="fa fa-fw fa-times-circle"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control" placeholder="Search or hit ESC.."
                                id="page-header-search-input" name="page-header-search-input">
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Header Search -->

            <!-- Header Loader -->
            <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
            <div id="page-header-loader" class="overlay-header bg-white">
                <div class="content-header">
                    <div class="w-100 text-center">
                        <i class="fa fa-fw fa-circle-notch fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
        <!-- END Header -->