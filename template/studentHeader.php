<?php
//echo $_SESSION["LOA"] !== 'Student' ? "<script type='text/javascript'>window.history.back();</script>" : '';
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nexus IT Training Center | STUDENT</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/Nexus/CDNs/dashboard/font-awesome/css/all.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/Nexus/CDNs/dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
   folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/Nexus/CDNs/dist/css/skins/skin-purple.min.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

    <!-- <link rel="stylesheet" type="text/css" href="../CDNs/admin.css"> -->

    <style type="text/css">
        .dropdown-menu {
            max-height: 300px;
            overflow-y: scroll;
        }
    </style>

</head>

<body class="hold-transition skin-purple sidebar-mini">


    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>NXS</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">Nexus | STUDENT</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- OR navbar fixed-top -->
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <span class="hidden-xs" style="color:white;">Hello, <?php echo Session::get('fullName'); ?>!</span>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown notifications-menu open">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu" style="overflow-y: hidden;">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                                                page and may cause design problems
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-red"></i> 5 new members joined
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-user text-red"></i> You changed your username
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">
                        <i class="fas fa-compass"></i>
                        <span>&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;NAVIGATION</span>
                    </li>
                    <li class="">
                        <a href="index.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>&emsp;Dashboard</span>
                        </a>
                    </li>
                    <li><a href="terms.php"><i class="fas fa-handshake"></i>&emsp;Terms and Conditions</a></li>
                    <li><a href="quotationRequest.php"><i class="fas fa-mail-bulk"></i>&emsp;Quotation Requests</a></li>
                    <li><a href="enrollment.php"><i class="fab fa-leanpub"></i>&emsp;Enrollment</a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-users-cog"></i>
                            <span>&emsp;Trainings</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="finishedTrainings"><i class="fas fa-check-double"></i>&emsp;Finished Trainings</a></li>
                            <li><a href="cancelledReservations"><i class="fas fa-window-close"></i>&emsp;Cancelled Trainings</a></li>
                            <li><a href="rejectedPayments"><i class="fas fa-times-circle"></i>&emsp;Rejected Payments</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="profile.php"><i class="fas fa-user-edit"></i>&emsp;Profile </a>
                    </li>
                    <li>
                        <a href="help.php"><i class="fas fa-question-circle"></i>&emsp; Help</a>
                    </li>
                    <li>
                        <a href="" class="logout">

                            <i class="fas fa-sign-out-alt"></i>
                            <span>&emsp; Logout</span>
                        </a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- START OF PAGE CONTENTS (MAIN) -->

        <div class="content-wrapper">