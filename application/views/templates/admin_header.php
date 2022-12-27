<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>NGI Payments</title>

    <meta name="description" content="NGI Payments">
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
    <!-- Page Container -->
    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <!-- Sidebar -->
        <nav id="sidebar" aria-label="Main Navigation">
            <!-- Side Header -->
            <div class="content-header bg-white-5">
                <!-- Logo -->
                <a class="font-w600 text-dual" href="index.html">
                    <span class="smini-visible">
                        <i class="fa fa-circle-notch text-primary"></i>
                    </span>
                    <span class="smini-hide font-size-h5 tracking-wider">
                        NGI <span class="font-w400">Campus</span>
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
                            <?php echo anchor('admin/dashboard','<i class="nav-main-link-icon fa fa-tachometer-alt"></i><span class="nav-main-link-name">Dashboard</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>

                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "students")? 'active' :''; ?>
                            <?php echo anchor('admin/students','<i class="nav-main-link-icon fa fa-user-graduate"></i><span class="nav-main-link-name">Students</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>
                        <!-- <li class="nav-main-item">
                            <?php $menu_active = ($menu == "system")? 'active' :''; ?>
                            <a class="nav-main-link nav-main-link-submenu <?=$menu_active;?>" data-toggle="submenu"
                                aria-haspopup="true" aria-expanded="false" href="#">
                                <i class="nav-main-link-icon fa fa-cogs"></i>
                                <span class="nav-main-link-name">System</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <?php $sub_menu_active = ($sub_menu == "academic_years")? 'active' :''; ?>
                                    <?php echo anchor('admin/academic_years','<i class="nav-main-link-icon fa fa-calendar"></i><span class="nav-main-link-name">Academic Years</span>', 'class="nav-main-link '.$sub_menu_active.'"');?>
                                </li>
                                <li class="nav-main-item">
                                    <?php $sub_menu_active = ($sub_menu == "courses")? 'active' :''; ?>
                                    <?php echo anchor('admin/courses','<i class="nav-main-link-icon fa fa-dice-d6"></i><span class="nav-main-link-name">Courses</span>', 'class="nav-main-link '.$sub_menu_active.'"');?>
                                </li>
                                <li class="nav-main-item">
                                    <?php $sub_menu_active = ($sub_menu == "sections")? 'active' :''; ?>
                                    <?php echo anchor('admin/staff','<i class="nav-main-link-icon fa fa-users"></i><span class="nav-main-link-name">Accounts Staff</span>', 'class="nav-main-link '.$sub_menu_active.'"');?>
                                </li>
                            </ul>
                        </li> -->
                        <li class="nav-main-item">
                            <?php $sub_menu_active = ($sub_menu == "academic_years")? 'active' :''; ?>
                            <?php echo anchor('admin/academic_years','<i class="nav-main-link-icon fa fa-calendar"></i><span class="nav-main-link-name">Academic Years</span>', 'class="nav-main-link '.$sub_menu_active.'"');?>
                        </li>
                        <li class="nav-main-item">
                            <?php $sub_menu_active = ($sub_menu == "courses")? 'active' :''; ?>
                            <?php echo anchor('admin/courses','<i class="nav-main-link-icon fa fa-dice-d6"></i><span class="nav-main-link-name">Courses</span>', 'class="nav-main-link '.$sub_menu_active.'"');?>
                        </li>
                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "feeStructure")? 'active' :''; ?>
                            <?php echo anchor('admin/feeStructure','<i class="nav-main-link-icon fa fa-book"></i><span class="nav-main-link-name">Fee Structure</span>', 'class="nav-main-link '.$menu_active.'"');?>
                        </li>
                        <li class="nav-main-item">
                            <?php $sub_menu_active = ($sub_menu == "staff")? 'active' :''; ?>
                            <?php echo anchor('admin/staff','<i class="nav-main-link-icon fa fa-users"></i><span class="nav-main-link-name">Accounts Staff</span>', 'class="nav-main-link '.$sub_menu_active.'"');?>
                        </li>
                        <li class="nav-main-item">
                            <?php $menu_active = ($menu == "reports")? 'active' :''; ?>
                            <a class="nav-main-link nav-main-link-submenu <?=$menu_active;?>" data-toggle="submenu"
                                aria-haspopup="true" aria-expanded="false" href="#">
                                <i class="nav-main-link-icon fa fa-chart-bar"></i>
                                <span class="nav-main-link-name">Reports</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <?php echo anchor('admin/report1','<i class="nav-main-link-icon fa fa-list"></i><span class="nav-main-link-name">Fee Collection Report</span>', 'class="nav-main-link"');?>
                                </li>
                                <li class="nav-main-item">
                                    <?php echo anchor('admin/report2','<i class="nav-main-link-icon fa fa-list"></i><span class="nav-main-link-name">Headwise Fee Collection Report</span>', 'class="nav-main-link"');?>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php echo anchor('admin/logout','<i class="nav-main-link-icon fa fa-sign-out-alt"></i><span class="nav-main-link-name">Logout</span>','class="nav-main-link"');?>
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

                    <!-- Open Search Section (visible on smaller screens) -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-sm btn-dual d-md-none" data-toggle="layout"
                        data-action="header_search_on">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                    <!-- END Open Search Section -->

                    <!-- Search Form (visible on larger screens) -->
                    <?=form_open('admin/search','name="form" novalidate class="d-none d-md-inline-block form-horizontal" method="post"');?>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-alt" placeholder="Search" id="search"
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
                    <!-- User Dropdown -->
                    <div class="dropdown d-inline-block ml-2">
                        <button type="button" class="btn btn-sm btn-dual d-flex align-items-center"
                            id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="rounded-circle" src="<?=base_url();?>assets/img/avatar.jpg" alt="Header Avatar"
                                style="width: 21px;">
                            <span class="d-none d-sm-inline-block ml-2"><?=$name;?></span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0"
                            aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-primary-dark rounded-top">
                                <img class="img-avatar img-avatar48 img-avatar-thumb"
                                    src="<?=base_url();?>assets/img/avatar.jpg" alt="">
                                <p class="mt-2 mb-0 text-white font-w500"><?=$username;?></p>
                                <p class="mb-0 text-white-50 font-size-sm">NCMS</p>
                            </div>
                            <div class="p-2">
                                <?php
                                        echo anchor('admin/logout','<span class="font-size-sm font-w500">Logout</span>','class="dropdown-item d-flex align-items-center justify-content-between"');
                                    ?>
                                <!--<a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_profile.html">-->
                                <!--    <span class="font-size-sm font-w500">Profile</span>-->
                                <!--    <span class="badge badge-pill badge-primary ml-2">1</span>-->
                                <!--</a>-->
                                <!--<a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">-->
                                <!--    <span class="font-size-sm font-w500">Settings</span>-->
                                <!--</a>-->
                                <!--<div role="separator" class="dropdown-divider"></div>-->
                                <!--<a class="dropdown-item d-flex align-items-center justify-content-between" href="op_auth_signin.html">-->
                                <!--    <span class="font-size-sm font-w500">Log Out</span>-->
                                <!--</a>-->
                            </div>
                        </div>
                    </div>
                    <!-- END User Dropdown -->

                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

        </header>
        <!-- END Header -->